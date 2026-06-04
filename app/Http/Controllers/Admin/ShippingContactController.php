<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingContact;
use Illuminate\Http\Request;

class ShippingContactController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $contacts = ShippingContact::when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.shipping_contacts.index', compact('contacts', 'search'));
    }

    public function create()
    {
        return view('admin.shipping_contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'keterangan' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        $validated['aktif'] = $request->has('aktif');

        ShippingContact::create($validated);

        return redirect()->route('admin.shipping-contacts.index')->with('success', 'Kontak Pengiriman berhasil ditambahkan.');
    }

    public function edit(ShippingContact $shippingContact)
    {
        return view('admin.shipping_contacts.edit', compact('shippingContact'));
    }

    public function update(Request $request, ShippingContact $shippingContact)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'keterangan' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        $validated['aktif'] = $request->has('aktif');

        $shippingContact->update($validated);

        return redirect()->route('admin.shipping-contacts.index')->with('success', 'Kontak Pengiriman berhasil diperbarui.');
    }

    public function destroy(ShippingContact $shippingContact)
    {
        if ($shippingContact->outlets()->exists()) {
            return back()->with('error', 'Kontak Pengiriman tidak dapat dihapus karena masih dikaitkan dengan Outlet.');
        }

        $shippingContact->delete();
        return redirect()->route('admin.shipping-contacts.index')->with('success', 'Kontak Pengiriman berhasil dihapus.');
    }
}
