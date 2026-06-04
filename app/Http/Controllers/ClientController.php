<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;
use App\Models\Product;
use App\Models\Distributor;
use App\Models\Outlet;
use App\Models\CustomerProfile;
use App\Models\LeadRequest;
use App\Models\LeadAction;
use App\Models\Article;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleClient;

class ClientController extends Controller
{
    // Home / Landing Page
    public function index()
    {
        $products = Product::where('status', 'ACTIVE')->with('variants')->get();
        $provinces = Province::orderBy('nama')->get();
        $articles = Article::where('status', 'PUBLISHED')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('landing', compact('products', 'provinces', 'articles'));
    }

    // Get cities under a province for AJAX select
    public function getCities($province_id)
    {
        $cities = City::where('provinsi_id', $province_id)->orderBy('nama')->get();
        return response()->json($cities);
    }

    // Process lead and search for closest petshops/distributor
    public function searchOutlets(Request $request)
    {
        // 1. Honeypot check
        if ($request->filled('email_check')) {
            // Silently redirect back like a successful bot trap
            return redirect('/');
        }

        // 2. Validate input
        $request->validate([
            'nama' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'provinsi_id' => 'required|exists:provinces,id',
            'kota_id' => 'required|exists:cities,id',
            'produk_id' => 'required|exists:products,id',
            'varian_level_1' => 'nullable|string',
            'varian_level_2' => 'nullable|string',
            'varian_level_3' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // 3. Turnstile anti-spam check (if configured in .env)
        $turnstileSecret = env('TURNSTILE_SECRET_KEY');
        if ($turnstileSecret && $turnstileSecret !== '1x00000000000000000000000000000000') {
            $token = $request->input('cf-turnstile-response');
            if (!$token) {
                return back()->withErrors(['turnstile' => 'Validasi keamanan Turnstile diperlukan.'])->withInput();
            }

            try {
                $guzzle = new GuzzleClient();
                $res = $guzzle->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'form_params' => [
                        'secret' => $turnstileSecret,
                        'response' => $token,
                        'remoteip' => $request->ip()
                    ]
                ]);
                $body = json_decode($res->getBody(), true);
                if (!($body['success'] ?? false)) {
                    return back()->withErrors(['turnstile' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'])->withInput();
                }
            } catch (\Exception $e) {
                // Fallback gracefully on API connection timeout/errors to not block users
                logger()->error('Turnstile validation error: ' . $e->getMessage());
            }
        }

        // 4. Rate Limiting check (max 5 searches per 10 minutes per IP)
        $rateKey = 'lead-search:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return back()->withErrors(['rate_limit' => "Terlalu banyak permintaan pencarian. Silakan coba lagi dalam $seconds detik."])->withInput();
        }
        RateLimiter::hit($rateKey, 600);

        // 5. Save or update customer profile
        $province = Province::find($request->provinsi_id);
        $city = City::find($request->kota_id);

        $customer = CustomerProfile::updateOrCreate(
            ['whatsapp' => $request->whatsapp],
            [
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'provinsi' => $province ? $province->nama : null,
                'kota' => $city ? $city->nama : null,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'uuid' => (string) Str::uuid()
            ]
        );

        // 6. Fetch active outlets in the chosen city
        $outletsQuery = Outlet::where('kota_id', $request->kota_id)
            ->where('status', 'AKTIF')
            ->with(['shippingContacts' => function($q) {
                $q->orderBy('pivot_urutan', 'asc');
            }]);

        $outlets = $outletsQuery->get();

        // 7. Sort by featured and proximity if GPS coords are available
        $userLat = $request->latitude;
        $userLon = $request->longitude;

        if ($userLat && $userLon && $outlets->count() > 0) {
            foreach ($outlets as $outlet) {
                if ($outlet->latitude && $outlet->longitude) {
                    $outlet->distance = $this->haversine($userLat, $userLon, $outlet->latitude, $outlet->longitude);
                } else {
                    $outlet->distance = 99999; // fallback high distance
                }
            }

            // Sort logic: Featured goes first, then sorted by distance
            $outlets = $outlets->sort(function($a, $b) {
                if ($a->featured !== $b->featured) {
                    return $b->featured <=> $a->featured;
                }
                return $a->distance <=> $b->distance;
            })->values();
        } else {
            // No GPS coords: sort by featured desc, then name
            $outlets = $outlets->sortByDesc('featured')->values();
        }

        // 8. Allocate distributor & outlet for lead routing
        $allocatedOutlet = $outlets->first();
        $allocatedDistributor = null;

        if ($allocatedOutlet) {
            $allocatedDistributor = Distributor::find($allocatedOutlet->distributor_id);
        } else {
            // Fallback to active distributor in the city
            $allocatedDistributor = Distributor::where('kota_id', $request->kota_id)->where('status', 'ACTIVE')->first();
        }

        if (!$allocatedDistributor) {
            // Global active distributor fallback
            $allocatedDistributor = Distributor::where('status', 'ACTIVE')->first();
        }

        // 9. Record Lead Request
        $lead = LeadRequest::create([
            'customer_id' => $customer->id,
            'produk_id' => $request->produk_id,
            'varian_level_1' => $request->varian_level_1 ?? 'Default',
            'varian_level_2' => $request->varian_level_2,
            'varian_level_3' => $request->varian_level_3,
            'kota_id' => $request->kota_id,
            'outlet_id' => $allocatedOutlet ? $allocatedOutlet->id : null,
            'distributor_id' => $allocatedDistributor ? $allocatedDistributor->id : null,
        ]);

        // Record initial view event
        if ($allocatedOutlet) {
            LeadAction::create([
                'lead_id' => $lead->id,
                'action_type' => 'VIEW_OUTLET',
                'created_at' => now(),
            ]);
        }

        return view('outlet_search', compact('lead', 'outlets', 'allocatedDistributor', 'customer', 'city'));
    }

    // API to log click events via AJAX before WhatsApp redirection
    public function logAction(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:lead_requests,id',
            'action_type' => 'required|in:CLICK_WA_OUTLET,CLICK_WA_SHIPPING_CONTACT',
        ]);

