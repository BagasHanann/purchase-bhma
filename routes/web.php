<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RequestItemsController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('dashboard', function () {
	return view('layouts.master');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('categories', CategoriesController::class);
        Route::get('/apiCategories', [CategoriesController::class, 'apiCategories'])->name('api.categories');
        Route::resource('suppliers', SupplierController::class);
        Route::get('/apiSuppliers', [SupplierController::class, 'apiSuppliers'])->name('api.suppliers');   
        Route::resource('products', ProductsController::class);
        Route::get('/apiProducts', [ProductsController::class, 'apiProducts'])->name('api.products');
        Route::resource('productsIn', OrderItemsController::class);
        Route::get('/apiProductsIn', [OrderItemsController::class, 'apiProductsIn'])->name('api.productsIn');
        Route::get('/exportOrderItemsAll', [OrderItemsController::class, 'exportOrderItemsAll'])->name('exportPDF.OrderItemsAll');
        Route::get('exportPDF/{id}', [OrderItemsController::class, 'exportPDF'])->name('exportPDF.orderItem');
        Route::post('/requestItems/{id}/status', [RequestItemsController::class, 'updateStatus'])->name('requestItems.updateStatus');
        Route::post('/fetchApprovedRequestDetails', [OrderItemsController::class, 'fetchApprovedRequestDetails'])->name('fetchApprovedRequestDetails');        
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::get('/apiUser', [UserController::class, 'apiUsers'])->name('api.users');
        Route::post('/register', [UserController::class, 'store'])->name('register');
    });

    Route::middleware('role:admin,staff,field')->group(function () {
        Route::resource('requestItems', RequestItemsController::class);
    });
    
    Route::middleware('role:admin,field,staff')->group(function () {
        Route::get('/apiRequest', [RequestItemsController::class, 'apiRequest'])->name('api.request');
    });

    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::patch('/update-profile', [ProfileController::class, 'update'])->name('update-profile');
});




