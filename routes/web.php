<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MasterBrandTypeController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Middleware\IsLogin;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [PublicDashboardController::class, 'index'])->name('public.home');
Route::get('/dash-public', [PublicDashboardController::class, 'index'])->name('public.dashboard');
Route::get('/dash-public/barang/{id}', [PublicDashboardController::class, 'show'])->whereNumber('id')->name('public.dashboard.show');

Route::middleware([IsLogin::class])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'index'])->name('dashboard.admin');
    Route::middleware('admin.access:inventory,found_items')->group(function () {
        Route::get('/admin/search', [DashboardController::class, 'search']);
    });

    Route::middleware('admin.access:inventory')->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index']);
        Route::get('/inventory/create', [InventoryController::class, 'create']);
        Route::get('/inventory/edit/{id}', [InventoryController::class, 'edit']);
        Route::post('/inventory/store', [InventoryController::class, 'store']);
        Route::put('/inventory/{id}', [InventoryController::class, 'update']);
        Route::delete('/inventory/{id}', [InventoryController::class, 'delete']);

        Route::get('/reports/inventory', [ReportController::class, 'inventory']);
        Route::get('/reports/inventory/export', [ReportController::class, 'exportInventory']);
    });

    Route::middleware('admin.access:documents')->group(function () {
        Route::get('/documents', [DocumentController::class, 'index']);
        Route::get('/documents/create', [DocumentController::class, 'create']);
        Route::post('/documents/store', [DocumentController::class, 'store']);
        Route::get('/documents/edit/{id}', [DocumentController::class, 'edit']);
        Route::put('/documents/{id}', [DocumentController::class, 'update']);
        Route::delete('/documents/{id}', [DocumentController::class, 'delete']);
    });

    Route::middleware('admin.access:found_items')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/create', [ProductController::class, 'create']);
        Route::get('/products/edit/{id}', [ProductController::class, 'edit']);
        Route::post('/products/store', [ProductController::class, 'store']);
        Route::post('/products/{id}/return', [ProductController::class, 'processReturn']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'delete']);
    });

    Route::middleware('admin.access:super_admin')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/create', [CategoryController::class, 'create']);
        Route::post('/categories/store', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'delete']);

        Route::get('/locations', [LocationController::class, 'index']);
        Route::get('/locations/create', [LocationController::class, 'create']);
        Route::post('/locations/store', [LocationController::class, 'store']);
        Route::put('/locations/{id}', [LocationController::class, 'update']);
        Route::delete('/locations/{id}', [LocationController::class, 'delete']);
        Route::post('/locations/sub-locations', [LocationController::class, 'storeSubLocation']);
        Route::put('/locations/sub-locations/{id}', [LocationController::class, 'updateSubLocation']);
        Route::delete('/locations/sub-locations/{id}', [LocationController::class, 'deleteSubLocation']);

        Route::redirect('/master-brand-type', '/merek');
        Route::redirect('/master-brand-type/create', '/merek/create');
        Route::get('/merek', [MasterBrandTypeController::class, 'index']);
        Route::get('/merek/create', [MasterBrandTypeController::class, 'create']);
        Route::post('/merek', [MasterBrandTypeController::class, 'storeBrand']);
        Route::put('/merek/{id}', [MasterBrandTypeController::class, 'updateBrand']);
        Route::delete('/merek/{id}', [MasterBrandTypeController::class, 'deleteBrand']);

        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::get('/admin/users/create', [AdminUserController::class, 'create']);
        Route::post('/admin/users', [AdminUserController::class, 'store']);
        Route::get('/admin/users/{id}/edit', [AdminUserController::class, 'edit']);
        Route::put('/admin/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);
    });
});
