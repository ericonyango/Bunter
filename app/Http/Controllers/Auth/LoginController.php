<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\RequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\VerifySinginRequest;
use App\Marketplace\Utility\Captcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showSignIn(): View
    {
        return view('auth.signin')->with([
            'captcha' => Captcha::Build()
        ]);
    }

    public function postSignIn(SignInRequest $request): RedirectResponse
    {
        try {
            return $request ->persist();
        }catch (RequestException $e){
            session() -> flash('errormessage',$e->getMessage());
            return redirect()->back();
        }
    }

    public function postSignOut(): RedirectResponse
    {
        auth()->logout();
        session()->flush();
        return redirect()->route('home');
    }

    public function showVerify(): View
    {
        return view('auth.verify');
    }

    public function postVerify(VerifySinginRequest $request): RedirectResponse
    {
        try{
            return $request -> persist();
        }
        catch (RequestException $exception){
            session() -> flash('errormessage', $exception -> getMessage());
            return redirect() -> back();
        }
    }
}
