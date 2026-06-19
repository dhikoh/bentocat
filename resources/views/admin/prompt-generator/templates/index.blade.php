@extends('layouts.admin')

@section('title', 'Kelola Template Prompt')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-amber-500 font-bold uppercase tracking-wider">
                <a href="{{ route('admin.prompt-generator.index') }}" class="hover:underline">Asisten Prompt</a>
                <span>&gt;</span>
                <span class="text-slate-400">Daftar Template</span>
            </div>
            <h1 class="text-2xl font-bold text-white mt-1">Kelola Template Prompt</h1>
            <p class="text-sm text-slate-400">Tambah, ubah, dan kelola template instan yang digunakan oleh asisten prompt pemasaran BentoCat.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.prompt-generator.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold px-5 py-2.5 rounded-xl border border-slate-700 transition flex items-center gap-2">
                &larr; Kembali
            </a>
            <a href="{{ route('admin.prompt-generator.templates.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition flex items-center gap-2">
                <span>Tambah Template</span> ➕
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama Template</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Variabel Pengisian</th>
                        <th class="px-6 py-4">Target default</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse ($templates as $tmpl)
                        <tr class="hover:bg-slate-900/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $tmpl->name }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5 max-w-xs truncate" title="{{ $tmpl->base_prompt }}">{{ $tmpl->base_prompt }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $tmpl->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if(!empty($tmpl->placeholders))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(explode(',', $tmpl->placeholders) as $ph)
                                            <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                                {{ trim($ph) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-slate-600 italic">Tidak ada variabel</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $tmpl->target_audience }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.prompt-generator.templates.edit', $tmpl->id) }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg border border-slate-700 transition-all" title="Edit Template">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.prompt-generator.templates.destroy', $tmpl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg border border-rose-500/20 transition-all" title="Hapus Template">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                Belum ada template prompt yang terdaftar. Klik "Tambah Template" untuk membuat yang pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
