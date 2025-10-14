<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\adminRole;

class UserController extends Controller
{
    // Hàm đăng ký
    public function register(Request $request)
    {
        // Kiểm tra dữ liệu nhập
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:3'
        ]);

        // Kiểm tra từng trường xem có trùng hay không
        if (User::where('name', $request->name)->exists()) {
            return back()->withErrors(['name' => 'Tên này đã có người sử dụng.'])->withInput();
        }

        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email này đã tồn tại.'])->withInput();
        }

        // if (User::where('password', $request->password)->exists()) {
        //     return back()->withErrors(['password' => 'Mật khẩu này đã được sử dụng, hãy chọn mật khẩu khác.'])->withInput();
        // }

        // Tạo user mới (KHÔNG mã hoá password)
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password; // không dùng Hash
        $user->save();

        // Quay lại trang đăng nhập
        return redirect('/login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        // So sánh trực tiếp mật khẩu
        if ($user && $user->password === $request->password) {
            Auth::login($user); // Đăng nhập thành công

            // Kiểm tra xem user có trong bảng adminRole không
            $isAdmin = adminRole::where('U_ID', $user->id)->exists();

            if ($isAdmin) {
                // Lấy danh sách user để truyền qua view admin
                $users = User::all();
                return view('admin.index', compact('users'));
            }
            return redirect('/home');
        }

        // Nếu sai thông tin đăng nhập
        return back()->withErrors([
            'email' => 'Sai email hoặc mật khẩu.'
        ]);
    }

    //  Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Bạn đã đăng xuất.');
    }
}