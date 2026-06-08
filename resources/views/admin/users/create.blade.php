@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Tambah Pengguna</h1>
            <p class="text-slate-400 mt-1">Daftarkan akun baru untuk mengelola panel admin BentoCat.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl text-sm transition-all">
            ← Kembali
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-400 text-sm space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <span>⚠️</span> Mohon perbaiki kesalahan berikut:
                    </div>
                    <ul class="list-disc list-inside pl-2 text-xs space-y-0.5 opacity-90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-2">
                <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Nama lengkap staf..."
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="email@bentocat.com"
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="role" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Role / Hak Akses</label>
                <select name="role" id="role" required class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    <option value="">-- Pilih Role --</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>👑 Superadmin</option>
                    <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>📝 Editor</option>
                    <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>🚀 Marketing</option>
                    <option value="contributor" {{ old('role') == 'contributor' ? 'selected' : '' }}>👤 Contributor</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter..."
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ketik ulang password..."
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800/60">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition-all text-sm">
                    Batal
                </a>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-6 py-3 rounded-xl shadow-lg shadow-amber-500/10 transition-all cursor-pointer text-sm">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
