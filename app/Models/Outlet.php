<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_id', 'kota_id', 'nama_outlet', 'nama_pic', 'whatsapp',
        'alamat_lengkap', 'latitude', 'longitude', 'google_maps_url',
        'featured', 'is_mitra', 'status', 'delivery_mode'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_mitra' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function scopeMitra($query)
    {
        return $query->where('is_mitra', true);
    }

    public function scopeNonMitra($query)
    {
        return $query->where('is_mitra', false);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function shippingContacts(): BelongsToMany
    {
        return $this->belongsToMany(ShippingContact::class, 'petshop_shipping_contacts', 'petshop_id', 'shipping_contact_id')
                    ->withPivot('urutan')
                    ->withTimestamps()
                    ->orderBy('pivot_urutan');
    }

    public function leadRequests(): HasMany
    {
        return $this->hasMany(LeadRequest::class, 'outlet_id');
    }

    public function setWhatsappAttribute($value)
    {
        $clean = preg_replace('/[^0-9]/', '', $value);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        $this->attributes['whatsapp'] = $clean;
    }

    public function getFormattedWhatsappAttribute()
    {
        $clean = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        return $clean;
    }

    protected static function booted()
    {
        static::saving(function ($outlet) {
            // Cek jika latitude/longitude kosong
            if (!$outlet->latitude || !$outlet->longitude || floatval($outlet->latitude) == 0 || floatval($outlet->longitude) == 0) {
                
                // 1. Ekstraksi dari Google Maps URL jika ada
                if ($outlet->google_maps_url) {
                    $coords = static::extractCoordsFromGoogleMaps($outlet->google_maps_url);
                    if ($coords) {
                        $outlet->latitude = $coords['lat'];
                        $outlet->longitude = $coords['lng'];
                        return; // Selesai jika berhasil
                    }
                }

                // 2. Geocode dari alamat & kota jika Google Maps tidak menghasilkan koordinat atau tidak ada
                // Gunakan kota dari relasi jika belum tersimpan, atau query dari kota_id
                $city = $outlet->city ?? \App\Models\City::find($outlet->kota_id);
                $cityName = $city ? $city->nama : '';
                $address = $outlet->alamat_lengkap;

                if ($address && $cityName) {
                    $coords = static::geocodeAddress($address, $cityName);
                    if ($coords) {
                        $outlet->latitude = $coords['lat'];
                        $outlet->longitude = $coords['lng'];
                    }
                }
            }
        });
    }

    /**
     * Resolusi link Google Maps (termasuk short URL) untuk mengekstrak latitude & longitude.
     */
    public static function extractCoordsFromGoogleMaps($url)
    {
        try {
            // Resolusi tautan pendek maps.app.goo.gl / goo.gl/maps
            if (str_contains($url, 'goo.gl') || str_contains($url, 'maps.app.goo.gl')) {
                $response = Http::withoutRedirecting()->timeout(5)->head($url);
                $location = $response->header('Location');
                if ($location) {
                    $url = $location;
                } else {
                    $response = Http::withoutRedirecting()->timeout(5)->get($url);
                    $url = $response->header('Location') ?? $url;
                }
            }

            // Cari koordinat dengan pola @lat,lng
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
                return [
                    'lat' => floatval($matches[1]),
                    'lng' => floatval($matches[2])
                ];
            }

            // Cari koordinat dengan pola q=lat,lng
            if (preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
                return [
                    'lat' => floatval($matches[1]),
                    'lng' => floatval($matches[2])
                ];
            }

            // Cari koordinat dengan pola path /lat,lng
            if (preg_match('/\/(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
                return [
                    'lat' => floatval($matches[1]),
                    'lng' => floatval($matches[2])
                ];
            }
        } catch (\Exception $e) {
            Log::error('Ekstraksi Google Maps gagal: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Memanggil Nominatim API untuk mencari koordinat berdasarkan teks alamat.
     */
    public static function geocodeAddress($address, $cityName)
    {
        try {
            // Gabungkan alamat + kota + negara
            $query = $address . ', ' . $cityName . ', Indonesia';

            $response = Http::withHeaders([
                'User-Agent' => 'BentoCatApp/1.0 (admin@bentocat.id)'
            ])->timeout(8)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                    return [
                        'lat' => floatval($data[0]['lat']),
                        'lng' => floatval($data[0]['lon'])
                    ];
                }
            }
            
            // Jeda 1 detik demi menghormati rate limit Nominatim
            sleep(1);
        } catch (\Exception $e) {
            Log::error('Geocoding Nominatim gagal: ' . $e->getMessage());
        }
        return null;
    }
}
