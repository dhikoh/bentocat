@extends('layouts.admin')

@section('title', 'Kelola Petshop & Outlet')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Outlet / Petshop Terdekat</h1>
            <p class="text-sm text-slate-400">Daftar mitra toko retail (petshop) yang menyediakan stok BentoCat maupun toko prospek.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.outlets.export') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold px-4 py-2.5 rounded-xl shadow-lg transition-all flex items-center gap-2 text-sm">
                <span>Ekspor CSV</span> 📥
            </a>
            <a href="{{ route('admin.outlets.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 text-sm">
                <span>Tambah Outlet</span> 🐾
            </a>
        </div>
    </div>
 
    <!-- Summary Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Distributor Penyuplai</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ $countDistributors }}</span>
            </div>
            <span class="text-2xl bg-blue-500/10 p-3 rounded-xl border border-blue-500/20 text-blue-400">🏢</span>
        </div>
        
        <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Petshop Mitra Resmi</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ $countMitra }}</span>
            </div>
            <span class="text-2xl bg-amber-500/10 p-3 rounded-xl border border-amber-500/20 text-amber-400">🛡️</span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Petshop Non-Mitra</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ $countNonMitra }}</span>
            </div>
            <span class="text-2xl bg-slate-800 p-3 rounded-xl border border-slate-700 text-slate-400">🏪</span>
        </div>
    </div>

    <!-- CSV Bulk Import Panel -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl">
        <h2 class="text-md font-bold text-white mb-2 flex items-center gap-2">
            <span>Impor Bulk Petshop dari CSV</span>
            <span class="text-xs text-amber-500 font-normal">(Mencegah Duplikasi Otomatis berdasarkan WhatsApp / Nama & Kota)</span>
        </h2>
        <form action="{{ route('admin.outlets.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-end md:items-center gap-4">
            @csrf
            <div class="flex-1 w-full">
                <input type="file" name="csv_file" required class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-750 border border-slate-800 bg-slate-950 rounded-xl px-3 py-2 text-slate-300 focus:outline-none focus:border-amber-500">
            </div>
            <button type="submit" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-6 py-2.5 rounded-xl shadow-lg text-sm transition-all whitespace-nowrap">
                Unggah & Proses 🚀
            </button>
        </form>
        <p class="text-[11px] text-slate-500 mt-2">
            Format CSV: <strong>Nama Petshop, Alamat, No WA, Mitra, Kota, Distributor, Kurir</strong> (Pisahkan kurir dengan koma, contoh: <code>Kurir A (0812345678)</code>).
        </p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl">
        <form action="{{ route('admin.outlets.index') }}" method="GET" class="space-y-4">
            <!-- Row 1: Search & Base Filters -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Cari Outlet</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama outlet, PIC, kota, distributor..." class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-605 focus:outline-none transition-all">
                </div>
                
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Hubungan</label>
                    <select name="is_mitra" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-300 focus:outline-none transition-all">
                        <option value="">Semua Hubungan</option>
                        <option value="1" {{ $isMitra === '1' ? 'selected' : '' }}>Mitra Resmi BentoCat</option>
                        <option value="0" {{ $isMitra === '0' ? 'selected' : '' }}>Toko Terdaftar (Non-Mitra)</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Provinsi</label>
                    <select name="provinsi_id" id="filter-province" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-300 focus:outline-none transition-all">
                        <option value="">Semua Provinsi</option>
                        @foreach($provincesList as $prov)
                            <option value="{{ $prov->id }}" {{ $provinceId == $prov->id ? 'selected' : '' }}>{{ $prov->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-700 text-white font-semibold py-2 rounded-xl text-sm transition-all text-center">
                        Cari
                    </button>
                    @if($search || $isMitra !== null || $provinceId || !empty($cityIds))
                        <a href="{{ route('admin.outlets.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 p-2.5 rounded-xl text-sm flex items-center justify-center transition-all" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </div>

            <!-- Row 2: Dynamic City Checkboxes -->
            <div id="city-filter-wrapper" class="border-t border-slate-800/60 pt-3 {{ $provinceId ? '' : 'hidden' }}">
                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-xs font-bold text-slate-400">Pilih Kota:</span>
                        <div class="flex items-center gap-4">
                            <!-- Local Search Box inside Cities checklist -->
                            <input type="text" id="city-local-search" placeholder="Cari kota..." class="bg-slate-950 border border-slate-850 focus:border-amber-500 rounded-lg px-2.5 py-1 text-xs text-slate-350 placeholder:text-slate-700 focus:outline-none transition-all">
                            <label class="inline-flex items-center gap-1.5 text-xs text-slate-400 cursor-pointer select-none">
                                <input type="checkbox" id="check-all-cities" class="rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-0 focus:ring-offset-0">
                                Pilih Semua
                            </label>
                        </div>
                    </div>

                    <!-- Scrollable checklist grid -->
                    <div id="city-checkboxes-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 max-h-40 overflow-y-auto p-3 bg-slate-950/60 border border-slate-850 rounded-xl">
                        @if($provinceId && isset($citiesList))
                            @foreach($citiesList as $city)
                                <label class="city-checkbox-item inline-flex items-center gap-2 text-xs text-slate-300 cursor-pointer hover:text-white transition-colors">
                                    <input type="checkbox" name="city_ids[]" value="{{ $city->id }}" class="city-checkbox rounded border-slate-850 bg-slate-950 text-amber-500 focus:ring-0 focus:ring-offset-0" {{ in_array($city->id, $cityIds) ? 'checked' : '' }}>
                                    <span class="city-name truncate">{{ $city->nama }}</span>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Grid / Table -->
    <div class="bg-slate-900/20 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-500 uppercase bg-slate-900/40 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" id="select-all-outlets" class="rounded bg-slate-950 border-slate-800 text-amber-500 focus:ring-amber-500">
                        </th>
                        <th class="px-6 py-4">Outlet / Kota</th>
                        <th class="px-6 py-4 text-center">Status Mitra</th>
                        <th class="px-6 py-4">Distributor Penyuplai</th>
                        <th class="px-6 py-4">PIC / WhatsApp</th>
                        <th class="px-6 py-4 text-center">Metode Pengiriman</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($outlets as $outlet)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4 w-10">
                                <input type="checkbox" name="outlet_ids[]" value="{{ $outlet->id }}" class="outlet-checkbox rounded bg-slate-950 border-slate-800 text-amber-500 focus:ring-amber-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-semibold text-white">{{ $outlet->nama_outlet }}</span>
                                    @if($outlet->featured)
                                        <span class="text-xs" title="Featured Outlet">⭐️</span>
                                    @endif
                                </div>
                                <span class="block text-xs text-amber-500">{{ $outlet->city->nama }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($outlet->is_mitra)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500/10 border border-amber-500/30 text-amber-400">
                                        🛡️ Mitra Resmi
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 border border-slate-700 text-slate-400">
                                        Non-Mitra
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $outlet->distributor->nama }}
                             </td>
                            <td class="px-6 py-4">
                                <span class="block font-medium text-slate-200">{{ $outlet->nama_pic }}</span>
                                <a href="https://wa.me/{{ $outlet->formatted_whatsapp }}" target="_blank" class="text-xs text-slate-400 hover:underline hover:text-amber-500 flex items-center gap-1">
                                    💬 {{ $outlet->whatsapp }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-slate-400">
                                @if($outlet->delivery_mode === 'SELF_DELIVERY')
                                    <span class="text-emerald-400 font-medium">🛵 Pengiriman Sendiri</span>
                                @elseif($outlet->delivery_mode === 'RECOMMENDED_SHIPPING_CONTACT')
                                    <span class="text-blue-400 font-medium">📋 Kurir Rekomendasi</span>
                                @else
                                    <span class="text-slate-500 font-medium">🚶 Ambil di Toko</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($outlet->status === 'AKTIF')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Aktif</span>
                                @elseif($outlet->status === 'STOK_KOSONG')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 uppercase">Stok Habis</span>
                                @elseif($outlet->status === 'TUTUP')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 border border-slate-700 text-slate-500 uppercase">Tutup</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.outlets.edit', $outlet->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    @if(Auth::user() && Auth::user()->role === 'superadmin')
                                        <form action="{{ route('admin.outlets.destroy', $outlet->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus outlet ini?')">
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
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan outlet/petshop terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($outlets->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $outlets->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Hidden Bulk Action Forms -->
    <form id="bulk-reassign-form" action="{{ route('admin.outlets.batch-reassign') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="distributor_id" id="reassign-distributor-id">
        <div id="reassign-ids-container"></div>
    </form>

    <form id="bulk-delete-form" action="{{ route('admin.outlets.batch-delete') }}" method="POST" class="hidden">
        @csrf
        <div id="delete-ids-container"></div>
    </form>

    <!-- Floating Bulk Action Bar -->
    <div id="bulk-action-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900/90 border border-slate-800 px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md flex flex-col md:flex-row items-center gap-4 z-50 transition-all duration-300 translate-y-24 opacity-0 pointer-events-none max-w-4xl w-[90%] md:w-auto">
        <div class="text-xs font-semibold text-slate-300 whitespace-nowrap">
            <span id="selected-count" class="text-amber-500 font-extrabold text-sm">0</span> outlet dipilih
        </div>
        <div class="h-6 w-px bg-slate-800 hidden md:block"></div>
        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
            <!-- Reassign Distributor -->
            <div class="flex items-center gap-2 bg-slate-950/60 p-1.5 rounded-xl border border-slate-800/85 w-full md:w-auto">
                <select id="bulk-distributor-select" class="bg-transparent border-none focus:ring-0 text-xs text-slate-350 py-1 pl-2 pr-8 focus:outline-none">
                    <option value="" class="bg-slate-900 text-slate-400">Pindahkan ke Distributor...</option>
                    @foreach($distributorsList as $dist)
                        <option value="{{ $dist->id }}" class="bg-slate-900 text-slate-200">{{ $dist->nama }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="submitBulkReassign()" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-3 py-1.5 rounded-lg transition-all whitespace-nowrap">
                    Pindahkan 🚚
                </button>
            </div>

            <!-- Batch Delete (restricted to superadmin) -->
            @if(Auth::user() && Auth::user()->role === 'superadmin')
                <button type="button" onclick="submitBulkDelete()" class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-lg shadow-rose-500/10 transition-all w-full md:w-auto whitespace-nowrap">
                    Hapus Terpilih 🗑️
                </button>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-outlets');
    const checkboxes = document.querySelectorAll('.outlet-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkBar() {
        const checkedBoxes = document.querySelectorAll('.outlet-checkbox:checked');
        const count = checkedBoxes.length;
        selectedCountSpan.textContent = count;

        if (count > 0) {
            bulkBar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
        } else {
            bulkBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkBar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked && selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            updateBulkBar();
        });
    });

    // Province and City Filters Dynamic Loading & Handling
    const provinceSelect = document.getElementById('filter-province');
    const cityFilterWrapper = document.getElementById('city-filter-wrapper');
    const cityCheckboxesContainer = document.getElementById('city-checkboxes-container');
    const checkAllCities = document.getElementById('check-all-cities');
    const cityLocalSearch = document.getElementById('city-local-search');

    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            if (!provinceId) {
                cityFilterWrapper.classList.add('hidden');
                cityCheckboxesContainer.innerHTML = '';
                return;
            }

            // Show loading state
            cityFilterWrapper.classList.remove('hidden');
            cityCheckboxesContainer.innerHTML = '<div class="col-span-full text-xs text-slate-500 py-2">Memuat kota...</div>';

            fetch(`/api/cities-by-province/${provinceId}`)
                .then(res => res.json())
                .then(cities => {
                    cityCheckboxesContainer.innerHTML = '';
                    if (cities.length === 0) {
                        cityCheckboxesContainer.innerHTML = '<div class="col-span-full text-xs text-slate-500 py-2">Tidak ada kota di provinsi ini.</div>';
                        return;
                    }

                    cities.forEach(city => {
                        const label = document.createElement('label');
                        label.className = 'city-checkbox-item inline-flex items-center gap-2 text-xs text-slate-350 cursor-pointer hover:text-white transition-colors';
                        
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'city_ids[]';
                        checkbox.value = city.id;
                        checkbox.className = 'city-checkbox rounded border-slate-850 bg-slate-950 text-amber-500 focus:ring-0 focus:ring-offset-0';
                        
                        const span = document.createElement('span');
                        span.className = 'city-name truncate';
                        span.textContent = city.nama;

                        label.appendChild(checkbox);
                        label.appendChild(span);
                        cityCheckboxesContainer.appendChild(label);
                    });

                    // Reset search and check all
                    if (cityLocalSearch) cityLocalSearch.value = '';
                    if (checkAllCities) checkAllCities.checked = false;
                })
                .catch(err => {
                    console.error('Error fetching cities:', err);
                    cityCheckboxesContainer.innerHTML = '<div class="col-span-full text-xs text-rose-500 py-2">Gagal memuat kota.</div>';
                });
        });
    }

    if (checkAllCities) {
        checkAllCities.addEventListener('change', function() {
            const isChecked = this.checked;
            const visibleItems = cityCheckboxesContainer.querySelectorAll('.city-checkbox-item:not(.hidden) input[type="checkbox"]');
            visibleItems.forEach(cb => {
                cb.checked = isChecked;
            });
        });
    }

    if (cityLocalSearch) {
        cityLocalSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const items = cityCheckboxesContainer.querySelectorAll('.city-checkbox-item');
            
            items.forEach(item => {
                const name = item.querySelector('.city-name').textContent.toLowerCase();
                if (name.includes(query)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });

            if (checkAllCities) checkAllCities.checked = false;
        });
    }
});

