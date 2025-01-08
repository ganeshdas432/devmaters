<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FcmConfigController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\KycDocumentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('about');

Route::get('/privacy', function () {
    return Inertia::render('Privacy', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('privacy');

Route::get('/terms', function () {
    return Inertia::render('Terms', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('terms');

Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('contact');

Route::middleware('auth')->group(function () {

    // Dashboard Routes
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware('verified')->name('dashboard');

    // AppUser Routes
    Route::get('/add_rider', [AppUserController::class, 'add_rider'])->name('add.rider');
    Route::get('/rider_list', [AppUserController::class, 'Rider_list'])->name('riderlist');
    Route::get('/vendor_list', [AppUserController::class, 'Vendor_list'])->name('vendorlist');
    Route::get('customer_list', [AppUserController::class, 'Customer_list'])->name('customerlist');
    Route::get('/appuser/profile/{id}', [AppUserController::class, 'profile'])->name('appuser.profile');

    //kyc Document Routes
    Route::get('/kyc/upload/{userId}/{kycType}', [KycDocumentController::class, 'kyc_upload'])->name('kyc.upload');



    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shop Routes
    Route::get('/shop_list', [ShopController::class, 'shoplist'])->name('shoplist');
    Route::get('/shop/add', [ShopController::class, 'create'])->name('shop.add');
    Route::post('/shop', [ShopController::class, 'store'])->name('shop.store');
    Route::get('api/shop/{id}', [ShopController::class, 'singleshopapi']);
    Route::get('/shops/edit/{id}', [ShopController::class, 'edit'])->name('shop.edit');
    Route::post('/shops/{id}', [ShopController::class, 'update'])->name('shop.update');
    Route::put('/api/shops/{id}/status', [ShopController::class, 'updateStatus']);
    Route::post('/save-location', [ShopController::class, 'locationstore'])->name('location.store');

    // Product Routes
    Route::get('/product_list', [ProductController::class, 'product_list'])->name('productlist');
    Route::get('/product/add', [ProductController::class, 'add_product'])->name('add_product');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');

    // Category Routes
    Route::get('/category_list', [CategoryController::class, 'category_list'])->name('categorylist');
    Route::get('/category/add', [CategoryController::class, 'create'])->name('category.add');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');

    // Unit Routes
    Route::get('/unit_list', [UnitController::class, 'unit_list'])->name('unitlist');
    Route::get('/unit/add', [UnitController::class, 'create'])->name('unit.add');
    Route::get('/unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::delete('api/unit_delete/{id}', [UnitController::class, 'destroy']);

    // Order Routes
    Route::get('/order_list', [OrderController::class, 'order_list'])->name('orderlist');
    Route::get('orders', [OrderController::class, 'list'])->name('orders');
    Route::get('order_details_view/{id}', [OrderController::class, 'details'])->name('orderdetailsview');

    // Delivery Routes
    Route::get('deliveries', [DeliveryController::class, 'list'])->name('deliveries');
    Route::get('/delivery/{id}', [DeliveryController::class, 'details'])->name('delivery.show');

    // Setting Routes
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('company_details', [SettingController::class, 'company_details'])->name('settings.company_details');
    Route::get('sliders', [SettingController::class, 'list'])->name('settings.sliders');
    Route::get('charges', [SettingController::class, 'chargelist'])->name('settings.charges');
    Route::post('/slider', [SettingController::class, 'sliderstore'])->name('slider.store');
    Route::delete('api/slide_delete/{id}', [SettingController::class, 'slidedelete']);
    Route::post('/charges', [SettingController::class, 'chargestore'])->name('charge.store');
    Route::delete('api/charge_delete/{id}', [SettingController::class, 'charge_delete']);
    Route::get('charges/{charge}', [SettingController::class, 'chargeshow'])->name('charges.show'); // Show charge
    Route::get('charges/{charge}/edit', [SettingController::class, 'chargeedit'])->name('charges.edit'); // Edit form
    Route::put('charges/{charge}', [SettingController::class, 'chargeupdate'])->name('charges.update'); // Update charge
    Route::delete('charges/{charge}', [SettingController::class, 'chargedestroy'])->name('charges.destroy'); // Delete charge

    // Page Routes
    Route::get('/pages', [PageController::class, 'index'])->name('settings.pages');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

    // FCM Config Routes
    Route::get('/fcm-config', [FcmConfigController::class, 'showConfigForm'])->name('fcm.config.form');
    Route::post('/fcm-config', [FcmConfigController::class, 'store'])->name('fcm.config.store');

    // Auth Routes
    Route::get('/appuser/edit/{id}', [AuthController::class, 'edit'])->name('appuser.edit');
    Route::put('/appuser/{id}', [AuthController::class, 'update'])->name('appuser.update');
    Route::put('/api/user/activate/{id}', [AuthController::class, 'activateAccount'])->name('user.activate');



    // Rating Routes
    Route::get('ratings', [RatingController::class, 'list'])->name('ratings');
});

require __DIR__ . '/auth.php';