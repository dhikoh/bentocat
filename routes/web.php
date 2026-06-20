<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\ShippingContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\MarketingLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PromptGeneratorController;
use App\Http\Controllers\ClientController;

// Public client routes (Fase 3 & 4)
Route::get('/', [ClientController::class, 'index'])->name('home');
Route::get('/api/cities-by-province/{province_id}', [ClientController::class, 'getCities']);
Route::get('/api/outlets-by-city/{city_id}', [ClientController::class, 'getOutletsByCity']);
Route::match(['get', 'post'], '/search-outlet', [ClientController::class, 'searchOutlets'])->name('search-outlet');
Route::get('/list-petshop', [ClientController::class, 'listPetshops'])->name('petshop.list');
Route::post('/api/leads/create-and-log', [ClientController::class, 'createLeadAndAction'])->name('leads.create-and-log');
Route::post('/api/leads/action', [ClientController::class, 'logAction'])->name('leads.log-action');

// Content Marketing / Blog Hub
Route::get('/blog', [ClientController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [ClientController::class, 'blogShow'])->name('blog.show');

// Redirects for old static HTML pages (SEO Migration)
Route::redirect('/index.html', '/', 301);
Route::redirect('/mitra.html', '/list-petshop', 301);
Route::redirect('/tentang-kami.html', '/', 301);
Route::redirect('/bentocat-5-liter.html', '/', 301);
Route::redirect('/bentocat-10-liter.html', '/', 301);
Route::redirect('/bentocat-25-liter.html', '/', 301);
Route::redirect('/sitemaps.xml', '/sitemap.xml', 301);
Route::get('/assets/images/{any}', function () {
    return redirect('/images/product_default.png', 301);
})->where('any', '.*');

// Sitemap
Route::get('/sitemap.xml', [ClientController::class, 'sitemap'])->name('sitemap');

// Dynamic Local SEO routes (must be placed at the end or outside prefix to avoid capture)
Route::get('/kota/{slug}', [ClientController::class, 'cityLanding'])->name('city.landing');

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated Routes
    Route::middleware(['auth', 'restrict.marketing'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Provinces & Cities CRUD
        Route::get('/regions', [RegionController::class, 'index'])->name('regions.index');
        Route::post('/regions/province', [RegionController::class, 'storeProvince'])->name('regions.province.store');
        Route::put('/regions/province/{province}', [RegionController::class, 'updateProvince'])->name('regions.province.update');
        Route::delete('/regions/province/{province}', [RegionController::class, 'destroyProvince'])->name('regions.province.destroy');
        
        Route::get('/regions/{province}/cities', [RegionController::class, 'cities'])->name('regions.cities');
        Route::post('/regions/{province}/city', [RegionController::class, 'storeCity'])->name('regions.city.store');
        Route::put('/regions/city/{city}', [RegionController::class, 'updateCity'])->name('regions.city.update');
        Route::delete('/regions/city/{city}', [RegionController::class, 'destroyCity'])->name('regions.city.destroy');

        // Core Catalog, Partners & Shipping CRUD Resources
        Route::resource('/distributors', DistributorController::class);
        
        // Outlet CSV Import/Export (defined before resource)
        Route::get('/outlets/export', [OutletController::class, 'exportCsv'])->name('outlets.export');
        Route::post('/outlets/import', [OutletController::class, 'importCsv'])->name('outlets.import');
        Route::post('/outlets/batch-delete', [OutletController::class, 'batchDelete'])->name('outlets.batch-delete');
        Route::post('/outlets/batch-reassign', [OutletController::class, 'batchReassign'])->name('outlets.batch-reassign');
        Route::post('/outlets/batch-reassign-shipping', [OutletController::class, 'batchReassignShipping'])->name('outlets.batch-reassign-shipping');
        Route::post('/outlets/batch-update-status', [OutletController::class, 'batchUpdateStatus'])->name('outlets.batch-update-status');
        Route::post('/outlets/clear', [OutletController::class, 'clearOutlets'])->name('outlets.clear');
        Route::resource('/outlets', OutletController::class);
        
        Route::resource('/shipping-contacts', ShippingContactController::class);
        Route::resource('/products', ProductController::class);

        // Nested Hierarchical Variants
        Route::get('/products/{product}/variants', [ProductController::class, 'variants'])->name('products.variants');
        Route::post('/products/{product}/variants', [ProductController::class, 'storeVariant'])->name('products.variants.store');
        Route::delete('/variants/{variant}', [ProductController::class, 'destroyVariant'])->name('variants.destroy');

        // Editorial CMS (Article blocks)
        Route::resource('/articles', ArticleController::class);
        Route::post('/articles/upload-image', [ArticleController::class, 'uploadImage'])->name('articles.upload-image');
        Route::post('/articles/ai-assist', [ArticleController::class, 'aiAssist'])->name('articles.ai-assist');

        // Leads View & CSV Export & Delete & Import
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/export', [LeadController::class, 'exportCsv'])->name('leads.export');
        Route::post('/leads/import', [LeadController::class, 'importCsv'])->name('leads.import');
        Route::post('/leads/clear', [LeadController::class, 'clearLeads'])->name('leads.clear');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Customer CRM (Full Resource)
        Route::get('/customers/export', [CustomerController::class, 'exportCsv'])->name('customers.export');
        Route::post('/customers/import', [CustomerController::class, 'importCsv'])->name('customers.import');
        Route::post('/customers/clear', [CustomerController::class, 'clearCustomers'])->name('customers.clear');
        Route::resource('/customers', CustomerController::class);

        // Marketing Logs (Submit daily logs)
        Route::get('/my-logs', [MarketingLogController::class, 'index'])->name('my-logs.index');
        Route::get('/my-logs/create', [MarketingLogController::class, 'create'])->name('my-logs.create');
        Route::post('/my-logs', [MarketingLogController::class, 'store'])->name('my-logs.store');
        Route::get('/my-logs/{log}/edit', [MarketingLogController::class, 'edit'])->name('my-logs.edit');
        Route::put('/my-logs/{log}', [MarketingLogController::class, 'update'])->name('my-logs.update');
        Route::delete('/my-logs/{log}', [MarketingLogController::class, 'destroy'])->name('my-logs.destroy');

        // Superadmin and Marketing (SEO / Pixel Settings & Prompt Generator)
        Route::middleware('role:superadmin,marketing')->group(function () {
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

            // Prompt Generator & Templates
            Route::get('/prompt-generator', [PromptGeneratorController::class, 'index'])->name('prompt-generator.index');
            Route::post('/prompt-generator/save-product', [PromptGeneratorController::class, 'saveProduct'])->name('prompt-generator.save-product');
            Route::post('/prompt-generator/generate', [PromptGeneratorController::class, 'generate'])->name('prompt-generator.generate');
            Route::get('/prompt-generator/download', [PromptGeneratorController::class, 'downloadHandbook'])->name('prompt-generator.download');
            
            // AJAX Customer History & Quick CRUD
            Route::get('/prompt-generator/customers/{customer}/history', [PromptGeneratorController::class, 'getCustomerHistory'])->name('prompt-generator.customers.history');
            Route::delete('/prompt-generator/history/{history}', [PromptGeneratorController::class, 'deleteHistory'])->name('prompt-generator.history.destroy');
            Route::post('/prompt-generator/customers/quick-store', [PromptGeneratorController::class, 'quickStoreCustomer'])->name('prompt-generator.customers.quick-store');
            Route::put('/prompt-generator/customers/{customer}/quick-update', [PromptGeneratorController::class, 'quickUpdateCustomer'])->name('prompt-generator.customers.quick-update');
            Route::delete('/prompt-generator/customers/{customer}/quick-destroy', [PromptGeneratorController::class, 'quickDestroyCustomer'])->name('prompt-generator.customers.quick-destroy');
            
            // CRUD Marketing Templates
            Route::get('/prompt-generator/templates', [PromptGeneratorController::class, 'indexTemplates'])->name('prompt-generator.templates.index');
            Route::get('/prompt-generator/templates/create', [PromptGeneratorController::class, 'createTemplate'])->name('prompt-generator.templates.create');
            Route::post('/prompt-generator/templates', [PromptGeneratorController::class, 'storeTemplate'])->name('prompt-generator.templates.store');
            Route::get('/prompt-generator/templates/{template}/edit', [PromptGeneratorController::class, 'editTemplate'])->name('prompt-generator.templates.edit');
            Route::put('/prompt-generator/templates/{template}', [PromptGeneratorController::class, 'updateTemplate'])->name('prompt-generator.templates.update');
            Route::delete('/prompt-generator/templates/{template}', [PromptGeneratorController::class, 'destroyTemplate'])->name('prompt-generator.templates.destroy');
        });

        // Superadmin only (Monitor logs & User accounts)
        Route::middleware('role:superadmin')->group(function () {
            Route::get('/marketing-logs', [MarketingLogController::class, 'adminIndex'])->name('marketing-logs.index');
            Route::get('/marketing-logs/export', [MarketingLogController::class, 'exportCsv'])->name('marketing-logs.export');
            Route::post('/marketing-logs/{log}/evaluate', [MarketingLogController::class, 'evaluate'])->name('marketing-logs.evaluate');

            // User Management
            Route::get('/users/{user}/reset-password', [UserController::class, 'showResetPassword'])->name('users.reset-password');
            Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
            Route::resource('/users', UserController::class);
        });

        // Audit & Business Health Panel (Superadmin / Editor)
        Route::middleware('role:superadmin,editor')->group(function () {
            Route::get('/audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
            Route::post('/audit/merge', [\App\Http\Controllers\Admin\AuditController::class, 'merge'])->name('audit.merge');
        });
    });
});


