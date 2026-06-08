@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Manajemen Pengguna</h1>
            <p class="text-slate-400 mt-1">Kelola dan atur hak akses akun pengguna terdaftar untuk dashboard BentoCat.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-3 rounded-2xl shadow-lg shadow-amber-500/10 transition-all cursor-pointer">
                <span>Tambah Pengguna</span> ➕
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-sm flex items-center gap-3">
        <span>✅</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-400 text-sm flex items-center gap-3">
        <span>⚠️</span>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="space-y-2">
                <label for="search" class="block text-xs font-bold text-slate-400 uppercase">Cari Pengguna</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="role" class="block text-xs font-bold text-slate-400 uppercase">Role / Hak Akses</label>
                <select name="role" id="role" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    <option value="">Semua Role</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    <option value="editor" {{ request('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="marketing" {{ request('role') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="contributor" {{ request('role') == 'contributor' ? 'selected' : '' }}>Contributor</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl transition-all cursor-pointer text-center text-sm">
                    Filter 🔍
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition-all text-center text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table List -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-slate-800/80">
            <h3 class="font-bold text-slate-200">Daftar Akun Terdaftar</h3>
        </div>

        @if($users->isEmpty())
            <div class="p-12 text-center">
                <span class="text-4xl block mb-3">👥</span>
                <h3 class="text-slate-300 font-bold">Tidak ada pengguna ditemukan</h3>
                <p class="text-slate-500 text-sm mt-1">Coba sesuaikan kata kunci filter pencarian Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-950/40">
                            <th class="px-6 py-4">Nama & Email</th>
                            <th class="px-6 py-4">Role / Akses</th>
                            <th class="px-6 py-4">Tanggal Bergabung</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300 text-sm">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-200">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'superadmin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            👑 Superadmin
                                        </span>
                                    @elseif($user->role === 'editor')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            📝 Editor
                                        </span>
                                    @elseif($user->role === 'marketing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                            🚀 Marketing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700/50">
                                            👤 Contributor
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                    {{ $user->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-lg transition-all">
                                            Edit ✏️
                                        </a>
                                        <a href="{{ route('admin.users.reset-password', $user->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold rounded-lg transition-all">
                                            Kunci 🔑
                                        </a>
                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 font-bold rounded-lg transition-all cursor-pointer">
                                                    Hapus 🗑️
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-3 py-1.5 text-slate-600 font-bold bg-slate-900/60 rounded-lg cursor-not-allowed select-none" title="Anda tidak dapat menghapus akun Anda sendiri">
                                                Akun Anda
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-6 border-t border-slate-800/80 bg-slate-950/20">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
