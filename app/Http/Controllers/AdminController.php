<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\adminRole;
use App\Models\Flight;

class AdminController extends BaseController
{
    public function __construct()
    {
        // Bắt buộc phải đăng nhập mới được vào
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Kiểm tra xem user có trong bảng adminRole không
        $isAdmin = adminRole::where('U_ID', $user->id)->exists();
        if (!$isAdmin) {
            return redirect('/home')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }

        return view('admin.index');
    }

    public function usersList()
    {
        $user = Auth::user();
        // Lấy tất cả người dùng từ bảng 'users'
        $users = DB::table('users')->get();

        // Trả về view với danh sách người dùng
        return view('admin.users', compact('users'));
    }

    // Phương thức sửa thông tin người dùng
    public function editUser($id)
    {
        // Lấy thông tin người dùng dựa trên ID
        $user = User::findOrFail($id);
        $isAdmin = adminRole::where('U_ID', $user->id)->exists();

        return view('admin.editUser', compact('user', 'isAdmin'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->name = $request->name;
        $user->email = $request->email;

        if (!empty($request->password)) {
            $user->password = $request->password;
        }

        $user->save();

        if ($request->has('is_admin')) {
        // Nếu có tick => thêm vào bảng adminRole nếu chưa có
            adminRole::updateOrCreate(['U_ID' => $user->id]);
        } else {
            // Nếu bỏ tick => xóa quyền admin
            adminRole::where('U_ID', $user->id)->delete();
        }

        // Quay lại danh sách người dùng
        return redirect()->route('admin.users')->with('success', 'Cập nhật thông tin người dùng thành công.');
    }

    // Phương thức xóa người dùng
    public function deleteUser($id)
    {
        // Xóa người dùng từ bảng 'users'
        DB::table('users')->where('id', $id)->delete();

        // Redirect về trang danh sách người dùng với thông báo
        return redirect()->route('admin.users')->with('success', 'Người dùng đã được xóa.');
    }

    public function flightsList()
    {
        $flights = Flight::orderBy('DepartureTime', 'asc')->get();

        return view('admin.flights', compact('flights'));
    }

    public function flightDetail($id)
    {
        $flight = Flight::with([
            'airline',
            'aircraft',
            'departureAirport',
            'arrivalAirport',
            'fares.cabinClass'
        ])->findOrFail($id);

        // Lấy danh sách ghế đã được đặt từ SeatAvailability
        $bookedSeats = DB::table('SeatAvailability')
            ->where('FlightID', $flight->FlightID)
            ->where('IsBooked', 1)
            ->pluck('SeatID') // chỉ cần SeatID, giả sử SeatID từ 1 đến 116
            ->toArray();

        return view('admin.flightDetail', compact('flight', 'bookedSeats'));
    }
    private function authorizeAdmin()
    {
        $user = Auth::user();
        if (!AdminRole::where('U_ID', $user->id)->exists()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
    }
}
