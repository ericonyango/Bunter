<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function home(): View
    {
        return view('welcome');
    }

    public function login(): RedirectResponse
    {
        return redirect()->route('auth.signin');
    }
}
