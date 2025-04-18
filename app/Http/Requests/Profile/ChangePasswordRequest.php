<?php

namespace App\Http\Requests\Profile;

use App\Exceptions\RequestException;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.confirmed' => 'You didn\'t confirm password correctly!',
            'new_password.min' => 'Your new password must be at least 8 characters length.'
        ];
    }

    /**
     * @throws EnvironmentIsBrokenException
     * @throws WrongKeyOrModifiedCiphertextException
     * @throws RequestException
     */
    public function persist()
    {
        $user = auth() ->user();
        if (Hash::check($this -> old_password,$user -> password)){
            // change user's password
            $user -> password = bcrypt($this -> new_password);

            // re-encrypt user's private key with new password
            $decryptedPrivateKey = Crypto::decryptWithPassword($user->msg_private_key, $this->old_password);
            $user->msg_private_key = Crypto::encryptWithPassword($decryptedPrivateKey, $this->new_password);

            // save changes
            $user -> save();


            session() -> flash('success', 'You have successfully changed your password!');
        }
        else
            throw new RequestException('success', 'You have successfully changed your password!');
    }
}
