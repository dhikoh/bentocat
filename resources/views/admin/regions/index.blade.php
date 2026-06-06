@extends('layouts.admin')

@section('title', 'Daftar Wilayah (Provinsi)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Wilayah & Provinsi</h1>
            <p class="text-sm text-slate-400">Daftar provinsi di Indonesia untuk merelasikan kota-kota sebaran outlet BentoCat.</p>
        </div>
        <button onclick="toggleModal('add-province-modal')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2">
            <span>Tambah Provinsi</span> 🐾
        </button>
    </div>

    <!-- Filter & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.regions.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama provinsi..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.regions.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm flex items-center justify-center transition-all">
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
                        <th class="px-6 py-4">Nama Provinsi</th>
                        <th class="px-6 py-4 text-center">Jumlah Kota</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($provinces as $province)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4 font-semibold text-white">{{ $province->nama }}</td>
                            <td class="px-6 py-4 text-center font-bold text-slate-400">
                                <a href="{{ route('admin.regions.cities', $province->id) }}" class="hover:underline text-amber-500">
                                    {{ $province->cities_count }} Kota
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($province->is_hidden)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase">Hidden</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.regions.cities', $province->id) }}" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Kelola Kota
                                    </a>
                                    <button onclick="editProvince({{ $province->id }}, '{{ $province->nama }}', {{ $province->is_hidden ? 'true' : 'false' }})" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Edit
                                    </button>
                                    @if(Auth::user() && Auth::user()->role === 'superadmin')
                                        <form action="{{ route('admin.regions.province.destroy', $province->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus provinsi ini? Semua data kota di dalamnya harus kosong.')">
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
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan provinsi terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($provinces->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $provinces->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Provinsi -->
<div id="add-province-modal" class="fixed inset-0 z-50 items-center justify-center hidden bg-slate-950/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
        <h2 class="text-xl font-bold text-white mb-4">Tambah Provinsi</h2>
        <form action="{{ route('admin.regions.province.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Provinsi</label>
                <input type="text" name="nama" required class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-slate-350 select-none cursor-pointer">
                    <input type="checkbox" name="is_hidden" value="1" class="rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-0">
                    Sembunyikan Provinsi & Kota di dalamnya
                </label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="toggleModal('add-province-modal')" class="bg-slate-850 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Provinsi -->
<div id="edit-province-modal" class="fixed inset-0 z-50 items-center justify-center hidden bg-slate-950/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
        <h2 class="text-xl font-bold text-white mb-4">Edit Provinsi</h2>
        <form id="edit-province-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Provinsi</label>
                <input type="text" name="nama" id="edit-province-nama" required class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm text-slate-350 select-none cursor-pointer">
                    <input type="checkbox" name="is_hidden" id="edit-province-hidden" value="1" class="rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-0">
                    Sembunyikan Provinsi & Kota di dalamnya
                </label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="toggleModal('edit-province-modal')" class="bg-slate-850 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
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

    function editProvince(id, name, isHidden) {
        document.getElementById('edit-province-nama').value = name;
        document.getElementById('edit-province-hidden').checked = isHidden;
        document.getElementById('edit-province-form').action = `/admin/regions/province/${id}`;
        toggleModal('edit-province-modal');
    }
</script>
@endsection
