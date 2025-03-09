<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NavigationController extends Controller
{
    public function getNavigationData()
    {
        $user = Auth::user(); // Получаем текущего пользователя
        return view('layouts.navigation', compact('user')); // Передаем пользователя в представление
    }
}
