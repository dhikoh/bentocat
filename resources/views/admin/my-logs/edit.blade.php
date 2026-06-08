@extends('layouts.admin')

@section('title', 'Ubah Aktivitas Harian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.my-logs.index') }}" class="p-3 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-slate-300 rounded-2xl transition-all font-semibold">
            ⬅️ Kembali
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Ubah Log Aktivitas</h1>
            <p class="text-slate-400 mt-1">Ubah atau lengkapi detail catatan aktivitas harian Anda.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-sm">
            <span class="font-bold block mb-1">Terjadi kesalahan validasi:</span>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8">
        <form action="{{ route('admin.my-logs.update', $log->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="log_date" class="block text-xs font-bold text-slate-400 uppercase">Tanggal Kegiatan</label>
                <input type="date" name="log_date" id="log_date" value="{{ old('log_date', $log->log_date->format('Y-m-d')) }}" required
                       class="w-full max-w-xs bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="activity_title" class="block text-xs font-bold text-slate-400 uppercase">Judul Aktivitas / Pekerjaan</label>
                <input type="text" name="activity_title" id="activity_title" value="{{ old('activity_title', $log->activity_title) }}" required
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="activity_details" class="block text-xs font-bold text-slate-400 uppercase">Detail Kegiatan (Workflow & Hasil)</label>
                <textarea name="activity_details" id="activity_details" rows="8" required
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('activity_details', $log->activity_details) }}</textarea>
            </div>

            <div class="flex items-center justify-between border-t border-slate-800 pt-6">
                <span class="text-xs text-slate-500">Pastikan rincian yang diubah sudah sesuai.</span>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-3 rounded-2xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer">
                    <span>Simpan Perubahan</span> 💾
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
