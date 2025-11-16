<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use Symfony\Component\Routing\DependencyInjection\RoutingResolverPass;


Route::middleware('auth')->get('/user/info', [ProfileController::class, 'info'])
    ->name('user.info');


Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');


Route::get('/', [HomeController::class, 'index'])->name('home');

// Route test Hello
Route::get('/hello', function () {
    return 'Xin chào Laravel!';
});

// Route gọi lại index
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
//Route::get('/user/info', [UserController::class, 'info'])->name('user.info')->middleware('auth');
// Redirect đường dẫn cũ /user/info sang route đúng /profile
//Route::get('/user/info', function () { return redirect()->route('user.info'); });
// Route thông tin cá nhân cho đúng URL
Route::get('/user/info', [UserController::class, 'info'])
    ->name('user.info')
    ->middleware('auth');


Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
Route::post('/user/update', [UserController::class, 'update'])->name('user.update')->middleware('auth');


//Detail
Route::get('/detail/{id}', [HomeController::class, 'show'])->name('flights.detail');

//Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/remove/{ticket}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/store-and-checkout', [CartController::class, 'storeAndCheckout'])->name('cart.storeAndCheckout');
Route::post('/cart/cancel/{ticket}', [CartController::class, 'cancel'])->name('cart.cancel');
Route::get('/cart/edit/{ticket}', [CartController::class, 'edit'])->name('cart.edit');

