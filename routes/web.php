<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\TextileClassificationController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/admin-login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/api/gst-lookup', [RegisterController::class, 'gstLookup'])->name('api.gst-lookup');
Route::get('/api/bhashini/status', [\App\Http\Controllers\BhashiniController::class, 'status'])->name('api.bhashini.status');
Route::post('/api/bhashini/translate', [\App\Http\Controllers\BhashiniController::class, 'translate'])->name('api.bhashini.translate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dynamic CMS Routes
Route::get('/', [CmsController::class, 'show'])->name('home');
Route::get('/sitemap', [CmsController::class, 'sitemap'])->name('sitemap');
Route::get('/p/{slug}', [CmsController::class, 'show']);

// Static / Hardcoded Pages (Fallback)
Route::get('/about', function () { return redirect('/p/about'); });
Route::get('/about-tc', function () { return redirect()->away('https://textilescommittee.gov.in'); })->name('about-tc');
Route::get('/clusters', function () { return redirect('/p/clusters'); });
Route::get('/fta-benefits', function () { return redirect('/p/fta-benefits'); });
Route::get('/online-services', function () { return redirect('/p/online-services'); });
Route::get('/media', function () { return redirect('/p/media'); });
Route::get('/news', function () { return redirect('/p/news'); });
Route::get('/help-support', function () { return redirect('/p/help-support'); });

// Frontend Resource List
Route::get('/resources', [ResourceController::class, 'list'])->name('resources.list');
Route::post('/api/chat', [App\Http\Controllers\ChatController::class, 'chat'])->name('api.chat');

// Textile Classification & Attribute Discovery API
Route::prefix('api/classifications')->name('api.classifications.')->group(function () {
    Route::get('/search', [TextileClassificationController::class, 'search'])->name('search');
    Route::get('/tree', [TextileClassificationController::class, 'tree'])->name('tree');
    Route::get('/subcategories', [TextileClassificationController::class, 'getSubcategories'])->name('subcategories');
    Route::get('/category/{category?}/subcategories', [TextileClassificationController::class, 'getSubcategories']);
    Route::get('/products', [TextileClassificationController::class, 'getProducts'])->name('products');
    Route::get('/subcategory/{subcategory?}/products', [TextileClassificationController::class, 'getProducts']);
    Route::get('/category-attributes', [TextileClassificationController::class, 'getCategoryAttributes'])->name('category-attributes');
    Route::get('/category/{category?}/attributes', [TextileClassificationController::class, 'getCategoryAttributes']);
});

// Product Catalogue Frontend
Route::get('/catalogue', [ProductController::class, 'catalogue'])->name('catalogue.index');
Route::get('/catalogue/{product}', [ProductController::class, 'showCatalogue'])->name('catalogue.show');
Route::post('/catalogue/{product}/interaction', [ProductController::class, 'recordInteraction'])->name('catalogue.interaction');

// TEFC KPI & Performance Dashboard
Route::get('/kpi-dashboard', [App\Http\Controllers\KpiDashboardController::class, 'index'])->name('kpi.dashboard');
Route::get('/dashboard-summary', function () { return redirect()->route('kpi.dashboard'); });

Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::post('/catalogue/enquiry', [ProductController::class, 'storeGeneralInquiry'])->name('catalogue.general-inquiry');
    Route::post('/catalogue/{product}/inquiry', [ProductController::class, 'storeInquiry'])->name('catalogue.inquiry');
});

