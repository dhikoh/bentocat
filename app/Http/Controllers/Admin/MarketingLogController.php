<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingLogController extends Controller
{
    /**
     * Display a listing of the authenticated marketing user's logs.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = MarketingLog::with(['outlet', 'customerProfile'])
            ->where('user_id', $user->id);

        // Sort by potential closing or default to log date
        $sortBy = $request->input('sort_by', 'log_date');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy === 'potential_closing') {
            $query->orderBy('potential_closing', $sortOrder)
                  ->orderBy('log_date', 'desc');
        } else {
            $query->orderBy('log_date', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        $logs = $query->paginate(15);

        return view('admin.my-logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new log.
     */
    public function create()
    {
        $outlets = \App\Models\Outlet::orderBy('nama_outlet', 'asc')->get();
        $customers = \App\Models\CustomerProfile::orderBy('nama', 'asc')->get();
        return view('admin.my-logs.create', compact('outlets', 'customers'));
    }

    /**
     * Store a newly created log in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'log_date' => 'required|date|before_or_equal:today',
            'activity_title' => 'required|string|max:255',
            'activity_details' => 'required|string',
            'outlet_id' => 'nullable|exists:outlets,id',
            'customer_profile_id' => 'nullable|exists:customer_profiles,id',
            'agenda' => 'nullable|string',
            'potential_closing' => 'nullable|integer|min:0|max:100',
            'followup_feedback' => 'nullable|string',
            'crm_stage' => 'nullable|string|in:Cold,Warm,Hot,Closed-Won,Closed-Lost',
        ], [
            'log_date.before_or_equal' => 'Tanggal log tidak boleh di masa depan.',
            'activity_title.required' => 'Judul aktivitas wajib diisi.',
            'activity_details.required' => 'Detail aktivitas wajib diisi.',
            'outlet_id.exists' => 'Outlet/Petshop yang dipilih tidak valid.',
            'customer_profile_id.exists' => 'Pelanggan yang dipilih tidak valid.',
        ]);

        MarketingLog::create([
            'user_id' => Auth::id(),
            'log_date' => $request->log_date,
            'activity_title' => $request->activity_title,
            'activity_details' => $request->activity_details,
            'outlet_id' => $request->outlet_id,
            'customer_profile_id' => $request->customer_profile_id,
            'agenda' => $request->agenda,
            'potential_closing' => $request->potential_closing ?? 0,
            'followup_feedback' => $request->followup_feedback,
            'crm_stage' => $request->crm_stage ?? 'Cold',
        ]);

        return redirect()->route('admin.my-logs.index')
            ->with('success', 'Log aktivitas harian berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified log.
     */
    public function edit(MarketingLog $log)
    {
        // Prevent editing other users' logs
        if ($log->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke log ini.');
        }

        $outlets = \App\Models\Outlet::orderBy('nama_outlet', 'asc')->get();
        $customers = \App\Models\CustomerProfile::orderBy('nama', 'asc')->get();

        return view('admin.my-logs.edit', compact('log', 'outlets', 'customers'));
    }

    /**
     * Update the specified log in storage.
     */
    public function update(Request $request, MarketingLog $log)
    {
        // Prevent updating other users' logs
        if ($log->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke log ini.');
        }

        $request->validate([
            'log_date' => 'required|date|before_or_equal:today',
            'activity_title' => 'required|string|max:255',
            'activity_details' => 'required|string',
            'outlet_id' => 'nullable|exists:outlets,id',
            'customer_profile_id' => 'nullable|exists:customer_profiles,id',
            'agenda' => 'nullable|string',
            'potential_closing' => 'nullable|integer|min:0|max:100',
            'followup_feedback' => 'nullable|string',
            'crm_stage' => 'nullable|string|in:Cold,Warm,Hot,Closed-Won,Closed-Lost',
        ], [
            'log_date.before_or_equal' => 'Tanggal log tidak boleh di masa depan.',
            'activity_title.required' => 'Judul aktivitas wajib diisi.',
            'activity_details.required' => 'Detail aktivitas wajib diisi.',
            'outlet_id.exists' => 'Outlet/Petshop yang dipilih tidak valid.',
            'customer_profile_id.exists' => 'Pelanggan yang dipilih tidak valid.',
        ]);

        $log->update([
            'log_date' => $request->log_date,
            'activity_title' => $request->activity_title,
            'activity_details' => $request->activity_details,
            'outlet_id' => $request->outlet_id,
            'customer_profile_id' => $request->customer_profile_id,
            'agenda' => $request->agenda,
            'potential_closing' => $request->potential_closing ?? 0,
            'followup_feedback' => $request->followup_feedback,
            'crm_stage' => $request->crm_stage ?? 'Cold',
        ]);

        return redirect()->route('admin.my-logs.index')
            ->with('success', 'Log aktivitas harian berhasil diperbarui.');
    }

    /**
     * Remove the specified log from storage.
     */
    public function destroy(MarketingLog $log)
    {
        // Deny deletion for marketing role users
        if (Auth::user()->role === 'marketing') {
            abort(403, 'Staf marketing tidak diizinkan menghapus log aktivitas harian.');
        }

        // Prevent non-superadmins from deleting other users' logs
        if (Auth::user()->role !== 'superadmin' && $log->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke log ini.');
        }

        $log->delete();

        return redirect()->route('admin.my-logs.index')
            ->with('success', 'Log aktivitas harian berhasil dihapus.');
    }

    /**
     * Display a listing of all marketing logs for superadmin.
     */
    public function adminIndex(Request $request)
    {
        $query = MarketingLog::with(['user', 'outlet', 'customerProfile']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('log_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('log_date', '<=', $request->end_date);
        }

        // Filter by crm_stage
        if ($request->filled('crm_stage')) {
            $query->where('crm_stage', $request->crm_stage);
        }

        // Sort by potential closing or default to log date
        $sortBy = $request->input('sort_by', 'log_date');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy === 'potential_closing') {
            $query->orderBy('potential_closing', $sortOrder)
                  ->orderBy('log_date', 'desc');
        } else {
            $query->orderBy('log_date', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        $logs = $query->paginate(20);

        // Fetch all marketing users for filter options
        $marketingUsers = User::where('role', 'marketing')->get();

        // Compile prompt text for AI
        $aiPrompt = $this->compileAiPrompt($request);

        return view('admin.marketing-logs.index', compact('logs', 'marketingUsers', 'aiPrompt'));
    }

    /**
     * Store rating and notes for marketing log from superadmin.
     */
    public function evaluate(Request $request, MarketingLog $log)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Hanya superadmin yang dapat memberikan penilaian kinerja.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ], [
            'rating.required' => 'Nilai rating wajib diisi.',
            'rating.integer' => 'Nilai rating harus berupa angka.',
            'rating.min' => 'Nilai rating minimal adalah 1.',
            'rating.max' => 'Nilai rating maksimal adalah 5.',
        ]);

        $log->update([
            'rating' => $request->rating,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Penilaian kinerja log berhasil disimpan.');
    }

    /**
     * Export marketing logs to CSV format.
     */
    public function exportCsv(Request $request)
    {
        $query = MarketingLog::with(['user', 'outlet', 'customerProfile']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('log_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('log_date', '<=', $request->end_date);
        }
        if ($request->filled('crm_stage')) {
            $query->where('crm_stage', $request->crm_stage);
        }

        $logs = $query->orderBy('log_date', 'asc')->get();

        $filename = "marketing-logs-" . date('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['ID', 'Nama Staff', 'Email Staff', 'Tanggal Kegiatan', 'Judul Aktivitas', 'Detail Aktivitas', 'Outlet/Petshop', 'Pelanggan/Customer', 'Agenda Tindak Lanjut', 'Status CRM', 'Potensi Closing (%)', 'Tanggapan Customer', 'Rating Kinerja', 'Catatan Evaluasi', 'Dibuat Pada']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->user->email ?? 'N/A',
                    $log->log_date->format('Y-m-d'),
                    $log->activity_title,
                    $log->activity_details,
                    $log->outlet->nama_outlet ?? 'N/A',
                    $log->customerProfile->nama ?? 'N/A',
                    $log->agenda ?? '',
                    $log->crm_stage,
                    $log->potential_closing . '%',
                    $log->followup_feedback ?? '',
                    $log->rating ?? 'Belum Dinilai',
                    $log->notes ?? '',
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper to compile AI prompt based on current query.
     */
    private function compileAiPrompt(Request $request)
    {
        $query = MarketingLog::with(['user', 'outlet', 'customerProfile']);

        $staffName = "Semua Staff Marketing";
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
            $user = User::find($request->user_id);
            if ($user) {
                $staffName = $user->name;
            }
        }
        
        $startDate = $request->filled('start_date') ? $request->start_date : 'Awal Catatan';
        $endDate = $request->filled('end_date') ? $request->end_date : date('Y-m-d');

        if ($request->filled('start_date')) {
            $query->whereDate('log_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('log_date', '<=', $request->end_date);
        }
        if ($request->filled('crm_stage')) {
            $query->where('crm_stage', $request->crm_stage);
        }

        // Fetch logs for compiling prompt
        $logs = $query->orderBy('log_date', 'asc')->get();

        if ($logs->isEmpty()) {
            return "Catatan log harian kosong untuk filter yang dipilih.";
        }

        $prompt = "Berikut adalah log aktivitas harian staf marketing BentoCat untuk dianalisis:\n\n";
        $prompt .= "--- INFORMASI STAF & PERIODE ---\n";
        $prompt .= "Nama Marketing: " . $staffName . "\n";
        $prompt .= "Periode: " . $startDate . " s/d " . $endDate . "\n\n";
        $prompt .= "--- DAFTAR LOG AKTIVITAS ---\n";

        foreach ($logs as $index => $log) {
            $dateStr = $log->log_date->format('d M Y');
            $prompt .= ($index + 1) . ". [" . $dateStr . "] - " . $log->activity_title . "\n";
            $prompt .= "   Outlet / Petshop: " . ($log->outlet->nama_outlet ?? '-') . "\n";
            $prompt .= "   Customer: " . ($log->customerProfile->nama ?? '-') . "\n";
            $prompt .= "   Status Prospek (CRM Stage): " . $log->crm_stage . "\n";
            $prompt .= "   Potensi Closing: " . $log->potential_closing . "%\n";
            $prompt .= "   Tanggapan / Respon Pelanggan: " . ($log->followup_feedback ?? '-') . "\n";
            $prompt .= "   Agenda: " . ($log->agenda ?? '-') . "\n";
            $prompt .= "   Detail: " . str_replace("\n", "\n   ", trim($log->activity_details)) . "\n\n";
        }

        $prompt .= "--- INSTRUKSI ANALISIS EVALUASI AI ---\n";
        $prompt .= "Berdasarkan log aktivitas harian di atas, tolong lakukan audit dan evaluasi kinerja komprehensif:\n";
        $prompt .= "1. Analisis Produktivitas & Efisiensi: Apakah aktivitas harian yang dilakukan mencerminkan efisiensi kerja yang tinggi? Apakah ada pola penundaan atau jenis tugas yang kurang produktif?\n";
        $prompt .= "2. Relevansi Strategis: Seberapa baik aktivitas tersebut menunjang tujuan bisnis BentoCat (misal: kunjungan ke outlet/petshop baru, penanganan database pelanggan, penulisan artikel SEO blog, tindak lanjut agenda/rencana ke depan)?\n";
        $prompt .= "3. Identifikasi Masalah & Hambatan: Apakah ada kendala operasional yang berulang dari rincian log mereka?\n";
        $prompt .= "4. Rekomendasi Taktis & Strategis: Tuliskan 3-5 poin rekomendasi tindakan nyata (actionable recommendations) untuk memperbaiki atau melipatgandakan kinerja staf marketing ini pada periode berikutnya.\n\n";
        $prompt .= "Sajikan hasil evaluasi dalam format analisis profesional yang terstruktur, padat, dan objektif.";

        return $prompt;
    }
}
