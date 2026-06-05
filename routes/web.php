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
use App\Http\Controllers\ClientController;

// Public client routes (Fase 3 & 4)
Route::get('/', [ClientController::class, 'index'])->name('home');
Route::get('/api/cities-by-province/{province_id}', [ClientController::class, 'getCities']);
Route::get('/api/outlets-by-city/{city_id}', [ClientController::class, 'getOutletsByCity']);
Route::match(['get', 'post'], '/search-outlet', [ClientController::class, 'searchOutlets'])->name('search-outlet');
Route::post('/api/leads/create-and-log', [ClientController::class, 'createLeadAndAction'])->name('leads.create-and-log');
Route::post('/api/leads/action', [ClientController::class, 'logAction'])->name('leads.log-action');

// Content Marketing / Blog Hub
Route::get('/blog', [ClientController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [ClientController::class, 'blogShow'])->name('blog.show');

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
    Route::middleware('auth')->group(function () {
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

        // Leads View & CSV Export & Delete
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/export', [LeadController::class, 'exportCsv'])->name('leads.export');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Customer CRM (Full Resource)
        Route::get('/customers/export', [CustomerController::class, 'exportCsv'])->name('customers.export');
        Route::resource('/customers', CustomerController::class);

        // Website Settings (Superadmin only)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Audit & Business Health Panel (Superadmin / Editor)
        Route::get('/audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
        Route::post('/audit/merge', [\App\Http\Controllers\Admin\AuditController::class, 'merge'])->name('audit.merge');
    });
});
