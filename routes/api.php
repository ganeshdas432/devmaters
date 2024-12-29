<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FcmConfigController;
use App\Http\Controllers\ActiveRiderController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\EmailConfigController;
use App\Http\Controllers\KycDocumentController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/appuser/{usertype}', [AuthController::class, 'user_by_usertype']);
Route::get('/counts', [AuthController::class, 'getCounts']);
Route::get('/unitlist', [UnitController::class, 'unit_api']);
Route::get('/shoplist', [ShopController::class, 'shoplistapi']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show'])->name('api.products.show');

Route::get('/orderlist', [OrderController::class, 'order_api']);
Route::get('/rideorderlist', [OrderController::class, 'ride_order_api']);
Route::get('/porterorderlist', [OrderController::class, 'porter_order_api']);

Route::get('/orderlist/{cid}', [OrderController::class, 'order_bycid_api']);
Route::get('/rideorderlist/{cid}', [OrderController::class, 'rider_order_bycid_api']);
Route::get('/porterorderlist/{cid}', [OrderController::class, 'porter_order_bycid_api']);

Route::get('/order/{id}', [OrderController::class, 'order_details_api']);

Route::post('/order/status/{orderId}', [OrderController::class, 'updateStatus']);
Route::post('/order/assign/{orderId}', [OrderController::class, 'assignDeliveryBoy']);

Route::post('/orders', [OrderController::class, 'store']);
Route::post('/rideorders', [OrderController::class, 'storeRider']);
Route::post('/porterorders', [OrderController::class, 'storePorter']);

Route::get('statuses', [OrderController::class, 'getStatus']);
Route::get('riders', [AuthController::class, 'getRiders']);

Route::get('pending_orders/{order_status}', [DeliveryController::class, 'pending_orders']);
Route::get('rider_accept/{order_id}/{rid}', [DeliveryController::class, 'order_accept']);
Route::get('/rider_deliveries', [DeliveryController::class, 'getRideOrders']);

Route::get('/all_deliveries', [DeliveryController::class, 'index']);
Route::get('/deliveries_by_cid/{cid}', [DeliveryController::class, 'deliveries_by_cid']);
Route::post('/deliveries', [DeliveryController::class, 'store']);  // POST route to submit data
Route::get('/delivery/{id}', [DeliveryController::class, 'delivery_details_api']);

Route::get('/ratinglist', [RatingController::class, 'rating_api']);

Route::get('/catlist', [CategoryController::class, 'category_api']);

Route::apiResource('address', AddressController::class);

Route::get('/details', [SettingController::class, 'show']);

Route::post('/details/update', [SettingController::class, 'update']);
Route::get('/slider_images', [SettingController::class, 'sliderallapi']);

Route::get('charges', [SettingController::class, 'chargeallapi']);
Route::get('pages', [SettingController::class, 'pageallapi']);

Route::put('/pages/{id}', [PageController::class, 'update']);
Route::delete('/pages/{id}', [PageController::class, 'destroy']);
Route::get('/pages/title/{title}', [PageController::class, 'getPageByTitle']);

Route::get('/fcm-config', [FcmConfigController::class, 'index']);
Route::post('/fcm-config', [FcmConfigController::class, 'store']);

Route::get('/users-details/{id}', [UserDetailsController::class, 'show']);

Route::put('/active_riders/{rider_id}/status', [ActiveRiderController::class, 'updateStatus']);
Route::get('/getActiveRider/{rider_id}', [ActiveRiderController::class, 'getStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users_details', [UserDetailsController::class, 'index']);
    Route::post('/users_details', [UserDetailsController::class, 'store']);
    Route::put('/users_details/{id}', [UserDetailsController::class, 'update']);
    Route::delete('/users_details/{id}', [UserDetailsController::class, 'destroy']);
});

Route::put('/appuser/{id}/status', [AppUserController::class, 'updateStatus']);

// Zone routes
Route::get('/zones', [ZoneController::class, 'index']);
Route::post('/zones', [ZoneController::class, 'store']);
Route::put('/zones/{zone}', [ZoneController::class, 'update']);
Route::delete('/zones/{zone}', [ZoneController::class, 'destroy']);

// Auth routes
Route::post('/register', [AuthController::class, 'register']);

// Payment Settings routes
Route::get('/payment-settings', [PaymentSettingController::class, 'index']);
Route::post('/payment-settings', [PaymentSettingController::class, 'update']);

// Email Configuration Routes
Route::get('/email-config', [EmailConfigController::class, 'index']);
Route::post('/email-config', [EmailConfigController::class, 'store']);
Route::post('/email-config/test', [EmailConfigController::class, 'test']);

// KYC Document Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kyc-documents', [KycDocumentController::class, 'index']);
    Route::post('/kyc-documents', [KycDocumentController::class, 'store']);
    Route::get('/kyc-documents/{document}', [KycDocumentController::class, 'show']);
    Route::post('/kyc-documents/{document}/verify', [KycDocumentController::class, 'verify']);
    Route::delete('/kyc-documents/{document}', [KycDocumentController::class, 'destroy']);
});