// Exporter Portal (Buyer/Seller)
Route::middleware(['auth', 'role:buyer,seller'])->group(function () {
    Route::get('/exporter-portal', [ProfileController::class, 'index'])->name('exporter-portal');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Buyer specific routes
    Route::middleware(['role:buyer'])->group(function () {
        Route::get('/buyer/sellers', [BuyerController::class, 'sellerDirectory'])->name('buyer.sellers');
        Route::post('/buyer/sellers/{seller}/toggle-save', [BuyerController::class, 'toggleSaveSeller'])->name('buyer.sellers.toggle-save');
        Route::get('/buyer/saved-sellers', [BuyerController::class, 'savedSellers'])->name('buyer.saved-sellers');
        Route::get('/buyer/inquiries', [BuyerController::class, 'myInquiries'])->name('buyer.inquiries');
    });

    // Seller specific routes
    Route::middleware(['role:seller'])->group(function () {
        Route::get('/seller/inquiries', [ProductController::class, 'sellerInquiries'])->name('seller.inquiries');
        Route::resource('seller/products', ProductController::class)->names([
            'index' => 'seller.products.index',
            'create' => 'seller.products.create',
            'store' => 'seller.products.store',
            'edit' => 'seller.products.edit',
            'update' => 'seller.products.update',
            'destroy' => 'seller.products.destroy',
        ])->except(['show']);

        // Exporter Product Acknowledgment (Higher Authority Verification & Direct Publishing)
        Route::post('/seller/products/{product}/acknowledge', [ProductController::class, 'acknowledge'])->name('seller.products.acknowledge');
        Route::post('/seller/products/{product}/request-revision', [ProductController::class, 'requestRevision'])->name('seller.products.request-revision');
        Route::delete('/seller/products/{product}/media/{media}', [ProductController::class, 'destroyMedia'])->name('seller.products.media.destroy');
        Route::post('/seller/products/{product}/media/{media}/primary', [ProductController::class, 'setPrimaryMedia'])->name('seller.products.media.primary');

        // Data Entry Operator Management (Authorized Signatory / Exporter only)
        Route::get('/exporter/operators', [\App\Http\Controllers\ExporterOperatorController::class, 'index'])->name('exporter.operators.index');
        Route::post('/exporter/operators', [\App\Http\Controllers\ExporterOperatorController::class, 'store'])->name('exporter.operators.store');
        Route::post('/exporter/operators/{operator}/toggle', [\App\Http\Controllers\ExporterOperatorController::class, 'toggleStatus'])->name('exporter.operators.toggle');
        Route::delete('/exporter/operators/{operator}', [\App\Http\Controllers\ExporterOperatorController::class, 'destroy'])->name('exporter.operators.destroy');
    });
});

// Authenticated CMS Uploads
Route::post('/cms/upload-cluster-image', [AdminDashboardController::class, 'uploadClusterImage'])->name('cms.upload-cluster-image')->middleware('auth');
Route::post('/cms/upload-file', [AdminDashboardController::class, 'uploadCmsFile'])->name('cms.upload-file')->middleware('auth');

// Admin Dashboard & CMS
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('admin')->group(function () {
        Route::get('/cms', [AdminDashboardController::class, 'cms'])->name('admin.cms');
        Route::get('/cms/sections/{section}/edit', [AdminDashboardController::class, 'editSection'])->name('admin.sections.edit');
        Route::post('/cms/sections/{section}/update', [AdminDashboardController::class, 'updateSection'])->name('admin.sections.update');
        Route::post('/cms/sections/{section}/toggle-status', [AdminDashboardController::class, 'toggleSectionStatus'])->name('admin.sections.toggle');
        Route::post('/cms/sections/{section}/toggle-map-card', [AdminDashboardController::class, 'toggleMapCardStatus'])->name('admin.sections.toggle-map-card');
        
        // User Management
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/status', [AdminDashboardController::class, 'updateUserStatus'])->name('admin.users.status');

        // Resource Management
        Route::get('/resources', [ResourceController::class, 'index'])->name('admin.resources');
        Route::post('/resources', [ResourceController::class, 'store'])->name('admin.resources.store');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('admin.resources.destroy');
        Route::delete('/resources/media/{section_type}/{index}', [ResourceController::class, 'deleteMediaItem'])->name('admin.media.destroy');

        // Global Settings
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('admin.settings.update');

        // Menu Management
        Route::get('/menus', [AdminDashboardController::class, 'menus'])->name('admin.menus');
        Route::post('/menus/{menu}', [AdminDashboardController::class, 'updateMenu'])->name('admin.menus.update');

        // Product Moderation
        Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('admin.products.show');
        Route::post('/products/{product}/status', [AdminProductController::class, 'updateStatus'])->name('admin.products.status');

        // HO Direct Access into TEFC Centres
        Route::get('/centre/switch/{code}', [AdminDashboardController::class, 'switchToCentre'])->name('admin.centre.switch');
        Route::get('/centre/exit', [AdminDashboardController::class, 'exitCentreSwitch'])->name('admin.centre.exit');
    });
});

