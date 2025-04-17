<?php

namespace App\Http\Requests\Auth;

use App\Marketplace\Encryption\Keypair;
use App\Marketplace\Utility\Mnemonic;
use App\Models\User;
use App\Rules\Captcha;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SignUpRequest extends FormRequest
{
    private mixed $refid;



    protected function prepareForValidation(): void
    {
        // Ensure $refid is set, even if the request doesn't include it
        $this->refid = $this->input('refid', null);
    }

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

            'captcha' => ['required', new Captcha()],
            'username' => 'required|unique:users|alpha_num|min:4|max:12',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'captcha.required' => 'Captcha is required',
            'username.required' => 'Username is required',
            'username.min' => 'Username must have at least 4 characters',
            'username.unique' => 'Account with that username already exists',
            'username.max' => 'Username cannot be longer than 12 characters',
            'username.alpha_num' => 'You can only use alpha-numeric characters for username',
            'password.required' => 'Password is required',
            'password.min' => 'Password must have at least 8 characters',
            'password.confirmed' => 'Password must be confirmed',
            'password.different' => 'Password can\'t be same as username',
        ];
    }

    /**
     * @throws \SodiumException
     * @throws EnvironmentIsBrokenException
     */
    public function persist(): void
    {

        //check if there is referral id
        $referred_by = null;
        if (!empty($this->refid)) {
            $referred_by = User::where('referral_code', $this->refid)->first();
        }


        // create users public and private RSA Keys
        $keyPair = new Keypair();
        $privateKey = $keyPair->getPrivateKey();
        $publicKey =   $keyPair->getPublicKey();
        // encrypt private key with user's password
        $encryptedPrivateKey = Crypto::encryptWithPassword($privateKey, $this->password);

        $mnemonic = (new Mnemonic())->generate(config('marketplace.mnemonic_length'));

        $user = new User();
        $user->username = $this->username;
        $user->password = bcrypt($this->password);
        $user->mnemonic = bcrypt(hash('sha256', $mnemonic));
        $user->referral_code = strtoupper(Str::random(6));
        $user->msg_public_key = encrypt($publicKey);
        $user->msg_private_key = $encryptedPrivateKey;
        $user -> referred_by = optional($referred_by) -> id;
        $user->save();

        // generate vendor addresses
//        $user->generateDepositAddresses();

        session()->flash('mnemonic_key', $mnemonic);
    }
}
