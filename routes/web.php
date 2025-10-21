<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

// Route về trang home
// Route::get('/', function () {
//     return view('home'); 
//});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route test Hello
Route::get('/hello', function () {
    return 'Xin chào Laravel!';
});

// Route gọi lại index (nếu bạn có resources/views/index.blade.php)
Route::get('/index', function () {
    return view('index');
});

// ================= AUTH =================

// Hiển thị form Đăng ký
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Xử lý form Đăng ký
Route::post('/register', [UserController::class, 'register']);

// Hiển thị form Đăng nhập
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Đăng nhập
Route::post('/login', [UserController::class, 'login']);

// Đăng xuất
Route::post('/logout', [userController::class, 'logout'])->name('logout');

// Trang Home (chỉ vào được khi đã login)
Route::get('/home', [HomeController::class, 'index'])
->middleware('auth')
->name('home');

// Trang thông tin user
Route::get('/user/info', [UserController::class, 'info'])->name('user.info')->middleware('auth');

// Trang admin chỉ cho phép admin truy cập
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth')->name('admin');
Route::get('/adminUsers', [AdminController::class, 'usersList'])->name('admin.users');
Route::get('/adminUsers/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
Route::put('/adminUsers/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
Route::delete('/adminUsers/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
Route::get('/adminFlights', [AdminController::class, 'flightsList'])->name('admin.flights');
Route::get('/adminFlights/{id}', [AdminController::class, 'flightDetail'])->name('admin.flightDetail');

//Detail
Route::get('/detail/{id}', [HomeController::class, 'show'])->name('flights.detail');

// Xem giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Thêm vào giỏ hàng
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

// Xóa khỏi giỏ hàng
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');