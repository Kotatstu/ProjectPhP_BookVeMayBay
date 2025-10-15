<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// Route về trang home
Route::get('/', function () {
    return view('home'); 
});

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
Route::get('/home', function () {
    return view('home');
})->middleware('auth');

// Trang admin chỉ cho phép admin truy cập
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth')->name('admin');
Route::get('/adminUsers', [AdminController::class, 'usersList'])->name('admin.users');
Route::get('/adminUsers/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
Route::put('/adminUsers/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
Route::delete('/adminUsers/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');