function getCheckedOutletIds() {
    const ids = [];
    document.querySelectorAll('.outlet-checkbox:checked').forEach(cb => {
        ids.push(cb.value);
    });
    return ids;
}

function submitBulkReassign() {
    const ids = getCheckedOutletIds();
    const distributorSelect = document.getElementById('bulk-distributor-select');
    const distributorId = distributorSelect.value;

    if (!distributorId) {
        alert('Silakan pilih distributor tujuan terlebih dahulu.');
        return;
    }

    if (ids.length === 0) {
        alert('Tidak ada outlet yang dipilih.');
        return;
    }

    const distributorName = distributorSelect.options[distributorSelect.selectedIndex].text;
    if (confirm(`Apakah Anda yakin ingin memindahkan ${ids.length} outlet terpilih ke distributor "${distributorName}"?`)) {
        const form = document.getElementById('bulk-reassign-form');
        document.getElementById('reassign-distributor-id').value = distributorId;
        
        const container = document.getElementById('reassign-ids-container');
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'outlet_ids[]';
            input.value = id;
            container.appendChild(input);
        });

        form.submit();
    }
}

function submitBulkDelete() {
    const ids = getCheckedOutletIds();
    if (ids.length === 0) {
        alert('Tidak ada outlet yang dipilih.');
        return;
    }

    if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} outlet terpilih? Outlet yang memiliki data lead terhubung akan otomatis dilewati untuk menjaga integritas data.`)) {
        const form = document.getElementById('bulk-delete-form');
        const container = document.getElementById('delete-ids-container');
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'outlet_ids[]';
            input.value = id;
            container.appendChild(input);
        });

        form.submit();
    }
}
</script>
@endsection
