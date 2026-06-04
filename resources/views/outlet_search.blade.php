@extends('layouts.client')

@section('title', 'Hasil Pencarian Petshop BentoCat Resmi - Kota ' . $city->nama)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">

    <!-- Search overview header -->
    <div class="space-y-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}#cari-outlet" class="text-xs font-bold text-amber-650 hover:text-amber-750 hover:underline">← Ulangi Pencarian</a>
        </div>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-slate-900">Petshop Resmi di Kota {{ $city->nama }}</h1>
        <p class="text-sm text-slate-600">Menemukan {{ $outlets->count() }} outlet aktif yang menjual produk pasir kucing BentoCat.</p>
    </div>

    <!-- Map Container (Optional, rendered if coordinates exist) -->
    <div class="bg-white border border-slate-100 rounded-3xl p-4 shadow-md shadow-amber-900/5 overflow-hidden">
        <div id="results-map" class="h-64 sm:h-80 w-full rounded-2xl border border-slate-150 bg-slate-50"></div>
    </div>

    <!-- Outlets Grid list -->
    <div class="space-y-6">
        @forelse($outlets as $outlet)
            <div class="bg-white border {{ !$outlet->is_mitra ? 'border-slate-200/80 opacity-90' : ($outlet->featured ? 'border-amber-500 bg-amber-50/20 shadow-md' : 'border-slate-100 shadow-md') }} p-6 sm:p-8 rounded-3xl space-y-6 relative hover:shadow-lg transition-all">
                
                <!-- Recommendation badge or Non-Mitra status -->
                @if($outlet->is_mitra)
                    @if($outlet->featured)
                        <span class="absolute top-6 right-6 bg-amber-500 text-slate-950 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            ★ Rekomendasi Utama
                        </span>
                    @endif
                @else
                    <span class="absolute top-6 right-6 bg-slate-100 border border-slate-200 text-slate-550 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                        ⚠️ Mitra Non-Aktif / Belum Menyediakan Produk
                    </span>
                @endif

                <div class="space-y-3">
                    <h3 class="font-outfit font-black text-xl text-slate-900 flex items-center gap-2">
                        <span>🏪 {{ $outlet->nama_outlet }}</span>
                        @if(isset($outlet->distance) && $outlet->distance < 99999)
                            <span class="text-xs text-amber-750 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-lg">
                                {{ number_format($outlet->distance, 2) }} km dari Anda
                            </span>
                        @endif
                    </h3>
                    <p class="text-xs text-slate-550 max-w-2xl leading-relaxed">{{ $outlet->alamat }}</p>
                </div>

                @if($outlet->is_mitra)
                    <!-- Delivery modes grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2">
                            <span>🏠</span>
                            <div>
                                <span class="block font-semibold">Toko Mandiri (Self-delivery)</span>
                                <span class="block text-[10px] text-slate-450">{{ $outlet->delivery_mode === 'SELF_DELIVERY' || $outlet->delivery_mode === 'BOTH' ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 border-t sm:border-t-0 sm:border-x border-slate-200 pt-2 sm:pt-0 sm:px-3">
                            <span>🚚</span>
                            <div>
                                <span class="block font-semibold">Rekomendasi Kurir Lokal</span>
                                <span class="block text-[10px] text-slate-450">{{ $outlet->delivery_mode === 'RECOMMENDED_SHIPPING_CONTACT' || $outlet->delivery_mode === 'BOTH' ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 border-t sm:border-t-0 pt-2 sm:pt-0">
                            <span>🏪</span>
                            <div>
                                <span class="block font-semibold">Ambil Sendiri (Pickup)</span>
                                <span class="block text-[10px] text-slate-450">Selalu Tersedia</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <!-- Action WA Redirect Button -->
                    <button onclick="contactOutlet({{ $outlet->id }}, '{{ $outlet->whatsapp }}', '{{ $outlet->nama_outlet }}', {{ $outlet->is_mitra ? 'true' : 'false' }})" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2 text-sm">
                        <span>Hubungi Petshop via WhatsApp</span> 💬
                    </button>
                    @if($outlet->maps_url)
                        <a href="{{ $outlet->maps_url }}" target="_blank" class="bg-white border border-slate-200 text-slate-700 font-semibold px-6 py-3 rounded-xl hover:bg-slate-50 transition-all text-xs flex items-center justify-center gap-1.5 shadow-sm font-sans">
                            <span>Petunjuk Arah</span> 🗺️
                        </a>
                    @endif
                </div>

                <!-- Shipping couriers section -->
                @if($outlet->is_mitra && ($outlet->delivery_mode === 'RECOMMENDED_SHIPPING_CONTACT' || $outlet->delivery_mode === 'BOTH') && $outlet->shippingContacts->count() > 0)
                    <div class="border-t border-slate-100 pt-6 space-y-4">
                        <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                            <span>🚚 Kurir Lokal Terdekat Direkomendasikan Petshop:</span>
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($outlet->shippingContacts as $courier)
                                <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl flex flex-col justify-between gap-3 shadow-sm">
                                    <div class="space-y-1">
                                        <span class="block font-bold text-xs text-slate-900">{{ $courier->nama }}</span>
                                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ $courier->keterangan ?: 'Melayani area jangkauan toko.' }}</p>
                                    </div>
                                    <button onclick="contactCourier({{ $outlet->id }}, '{{ $courier->whatsapp }}', '{{ $courier->nama }}')" class="w-full bg-white border border-slate-200 hover:border-amber-500/30 hover:text-amber-650 text-slate-650 text-xs font-semibold py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm font-sans">
                                        <span>Chat Kurir Mas Joko</span> 💬
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @empty
            <!-- Fallback distributor view when no outlets are active in city -->
            <div class="bg-white border border-amber-200/60 p-8 rounded-3xl text-center shadow-md space-y-6">
                <span class="text-4xl block">😿</span>
                <div class="space-y-2">
                    <h3 class="font-outfit font-black text-xl text-slate-900">Belum Ada Petshop Terdaftar di Kota {{ $city->nama }}</h3>
                    <p class="text-xs text-slate-600 max-w-xl mx-auto leading-relaxed">
                        Kami sedang memperluas jangkauan ke petshop di daerah Anda. Untuk saat ini, Anda dapat memesan langsung melalui distributor utama wilayah kami dengan opsi kirim kurir gudang.
                    </p>
                </div>

                @if($allocatedDistributor)
                    <div class="bg-slate-50 max-w-md mx-auto p-5 rounded-2xl border border-slate-100 space-y-4 text-left shadow-sm">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Distributor Resmi Wilayah:</span>
                        <div class="space-y-1 text-xs">
                            <strong class="block text-slate-900 text-sm">{{ $allocatedDistributor->nama }}</strong>
                            <span class="block text-slate-600">WhatsApp PIC: {{ $allocatedDistributor->whatsapp }} ({{ $allocatedDistributor->pic }})</span>
                            <span class="block text-slate-600">Gudang Alamat: {{ $allocatedDistributor->alamat }}</span>
                        </div>
                        <button onclick="contactDistributor('{{ $allocatedDistributor->whatsapp }}', '{{ $allocatedDistributor->nama }}')" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2 text-xs">
                            <span>Hubungi Distributor Resmi</span> 💬
                        </button>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Distributor wilayah belum terdaftar.</p>
                @endif
            </div>
        @endforelse
    </div>

</div>
@endsection

@section('scripts')
<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""></script>

<script>
    // Lead ID passed from controller
    const leadId = {{ $lead->id }};
    const customerName = "{{ $customer->nama }}";
    const productName = "{{ $lead->product->nama }}";
    const aroma = "{{ $lead->varian_level_2 ?? '-' }}";
    const size = "{{ $lead->varian_level_3 ?? '-' }}";
    const varianText = "{{ $lead->varian_level_1 }} {{ $lead->varian_level_2 ? ' • ' . $lead->varian_level_2 : '' }}";

    // Setup Leaflet map
    const map = L.map('results-map').setView([-7.2575, 112.7521], 11); // Fallback Surabaya coordinates

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const markers = [];

    // Add user marker if coordinates are shared
    const userLat = {{ $customer->latitude ?: 'null' }};
    const userLon = {{ $customer->longitude ?: 'null' }};

    if (userLat && userLon) {
        const userIcon = L.divIcon({
            html: '📍',
            iconSize: [30, 30],
            className: 'text-2xl text-center'
        });
        L.marker([userLat, userLon], { icon: userIcon }).addTo(map).bindPopup('Lokasi Anda').openPopup();
        map.setView([userLat, userLon], 13);
    }

    // Add outlets markers
    @foreach($outlets as $outlet)
        @if($outlet->latitude && $outlet->longitude)
            (function() {
                const marker = L.marker([{{ $outlet->latitude }}, {{ $outlet->longitude }}])
                    .addTo(map)
                    .bindPopup(`<strong>{{ $outlet->nama_outlet }}</strong><br/><span style="font-size: 11px;">{{ Str::limit($outlet->alamat, 60) }}</span>`);
                markers.push(marker);
            })();
        @endif
    @endforeach

    // Fit map bounds to show all markers if any exist
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    // AJAX Action loggers
    function contactOutlet(outletId, whatsapp, outletName, isMitra) {
        fetch('{{ route("leads.log-action") }}', {
            method: 'POST',
            body: JSON.stringify({
                lead_id: leadId,
                action_type: 'CLICK_WA_OUTLET'
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .finally(() => {
            let message = '';
            if (isMitra) {
                message = `Halo ${outletName}, saya ingin membeli produk BentoCat ${productName} - ${aroma} - ${size} dari web bentocat.id. Apakah stok tersedia?`;
            } else {
                message = `Halo ${outletName}, saya melihat toko Anda di bentocat.id. Apakah Anda menyediakan pasir kucing BentoCat? Saya sangat tertarik untuk membelinya.`;
            }
            const waUrl = `https://wa.me/${whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        });
    }

    function contactCourier(outletId, whatsapp, courierName) {
        fetch('{{ route("leads.log-action") }}', {
            method: 'POST',
            body: JSON.stringify({
                lead_id: leadId,
                action_type: 'CLICK_WA_SHIPPING_CONTACT'
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .finally(() => {
            const message = `Halo ${courierName}, saya ${customerName} merujuk dari website BentoCat. Ingin memesan pengantaran pasir kucing dari petshop ke alamat rumah saya.`;
            const waUrl = `https://wa.me/${whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        });
    }

    function contactDistributor(whatsapp, distName) {
        const message = `Halo ${distName}, saya ${customerName} melihat dari website BentoCat. Di kota saya belum ada petshop resmi terdaftar, apakah saya bisa membeli BentoCat ${productName} varian ${varianText} langsung dikirim dari distributor?`;
        const waUrl = `https://wa.me/${whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }
</script>
@endsection
