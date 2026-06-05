<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\City;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $distributors = Distributor::with('city')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('pic', 'like', "%{$search}%")
                    ->orWhereHas('city', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.distributors.index', compact('distributors', 'search'));
    }

    public function create()
    {
        $cities = City::orderBy('nama')->get();
        return view('admin.distributors.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kota_id' => 'required|exists:cities,id',
            'nama' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'alamat' => 'required|string',
            'tampil_ke_publik' => 'boolean',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $validated['tampil_ke_publik'] = $request->has('tampil_ke_publik');

        Distributor::create($validated);

        return redirect()->route('admin.distributors.index')->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function edit(Distributor $distributor)
    {
        $cities = City::orderBy('nama')->get();
        return view('admin.distributors.edit', compact('distributor', 'cities'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $validated = $request->validate([
            'kota_id' => 'required|exists:cities,id',
            'nama' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'alamat' => 'required|string',
            'tampil_ke_publik' => 'boolean',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $validated['tampil_ke_publik'] = $request->has('tampil_ke_publik');

        $distributor->update($validated);

        return redirect()->route('admin.distributors.index')->with('success', 'Distributor berhasil diperbarui.');
    }

    public function destroy(Distributor $distributor)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus distributor.');
        }

        if ($distributor->outlets()->exists()) {
            return back()->with('error', 'Distributor tidak dapat dihapus karena memiliki relasi ke Outlet.');
        }

        $distributor->delete();
        return redirect()->route('admin.distributors.index')->with('success', 'Distributor berhasil dihapus.');
    }
}
