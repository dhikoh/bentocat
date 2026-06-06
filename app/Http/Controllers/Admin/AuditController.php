<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Distributor;
use App\Models\ShippingContact;
use App\Models\CustomerProfile;
use App\Models\LeadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !in_array(auth()->user()->role, ['superadmin', 'editor'])) {
            abort(403, 'Unauthorized action. Hanya Superadmin dan Editor yang dapat mengakses audit database.');
        }

        // 1. Data Duplication Audit - Petshops
        // Group by WhatsApp
        $petshopDupesByWa = Outlet::select('whatsapp', DB::raw('count(id) as count'))
            ->groupBy('whatsapp')
            ->having('count', '>', 1)
            ->get();

        $petshopWaGroups = [];
        foreach ($petshopDupesByWa as $dupe) {
            $petshopWaGroups[$dupe->whatsapp] = Outlet::with('city')
                ->where('whatsapp', $dupe->whatsapp)
                ->orderBy('id')
                ->get();
        }

        // Group by Name + City
        $petshopDupesByNameCity = Outlet::select('nama_outlet', 'kota_id', DB::raw('count(id) as count'))
            ->groupBy('nama_outlet', 'kota_id')
            ->having('count', '>', 1)
            ->get();

        $petshopNameCityGroups = [];
        foreach ($petshopDupesByNameCity as $dupe) {
            $key = $dupe->nama_outlet . ' - ' . $dupe->kota_id;
            $petshopNameCityGroups[$key] = Outlet::with('city')
                ->where('nama_outlet', $dupe->nama_outlet)
                ->where('kota_id', $dupe->kota_id)
                ->orderBy('id')
                ->get();
        }

        // 2. Data Duplication Audit - Distributors
        // Group by WhatsApp
        $distributorDupesByWa = Distributor::select('whatsapp', DB::raw('count(id) as count'))
            ->groupBy('whatsapp')
            ->having('count', '>', 1)
            ->get();

        $distributorWaGroups = [];
        foreach ($distributorDupesByWa as $dupe) {
            $distributorWaGroups[$dupe->whatsapp] = Distributor::with('city')
                ->where('whatsapp', $dupe->whatsapp)
                ->orderBy('id')
                ->get();
        }

        // Group by Name + City
        $distributorDupesByNameCity = Distributor::select('nama', 'kota_id', DB::raw('count(id) as count'))
            ->groupBy('nama', 'kota_id')
            ->having('count', '>', 1)
            ->get();

        $distributorNameCityGroups = [];
        foreach ($distributorDupesByNameCity as $dupe) {
            $key = $dupe->nama . ' - ' . $dupe->kota_id;
            $distributorNameCityGroups[$key] = Distributor::with('city')
                ->where('nama', $dupe->nama)
                ->where('kota_id', $dupe->kota_id)
                ->orderBy('id')
                ->get();
        }

        // 3. Data Duplication Audit - Shipping Contacts / Couriers
        // Group by WhatsApp
        $courierDupesByWa = ShippingContact::select('whatsapp', DB::raw('count(id) as count'))
            ->groupBy('whatsapp')
            ->having('count', '>', 1)
            ->get();

        $courierWaGroups = [];
        foreach ($courierDupesByWa as $dupe) {
            $courierWaGroups[$dupe->whatsapp] = ShippingContact::where('whatsapp', $dupe->whatsapp)
                ->orderBy('id')
                ->get();
        }

        // Group by Name
        $courierDupesByName = ShippingContact::select('nama', DB::raw('count(id) as count'))
            ->groupBy('nama')
            ->having('count', '>', 1)
            ->get();

        $courierNameGroups = [];
        foreach ($courierDupesByName as $dupe) {
            $courierNameGroups[$dupe->nama] = ShippingContact::where('nama', $dupe->nama)
                ->orderBy('id')
                ->get();
        }

        // 4. Business Health Metrics
        $totalCustomers = CustomerProfile::count();
        $repeatCustomers = CustomerProfile::has('leadRequests', '>=', 2)->count();
        $retentionRate = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0;

        $activeMitraCount = Outlet::mitra()->where('status', 'AKTIF')->count();
        $activeMitraWithCourierCount = Outlet::mitra()
            ->where('status', 'AKTIF')
            ->has('shippingContacts')
            ->count();
        $courierAttachmentRate = $activeMitraCount > 0 ? round(($activeMitraWithCourierCount / $activeMitraCount) * 100, 1) : 0;

        $totalOutlets = Outlet::count();
        $activeOutlets = Outlet::where('status', 'AKTIF')->count();
        $partnerActiveRatio = $totalOutlets > 0 ? round(($activeOutlets / $totalOutlets) * 100, 1) : 0;

        // Supply Gap Index: Cities with leads but no active petshops
        $supplyGapCities = DB::table('lead_requests')
            ->join('cities', 'lead_requests.kota_id', '=', 'cities.id')
            ->select('cities.id', 'cities.nama', DB::raw('count(lead_requests.id) as total_demand'))
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('outlets')
                    ->whereColumn('outlets.kota_id', 'cities.id')
                    ->where('outlets.is_mitra', true)
                    ->where('outlets.status', 'AKTIF');
            })
            ->groupBy('cities.id', 'cities.nama')
            ->orderByDesc('total_demand')
            ->limit(10)
            ->get();

        return view('admin.audit.index', compact(
            'petshopWaGroups', 'petshopNameCityGroups',
            'distributorWaGroups', 'distributorNameCityGroups',
            'courierWaGroups', 'courierNameGroups',
            'totalCustomers', 'repeatCustomers', 'retentionRate',
            'courierAttachmentRate', 'partnerActiveRatio', 'supplyGapCities'
        ));
    }

    public function merge(Request $request)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action. Hanya Superadmin yang dapat melakukan penyatuan data.');
        }

        $request->validate([
            'type' => 'required|in:petshop,distributor,kurir',
            'target_id' => 'required|integer',
            'duplicate_id' => 'required|integer',
        ]);

        $type = $request->input('type');
        $targetId = $request->input('target_id');
        $duplicateId = $request->input('duplicate_id');

        if ($targetId == $duplicateId) {
            return back()->with('error', 'Data utama dan data duplikat tidak boleh sama.');
        }

        try {
            DB::transaction(function () use ($type, $targetId, $duplicateId) {
                if ($type === 'petshop') {
                    $target = Outlet::findOrFail($targetId);
                    $duplicate = Outlet::findOrFail($duplicateId);

                    // 1. Move LeadRequests
                    LeadRequest::where('outlet_id', $duplicateId)->update(['outlet_id' => $targetId]);

                    // 2. Move ShippingContacts (avoid key violations)
                    $targetShippingIds = $target->shippingContacts()->pluck('shipping_contacts.id')->toArray();
                    
                    $duplicateShippingLinks = DB::table('petshop_shipping_contacts')
                        ->where('petshop_id', $duplicateId)
                        ->get();

                    foreach ($duplicateShippingLinks as $link) {
                        if (in_array($link->shipping_contact_id, $targetShippingIds)) {
                            // Target already has this, delete duplicate's pivot link
                            DB::table('petshop_shipping_contacts')->where('id', $link->id)->delete();
                        } else {
                            // Target doesn't have this, update the pivot link to point to target
                            DB::table('petshop_shipping_contacts')
                                ->where('id', $link->id)
                                ->update(['petshop_id' => $targetId]);
                        }
                    }

                    // 3. Delete Duplicate
                    $duplicate->delete();

                } elseif ($type === 'distributor') {
                    $target = Distributor::findOrFail($targetId);
                    $duplicate = Distributor::findOrFail($duplicateId);

                    // 1. Move Outlets pointing to duplicate distributor
                    Outlet::where('distributor_id', $duplicateId)->update(['distributor_id' => $targetId]);

                    // 2. Move LeadRequests pointing to duplicate distributor
                    LeadRequest::where('distributor_id', $duplicateId)->update(['distributor_id' => $targetId]);

                    // 3. Delete Duplicate
                    $duplicate->delete();

                } elseif ($type === 'kurir') {
                    $target = ShippingContact::findOrFail($targetId);
                    $duplicate = ShippingContact::findOrFail($duplicateId);

                    // Move Petshop associations (avoid pivot uniqueness violations)
                    $targetPetshopIds = $target->outlets()->pluck('outlets.id')->toArray();

                    $duplicatePivotLinks = DB::table('petshop_shipping_contacts')
                        ->where('shipping_contact_id', $duplicateId)
                        ->get();

                    foreach ($duplicatePivotLinks as $link) {
                        if (in_array($link->petshop_id, $targetPetshopIds)) {
                            // Already linked to target, delete
                            DB::table('petshop_shipping_contacts')->where('id', $link->id)->delete();
                        } else {
                            // Update to point to target
                            DB::table('petshop_shipping_contacts')
                                ->where('id', $link->id)
                                ->update(['shipping_contact_id' => $targetId]);
                        }
                    }

                    // Delete Duplicate
                    $duplicate->delete();
                }
            });

            return back()->with('success', 'Penyatuan data duplikat berhasil diselesaikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyatukan data: ' . $e->getMessage());
        }
    }
}