// Checkout
Route::get('/cart/checkout/{ticket}', [CartController::class, 'checkoutForm'])->name('cart.checkoutForm');
Route::post('/cart/checkout/{ticket}', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout-all', [CartController::class, 'checkoutAll'])->name('cart.checkoutAll');
Route::get('/cart/checkout-all', [CartController::class, 'checkoutAllForm'])->name('cart.checkoutAllForm');

//Tìm kiếm chuyến bay trang home
Route::get('/search', [HomeController::class, 'search'])->name('flights.search');



// // Trang admin chỉ cho phép admin truy cập
// Route::get('/admin', [AdminController::class, 'index'])->middleware('auth')->name('admin');
// Route::get('/adminUsers', [AdminController::class, 'usersList'])->name('admin.users');
// Route::get('/adminUsers/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
// Route::put('/adminUsers/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
// Route::delete('/adminUsers/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
// Route::get('/adminFlights', [AdminController::class, 'flightsList'])->name('admin.flights');
// Route::get('/adminFlights/{id}', [AdminController::class, 'flightDetail'])->name('admin.flightDetail');
// Route::get('/admin/flights/create', [AdminController::class, 'createFlight'])->name('admin.flights.create');
// Route::post('/admin/flights/store', [AdminController::class, 'storeFlight'])->name('admin.flights.store');
// Route::get('/admin/flights/edit/{id}', [AdminController::class, 'editFlight'])->name('admin.flights.edit');
// Route::post('/admin/flights/update/{id}', [AdminController::class, 'updateFlight'])->name('admin.flights.update');
// Route::delete('/admin/flights/delete/{id}', [AdminController::class, 'deleteFlight'])->name('admin.flights.delete');

// Route::prefix('admin')->name('admin.')->group(function () {
//     //Aircraft
//     Route::get('/aircrafts', [AdminController::class, 'listAircraft'])->name('aircrafts.index');
//     Route::get('/aircrafts/create', [AdminController::class, 'createAircraft'])->name('aircrafts.create');
//     Route::post('/aircrafts', [AdminController::class, 'storeAircraft'])->name('aircrafts.store');
//     Route::get('/aircrafts/{id}/edit', [AdminController::class, 'editAircraft'])->name('aircrafts.edit');
//     Route::put('/aircrafts/{id}', [AdminController::class, 'updateAircraft'])->name('aircrafts.update');
//     Route::delete('/aircrafts/{id}', [AdminController::class, 'deleteAircraft'])->name('aircrafts.destroy');

//     //Airline
//     Route::get('/airlines', [AdminController::class, 'listAirline'])->name('airlines.index');
//     Route::get('/airlines/create', [AdminController::class, 'createAirline'])->name('airlines.create');
//     Route::post('/airlines', [AdminController::class, 'storeAirline'])->name('airlines.store');
//     Route::get('/airlines/{id}/edit', [AdminController::class, 'editAirline'])->name('airlines.edit');
//     Route::put('/airlines/{id}', [AdminController::class, 'updateAirline'])->name('airlines.update');
//     Route::delete('/airlines/{id}', [AdminController::class, 'deleteAirline'])->name('airlines.destroy');

//     //Airport
//     Route::get('/airports', [AdminController::class, 'listAirport'])->name('airports.index');
//     Route::get('/airports/create', [AdminController::class, 'createAirport'])->name('airports.create');
//     Route::post('/airports', [AdminController::class, 'storeAirport'])->name('airports.store');
//     Route::get('/airports/{id}/edit', [AdminController::class, 'editAirport'])->name('airports.edit');
//     Route::put('/airports/{id}', [AdminController::class, 'updateAirport'])->name('airports.update');
//     Route::delete('/airports/{id}', [AdminController::class, 'deleteAirport'])->name('airports.destroy');

//     //Ticket
//     Route::get('/tickets', [AdminController::class, 'listTickets'])->name('tickets.index');
//     Route::get('/tickets/{id}/edit', [AdminController::class, 'editTicket'])->name('tickets.edit');
//     Route::post('/tickets/{id}/update', [AdminController::class, 'updateTicket'])->name('tickets.update');
//     Route::delete('/tickets/{id}', [AdminController::class, 'deleteTicket'])->name('tickets.delete');

//     //Fare
//     Route::get('/fares', [AdminController::class, 'listFare'])->name('fares.index');
//     Route::get('/fares/{id}/edit', [AdminController::class, 'editFare'])->name('fares.edit');
//     Route::put('/fares/{id}', [AdminController::class, 'updateFare'])->name('fares.update');
//     Route::delete('/fares/{id}', [AdminController::class, 'destroyFare'])->name('fares.destroy');
//     Route::get('/fares/create', [AdminController::class, 'createFare'])->name('fares.create');
//     Route::post('/fares', [AdminController::class, 'storeFare'])->name('fares.store');

//     //Revenue
//     Route::get('/revenue', [AdminController::class, 'revenue'])->name('revenue');
// });

//Route::get('/team-members', [AdminController::class, 'listMembers'])->name('members');
//==============================CÁC ROUTE CỦA ADMIN====================================
// Gom nhóm toàn bộ route admin, buộc phải đăng nhập và là admin
Route::middleware(['auth'])->group(function () {

    // Trang admin chỉ cho phép admin truy cập
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
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

        //Ticket
        Route::get('/tickets', [AdminController::class, 'listTickets'])->name('tickets.index');
        Route::get('/tickets/{id}/edit', [AdminController::class, 'editTicket'])->name('tickets.edit');
        Route::post('/tickets/{id}/update', [AdminController::class, 'updateTicket'])->name('tickets.update');
        Route::delete('/tickets/{id}', [AdminController::class, 'deleteTicket'])->name('tickets.delete');

        //Fare
        Route::get('/fares', [AdminController::class, 'listFare'])->name('fares.index');
        Route::get('/fares/{id}/edit', [AdminController::class, 'editFare'])->name('fares.edit');
        Route::put('/fares/{id}', [AdminController::class, 'updateFare'])->name('fares.update');
        Route::delete('/fares/{id}', [AdminController::class, 'destroyFare'])->name('fares.destroy');
        Route::get('/fares/create', [AdminController::class, 'createFare'])->name('fares.create');
        Route::post('/fares', [AdminController::class, 'storeFare'])->name('fares.store');

        //Revenue
        Route::get('/revenue', [AdminController::class, 'revenue'])->name('revenue');
    });
});

// Route ngoài admin
Route::get('/team-members', [AdminController::class, 'listMembers'])->name('members');

//Route map
Route::get('/routes/{id}', [HomeController::class, 'showRoute'])->name('route.show');

