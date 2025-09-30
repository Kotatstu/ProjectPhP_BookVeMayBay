<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function listView()
    {
        $users = User::all();

        return view('users.index', compact('users'));
        #view/users/index.blade.php
    }
}