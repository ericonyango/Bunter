<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\RequestException;
use App\Marketplace\Encryption\Keypair;
use App\Models\User;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Hash;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use SodiumException;

class RecoverPasswordMnemonicRequest extends FormRequest
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
            'username' => 'required|exists:users,username',
            'mnemonic'=> 'required',
            'password' => 'required|string|confirmed|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required',
            'username.exists' => 'User with that username does not exist',
            'mnemonic.required'=>'Mnemonic is required',
            'password.required'=>'Password is required',
            'password.confirmed' => 'You didn\'t confirm password correctly!',
            'password.min' => 'Password must have at least 8 characters',
        ];
    }

    /**
     * @throws RequestException
     * @throws SodiumException
     * @throws EnvironmentIsBrokenException
     */
    public function persist()
    {
        $user = User::where('username',$this->username)->first();
        //check if user exist

        if ($user == null){
            throw new RequestException('Could not find user with that username');
        }

        //check if mnemonics match

        if (
            !Hash::check($this->has('sha256', $this->mnemonic), $user->mnemonic)
        ){
            throw new RequestException('Mnemonic is not valid');
        }

        $user->password = bcrypt($this->password);

        //generate new key pair

        $keyPair = new Keypair();
        $privateKey = $keyPair->getPrivateKey();
        $publicKey = $keyPair->getPublicKey();

        //encrypt private key with user's password

        $encryptedPrivateKey = Crypto::encryptWithPassword($privateKey,$this->password);
        $user -> msg_public_key = encrypt($privateKey);
        $user->msg_private_key = $encryptedPrivateKey;

        $user->save();
        session() -> flash('success','You have successfully changed your password!');
        redirect() -> route('auth.signin');
    }
}
