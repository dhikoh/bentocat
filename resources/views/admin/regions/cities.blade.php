@extends('layouts.admin')

@section('title', 'Daftar Kota - ' . $province->nama)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.regions.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Provinsi</a>
                <span class="text-slate-600">/</span>
                <span class="text-sm text-slate-400">Kota</span>
            </div>
            <h1 class="text-2xl font-bold text-white mt-1">Kota di Provinsi: {{ $province->nama }}</h1>
            <p class="text-sm text-slate-400">Kelola daftar kota di bawah {{ $province->nama }} untuk titik jangkauan distributor dan outlet.</p>
        </div>
        <button onclick="toggleModal('add-city-modal')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2">
            <span>Tambah Kota</span> 🐾
        </button>
    </div>

    <!-- Filter & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.regions.cities', $province->id) }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kota..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.regions.cities', $province->id) }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm flex items-center justify-center transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Grid / Table -->
    <div class="bg-slate-900/20 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-500 uppercase bg-slate-900/40 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama Kota</th>
                        <th class="px-6 py-4">Slug SEO</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($cities as $city)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4 font-semibold text-white">{{ $city->nama }}</td>
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs">/kota/{{ $city->slug }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($city->is_hidden)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase">Hidden</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="editCity({{ $city->id }}, '{{ $city->nama }}', {{ $city->is_hidden ? 'true' : 'false' }})" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Edit
                                    </button>
                                    @if(Auth::user() && Auth::user()->role === 'superadmin')
                                        <form action="{{ route('admin.regions.city.destroy', $city->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan kota di provinsi ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($cities->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $cities->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Kota -->
<div id="add-city-modal" class="fixed inset-0 z-50 items-center justify-center hidden bg-slate-950/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
        <h2 class="text-xl font-bold text-white mb-4">Tambah Kota</h2>
        <form action="{{ route('admin.regions.city.store', $province->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Kota</label>
                <input type="text" name="nama" required class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all" placeholder="Contoh: Surabaya">
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-slate-350 select-none cursor-pointer">
                    <input type="checkbox" name="is_hidden" value="1" class="rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-0">
                    Sembunyikan Kota ini dari pencarian
                </label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="toggleModal('add-city-modal')" class="bg-slate-850 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kota -->
<div id="edit-city-modal" class="fixed inset-0 z-50 items-center justify-center hidden bg-slate-950/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
        <h2 class="text-xl font-bold text-white mb-4">Edit Kota</h2>
        <form id="edit-city-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Kota</label>
                <input type="text" name="nama" id="edit-city-nama" required class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-slate-350 select-none cursor-pointer">
                    <input type="checkbox" name="is_hidden" id="edit-city-hidden" value="1" class="rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-0">
                    Sembunyikan Kota ini dari pencarian
                </label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="toggleModal('edit-city-modal')" class="bg-slate-850 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }

    function editCity(id, name, isHidden) {
        document.getElementById('edit-city-nama').value = name;
        document.getElementById('edit-city-hidden').checked = isHidden;
        document.getElementById('edit-city-form').action = `/admin/regions/city/${id}`;
        toggleModal('edit-city-modal');
    }
</script>
@endsection
