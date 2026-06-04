@extends('layouts.client')

@section('title', 'Artikel & Edukasi Perawatan Kucing - BentoCat')
@section('meta_description', 'Kumpulan tips edukasi, cara merawat litter box, dan informasi produk pasir kucing BentoCat Premium.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    
    <!-- Header -->
    <div class="text-center space-y-4 max-w-2xl mx-auto">
        <span class="text-sm font-bold text-amber-600 uppercase tracking-wider">Pusat Edukasi Kucing</span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-900">Tips & Edukasi BentoCat</h1>
        <p class="text-sm text-slate-600">Dapatkan artikel terbaru seputar perawatan kesehatan kucing, tips kebersihan pasir litter box, dan info seputar pencinta kucing.</p>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
            <article class="bg-white border border-slate-100 rounded-3xl overflow-hidden group hover:border-amber-500/30 hover:shadow-lg transition-all flex flex-col justify-between">
                <div class="p-5 space-y-4">
                    <div class="aspect-[16/10] bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 flex items-center justify-center text-4xl font-serif text-slate-400">
                        🐈
                    </div>
                    <div class="space-y-2">
                        <span class="block text-[10px] text-slate-400 font-medium">
                            Dipublikasikan pada {{ $article->published_at ? $article->published_at->format('d M Y') : 'Baru' }}
                        </span>
                        <h3 class="font-outfit font-bold text-lg text-slate-900 group-hover:text-amber-650 transition-all leading-snug">
                            <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-550 leading-relaxed line-clamp-3">
                            {{ $article->summary ?: 'Klik baca lengkap untuk mengetahui detail selengkapnya tentang topik ini...' }}
                        </p>
                    </div>
                </div>
                <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center text-xs">
                    <span class="text-slate-500">Oleh: {{ $article->author->name }}</span>
                    <a href="{{ route('blog.show', $article->slug) }}" class="font-bold text-amber-650 hover:underline">Baca Lengkap →</a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-20 text-slate-400 italic">
                Belum ada tulisan artikel yang dipublikasikan saat ini.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="pt-8 flex justify-center">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection
