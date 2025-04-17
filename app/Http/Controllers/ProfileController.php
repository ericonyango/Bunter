<?php

namespace App\Http\Controllers;

use App\Exceptions\RequestException;
use App\Http\Requests\Profile\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verify_2fa');
    }

    public function index(): View
    {
        return view('profile.index');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        try{
            $request -> persist();
        }
        catch (RequestException $e){
            session() -> flash('errormessage', $e -> getMessage());
        }

        return redirect() -> back();
    }

    public function change2fa($turn): RedirectResponse
    {
        try{
            auth() -> user() -> set2fa($turn);
            session() -> flash('success', 'You have changed you 2FA setting.');
        }
        catch (RequestException $e){
            session() -> flash('errormessage', $e -> getMessage());
        }
        return redirect() -> back();

    }
}