        LeadAction::create([
            'lead_id' => $request->lead_id,
            'action_type' => $request->action_type,
            'created_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    // Dynamic SEO City routing (/kota/{slug})
    public function cityLanding($slug)
    {
        $city = City::where('slug', $slug)->firstOrFail();
        $province = Province::find($city->provinsi_id);

        $outlets = Outlet::where('kota_id', $city->id)
            ->where('status', 'AKTIF')
            ->orderBy('featured', 'desc')
            ->get();

        $distributor = Distributor::where('kota_id', $city->id)
            ->where('status', 'ACTIVE')
            ->first();

        // Fallback global active distributor
        if (!$distributor) {
            $distributor = Distributor::where('status', 'ACTIVE')->first();
        }

        $products = Product::where('status', 'ACTIVE')->get();
        $provinces = Province::orderBy('nama')->get();

        return view('city_routing', compact('city', 'province', 'outlets', 'distributor', 'products', 'provinces'));
    }

    // Articles Index (Content Marketing Hub)
    public function blogIndex()
    {
        $articles = Article::where('status', 'PUBLISHED')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('blog.index', compact('articles'));
    }

    // Article Detail Parser
    public function blogShow($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'PUBLISHED')
            ->firstOrFail();

        // Safely parse blocks JSON
        $blocks = [];
        if ($article->content_json) {
            $blocks = is_array($article->content_json) 
                ? $article->content_json 
                : (json_decode($article->content_json, true) ?: []);
        }

        return view('blog.show', compact('article', 'blocks'));
    }

    // Haversine Distance Calculation Helper (Returns in km)
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
