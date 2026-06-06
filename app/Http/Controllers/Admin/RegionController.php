<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $provinces = Province::withCount('cities')
            ->when($search, function ($query, $search) {
                $lowered = '%' . strtolower($search) . '%';
                $query->whereRaw('LOWER(nama) LIKE ?', [$lowered]);
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.regions.index', compact('provinces', 'search'));
    }

    public function storeProvince(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:provinces,nama',
        ]);

        Province::create([
            'nama' => $validated['nama'],
            'is_hidden' => $request->has('is_hidden'),
        ]);

        return redirect()->route('admin.regions.index')->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function updateProvince(Request $request, Province $province)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:provinces,nama,' . $province->id,
        ]);

        $province->update([
            'nama' => $validated['nama'],
            'is_hidden' => $request->has('is_hidden'),
        ]);

        return redirect()->route('admin.regions.index')->with('success', 'Provinsi berhasil diubah.');
    }

    public function destroyProvince(Province $province)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus provinsi.');
        }

        if ($province->cities()->exists()) {
            return back()->with('error', 'Provinsi tidak dapat dihapus karena memiliki data kota.');
        }

        $province->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Provinsi berhasil dihapus.');
    }

    public function cities(Province $province, Request $request)
    {
        $search = $request->input('search');
        $cities = $province->cities()
            ->when($search, function ($query, $search) {
                $lowered = '%' . strtolower($search) . '%';
                $query->whereRaw('LOWER(nama) LIKE ?', [$lowered]);
            })
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.regions.cities', compact('province', 'cities', 'search'));
    }

    public function storeCity(Request $request, Province $province)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $slug = Str::slug($validated['nama']);

        // Ensure unique slug
        $count = City::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $province->cities()->create([
            'nama' => $validated['nama'],
            'slug' => $slug,
            'is_hidden' => $request->has('is_hidden'),
        ]);

        return redirect()->route('admin.regions.cities', $province->id)->with('success', 'Kota berhasil ditambahkan.');
    }

    public function updateCity(Request $request, City $city)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $slug = Str::slug($validated['nama']);
        $count = City::where('slug', 'like', $slug . '%')->where('id', '!=', $city->id)->count();
        if ($count > 0) {
            $slug = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        }

        $city->update([
            'nama' => $validated['nama'],
            'slug' => $slug,
            'is_hidden' => $request->has('is_hidden'),
        ]);

        return redirect()->route('admin.regions.cities', $city->provinsi_id)->with('success', 'Kota berhasil diubah.');
    }

    public function destroyCity(City $city)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus kota.');
        }

        if ($city->outlets()->exists() || $city->distributors()->exists()) {
            return back()->with('error', 'Kota tidak dapat dihapus karena memiliki relasi ke Outlet atau Distributor.');
        }

        $provinceId = $city->provinsi_id;
        $city->delete();

        return redirect()->route('admin.regions.cities', $provinceId)->with('success', 'Kota berhasil dihapus.');
    }
}
