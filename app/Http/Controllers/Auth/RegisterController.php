<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\Marketplace\Utility\Captcha;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RegisterController extends Controller
{
    public function showSignUp($refid = ''): View
    {
        return view('auth.signup')->with([
            'refid'=>$refid,
            'captcha'=>Captcha::Build(),
        ]);
    }

    public function signUpPost(SignUpRequest $request): RedirectResponse
    {
        try {
            $request->persist();
            return redirect()->route('auth.mnemonic');
        }catch (\Exception $e){
            Log::error($e);
            return redirect()->back();
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function showMnemonic(): RedirectResponse|View
    {
        if (!session()->has('mnemonic_key'))
            return redirect() ->route('auth.signin');
        return view('auth.mnemonic')->with('mnemonic',session()->get('mnemonic_key'));
    }
}
