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
Route::get('/admin/flights/create', [AdminController::class, 'createFlight'])->name('admin.flights.create');
Route::post('/admin/flights/store', [AdminController::class, 'storeFlight'])->name('admin.flights.store');
Route::get('/admin/flights/edit/{id}', [AdminController::class, 'editFlight'])->name('admin.flights.edit');
Route::post('/admin/flights/update/{id}', [AdminController::class, 'updateFlight'])->name('admin.flights.update');
Route::delete('/admin/flights/delete/{id}', [AdminController::class, 'deleteFlight'])->name('admin.flights.delete');

Route::prefix('admin')->name('admin.')->group(function () {
    //Aircraft
    Route::get('/aircrafts', [AdminController::class, 'listAircraft'])->name('aircrafts.index');
    Route::get('/aircrafts/create', [AdminController::class, 'createAircraft'])->name('aircrafts.create');
    Route::post('/aircrafts', [AdminController::class, 'storeAircraft'])->name('aircrafts.store');
    Route::get('/aircrafts/{id}/edit', [AdminController::class, 'editAircraft'])->name('aircrafts.edit');
    Route::put('/aircrafts/{id}', [AdminController::class, 'updateAircraft'])->name('aircrafts.update');
    Route::delete('/aircrafts/{id}', [AdminController::class, 'deleteAircraft'])->name('aircrafts.destroy');

    //Airline
    Route::get('/airlines', [AdminController::class, 'listAirline'])->name('airlines.index');
    Route::get('/airlines/create', [AdminController::class, 'createAirline'])->name('airlines.create');
    Route::post('/airlines', [AdminController::class, 'storeAirline'])->name('airlines.store');
    Route::get('/airlines/{id}/edit', [AdminController::class, 'editAirline'])->name('airlines.edit');
    Route::put('/airlines/{id}', [AdminController::class, 'updateAirline'])->name('airlines.update');
    Route::delete('/airlines/{id}', [AdminController::class, 'deleteAirline'])->name('airlines.destroy');

    //Airport
    Route::get('/airports', [AdminController::class, 'listAirport'])->name('airports.index');
    Route::get('/airports/create', [AdminController::class, 'createAirport'])->name('airports.create');
    Route::post('/airports', [AdminController::class, 'storeAirport'])->name('airports.store');
    Route::get('/airports/{id}/edit', [AdminController::class, 'editAirport'])->name('airports.edit');
    Route::put('/airports/{id}', [AdminController::class, 'updateAirport'])->name('airports.update');
    Route::delete('/airports/{id}', [AdminController::class, 'deleteAirport'])->name('airports.destroy');
});
//Detail
Route::get('/detail/{id}', [HomeController::class, 'show'])->name('flights.detail');

// Xem giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Thêm vào giỏ hàng
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

// Xóa khỏi giỏ hàng
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');