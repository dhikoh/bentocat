<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $distributors = Distributor::with('city')
            ->when($search, function ($query, $search) {
                $lowered = '%' . strtolower($search) . '%';
                $query->where(function ($q) use ($lowered) {
                    $q->whereRaw('LOWER(nama) LIKE ?', [$lowered])
                        ->orWhereRaw('LOWER(pic) LIKE ?', [$lowered])
                        ->orWhereHas('city', function ($c) use ($lowered) {
                            $c->whereRaw('LOWER(nama) LIKE ?', [$lowered]);
                        });
                });
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.distributors.index', compact('distributors', 'search'));
    }

    public function create()
    {
        $provinces = Province::orderBy('nama')->get();
        $oldProvinceId = old('provinsi_id');
        $cities = $oldProvinceId ? City::where('provinsi_id', $oldProvinceId)->orderBy('nama')->get() : [];
        return view('admin.distributors.create', compact('provinces', 'cities'));
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
        $provinces = Province::orderBy('nama')->get();
        $currentProvinceId = old('provinsi_id', $distributor->city ? $distributor->city->provinsi_id : null);
        $cities = $currentProvinceId ? City::where('provinsi_id', $currentProvinceId)->orderBy('nama')->get() : [];
        return view('admin.distributors.edit', compact('distributor', 'provinces', 'cities'));
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
