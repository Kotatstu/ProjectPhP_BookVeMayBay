<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        // 3 tài khoản đầu tiên (ID 1, 2, 3) là admin
        if ($user->id > 3) {
            return redirect('/home')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }

        // Lấy danh sách toàn bộ người dùng
        $users = DB::table('users')->get();

        return view('admin.index', compact('users'));
    }
}
