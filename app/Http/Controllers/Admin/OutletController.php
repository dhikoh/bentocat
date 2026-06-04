<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Distributor;
use App\Models\City;
use App\Models\ShippingContact;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $outlets = Outlet::with(['distributor', 'city'])
            ->when($search, function ($query, $search) {
                $query->where('nama_outlet', 'like', "%{$search}%")
                    ->orWhere('nama_pic', 'like', "%{$search}%")
                    ->orWhereHas('city', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('distributor', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nama_outlet')
            ->paginate(10);

        return view('admin.outlets.index', compact('outlets', 'search'));
    }

    public function create()
    {
        $distributors = Distributor::where('status', 'ACTIVE')->orderBy('nama')->get();
        $cities = City::orderBy('nama')->get();
        $shippingContacts = ShippingContact::where('aktif', true)->orderBy('nama')->get();
        return view('admin.outlets.create', compact('distributors', 'cities', 'shippingContacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'kota_id' => 'required|exists:cities,id',
            'nama_outlet' => 'required|string|max:255',
            'nama_pic' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'alamat_lengkap' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:2083',
            'featured' => 'boolean',
            'status' => 'required|in:AKTIF,NONAKTIF,STOK_KOSONG,TUTUP',
            'delivery_mode' => 'required|in:SELF_DELIVERY,RECOMMENDED_SHIPPING_CONTACT,PICKUP_ONLY',
            'shipping_contacts' => 'nullable|array',
            'shipping_contacts.*' => 'exists:shipping_contacts,id',
        ]);

        $validated['featured'] = $request->has('featured');

        $outlet = Outlet::create($validated);

        if ($request->filled('shipping_contacts') && $validated['delivery_mode'] === 'RECOMMENDED_SHIPPING_CONTACT') {
            $syncData = [];
            foreach ($request->input('shipping_contacts') as $index => $contactId) {
                $syncData[$contactId] = ['urutan' => $index + 1];
            }
            $outlet->shippingContacts()->sync($syncData);
        } else {
            $outlet->shippingContacts()->detach();
        }

        return redirect()->route('admin.outlets.index')->with('success', 'Outlet/Petshop berhasil ditambahkan.');
    }

    public function edit(Outlet $outlet)
    {
        $distributors = Distributor::where('status', 'ACTIVE')->orderBy('nama')->get();
        $cities = City::orderBy('nama')->get();
        $shippingContacts = ShippingContact::where('aktif', true)->orderBy('nama')->get();
        $selectedContacts = $outlet->shippingContacts->pluck('id')->toArray();
        return view('admin.outlets.edit', compact('outlet', 'distributors', 'cities', 'shippingContacts', 'selectedContacts'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'kota_id' => 'required|exists:cities,id',
            'nama_outlet' => 'required|string|max:255',
            'nama_pic' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'alamat_lengkap' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:2083',
            'featured' => 'boolean',
            'status' => 'required|in:AKTIF,NONAKTIF,STOK_KOSONG,TUTUP',
            'delivery_mode' => 'required|in:SELF_DELIVERY,RECOMMENDED_SHIPPING_CONTACT,PICKUP_ONLY',
            'shipping_contacts' => 'nullable|array',
            'shipping_contacts.*' => 'exists:shipping_contacts,id',
        ]);

        $validated['featured'] = $request->has('featured');

        $outlet->update($validated);

        if ($request->filled('shipping_contacts') && $validated['delivery_mode'] === 'RECOMMENDED_SHIPPING_CONTACT') {
            $syncData = [];
            foreach ($request->input('shipping_contacts') as $index => $contactId) {
                $syncData[$contactId] = ['urutan' => $index + 1];
            }
            $outlet->shippingContacts()->sync($syncData);
        } else {
            $outlet->shippingContacts()->detach();
        }

        return redirect()->route('admin.outlets.index')->with('success', 'Outlet/Petshop berhasil diperbarui.');
    }

    public function destroy(Outlet $outlet)
    {
        if ($outlet->leadRequests()->exists()) {
            return back()->with('error', 'Outlet tidak dapat dihapus karena memiliki log data lead.');
        }

        $outlet->shippingContacts()->detach();
        $outlet->delete();
        return redirect()->route('admin.outlets.index')->with('success', 'Outlet/Petshop berhasil dihapus.');
    }
}
