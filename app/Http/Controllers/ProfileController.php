<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function info()
    {
        $user = Auth::user();

        $customer = DB::table('Customers')
                    ->where('UserID', $user->id)
                    ->first();

        $loyalty = null;
        if ($customer) {
            $loyalty = DB::table('LoyaltyPrograms')
                        ->where('CustomerID', $customer->CustomerID)
                        ->first();
        }

        return view('users.info', compact('user', 'customer', 'loyalty'));
    }
}