// TEFC Centre Officer Management Routes (Accessible by Center Role & HO Admin)
Route::middleware(['auth', 'role:center,admin'])->group(function () {
    Route::get('/center/dashboard', [\App\Http\Controllers\TefcCentreController::class, 'dashboard'])->name('center.dashboard');
    Route::get('/center/applications', [\App\Http\Controllers\TefcCentreController::class, 'applications'])->name('center.applications');
    Route::post('/center/applications/{user}/status', [\App\Http\Controllers\TefcCentreController::class, 'updateStatus'])->name('center.applications.status');

    // KPI Management Control Room & Data Feeding
    Route::get('/center/kpis', [\App\Http\Controllers\TefcCentreController::class, 'kpis'])->name('center.kpis');
    Route::post('/center/kpis/staff', [\App\Http\Controllers\TefcCentreController::class, 'storeStaff'])->name('center.kpis.staff.store');
    Route::post('/center/kpis/staff/{staff}/toggle', [\App\Http\Controllers\TefcCentreController::class, 'toggleStaff'])->name('center.kpis.staff.toggle');
    Route::delete('/center/kpis/staff/{staff}', [\App\Http\Controllers\TefcCentreController::class, 'deleteStaff'])->name('center.kpis.staff.delete');

    Route::post('/center/kpis/advisory', [\App\Http\Controllers\TefcCentreController::class, 'storeAdvisory'])->name('center.kpis.advisory.store');
    Route::delete('/center/kpis/advisory/{advisory}', [\App\Http\Controllers\TefcCentreController::class, 'deleteAdvisory'])->name('center.kpis.advisory.delete');

    Route::post('/center/kpis/training', [\App\Http\Controllers\TefcCentreController::class, 'storeTraining'])->name('center.kpis.training.store');
    Route::delete('/center/kpis/training/{training}', [\App\Http\Controllers\TefcCentreController::class, 'deleteTraining'])->name('center.kpis.training.delete');

    Route::post('/center/kpis/publication', [\App\Http\Controllers\TefcCentreController::class, 'storePublication'])->name('center.kpis.publication.store');
    Route::delete('/center/kpis/publication/{publication}', [\App\Http\Controllers\TefcCentreController::class, 'deletePublication'])->name('center.kpis.publication.delete');

    Route::post('/center/kpis/query', [\App\Http\Controllers\TefcCentreController::class, 'storeQuery'])->name('center.kpis.query.store');
    Route::post('/center/kpis/query/{queryRecord}/resolve', [\App\Http\Controllers\TefcCentreController::class, 'resolveQuery'])->name('center.kpis.query.resolve');
    Route::delete('/center/kpis/query/{queryRecord}', [\App\Http\Controllers\TefcCentreController::class, 'deleteQuery'])->name('center.kpis.query.delete');

    Route::post('/center/kpis/cluster-profile', [\App\Http\Controllers\TefcCentreController::class, 'updateClusterProfile'])->name('center.kpis.profile.update');

    // Cluster Portal Content Management for Centre Officers & HO
    Route::get('/center/cluster', [\App\Http\Controllers\TefcCentreController::class, 'clusterContent'])->name('center.cluster.edit');
    Route::post('/center/cluster', [\App\Http\Controllers\TefcCentreController::class, 'updateClusterContent'])->name('center.cluster.update');
});

