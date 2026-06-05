<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $articles = Article::with('author')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.articles.index', compact('articles', 'search'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content_json' => 'required|json',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'status' => 'required|in:DRAFT,UNDER_REVIEW,PUBLISHED',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $contentArray = json_decode($validated['content_json'], true);

        Article::create([
            'author_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'],
            'content_json' => $contentArray,
            'seo_title' => $validated['seo_title'] ?: $validated['title'],
            'seo_description' => $validated['seo_description'] ?: $validated['summary'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'PUBLISHED' ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Article $article)
    {
        // Decode blocks so the editor view can read them as structured JSON
        $blocksJson = json_encode($article->content_json);
        return view('admin.articles.edit', compact('article', 'blocksJson'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content_json' => 'required|json',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'status' => 'required|in:DRAFT,UNDER_REVIEW,PUBLISHED',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'like', $slug . '%')->where('id', '!=', $article->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $contentArray = json_decode($validated['content_json'], true);

        $article->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'],
            'content_json' => $contentArray,
            'seo_title' => $validated['seo_title'] ?: $validated['title'],
            'seo_description' => $validated['seo_description'] ?: $validated['summary'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'PUBLISHED' && !$article->published_at ? now() : $article->published_at,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads');
                if (!file_exists($uploadPath)) {
                    if (!@mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                        throw new \Exception("Tidak dapat membuat folder 'public/uploads' di server. Harap periksa izin akses penulisan (write permissions) folder 'public' di server.");
                    }
                }
                $file->move($uploadPath, $filename);

                return response()->json([
                    'success' => true,
                    'url' => asset('uploads/' . $filename)
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image upload failed: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Image upload failed.'], 400);
    }

    public function aiAssist(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
        ]);

        $title = $request->input('title');
        $summary = $request->input('summary') ?: '';

        $outline = [];
        $faqs = [];
        $seoTitle = '';
        $seoDesc = '';

        // Call Gemini API if key is present
        $apiKey = env('GEMINI_API_KEY');
        $apiSuccess = false;

        if ($apiKey) {
            try {
                $prompt = "Tolong analisis judul artikel: \"$title\" dan ringkasan: \"$summary\". Hasilkan JSON object valid dengan key berikut:\n" .
                    "                - \"outline\": array of 4 bullet points untuk struktur penulisan artikel.\n" .
                    "                - \"faqs\": array of 2 objects, masing-masing memiliki key \"q\" (pertanyaan) dan \"a\" (jawaban).\n" .
                    "                - \"seo_title\": string judul SEO yang direkomendasikan (maksimal 60 karakter).\n" .
                    "                - \"seo_description\": string deskripsi meta SEO yang direkomendasikan (maksimal 160 karakter).\n" .
                    "                Format output harus murni JSON valid tanpa tambahan teks markdown lain seperti ```json atau ```.";

                $response = Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $jsonText = $response->json('candidates.0.content.parts.0.text');
                    // Clean codeblock markers if any
                    $jsonText = trim(preg_replace('/^```(?:json)?|```$/m', '', $jsonText));
                    $data = json_decode($jsonText, true);
                    if ($data) {
                        $outline = $data['outline'] ?? [];
                        $faqs = $data['faqs'] ?? [];
                        $seoTitle = $data['seo_title'] ?? '';
                        $seoDesc = $data['seo_description'] ?? '';
                        $apiSuccess = true;
                    }
                }
            } catch (\Exception $e) {
                logger()->error('Gemini API Error: ' . $e->getMessage());
            }
        }

        // Fallback to local rule-based generation if API call was not successful
        if (!$apiSuccess) {
            $cleanTitle = e($title);
            $outline = [
                "Panduan awal dan pengenalan tentang " . $cleanTitle,
                "Manfaat serta kegunaan praktis dalam kehidupan sehari-hari",
                "Langkah-langkah penerapan dan tips optimal yang harus Anda ketahui",
                "Kesimpulan serta rekomendasi produk BentoCat pendukung"
            ];

            $faqs = [
                [
                    "q" => "Apa poin utama yang dibahas tentang " . $cleanTitle . "?",
                    "a" => "Artikel ini mengupas tuntas cara praktis, manfaat utama, serta kiat-kiat memaksimalkan efisiensi " . $cleanTitle . " untuk pemilik kucing."
                ],
                [
                    "q" => "Mengapa produk BentoCat direkomendasikan untuk topik ini?",
                    "a" => "BentoCat premium bentonite memiliki daya serap tinggi, higienis, dan sangat cocok dikombinasikan dengan tips perawatan di artikel ini."
                ]
            ];

            $seoTitle = Str::limit($cleanTitle . " - Tips BentoCat Premium", 60, '');
            
            $baseDesc = "Pelajari selengkapnya tentang " . $cleanTitle . ". " . ($summary ?: "Temukan panduan praktis, rekomendasi terbaik, dan ulasan terpercaya hanya di BentoCat.");
            $seoDesc = Str::limit($baseDesc, 160, '...');
        }

        // Fetch internal link suggestions from published articles
        $otherArticles = Article::where('status', 'PUBLISHED')
            ->where('title', '!=', $title)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $internalLinks = [];
        foreach ($otherArticles as $art) {
            $internalLinks[] = [
                'title' => $art->title,
                'url' => url('/blog/' . $art->slug)
            ];
        }

        // Add standard fallbacks if there are fewer than 2 internal links
        if (count($internalLinks) < 2) {
            $internalLinks[] = [
                'title' => 'Tips Menghemat Penggunaan Pasir Kucing di Rumah',
                'url' => url('/blog/tips-menghemat-penggunaan-pasir-kucing-di-rumah')
            ];
            $internalLinks[] = [
                'title' => 'Cari Outlet & Petshop BentoCat Terdekat',
                'url' => url('/')
            ];
        }

        return response()->json([
            'success' => true,
            'outline' => $outline,
            'faqs' => $faqs,
            'seo_title' => $seoTitle,
            'seo_description' => $seoDesc,
            'internal_links' => $internalLinks
        ]);
    }
}
