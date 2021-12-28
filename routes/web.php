<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);
Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/districts', [App\Http\Controllers\HomeController::class, 'districts'])->name('districts');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('admin.home')->middleware('validemail');
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('admin.profile');
        Route::post('/password', [App\Http\Controllers\Admin\ProfileController::class, 'password'])->name('admin.password');
        Route::post('/update-profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.updateprofile');
        Route::middleware('validemail')->group(function () {
            Route::resource('drivers', App\Http\Controllers\Admin\DriverController::class, ['names' => 'admin.drivers']);
            Route::resource('orders', App\Http\Controllers\Admin\OrderController::class, ['names' => 'admin.orders'])->only(['index', 'show', 'update', 'destroy']);
            Route::post('/orders/set-driver', [App\Http\Controllers\Admin\OrderController::class, 'setDriver'])->name('admin.orders.setdriver');
            Route::patch('/orders/cancel/{order}', [App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('admin.orders.cancel');
            Route::patch('/orders/set-driver-notes/{order}', [App\Http\Controllers\Admin\OrderController::class, 'setDriverNotes'])->name('admin.orders.setdrivernotes');
            Route::patch('/shops/change-status', [App\Http\Controllers\Admin\ShopController::class, 'status'])->name('admin.shops.status');
            Route::resource('shops', App\Http\Controllers\Admin\ShopController::class, ['names' => 'admin.shops']);
            Route::resource('members', App\Http\Controllers\Admin\MemberController::class, ['names' => 'admin.members']);
            Route::resource('products', App\Http\Controllers\Admin\ProductController::class, ['names' => 'admin.products']);
            Route::post('products/image/create/{product}', [App\Http\Controllers\Admin\ProductController::class, 'uploadImage'])->name('admin.products.image.create');
            Route::delete('products/image/delete/{productImage}', [App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('admin.products.image.destroy');
            Route::resource('product-categories', App\Http\Controllers\Admin\ProductCategoryController::class, ['names' => 'admin.product-categories']);
            Route::resource('product-units', App\Http\Controllers\Admin\ProductUnitController::class, ['names' => 'admin.product-units']);
            Route::resource('shippings', App\Http\Controllers\Admin\ShippingController::class, ['names' => 'admin.shippings']);
            Route::get('options', [App\Http\Controllers\Admin\OptionController::class, 'index'])->name('admin.options.index');
            Route::patch('options', [App\Http\Controllers\Admin\OptionController::class, 'update'])->name('admin.options.update');
            Route::post('/login-as-member/{member}', [App\Http\Controllers\Admin\MemberController::class, 'loginAs'])->name('admin.loginasmember');
        });
        Route::get('/notification', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notification');
        Route::get('/notification/{notification}', [App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('admin.notification.show');
        Route::patch('/notification/mark-all-as-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notification.markasread');
    });

    Route::middleware('member')->prefix('member')->group(function () {
        Route::get('/', [App\Http\Controllers\Member\HomeController::class, 'dashboard'])->name('member.dashboard');
        Route::get('/home', [App\Http\Controllers\Member\HomeController::class, 'index'])->name('member.home')->middleware('validemail');
        Route::get('/profile', [App\Http\Controllers\Member\ProfileController::class, 'index'])->name('member.profile');
        Route::post('/password', [App\Http\Controllers\Member\ProfileController::class, 'password'])->name('member.password');
        Route::post('/update-profile', [App\Http\Controllers\Member\ProfileController::class, 'update'])->name('member.updateprofile');
        Route::post('/update-address', [App\Http\Controllers\Member\ProfileController::class, 'updateAddress'])->name('member.updateaddress');
        Route::middleware('validemail')->group(function () {
            Route::resource('products', App\Http\Controllers\Member\ProductController::class);
            Route::post('products/image/create/{product}', [App\Http\Controllers\Member\ProductController::class, 'uploadImage'])->name('products.image.create');
            Route::delete('products/image/delete/{productImage}', [App\Http\Controllers\Member\ProductController::class, 'destroyImage'])->name('products.image.destroy');
            Route::resource('product-categories', App\Http\Controllers\Member\ProductCategoryController::class);
            Route::resource('shops', App\Http\Controllers\Member\ShopController::class)->except([
                'view', 'destroy'
            ]);
            Route::prefix('order')->group(function () {
                Route::get('/product', [App\Http\Controllers\Member\OrderFlowController::class, 'searchProduct'])->name('member.order.search');
                Route::get('/category/{slug}', [App\Http\Controllers\Member\OrderFlowController::class, 'categoryProduct'])->name('member.order.category');
                Route::get('/shop/{slug}', [App\Http\Controllers\Member\OrderFlowController::class, 'shopProduct'])->name('member.order.shop');
                Route::get('/cart', [App\Http\Controllers\Member\OrderFlowController::class, 'carts'])->name('member.order.cart');
                Route::get('/checkout', [App\Http\Controllers\Member\OrderFlowController::class, 'checkout'])->name('member.order.checkout');
                Route::get('/payment', [App\Http\Controllers\Member\OrderFlowController::class, 'payment'])->name('member.order.payment');
                Route::post('/payment', [App\Http\Controllers\Member\OrderFlowController::class, 'paymentProceed'])->name('member.order.paymentproceed');
                Route::get('/{slug}', [App\Http\Controllers\Member\OrderFlowController::class, 'detailProduct'])->name('member.order.product');
                Route::post('/cart/{product}/edit', [App\Http\Controllers\Member\OrderFlowController::class, 'addToCart'])->name('member.order.addtocart');
                Route::delete('/cart/{product}/delete', [App\Http\Controllers\Member\OrderFlowController::class, 'deleteCart'])->name('member.order.deletecart');
                Route::post('/cart/shipping', [App\Http\Controllers\Member\OrderFlowController::class, 'shipping'])->name('member.order.shipping');
                Route::post('/cart/notes', [App\Http\Controllers\Member\OrderFlowController::class, 'setNotes'])->name('member.order.notes');
            });
            Route::resource('invoice', App\Http\Controllers\Member\InvoiceController::class, ['names' => 'member.invoice'])->only(['index', 'show']);
            Route::resource('customer-order', App\Http\Controllers\Member\CustomerOrderController::class, ['names' => 'member.customerorder'])->only(['index', 'update', 'destroy', 'show']);
            Route::patch('/customer-order/cancel/{invoice}', [App\Http\Controllers\Member\CustomerOrderController::class, 'cancel'])->name('member.customerorder.cancel');
            Route::prefix('review')->group(function () {
                Route::get('/index', [App\Http\Controllers\Member\ReviewController::class, 'index'])->name('member.review.index');
                Route::get('/create/{product}', [App\Http\Controllers\Member\ReviewController::class, 'create'])->name('member.review.create');
                Route::post('/store/{product}', [App\Http\Controllers\Member\ReviewController::class, 'store'])->name('member.review.store');
                Route::patch('/update/{review}', [App\Http\Controllers\Member\ReviewController::class, 'update'])->name('member.review.update');
                Route::get('/show/{review}', [App\Http\Controllers\Member\ReviewController::class, 'show'])->name('member.review.show');
            });
        });
        Route::get('/notification', [App\Http\Controllers\Member\NotificationController::class, 'index'])->name('member.notification');
        Route::get('/notification/{notification}', [App\Http\Controllers\Member\NotificationController::class, 'show'])->name('member.notification.show');
        Route::patch('/notification/mark-all-as-read', [App\Http\Controllers\Member\NotificationController::class, 'markAsRead'])->name('member.notification.markasread');
        Route::post('/reload-balance', [App\Http\Controllers\Member\ProfileController::class, 'reloadBalance'])->name('member.reloadbalance');
        Route::get('/shop/{shop}', [App\Http\Controllers\Member\ShopController::class, 'show'])->name('member.shops.show');
        Route::patch('/shop/change-status', [App\Http\Controllers\Member\ShopController::class, 'status'])->name('member.shops.status');
    });

    Route::middleware('driver')->prefix('driver')->group(function () {
        Route::get('/', [App\Http\Controllers\Driver\HomeController::class, 'dashboard'])->name('driver.dashboard');
        Route::get('/home', [App\Http\Controllers\Driver\HomeController::class, 'dashboard'])->name('driver.home')->middleware('validemail');;
        Route::get('/profile', [App\Http\Controllers\Driver\ProfileController::class, 'index'])->name('driver.profile');
        Route::post('/password', [App\Http\Controllers\Driver\ProfileController::class, 'password'])->name('driver.password');
        Route::post('/update-profile', [App\Http\Controllers\Driver\ProfileController::class, 'update'])->name('driver.updateprofile');
        Route::resource('assignments', App\Http\Controllers\Driver\AssignmentController::class, ['names' => 'driver.assignments'])->only(['index', 'show', 'update'])->middleware('validemail');
        Route::get('/notification', [App\Http\Controllers\Driver\NotificationController::class, 'index'])->name('driver.notification');
        Route::get('/notification/{notification}', [App\Http\Controllers\Driver\NotificationController::class, 'show'])->name('driver.notification.show');
        Route::patch('/notification/mark-all-as-read', [App\Http\Controllers\Driver\NotificationController::class, 'markAsRead'])->name('driver.notification.markasread');
        Route::get('/member/{member}', [App\Http\Controllers\Driver\MemberController::class, 'show'])->name('driver.members.show');
        Route::get('/shop/{shop}', [App\Http\Controllers\Driver\ShopController::class, 'show'])->name('driver.shops.show');
    });

    Route::middleware('super')->prefix('super')->group(function () {
        Route::get('/', [App\Http\Controllers\Super\HomeController::class, 'dashboard'])->name('super.dashboard');
        Route::get('/home', [App\Http\Controllers\Super\HomeController::class, 'dashboard'])->name('super.home');
        Route::get('/profile', [App\Http\Controllers\Super\ProfileController::class, 'index'])->name('super.profile');
        Route::post('/password', [App\Http\Controllers\Super\ProfileController::class, 'password'])->name('super.password');
        Route::post('/update-profile', [App\Http\Controllers\Super\ProfileController::class, 'update'])->name('super.updateprofile');
        Route::get('/notification', [App\Http\Controllers\Super\NotificationController::class, 'index'])->name('super.notification');
        Route::get('/notification/{notification}', [App\Http\Controllers\Super\NotificationController::class, 'show'])->name('super.notification.show');
        Route::patch('/notification/mark-all-as-read', [App\Http\Controllers\Super\NotificationController::class, 'markAsRead'])->name('super.notification.markasread');
        Route::resource('admins', App\Http\Controllers\Super\AdminController::class, ['names' => 'super.admins']);
        Route::get('options', [App\Http\Controllers\Super\OptionController::class, 'index'])->name('super.options.index');
        Route::patch('options', [App\Http\Controllers\Super\OptionController::class, 'update'])->name('super.options.update');
        Route::post('/login-as-admin/{admin}', [App\Http\Controllers\Super\AdminController::class, 'loginAs'])->name('super.loginasadmin');
        Route::resource('members', App\Http\Controllers\Super\MemberController::class, ['names' => 'super.members']);
        Route::post('/login-as-member/{member}', [App\Http\Controllers\Super\MemberController::class, 'loginAs'])->name('super.loginasmember');
        Route::resource('drivers', App\Http\Controllers\Super\DriverController::class, ['names' => 'super.drivers']);
        Route::post('/login-as-driver/{driver}', [App\Http\Controllers\Super\DriverController::class, 'loginAs'])->name('super.loginasdriver');
        Route::get('/failedapi', [App\Http\Controllers\Super\FailedApiController::class, 'index'])->name('super.failedapies.index');
        Route::post('/reexecute/{apiRequest}', [App\Http\Controllers\Super\FailedApiController::class, 'reExecute'])->name('super.failedapies.reexecute');
        Route::delete('/failedapi/delete/{apiRequest}', [App\Http\Controllers\Super\FailedApiController::class, 'destroy'])->name('super.failedapies.destroy');
        Route::resource('mailqueues', App\Http\Controllers\Super\MailQueueController::class, ['names' => 'super.mailqueues'])->only(['index', 'update', 'destroy']);
        Route::post('/mailqueues/clear', [App\Http\Controllers\Super\MailQueueController::class, 'clear'])->name('super.mailqueues.clear');
        Route::resource('shippings', App\Http\Controllers\Super\ShippingController::class, ['names' => 'super.shippings']);
        Route::patch('/shops/change-status', [App\Http\Controllers\Super\ShopController::class, 'status'])->name('super.shops.status');
        Route::resource('shops', App\Http\Controllers\Super\ShopController::class, ['names' => 'super.shops']);
        Route::resource('products', App\Http\Controllers\Super\ProductController::class, ['names' => 'super.products']);
        Route::post('products/image/create/{product}', [App\Http\Controllers\Super\ProductController::class, 'uploadImage'])->name('super.products.image.create');
        Route::delete('products/image/delete/{productImage}', [App\Http\Controllers\Super\ProductController::class, 'destroyImage'])->name('super.products.image.destroy');
        Route::resource('product-categories', App\Http\Controllers\Super\ProductCategoryController::class, ['names' => 'super.product-categories']);
    });
});

Route::get('/relay', [App\Http\Controllers\Member\RegisterController::class, 'index'])->name('member.register');
Route::get('/relay.php', [App\Http\Controllers\Member\RegisterController::class, 'relay'])->name('member.relay');
Route::post('/relay', [App\Http\Controllers\Member\RegisterController::class, 'create'])->name('member.doregister');
Route::get('/set-password/{token}', [App\Http\Controllers\Member\RegisterController::class, 'setPassword'])->name('member.setpassword');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
