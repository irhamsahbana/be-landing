<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AccessRightController,
    AuthController,
    CategoryController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|-----------------------------------------------------------------            return route('login');These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['guest']], function() {
    Route::view('/login', 'pages.LoginIndex')->name('auth.login');
    Route::post('login', [AuthController::class, 'attempt'])->name('auth.login-attempt');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return view('App');
    })->name('app');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('access-rights/detail/{id}', [AccessRightController::class, 'show'])->name('access-right.show');
    Route::delete('access-rights/{id}', [AccessRightController::class, 'destroy'])->name('access-right.destroy');
    Route::patch('access-rights/{id}', [AccessRightController::class, 'update'])->name('access-right.update');
    Route::post('access-rights', [AccessRightController::class, 'store'])->name('access-right.store');
    Route::get('access-rights', [AccessRightController::class, 'index'])->name('access-right.index');

    Route::get('kategori/list', [CategoryController::class, 'list'])->name('category.list');
    Route::delete('kategori/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::patch('kategori/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('kategori/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('kategori', [CategoryController::class, 'index'])->name('category.index');
    Route::post('kategori', [CategoryController::class, 'store'])->name('category.store');
});
