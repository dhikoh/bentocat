@extends('layouts.client')

@section('title', $article->seo_title ?: $article->title . ' - BentoCat Blog')
@section('meta_description', $article->seo_description ?: ($article->summary ?: 'Baca artikel selengkapnya di BentoCat.'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">

    <!-- Header navigation & details -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
            <a href="{{ route('blog.index') }}" class="text-amber-500 hover:underline">← Kembali ke Blog</a>
            <span>•</span>
            <span>Kategori: Edukasi</span>
        </div>
        <h1 class="font-outfit font-black text-3xl sm:text-5xl text-white leading-tight">
            {{ $article->title }}
        </h1>
        <div class="flex items-center gap-3 text-xs text-slate-400 border-b border-slate-900 pb-6">
            <span class="font-semibold text-slate-300">Penulis: {{ $article->author->name }}</span>
            <span>•</span>
            <span>Dipublikasikan: {{ $article->published_at ? $article->published_at->format('d M Y') : 'Draft' }}</span>
        </div>
    </div>

    <!-- Render Content Blocks -->
    <div class="space-y-8">
        @forelse($blocks as $block)
            @php
                $type = $block['type'] ?? 'paragraph';
            @endphp

            @if($type === 'paragraph')
                <!-- Paragraph Block -->
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    {!! nl2br(e($block['content'] ?? '')) !!}
                </p>

            @elseif($type === 'qna')
                <!-- QnA Block -->
                <div class="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl space-y-3">
                    <div class="flex gap-2">
                        <span class="text-amber-500 font-extrabold font-outfit text-sm">TANYA:</span>
                        <strong class="text-sm text-white font-outfit">{{ $block['question'] ?? '' }}</strong>
                    </div>
                    <div class="flex gap-2 border-t border-slate-850 pt-3">
                        <span class="text-emerald-500 font-extrabold font-outfit text-sm">JAWAB:</span>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">{{ $block['answer'] ?? '' }}</p>
                    </div>
                </div>

            @elseif($type === 'dialogue')
                <!-- Dialogue Block -->
                <div class="flex gap-4 items-start bg-slate-950 p-4 rounded-2xl border border-slate-900 max-w-xl">
                    <div class="w-10 h-10 rounded-full bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-lg shrink-0">
                        👤
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xs font-bold text-amber-500 uppercase tracking-wider">{{ $block['character'] ?? 'Karakter' }}</span>
                        <p class="text-xs sm:text-sm text-slate-350 italic">"{{ $block['dialogue'] ?? '' }}"</p>
                    </div>
                </div>

            @elseif($type === 'quote')
                <!-- Quote Block -->
                <blockquote class="border-l-4 border-amber-500 bg-slate-900/20 p-5 rounded-r-2xl italic text-slate-300 text-xs sm:text-sm leading-relaxed space-y-1">
                    <p>"{{ $block['quote'] ?? '' }}"</p>
                    @if(!empty($block['source']))
                        <cite class="block text-[10px] text-slate-500 not-italic font-bold">— {{ $block['source'] }}</cite>
                    @endif
                </blockquote>

            @elseif($type === 'analogy')
                <!-- Analogy Block -->
                <div class="bg-gradient-to-r from-indigo-950/20 to-slate-900/40 border border-indigo-500/10 p-6 rounded-2xl space-y-2">
                    <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-1">
                        <span>💡 Analogi / Perumpamaan:</span>
                    </span>
                    <p class="text-xs sm:text-sm text-slate-350 leading-relaxed">
                        {{ $block['analogy'] ?? '' }}
                    </p>
                </div>

            @elseif($type === 'dalil')
                <!-- Dalil / References Block -->
                <div class="bg-slate-950 p-6 rounded-2xl border border-slate-900 space-y-3">
                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">
                        Dalil / Rujukan
                    </span>
                    <p class="text-sm font-serif text-slate-300 italic leading-relaxed">
                        "{{ $block['hadits'] ?? '' }}"
                    </p>
                    @if(!empty($block['source']))
                        <span class="block text-[10px] text-slate-500 font-mono text-right">— {{ $block['source'] }}</span>
                    @endif
                </div>

            @elseif($type === 'doa')
                <!-- Doa Block -->
                <div class="bg-slate-900/30 border border-slate-800 p-6 rounded-2xl space-y-4">
                    <span class="block text-[10px] font-bold text-amber-500 uppercase tracking-wider">Doa / Harapan:</span>
                    @if(!empty($block['doa']))
                        <p class="text-right text-lg font-serif text-white leading-loose">{{ $block['doa'] }}</p>
                    @endif
                    @if(!empty($block['latin']))
                        <p class="text-xs italic text-slate-400 font-sans">{{ $block['latin'] }}</p>
                    @endif
                    @if(!empty($block['meaning']))
                        <p class="text-xs text-slate-350 border-t border-slate-850 pt-2"><strong class="text-slate-400">Artinya:</strong> "{{ $block['meaning'] }}"</p>
                    @endif
                </div>

            @elseif($type === 'image')
                <!-- Image Block -->
                <div class="space-y-2">
                    <div class="rounded-2xl overflow-hidden border border-slate-900 bg-slate-950">
                        <img src="{{ $block['url'] ?? '#' }}" alt="{{ $block['caption'] ?? 'Gambar Artikel' }}" class="w-full max-h-96 object-cover">
                    </div>
                    @if(!empty($block['caption']))
                        <span class="block text-center text-[10px] text-slate-500 italic">{{ $block['caption'] }}</span>
                    @endif
                </div>

            @elseif($type === 'video')
                <!-- Video Block -->
                <div class="space-y-2">
                    <div class="aspect-video w-full rounded-2xl overflow-hidden border border-slate-900 bg-slate-950">
                        @php
                            $youtubeId = '';
                            $url = $block['youtube_url'] ?? '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $match)) {
                                $youtubeId = $match[1];
                            }
                        @endphp
                        @if($youtubeId)
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xs text-slate-600 italic">URL Video YouTube tidak valid.</div>
                        @endif
                    </div>
                    @if(!empty($block['caption']))
                        <span class="block text-center text-[10px] text-slate-500 italic">{{ $block['caption'] }}</span>
                    @endif
                </div>

            @endif
        @empty
            <p class="text-slate-500 italic">Artikel ini tidak memiliki blok konten.</p>
        @endforelse
    </div>

</div>
@endsection
