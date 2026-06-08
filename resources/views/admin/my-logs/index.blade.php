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
                            <th class="px-6 py-4">Judul Aktivitas</th>
                            <th class="px-6 py-4">Rincian Kegiatan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300 text-sm">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-amber-400">
                                    {{ $log->log_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-200">
                                    {{ $log->activity_title }}
                                </td>
                                <td class="px-6 py-4 max-w-xs md:max-w-md">
                                    <p class="truncate text-slate-400" title="{{ $log->activity_details }}">
                                        {{ Str::limit($log->activity_details, 80) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.my-logs.edit', $log->id) }}" class="p-2 text-slate-400 hover:text-amber-400 hover:bg-slate-800/80 rounded-xl transition-all" title="Edit Log">
                                            ✏️
                                        </a>
                                        
                                        <form action="{{ route('admin.my-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log aktivitas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-slate-800/80 rounded-xl transition-all cursor-pointer" title="Hapus Log">
                                                🗑️
                                            </button>
                                        </form>
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
