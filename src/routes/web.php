<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\BookMarkController;

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

/**
 * ログイン関連のページのルーティング
 */
Auth::routes([
    'register' => false // ユーザ登録機能をオフに切替
]);

Route::get('/register', [RegisterUserController::class, 'getRegister'])
    ->name('register');
Route::post('/register', [RegisterUserController::class, 'postRegister'])
    ->name('register');
Route::get('/registered', [RegisterUserController::class, 'registered'])
    ->name('registered');

Route::get('/login', [AuthenticatedSessionController::class, 'login'])
    ->name('login');
Route::get('/logout', [AuthenticatedSessionController::class, 'Logout'])
    ->name('logout');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [ShopController::class, 'index'])
        ->name('shop_list');
    Route::get('/detail', [ShopController::class, 'detail'])
        ->name('shop_detail');
    Route::Post('/done', [ShopController::class, 'reservation'])
        ->name('reservation');

    Route::get('/mypage', [MypageController::class, 'mypage'])
        ->name('my_page');
    Route::post('/mypage', [MypageController::class, 'edit'])
        ->name('edit');

    Route::get('/bookmark/{shop?}', [BookMarkController::class, 'bookmark'])
        ->name('bookmark');
    Route::get('/unbookmark/{shop?}', [BookMarkController::class, 'unbookmark'])
        ->name('unbookmark');

    Route::get('/review', [ShopController::class, 'review'])
        ->name('review');
    Route::Post('/reviewed', [ShopController::class, 'post'])
        ->name('post');

});

Route::group(['middleware' => ['auth', 'can:admin']], function () {
    Route::get('/admin', [MypageController::class, 'admin'])
        ->name('admin');


    //権限の付与
    Route::put('/admin/attach', [MypageController::class, 'attach'])->name('admin.attach');

    //権限を外す
    Route::put('/admin/detach', [MypageController::class, 'detach'])->name('admin.detach');
});

Route::group(['middleware' => ['auth', 'can:shopkeeper']], function () {
    Route::get('/shopkeeper', [MypageController::class, 'shopkeeper'])
        ->name('shopkeeper');
    Route::post('/shopkeeper', [MypageController::class, 'revise'])
    ->name('revise');
});
