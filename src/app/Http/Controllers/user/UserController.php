<?php

namespace app\Http\Controllers\user;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }
}
