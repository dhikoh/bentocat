@extends('layouts.client')

@section('title', $article->seo_title ?: $article->title . ' - BentoCat Blog')
@section('meta_description', $article->seo_description ?: ($article->summary ?: 'Baca artikel selengkapnya di BentoCat.'))

@section('schema')
@php
    $publishDate = $article->published_at ? $article->published_at->tz('Asia/Jakarta')->toIso8601String() : $article->created_at->tz('Asia/Jakarta')->toIso8601String();
    $modifiedDate = $article->updated_at->tz('Asia/Jakarta')->toIso8601String();
    $authorName = $article->author->name ?? 'BentoCat Editor';
    $siteName = \App\Models\Setting::get('site_name', 'BentoCat');
    $siteLogo = asset(\App\Models\Setting::get('site_logo', 'images/logo.png'));
    $ogImage = asset(\App\Models\Setting::get('seo_og_image', 'images/logo.png'));

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => url()->current()
        ],
        'headline' => $article->title,
        'description' => $article->seo_description ?: ($article->summary ?: 'Baca artikel selengkapnya di BentoCat.'),
        'image' => $ogImage,
        'datePublished' => $publishDate,
        'dateModified' => $modifiedDate,
        'author' => [
            '@type' => 'Person',
            'name' => $authorName
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $siteName,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $siteLogo
            ]
        ]
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">

    <!-- Header navigation & details -->
    <div class="space-y-4">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-550">
            <a href="{{ route('blog.index') }}" class="text-amber-650 hover:text-amber-750 hover:underline">← Kembali ke Blog</a>
            <span>•</span>
            <span>Kategori: Edukasi</span>
        </div>
        <h1 class="font-outfit font-black text-3xl sm:text-5xl text-slate-900 leading-tight">
            {{ $article->title }}
        </h1>
        <div class="flex items-center gap-3 text-xs text-slate-500 border-b border-slate-200/80 pb-6">
            <span class="font-semibold text-slate-700">Penulis: {{ $article->author->name }}</span>
            <span>•</span>
            <span>Dipublikasikan: {{ $article->published_at ? $article->published_at->format('d M Y') : 'Draft' }}</span>
        </div>
    </div>

    <!-- Render Content Blocks -->
    <div class="space-        @forelse($blocks as $block)
            @php
                $type = $block['type'] ?? 'paragraph';
            @endphp

            @if($type === 'paragraph')
                <!-- Paragraph Block -->
                <div class="space-y-2">
                    @if(!empty($block['heading']))
                        <h3 class="font-outfit font-black text-xl sm:text-2xl text-slate-900 mt-6">{{ $block['heading'] }}</h3>
                    @endif
                    @if(!empty($block['text']) || !empty($block['content']))
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                            {!! nl2br(e($block['text'] ?? ($block['content'] ?? ''))) !!}
                        </p>
                    @endif
                    @if(!empty($block['referenceUrl']))
                        <div class="pt-1">
                            <a href="{{ $block['referenceUrl'] }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-650 hover:underline">
                                <span>🔗 Rujukan Artikel</span> ↗
                            </a>
                        </div>
                    @endif
                </div>

            @elseif($type === 'quick_answer' || $type === 'qna')
                <!-- QnA / Quick Answer Block -->
                <div class="bg-amber-50/20 border border-amber-100 p-6 rounded-2xl shadow-sm space-y-3">
                    <div class="flex gap-2 items-start">
                        <span class="bg-amber-500 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Tanya Jawab</span>
                        @if(!empty($block['question']))
                            <strong class="text-sm text-slate-900 font-outfit">{{ $block['question'] }}</strong>
                        @endif
                    </div>
                    <div class="border-t border-amber-100/50 pt-3">
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                            {!! nl2br(e($block['text'] ?? ($block['answer'] ?? ''))) !!}
                        </p>
                        @if(!empty($block['referenceUrl']))
                            <div class="pt-2">
                                <a href="{{ $block['referenceUrl'] }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 hover:underline">
                                    <span>Rujukan Referensi</span> ↗
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($type === 'dialog' || $type === 'dialogue')
                <!-- Dialogue / Conversation Block -->
                <div class="space-y-4 max-w-xl bg-slate-50 p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">🗣️ Ilustrasi Percakapan:</span>
                    @if(!empty($block['lines']) && is_array($block['lines']))
                        @foreach($block['lines'] as $line)
                            @php
                                $role = strtolower($line['role'] ?? 'anak');
                                $avatar = $role === 'ibu' ? '👩' : ($role === 'ayah' ? '👨' : '👦');
                                $bg = $role === 'ibu' ? 'bg-rose-50 border-rose-100' : ($role === 'ayah' ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100');
                                $textCol = $role === 'ibu' ? 'text-rose-700' : ($role === 'ayah' ? 'text-indigo-700' : 'text-amber-700');
                            @endphp
                            <div class="flex gap-3 items-start">
                                <div class="w-8 h-8 rounded-full {{ $bg }} border flex items-center justify-center text-sm shrink-0 shadow-sm">
                                    {{ $avatar }}
                                </div>
                                <div class="space-y-0.5">
                                    <span class="block text-[10px] font-bold {{ $textCol }} uppercase tracking-wider">{{ $line['role'] ?? 'Anak' }}</span>
                                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed italic">"{{ $line['text'] ?? '' }}"</p>
                                </div>
                            </div>
                        @endforeach
                    @elseif(!empty($block['character']))
                        @php
                            $role = strtolower($block['character']);
                            $avatar = $role === 'ibu' ? '👩' : ($role === 'ayah' ? '👨' : '👦');
                            $bg = $role === 'ibu' ? 'bg-rose-50 border-rose-100' : ($role === 'ayah' ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100');
                            $textCol = $role === 'ibu' ? 'text-rose-700' : ($role === 'ayah' ? 'text-indigo-700' : 'text-amber-700');
                        @endphp
                        <div class="flex gap-4 items-start bg-white p-4 rounded-2xl border border-slate-100 shadow-sm max-w-xl">
                            <div class="w-10 h-10 rounded-full {{ $bg }} border flex items-center justify-center text-lg shrink-0">
                                {{ $avatar }}
                            </div>
                            <div class="space-y-1">
                                <span class="block text-xs font-bold {{ $textCol }} uppercase tracking-wider">{{ $block['character'] }}</span>
                                <p class="text-xs sm:text-sm text-slate-655 italic">"{{ $block['dialogue'] ?? '' }}"</p>
                            </div>
                        </div>
                    @endif
                </div>

            @elseif($type === 'quote')
                <!-- Quote Block -->
                <blockquote class="border-l-4 border-amber-500 bg-amber-50/30 p-5 rounded-r-2xl italic text-slate-700 text-xs sm:text-sm leading-relaxed space-y-1">
                    <p>"{{ $block['quote'] ?? '' }}"</p>
                    @if(!empty($block['source']))
                        <cite class="block text-[10px] text-slate-450 not-italic font-bold">— {{ $block['source'] }}</cite>
                    @endif
                </blockquote>

            @elseif($type === 'analogy')
                <!-- Analogy Block -->
                <div class="bg-indigo-50/20 border border-indigo-100/50 p-6 rounded-2xl space-y-2">
                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider flex items-center gap-1">
                        <span>💡 Analogi: {{ $block['title'] ?? 'Perumpamaan' }}</span>
                    </span>
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-sans">
                        {!! nl2br(e($block['text'] ?? ($block['analogy'] ?? ''))) !!}
                    </p>
                </div>

            @elseif($type === 'dalil')
                <!-- Riset / Studi Block -->
                <div class="bg-emerald-50/20 border border-emerald-100 p-6 rounded-2xl space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                            📚 Studi & Riset: {{ $block['title'] ?? 'Referensi' }}
                        </span>
                    </div>
                    @if(!empty($block['arabic']))
                        <blockquote class="border-l-4 border-emerald-500 pl-4 py-1 italic text-slate-800 text-sm md:text-base leading-relaxed font-sans font-medium">
                            "{!! nl2br(e($block['arabic'])) !!}"
                        </blockquote>
                    @elseif(!empty($block['hadits']))
                        <blockquote class="border-l-4 border-emerald-500 pl-4 py-1 italic text-slate-800 text-sm md:text-base leading-relaxed font-sans font-medium">
                            "{!! nl2br(e($block['hadits'])) !!}"
                        </blockquote>
                    @endif
                    @if(!empty($block['translation']))
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-sans">
                            <span class="font-semibold text-slate-500">Penjelasan:</span> {{ $block['translation'] }}
                        </p>
                    @endif
                    @if(!empty($block['source']))
                        <div class="flex justify-between items-center text-[10px] text-slate-450 pt-2 border-t border-emerald-100/50">
                            <span>Sumber Riset: <strong>{{ $block['source'] }}</strong></span>
                            @if(!empty($block['sourceUrl']))
                                <a href="{{ $block['sourceUrl'] }}" target="_blank" class="text-emerald-700 hover:underline">Rujukan Asli ↗</a>
                            @endif
                        </div>
                    @endif
                </div>

            @elseif($type === 'doa')
                <!-- Tips Khusus Block -->
                <div class="bg-amber-50/25 border border-amber-100 p-6 rounded-2xl space-y-4">
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-150 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                        💡 Tips: {{ $block['title'] ?? 'Rekomendasi' }}
                    </span>
                    @if(!empty($block['arabic']))
                        <p class="text-slate-800 text-sm md:text-base font-semibold leading-relaxed font-sans border-l-4 border-amber-400 pl-4 py-1">{{ $block['arabic'] }}</p>
                    @elseif(!empty($block['doa']))
                        <p class="text-slate-800 text-sm md:text-base font-semibold leading-relaxed font-sans border-l-4 border-amber-400 pl-4 py-1">{{ $block['doa'] }}</p>
                    @endif
                    @if(!empty($block['translation']))
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-sans">
                            <strong class="text-slate-500 font-semibold">Detail:</strong> {{ $block['translation'] }}
                        </p>
                    @elseif(!empty($block['meaning']))
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-sans">
                            <strong class="text-slate-500 font-semibold">Detail:</strong> {{ $block['meaning'] }}
                        </p>
                    @endif
                    @if(!empty($block['latin']))
                        <p class="text-xs italic text-slate-500 font-sans">Sorotan: {{ $block['latin'] }}</p>
                    @endif
                    @if(!empty($block['source']))
                        <div class="flex justify-between items-center text-[10px] text-slate-450 pt-2 border-t border-amber-100/50">
                            <span>Sumber: <strong>{{ $block['source'] }}</strong></span>
                            @if(!empty($block['sourceUrl']))
                                <a href="{{ $block['sourceUrl'] }}" target="_blank" class="text-amber-700 hover:underline">Link Asli ↗</a>
                            @endif
                        </div>
                    @endif
                </div>

            @elseif($type === 'image')
                <!-- Image Block -->
                <div class="space-y-2">
                    <div class="rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 shadow-sm">
                        <img src="{{ $block['url'] ?? '#' }}" alt="{{ $block['caption'] ?? 'Gambar Artikel' }}" class="w-full max-h-96 object-cover">
                    </div>
                    @if(!empty($block['caption']))
                        <span class="block text-center text-[10px] text-slate-400 italic">{{ $block['caption'] }}</span>
                    @endif
                </div>

            @elseif($type === 'video')
                <!-- Video Block -->
                <div class="space-y-2">
                    <div class="aspect-video w-full rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 shadow-sm">
                        @php
                            $youtubeId = '';
                            $url = $block['url'] ?? ($block['youtube_url'] ?? '');
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $match)) {
                                $youtubeId = $match[1];
                            }
                        @endphp
                        @if($youtubeId)
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xs text-slate-400 italic">URL Video YouTube tidak valid.</div>
                        @endif
                    </div>
                    @if(!empty($block['caption']))
                        <span class="block text-center text-[10px] text-slate-400 italic">{{ $block['caption'] }}</span>
                    @endif
                </div>
            @endif
        @empty
            <p class="text-slate-400 italic">Artikel ini tidak memiliki blok konten.</p>
        @endforelse
    </div>

</div>
@endsection
