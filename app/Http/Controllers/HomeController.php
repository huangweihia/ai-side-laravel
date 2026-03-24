<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * 个人中心（需要登录）
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
