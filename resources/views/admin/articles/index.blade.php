@extends('layouts.admin')

@section('title', 'CMS Artikel (Blog)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Artikel & SEO</h1>
            <p class="text-sm text-slate-400">Tulis konten edukasi kucing, info produk BentoCat, dan optimasi artikel untuk SEO Google.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2">
            <span>Tulis Artikel Baru</span> 🐾
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul artikel..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.articles.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm flex items-center justify-center transition-all">
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
                        <th class="px-6 py-4">Judul Artikel</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Tanggal Publikasi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($articles as $article)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4">
                                <span class="block font-semibold text-white">{{ $article->title }}</span>
                                <span class="block text-[10px] font-mono text-slate-500">/artikel/{{ $article->slug }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $article->author->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($article->status === 'PUBLISHED')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Published</span>
                                @elseif($article->status === 'UNDER_REVIEW')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 uppercase">Review</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-800 border border-slate-700 text-slate-500 uppercase">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $article->published_at ? $article->published_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan artikel terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
