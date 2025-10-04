<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:3'
        ]);

        // Lưu mật khẩu với Hash::make
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        // Sau khi đăng ký -> quay lại trang đăng nhập
        return redirect('/login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // Lấy user từ database
        $user = User::where('email', $request->email)->first();

        // Kiểm tra có tồn tại và đúng mật khẩu (so sánh bằng Hash::check)
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect('/home');
        }

        // Nếu sai
        return back()->withErrors([
            'email' => 'Sai email hoặc mật khẩu.'
        ]);
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Bạn đã đăng xuất.');
    }
}
