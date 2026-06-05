@extends('layouts.admin')

@section('title', 'Kelola Varian - ' . $product->nama)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Katalog Produk</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Hierarki Varian: {{ $product->nama }}</h1>
        <p class="text-sm text-slate-400">Kelola variasi produk hingga 3 tingkat kedalaman: {{ $product->label_level_1 }} (Level 1) → {{ $product->label_level_2 }} (Level 2) → {{ $product->label_level_3 }} (Level 3).</p>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Form to Add Root (Level 1) Variant -->
        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl h-fit space-y-4">
            <h2 class="text-lg font-bold text-white">Tambah {{ $product->label_level_1 }}</h2>
            <p class="text-xs text-slate-500">Mulai dengan menambahkan {{ strtolower($product->label_level_1) }} utama (misal: "Premium Series", "Eco Series").</p>
            
            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="parent_id" value="">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama {{ $product->label_level_1 }}</label>
                    <input type="text" name="nama" required 
                           placeholder="Contoh: Premium Series" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 rounded-xl text-sm transition-all">
                    Tambah {{ $product->label_level_1 }} 🐾
                </button>
            </form>
        </div>

        <!-- Right: Tree View (Level 1 -> Level 2 -> Level 3) -->
        <div class="lg:col-span-2 bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
            <h2 class="text-lg font-bold text-white mb-6">Pohon Struktur Varian</h2>

            <div class="space-y-4">
                @forelse($variantsTree as $level1)
                    <!-- Level 1 ({{ $product->label_level_1 }}) -->
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="bg-amber-500/20 text-amber-400 text-[9px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Level 1: {{ $product->label_level_1 }}</span>
                                <span class="font-bold text-white text-base">{{ $level1->nama }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="openAddModal({{ $level1->id }}, '{{ $level1->nama }}', 2)" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                    + {{ $product->label_level_2 }} (Lvl 2)
                                </button>
                                <form action="{{ route('admin.variants.destroy', $level1->id) }}" method="POST" onsubmit="return confirm('Menghapus varian ini akan menghapus semua sub-varian di bawahnya. Yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Level 2 ({{ $product->label_level_2 }}) -->
                        <div class="pl-6 border-l border-slate-800 space-y-3">
                            @forelse($level1->children as $level2)
                                <div class="bg-slate-950/60 border border-slate-850 rounded-xl p-3.5 space-y-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-blue-500/20 text-blue-400 text-[9px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Level 2: {{ $product->label_level_2 }}</span>
                                            <span class="font-semibold text-slate-200 text-sm">{{ $level2->nama }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="openAddModal({{ $level2->id }}, '{{ $level2->nama }}', 3)" class="bg-slate-800 hover:bg-slate-700 text-slate-400 px-2.5 py-1 rounded-lg text-xs font-bold transition-all">
                                                + {{ $product->label_level_3 }} (Lvl 3)
                                            </button>
                                            <form action="{{ route('admin.variants.destroy', $level2->id) }}" method="POST" onsubmit="return confirm('Menghapus varian ini akan menghapus semua sub-varian di bawahnya. Yakin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-2 py-1 rounded-lg text-xs font-bold transition-all">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Level 3 ({{ $product->label_level_3 }}) -->
                                    <div class="pl-6 border-l border-slate-850 space-y-2">
                                        @forelse($level2->children as $level3)
                                            <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-2 flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="bg-violet-500/20 text-violet-400 text-[9px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider">Level 3: {{ $product->label_level_3 }}</span>
                                                    <span class="text-xs font-medium text-slate-300">{{ $level3->nama }}</span>
                                                </div>
                                                <form action="{{ route('admin.variants.destroy', $level3->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus varian ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-400 text-[10px] font-bold px-2 py-0.5 rounded transition-all">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <p class="text-[11px] text-slate-600 italic">Belum ada {{ strtolower($product->label_level_3) }} (Level 3) ditambahkan untuk {{ strtolower($product->label_level_2) }} ini.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 italic pl-2">Belum ada {{ strtolower($product->label_level_2) }} (Level 2) ditambahkan untuk {{ strtolower($product->label_level_1) }} ini.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500 italic border border-dashed border-slate-800 rounded-2xl">
                        Belum ada data varian untuk produk ini.
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</div>

<!-- Modal Add Child Variant -->
<div id="add-variant-modal" class="fixed inset-0 z-50 items-center justify-center hidden bg-slate-950/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
        <h2 class="text-xl font-bold text-white" id="modal-title">Tambah Varian Baru</h2>
        <p class="text-xs text-slate-500 mt-1 mb-4" id="modal-desc"></p>
        
        <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="parent_id" id="modal-parent-id" value="">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2" id="modal-input-label">Nama Varian</label>
                <input type="text" name="nama" id="modal-input-nama" required class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="bg-slate-850 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                    Simpan Varian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const labelLevel1 = "{{ $product->label_level_1 }}";
    const labelLevel2 = "{{ $product->label_level_2 }}";
    const labelLevel3 = "{{ $product->label_level_3 }}";

    function openAddModal(parentId, parentName, targetLevel) {
        document.getElementById('modal-parent-id').value = parentId;
        
        const titleEl = document.getElementById('modal-title');
        const descEl = document.getElementById('modal-desc');
        const labelEl = document.getElementById('modal-input-label');
        const inputEl = document.getElementById('modal-input-nama');

        if (targetLevel === 2) {
            titleEl.innerText = `Tambah ${labelLevel2} (Level 2)`;
            descEl.innerText = `Menambahkan ${labelLevel2.toLowerCase()} baru di bawah ${labelLevel1.toLowerCase()} "${parentName}".`;
            labelEl.innerText = `Nama ${labelLevel2}`;
            inputEl.placeholder = `Contoh pilihan ${labelLevel2.toLowerCase()}`;
        } else if (targetLevel === 3) {
            titleEl.innerText = `Tambah ${labelLevel3} (Level 3)`;
            descEl.innerText = `Menambahkan ${labelLevel3.toLowerCase()} baru di bawah ${labelLevel2.toLowerCase()} "${parentName}".`;
            labelEl.innerText = `Nama ${labelLevel3}`;
            inputEl.placeholder = `Contoh pilihan ${labelLevel3.toLowerCase()}`;
        }

        const modal = document.getElementById('add-variant-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('add-variant-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endsection
