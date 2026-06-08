<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReviewController;

// ─── LOJA ───────────────────────────────────────────────────
Route::get('/', [StoreController::class, 'index'])->name('store');

// Rotas com rate limiting por IP
// checkout/order: máx 10 pedidos por minuto
Route::post('/checkout/order',  [StoreController::class, 'createOrder'])
    ->middleware('throttle:10,1')->name('store.order');

// checkout/intent: máx 15 por minuto (antes do pagamento)
Route::post('/checkout/intent', [StoreController::class, 'createPaymentIntent'])
    ->middleware('throttle:15,1')->name('store.intent');

// coupon: máx 20 tentativas por minuto
Route::post('/checkout/coupon', [StoreController::class, 'validateCoupon'])
    ->middleware('throttle:20,1')->name('store.coupon');

// track: máx 10 consultas por minuto
Route::post('/checkout/track',  [StoreController::class, 'trackOrder'])
    ->middleware('throttle:10,1')->name('store.track');

// reviews: máx 3 avaliações por hora por IP
Route::post('/reviews', [StoreController::class, 'submitReview'])
    ->middleware('throttle:3,60')->name('store.review');

// ─── AUTH (Breeze) ───────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── ADMIN ───────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');

    Route::get   ('/orders',                [OrderController::class, 'index'])->name('orders.index');
    Route::get   ('/orders/{order}',        [OrderController::class, 'show'])->name('orders.show');
    Route::patch ('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    Route::get   ('/products',                  [ProductController::class, 'index'])->name('products.index');
    Route::post  ('/products',                  [ProductController::class, 'store'])->name('products.store');
    Route::put   ('/products/{product}',        [ProductController::class, 'update'])->name('products.update');
    Route::patch ('/products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    Route::delete('/products/{product}',        [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    Route::get   ('/coupons',                 [CouponController::class, 'index'])->name('coupons.index');
    Route::post  ('/coupons',                 [CouponController::class, 'store'])->name('coupons.store');
    Route::patch ('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
    Route::delete('/coupons/{coupon}',        [CouponController::class, 'destroy'])->name('coupons.destroy');

    Route::get   ('/gallery',           [GalleryController::class, 'index'])->name('gallery.index');
    Route::post  ('/gallery/upload',    [GalleryController::class, 'upload'])->name('gallery.upload');
    Route::post  ('/gallery/assign',    [GalleryController::class, 'assign'])->name('gallery.assign');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get   ('/reviews',                  [ReviewController::class, 'index'])->name('reviews.index');
    Route::post  ('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}',         [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get  ('/settings',            [SettingController::class, 'index'])->name('settings.index');
    Route::post ('/settings',            [SettingController::class, 'update'])->name('settings.update');
    Route::post ('/settings/stripe',     [SettingController::class, 'updateStripe'])->name('settings.stripe');
    Route::post ('/settings/hero-image', [SettingController::class, 'uploadHeroImage'])->name('settings.hero-image');
});
