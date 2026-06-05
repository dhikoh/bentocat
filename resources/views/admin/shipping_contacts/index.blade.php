@extends('layouts.admin')

@section('title', 'Kelola Kontak Pengiriman')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Kontak Pengiriman Lokal</h1>
            <p class="text-sm text-slate-400">Daftar kurir lokal, ojek online, atau ekspedisi wilayah yang direkomendasikan outlet.</p>
        </div>
        <a href="{{ route('admin.shipping-contacts.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2">
            <span>Tambah Kurir/Ekspedisi</span> 🐾
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.shipping-contacts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kurir atau nomor WhatsApp..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.shipping-contacts.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm flex items-center justify-center transition-all">
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
                        <th class="px-6 py-4">Nama Kurir / Ekspedisi</th>
                        <th class="px-6 py-4">Nomor WhatsApp</th>
                        <th class="px-6 py-4">Keterangan Wilayah / Tarif</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4 font-semibold text-white">{{ $contact->nama }}</td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}" target="_blank" class="text-sm font-medium text-slate-200 hover:underline hover:text-amber-500 flex items-center gap-1">
                                    💬 {{ $contact->whatsapp }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 max-w-sm truncate" title="{{ $contact->keterangan }}">
                                {{ $contact->keterangan ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($contact->aktif)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.shipping-contacts.edit', $contact->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    @if(Auth::user() && Auth::user()->role === 'superadmin')
                                        <form action="{{ route('admin.shipping-contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontak pengiriman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan kontak pengiriman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($contacts->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $contacts->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
