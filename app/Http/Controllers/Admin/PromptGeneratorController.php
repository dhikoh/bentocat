<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\MarketingTemplate;
use App\Models\CustomerProfile;
use App\Models\PromptHistory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PromptGeneratorController extends Controller
{

    /**
     * Display the main prompt assistant interface.
     */
    public function index()
    {
        $productName = Setting::get('prompt_product_name', 'BentoCat Premium Cat Litter');
        $advantages = Setting::get('prompt_advantages', "1. Daya gumpal instan & sangat kuat (tidak hancur saat diserok)\n2. Bebas debu 99% (aman untuk pernapasan kucing & owner)\n3. Kontrol aroma/bau tak sedap maksimal (odor lock 24 jam)\n4. Lebih hemat & awet karena hanya mengganti bagian yang menggumpal");
        $marketingSystem = Setting::get('prompt_marketing_system', "Kemitraan retail petshop lokal (distribusi offline dengan harga wajar tanpa biaya logistik marketplace yang mahal) serta kerja sama agen wilayah.");
        
        $templates = MarketingTemplate::all();
        $customers = CustomerProfile::orderBy('nama', 'asc')->get();

        return view('admin.prompt-generator.index', compact('productName', 'advantages', 'marketingSystem', 'templates', 'customers'));
    }

    /**
     * Save the product profile information (CRUD).
     */
    public function saveProduct(Request $request)
    {
        $request->validate([
            'prompt_product_name' => 'required|string|max:255',
            'prompt_advantages' => 'nullable|string',
            'prompt_marketing_system' => 'nullable|string',
        ]);

        Setting::set('prompt_product_name', $request->prompt_product_name);
        Setting::set('prompt_advantages', $request->prompt_advantages);
        Setting::set('prompt_marketing_system', $request->prompt_marketing_system);

        return redirect()->route('admin.prompt-generator.index')
            ->with('success', 'Profil produk BentoCat berhasil disimpan.');
    }

    /**
     * Generate prompt from inputs.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:marketing_templates,id',
            'target_audience' => 'required|string',
            'tone' => 'required|string',
            'language' => 'required|string',
            'length' => 'required|string',
            'emoji_style' => 'required|string|in:standard,none',
            'customer_profile_id' => 'nullable|exists:customer_profiles,id',
            'variables' => 'nullable|array',
            'custom_notes' => 'nullable|string',
            'customer_chat' => 'nullable|string',
        ]);

        $template = MarketingTemplate::findOrFail($request->template_id);
        $productName = Setting::get('prompt_product_name', 'BentoCat Premium Cat Litter');
        $advantages = Setting::get('prompt_advantages', '');
        $marketingSystem = Setting::get('prompt_marketing_system', '');
        $whatsapp = Setting::get('contact_whatsapp', '+62 877-7771-7300');

        // Load customer profile if selected
        $customer = null;
        if (!empty($request->customer_profile_id)) {
            $customer = CustomerProfile::find($request->customer_profile_id);
        }

        // Compile base prompt placeholders
        $compiledPrompt = $template->base_prompt;
        if ($request->has('variables') && is_array($request->variables)) {
            foreach ($request->variables as $key => $value) {
                $replacement = !is_null($value) && trim($value) !== '' 
                    ? $value 
                    : '[' . str_replace('_', ' ', ucwords($key, '_')) . ']';
                $compiledPrompt = str_replace('{' . $key . '}', $replacement, $compiledPrompt);
            }
        }
        
        // Also fallback for any unsubmitted placeholders in the template
        if (!empty($template->placeholders)) {
            foreach (explode(',', $template->placeholders) as $key) {
                $key = trim($key);
                if (!empty($key) && !str_contains($compiledPrompt, '{' . $key . '}')) {
                    $replacement = '[' . str_replace('_', ' ', ucwords($key, '_')) . ']';
                    $compiledPrompt = str_replace('{' . $key . '}', $replacement, $compiledPrompt);
                }
            }
        }

        // Construct the prompt string
        $finalPrompt = "### SYSTEM INSTRUCTION & ROLE\n";
        $finalPrompt .= "Anda adalah asisten AI Pemasaran BentoCat ahli. Anda harus merespons instruksi ini secara akurat dengan merujuk dan menerapkan strategi-strategi yang tertulis dalam berkas \"marketing_skills_handbook.md\" yang diunggah oleh pengguna.\n\n";

        // Human-like organic rules (Anti-Bot)
        $finalPrompt .= "### KETENTUAN GAYA PENULISAN ORGANIK (ANTI-BOT / ANTI-SLOP)\n";
        $finalPrompt .= "- TULISAN HARUS ORGANIK & ALAMI: Hindari sapaan robotik atau basa-basi khas AI (seperti 'Halo pet lovers!', 'Memperkenalkan...', 'Apakah Anda ingin...', 'Tentu, berikut adalah...'). Mulailah tulisan/pesan Anda secara langsung, luwes, bersahabat, empati, dan mengalir layaknya sales lapangan berpengalaman asli Indonesia.\n";
        $finalPrompt .= "- STRUKTUR PARAGRAF: Gunakan paragraf pendek (maksimal 3-4 kalimat per paragraf) dengan kalimat yang ringkas agar mudah dipindai (scannable) oleh pembaca.\n";
        $finalPrompt .= "- NARASI MENGALIR: Jangan salin-tempel daftar keunggulan BentoCat secara kaku beruntun. Integrasikan keunggulan tersebut ke dalam kalimat transisi yang natural.\n\n";

        // Emoji styling rules
        $finalPrompt .= "### ATURAN EMOJI & EMOTICON\n";
        if ($request->emoji_style === 'none') {
            $finalPrompt .= "- DILARANG keras menggunakan emoji, emoticon, ikon, atau simbol visual grafis apa pun dalam teks output. Teks harus benar-benar bersih (pure clean text).\n\n";
        } else {
            $finalPrompt .= "- Gunakan emoji secara taktis, wajar, proporsional, dan kontekstual hanya untuk mempercantik poin penting atau pemisah baris (hindari spam emoji di akhir setiap kalimat).\n\n";
        }

        // Check if there is incoming customer chat
        if (!empty($request->customer_chat)) {
            $finalPrompt .= "### PESAN / CHAT MASUK DARI CUSTOMER (KONTEKS PERCAKAPAN)\n";
            $finalPrompt .= $request->customer_chat . "\n\n";
            $finalPrompt .= "### TUGAS KHUSUS RESPONS CHAT\n";
            $finalPrompt .= "Susunlah balasan langsung untuk pesan/chat masuk di atas berdasarkan profil produk BentoCat, panduan marketing handbook, dan kerangka instruksi di bawah.\n\n";
        }

        // Customer Profile context if selected
        if ($customer) {
            $finalPrompt .= "### PROFIL PELANGGAN SASARAN (KONTEKS PENERIMA)\n";
            $finalPrompt .= "- Nama Pelanggan: " . $customer->nama . "\n";
            $finalPrompt .= "- No. WhatsApp Pelanggan: " . $customer->whatsapp . "\n";
            if (!empty($customer->alamat)) {
                $finalPrompt .= "- Alamat Lengkap: " . $customer->alamat . "\n";
            }
            if (!empty($customer->kota)) {
                $finalPrompt .= "- Kota/Wilayah: " . $customer->kota . " (" . $customer->provinsi . ")\n";
            }
            $finalPrompt .= "\n";
        }
        
        $finalPrompt .= "### PROFIL PRODUK BENTOCAT (KONTEKS BISNIS)\n";
        $finalPrompt .= "- Nama Produk: " . $productName . "\n";
        if (!empty($advantages)) {
            $finalPrompt .= "- Keunggulan Produk:\n" . $advantages . "\n";
        }
        if (!empty($marketingSystem)) {
            $finalPrompt .= "- Sistem Pemasaran & Distribusi:\n" . $marketingSystem . "\n";
        }
        $finalPrompt .= "\n";

        $finalPrompt .= "### DETAIL TUGAS PEMASARAN\n";
        $finalPrompt .= "- Kategori Tugas: " . $template->category . "\n";
        $finalPrompt .= "- Target Penerima/Audiens: " . $request->target_audience . "\n";
        $finalPrompt .= "- Kerangka Dasar Instruksi:\n" . $compiledPrompt . "\n\n";

        if (!empty($request->custom_notes)) {
            $finalPrompt .= "### INSTRUKSI TAMBAHAN KHUSUS\n";
            $finalPrompt .= $request->custom_notes . "\n\n";
        }

        $finalPrompt .= "### KETENTUAN OUTPUT RESIDUAL\n";
        $finalPrompt .= "- Nada Bicara (Tone of Voice): " . $request->tone . "\n";
        $finalPrompt .= "- Bahasa Output: " . $request->language . "\n";
        $finalPrompt .= "- Panjang Teks: " . $request->length . "\n";
        $finalPrompt .= "- Kontak WhatsApp Hubungi: " . $whatsapp . " (Sertakan jika relevan di bagian CTA).\n";

        // Save generated prompt history if customer is selected
        if ($customer) {
            PromptHistory::create([
                'customer_profile_id' => $customer->id,
                'user_id' => auth()->id(),
                'template_name' => $template->name,
                'chat_input' => $request->customer_chat,
                'variables' => $request->variables,
                'generated_prompt' => $finalPrompt,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['prompt' => $finalPrompt]);
        }

        return redirect()->route('admin.prompt-generator.index')
            ->withInput()
            ->with('generated_prompt', $finalPrompt);
    }

    /**
     * Download the marketing handbook.
     */
    public function downloadHandbook()
    {
        $filePath = base_path('marketing_skills_handbook.md');
        
        if (!file_exists($filePath)) {
            $filePath = base_path('../marketing_skills_handbook.md');
        }
        
        if (!file_exists($filePath)) {
            $filePath = 'c:\Users\Dhiko Herlambang\.gemini\antigravity\playground\pulsing-pinwheel\Project\marketing_skills_handbook.md';
        }

        if (!file_exists($filePath)) {
            abort(404, 'Berkas marketing_skills_handbook.md tidak ditemukan di sistem.');
        }

        return response()->download($filePath, 'marketing_skills_handbook.md');
    }

    /* -------------------------------------------------------------------------- */
    /*                             TEMPLATE CRUD METHODS                           */
    /* -------------------------------------------------------------------------- */

    /**
     * Display a listing of the templates.
     */
    public function indexTemplates()
    {
        $templates = MarketingTemplate::latest()->get();
        return view('admin.prompt-generator.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function createTemplate()
    {
        return view('admin.prompt-generator.templates.create');
    }

    /**
     * Store a newly created template in storage.
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'target_audience' => 'required|string|max:255',
            'tone' => 'required|string|max:255',
            'placeholders' => 'nullable|string',
            'base_prompt' => 'required|string',
        ]);

        MarketingTemplate::create($request->all());

        return redirect()->route('admin.prompt-generator.templates.index')
            ->with('success', 'Template baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified template.
     */
    public function editTemplate(MarketingTemplate $template)
    {
        return view('admin.prompt-generator.templates.edit', compact('template'));
    }

    /**
     * Update the specified template in storage.
     */
    public function updateTemplate(Request $request, MarketingTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'target_audience' => 'required|string|max:255',
            'tone' => 'required|string|max:255',
            'placeholders' => 'nullable|string',
            'base_prompt' => 'required|string',
        ]);

        $template->update($request->all());

        return redirect()->route('admin.prompt-generator.templates.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroyTemplate(MarketingTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.prompt-generator.templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }

    /**
     * AJAX: Get customer profile and prompt history.
     */
    public function getCustomerHistory($id)
    {
        $customer = CustomerProfile::findOrFail($id);
        $history = PromptHistory::where('customer_profile_id', $id)
            ->latest()
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'template_name' => $item->template_name,
                    'chat_input' => $item->chat_input ?? '',
                    'generated_prompt' => $item->generated_prompt,
                    'created_at' => $item->created_at->translatedFormat('d M Y H:i'),
                ];
            });

        return response()->json([
            'customer' => $customer,
            'history' => $history
        ]);
    }

    /**
     * AJAX: Delete a prompt history item.
     */
    public function deleteHistory($id)
    {
        $history = PromptHistory::findOrFail($id);
        $history->delete();

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Quick store customer profile.
     */
    public function quickStoreCustomer(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
        ]);

        $validated['uuid'] = (string) Str::uuid();

        $customer = CustomerProfile::create($validated);

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    /**
     * AJAX: Quick update customer profile.
     */
    public function quickUpdateCustomer(Request $request, $id)
    {
        $customer = CustomerProfile::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    /**
     * AJAX: Quick destroy customer profile.
     */
    public function quickDestroyCustomer($id)
    {
        $customer = CustomerProfile::findOrFail($id);
        $customer->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
