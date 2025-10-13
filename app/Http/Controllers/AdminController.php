<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\adminRole;


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
        
        // Lấy danh sách toàn bộ người dùng
        $users = DB::table('users')->get();

        return view('admin.index', compact('users'));
    }
}
