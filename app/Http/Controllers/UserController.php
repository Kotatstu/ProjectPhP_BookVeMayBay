<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\adminRole;
use App\Models\PaymentMethod;

class UserController extends Controller
{
    // Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:3'
        ]);

        if (User::where('name', $request->name)->exists()) {
            return back()->withErrors(['name' => 'Tên này đã có người sử dụng.'])->withInput();
        }

        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email này đã tồn tại.'])->withInput();
        }

        // Tạo user (chưa mã hoá mật khẩu)
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return redirect('/login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->password === $request->password) {
            Auth::login($user);

            $isAdmin = adminRole::where('U_ID', $user->id)->exists();

            if ($isAdmin) {
                $users = User::all();
                return view('admin.index', compact('users'));
            }
            return redirect('/home');
        }

        return back()->withErrors(['email' => 'Sai email hoặc mật khẩu.']);
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Bạn đã đăng xuất.');
    }

    // Hiển thị thông tin người dùng
    public function info()
    {
        $user = Auth::user();

        // Tìm customer tương ứng với user
        $customer = Customer::where('UserID', $user->id)->first();

        // Tìm payment method tương ứng
        $payment = null;
        if ($customer) {
            $payment = PaymentMethod::where('CustomerID', $customer->CustomerID)->first();
        }

        // Lấy loyalty program (nếu có)
        $loyalty = $user->loyaltyProgram ?? null;

        return view('users.info', compact('user', 'customer', 'payment', 'loyalty'));
    }



    // Sửa thông tin
    public function edit()
    {
        $user = Auth::user();
        $customer = Customer::where('UserID', $user->id)->first();
        $payment = $customer ? $customer->paymentMethods()->first() : null;

        return view('users.edit', compact('user', 'customer', 'payment'));
    }

    // Cập nhật thông tin
    public function update(Request $request)
{
    $user = Auth::user();

    // Cập nhật bảng users
    $user->update([
        'name' => $request->input('name'),
    ]);

    // Cập nhật hoặc tạo bảng Customers
    $customer = Customer::firstOrCreate(['UserID' => $user->id]);
    $customer->update([
        'Phone' => $request->input('phone'),
        'Gender' => $request->input('gender'),
        'DateOfBirth' => $request->input('date_of_birth'),
        'Nationality' => $request->input('nationality'),
    ]);

    // Cập nhật hoặc tạo bảng PaymentMethods
    if ($request->filled('payment_type') || $request->filled('provider') || $request->filled('account_number')) {
        PaymentMethod::updateOrCreate(
            ['CustomerID' => $customer->CustomerID],
            [
                'PaymentType' => $request->input('payment_type'),
                'Provider' => $request->input('provider'),
                'AccountNumber' => $request->input('account_number'),
                'ExpiryDate' => $request->input('expiry_date'),
            ]
        );
    }

    return redirect()->route('user.info')->with('success', 'Cập nhật thông tin và phương thức thanh toán thành công!');
}

}
