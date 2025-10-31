<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        // lấy danh sách khách hàng và cả chương trình hội viên
        $customers = Customer::with(['user', 'loyaltyProgram'])->get();

        return view('customers.index', compact('customers'));
    }
}
