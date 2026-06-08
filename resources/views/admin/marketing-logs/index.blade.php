@extends('layouts.admin')

@section('title', 'Log Kerja Marketing')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Log Kerja Marketing</h1>
            <p class="text-slate-400 mt-1">Pantau produktivitas harian staf marketing dan salin prompt analisis kinerja untuk dievaluasi oleh AI.</p>
        </div>
        <div>
            <a href="{{ route('admin.marketing-logs.export', request()->all()) }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-3 rounded-2xl shadow-lg shadow-emerald-500/10 transition-all cursor-pointer">
                <span>Ekspor CSV</span> 📊
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6">
        <form action="{{ route('admin.marketing-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="space-y-2">
                <label for="user_id" class="block text-xs font-bold text-slate-400 uppercase">Staf Pemasaran</label>
                <select name="user_id" id="user_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    <option value="">Semua Marketing</option>
                    @foreach($marketingUsers as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-2">
                <label for="start_date" class="block text-xs font-bold text-slate-400 uppercase">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="end_date" class="block text-xs font-bold text-slate-400 uppercase">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl transition-all cursor-pointer text-center text-sm">
                    Filter 🔍
                </button>
                <a href="{{ route('admin.marketing-logs.index') }}" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition-all text-center text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- AI Prompt Copy Widget -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-amber-400 flex items-center gap-2">
                    <span>🤖</span> Prompt Evaluasi Kinerja AI
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Prompt di bawah ini berisi seluruh log aktivitas yang terfilter. Anda dapat menyalinnya langsung untuk dievaluasi pada AI (ChatGPT/Gemini/Claude).</p>
            </div>
            <button type="button" onclick="copyAiPrompt()" id="btn-copy-prompt" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer text-sm">
                <span id="copy-icon">📋</span> <span id="copy-text">Salin Prompt AI</span>
            </button>
        </div>
        
        <div class="relative bg-slate-950/80 border border-slate-800 rounded-2xl p-4 max-h-60 overflow-y-auto scrollbar-thin">
            <pre id="ai-prompt-content" class="text-slate-300 text-xs font-mono whitespace-pre-wrap leading-relaxed">{{ $aiPrompt }}</pre>
        </div>
    </div>

    <!-- Logs Table List -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-slate-800/80">
            <h3 class="font-bold text-slate-200">Catatan Aktivitas Staf Marketing</h3>
        </div>

        @if($logs->isEmpty())
            <div class="p-12 text-center">
                <span class="text-4xl block mb-3">📭</span>
                <h3 class="text-slate-300 font-bold">Tidak ada catatan ditemukan</h3>
                <p class="text-slate-500 text-sm mt-1">Coba sesuaikan filter atau tanggal pencarian Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-950/40">
                            <th class="px-6 py-4">Nama Staff</th>
                            <th class="px-6 py-4">Tanggal & Aktivitas</th>
                            <th class="px-6 py-4">Outlet & Customer</th>
                            <th class="px-6 py-4">Agenda Tindak Lanjut</th>
                            <th class="px-6 py-4">Evaluasi & Kinerja</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300 text-sm">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="font-bold text-slate-200">{{ $log->user->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $log->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="font-semibold text-amber-400 mb-1">
                                        {{ $log->log_date->format('d M Y') }}
                                    </div>
                                    <div class="font-bold text-slate-200 mb-2">
                                        {{ $log->activity_title }}
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-slate-400 text-xs line-clamp-2" id="detail-summary-{{ $log->id }}">
                                            {{ Str::limit($log->activity_details, 150) }}
                                        </p>
                                        @if(strlen($log->activity_details) > 150)
                                            <button type="button" onclick="toggleDetail({{ $log->id }})" id="btn-toggle-{{ $log->id }}" class="text-xs text-amber-500 font-semibold hover:underline focus:outline-none cursor-pointer">
                                                Selengkapnya ↓
                                            </button>
                                            <p class="text-slate-400 text-xs hidden whitespace-pre-wrap leading-relaxed mt-2 p-3 bg-slate-950/40 rounded-xl border border-slate-800" id="detail-full-{{ $log->id }}">
                                                {{ $log->activity_details }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($log->outlet)
                                            <div class="flex items-center gap-1.5 text-xs text-emerald-400">
                                                <span>🏢</span>
                                                <span class="font-semibold">{{ $log->outlet->name }}</span>
                                            </div>
                                        @endif
                                        @if($log->customerProfile)
                                            <div class="flex items-center gap-1.5 text-xs text-sky-400">
                                                <span>👤</span>
                                                <span class="font-semibold">{{ $log->customerProfile->nama }}</span>
                                            </div>
                                        @endif
                                        @if(!$log->outlet && !$log->customerProfile)
                                            <span class="text-xs text-slate-500 italic">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top max-w-xs">
                                    @if($log->agenda)
                                        <p class="text-slate-300 text-xs italic line-clamp-3" title="{{ $log->agenda }}">
                                            {{ $log->agenda }}
                                        </p>
                                    @else
                                        <span class="text-xs text-slate-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <div class="space-y-2">
                                        @if($log->rating)
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-0.5 text-amber-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span>{{ $i <= $log->rating ? '★' : '☆' }}</span>
                                                    @endfor
                                                </div>
                                                @if($log->notes)
                                                    <p class="text-slate-400 text-xs italic max-w-xs whitespace-normal break-words" title="{{ $log->notes }}">
                                                        "{{ $log->notes }}"
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">
                                                    Belum dinilai
                                                </span>
                                            </div>
                                        @endif

                                        <button type="button" onclick="toggleEvaluateForm({{ $log->id }})" class="inline-flex items-center gap-1 text-xs text-amber-500 hover:underline focus:outline-none cursor-pointer font-bold mt-2">
                                            <span>✏️</span> {{ $log->rating ? 'Ubah Penilaian' : 'Beri Nilai Kinerja' }}
                                        </button>
                                        
                                        <!-- Evaluate Form Container -->
                                        <div id="evaluate-form-{{ $log->id }}" class="hidden mt-3 p-4 bg-slate-950 border border-slate-800 rounded-2xl space-y-3 whitespace-normal max-w-xs shadow-xl">
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Formulir Penilaian</div>
                                            <form action="{{ route('admin.marketing-logs.evaluate', $log->id) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div class="space-y-1">
                                                    <label for="rating_{{ $log->id }}" class="block text-xs font-bold text-slate-400">Rating Kinerja:</label>
                                                    <select name="rating" id="rating_{{ $log->id }}" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                                                        <option value="">-- Pilih Rating --</option>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <option value="{{ $i }}" {{ $log->rating == $i ? 'selected' : '' }}>
                                                                {{ $i }} ★ ({{ ['Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'][$i-1] }})
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="space-y-1">
                                                    <label for="notes_{{ $log->id }}" class="block text-xs font-bold text-slate-400">Umpan Balik / Catatan:</label>
                                                    <textarea name="notes" id="notes_{{ $log->id }}" rows="2" class="w-full bg-slate-900 border border-slate-800 focus:border-amber-500 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 focus:outline-none" placeholder="Tuliskan umpan balik untuk marketing...">{{ $log->notes }}</textarea>
                                                </div>
                                                <div class="flex justify-end gap-2 pt-1">
                                                    <button type="button" onclick="toggleEvaluateForm({{ $log->id }})" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold px-3 py-1.5 rounded-lg text-xs cursor-pointer">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-3 py-1.5 rounded-lg text-xs cursor-pointer shadow-lg shadow-amber-500/10">
                                                        Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-6 border-t border-slate-800/80 bg-slate-950/20">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
    function copyAiPrompt() {
        const text = document.getElementById('ai-prompt-content').innerText;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('btn-copy-prompt');
            const icon = document.getElementById('copy-icon');
            const textEl = document.getElementById('copy-text');

            icon.innerText = '✅';
            textEl.innerText = 'Berhasil Disalin!';
            btn.classList.remove('bg-amber-500', 'hover:bg-amber-600');
            btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700', 'text-white');

            setTimeout(() => {
                icon.innerText = '📋';
                textEl.innerText = 'Salin Prompt AI';
                btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700', 'text-white');
                btn.classList.add('bg-amber-500', 'hover:bg-amber-600', 'text-slate-950');
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
            alert('Gagal menyalin prompt. Silakan salin secara manual.');
        });
    }

    function toggleDetail(id) {
        const summary = document.getElementById('detail-summary-' + id);
        const full = document.getElementById('detail-full-' + id);
        const btn = document.getElementById('btn-toggle-' + id);

        if (full.classList.contains('hidden')) {
            summary.classList.add('hidden');
            full.classList.remove('hidden');
            btn.innerText = 'Sembunyikan ↑';
        } else {
            summary.classList.remove('hidden');
            full.classList.add('hidden');
            btn.innerText = 'Selengkapnya ↓';
        }
    }

    function toggleEvaluateForm(id) {
        const form = document.getElementById('evaluate-form-' + id);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }
</script>
@endsection
