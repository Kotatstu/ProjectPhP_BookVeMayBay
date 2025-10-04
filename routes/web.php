<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route về trang welcome (Laravel mặc định)
Route::get('/', function () {
    return view('welcome'); 
});

// Route test Hello
Route::get('/hello', function () {
    return 'Xin chào Laravel!';
});

// Route gọi lại index (nếu bạn có resources/views/index.blade.php)
Route::get('/index', function () {
    return view('index');
});

// Route gọi controller UserController
Route::get('/users-view', [UserController::class, 'listView']);

// ================= AUTH =================

// Hiển thị form Đăng ký
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Xử lý form Đăng ký
Route::post('/register', [AuthController::class, 'register']);

// Hiển thị form Đăng nhập
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Xử lý form Đăng nhập
Route::post('/login', [AuthController::class, 'login']);

// Đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Trang Home (chỉ vào được khi đã login)
Route::get('/home', function () {
    return view('home');
})->middleware('auth');
