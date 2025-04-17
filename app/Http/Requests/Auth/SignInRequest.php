<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\RequestException;
use App\Marketplace\PGP;
use App\Models\User;
use App\Rules\Captcha;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SignInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username'=>'required|exists:users,username',
            'password'=>'required',
            'captcha' => ['required', new Captcha()],
        ];
    }

    public function messages(): array
    {
        return[
            'username.required' => 'Username is required',
            'username.exists' => 'User with that username does not exist',
            'password.required' => 'Password is required',
            'captcha.required' => 'Captcha is required',
        ];
    }

    /**
     * @throws RequestException
     * @throws \Exception
     */
    public function persist()
    {
        $user = User::where('username',$this->username)->first();
        if ($user == null){
            throw  new RequestException('Could not find user with that username');
        }

        //check if the password match

        if (!Hash::check($this->password,$user->password)){
            throw new RequestException('Password is not valid');
        }

        auth() -> login($user);
        $user -> last_seen = Carbon::now();
        $user -> save();
        $this->session() ->regenerate();

        //user does not have 2fa enabled, log him in straight away

        if (!$user->login_2fa){
            return redirect()->route('profile.index');
        }

        $validationString = str::random(10);
        $messageToEncrypt = "To verify login please copy validation string to the matching field.\nValidation string:" . $validationString;
        $encryptedMessage = PGP::EncryptMessage($messageToEncrypt,$user->pgp_key);

        //save to sessions

        session()->put('login_validation_string',bcrypt($validationString));
        session()->put('login_encrypted_message',$encryptedMessage);

        //return route to verify 2fa
        return  redirect() -> route('auth.verify');
    }
}
