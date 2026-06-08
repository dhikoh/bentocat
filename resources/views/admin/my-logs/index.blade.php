@extends('layouts.admin')

@section('title', 'Log Kerja Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Log Kerja Saya</h1>
            <p class="text-slate-400 mt-1">Catat aktivitas pemasaran, promosi outlet, dan interaksi leads harian Anda di sini.</p>
        </div>
        <div>
            <a href="{{ route('admin.my-logs.create') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-3 rounded-2xl shadow-lg shadow-amber-500/10 transition-all cursor-pointer">
                <span>Tambah Aktivitas Harian</span> 📝
            </a>
        </div>
    </div>

    <!-- Logs Table Card -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-slate-800/80">
            <h3 class="font-bold text-slate-200">Riwayat Catatan Harian</h3>
        </div>

        @if($logs->isEmpty())
            <div class="p-12 text-center">
                <span class="text-4xl block mb-3">📭</span>
                <h3 class="text-slate-300 font-bold">Belum ada catatan aktivitas</h3>
                <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">Silakan mulai menambahkan catatan kerja harian Anda dengan tombol di atas.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-950/40">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Aktivitas & Rincian</th>
                            <th class="px-6 py-4">Outlet & Customer</th>
                            <th class="px-6 py-4">Agenda Tindak Lanjut</th>
                            <th class="px-6 py-4">Nilai & Evaluasi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300 text-sm">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-amber-400">
                                    {{ $log->log_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="font-bold text-slate-200">{{ $log->activity_title }}</div>
                                    <p class="text-xs text-slate-400 mt-1 line-clamp-2" title="{{ $log->activity_details }}">
                                        {{ Str::limit($log->activity_details, 120) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 max-w-xs">
                                    @if($log->agenda)
                                        <p class="text-slate-300 text-xs italic line-clamp-2" title="{{ $log->agenda }}">
                                            {{ $log->agenda }}
                                        </p>
                                    @else
                                        <span class="text-xs text-slate-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->rating)
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-0.5 text-amber-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $log->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            @if($log->notes)
                                                <p class="text-slate-400 text-xs line-clamp-1 italic max-w-xs" title="{{ $log->notes }}">
                                                    "{{ $log->notes }}"
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-400">
                                            Belum dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.my-logs.edit', $log->id) }}" class="p-2 text-slate-400 hover:text-amber-400 hover:bg-slate-800/80 rounded-xl transition-all" title="Edit Log">
                                            ✏️
                                        </a>
                                        
                                        @if(Auth::user()->role !== 'marketing')
                                            <form action="{{ route('admin.my-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log aktivitas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-slate-800/80 rounded-xl transition-all cursor-pointer" title="Hapus Log">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
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
@endsection
