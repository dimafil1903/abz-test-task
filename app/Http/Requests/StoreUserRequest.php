<?php

namespace App\Http\Requests;

use App\Exceptions\UnprocessableContentException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email:rfc,dns|max:100|unique:users,email',
            'phone' => ['required', 'regex:/^[\+]{0,1}380([0-9]{9})$/', 'unique:users,phone'],
            'position_id' => 'required|exists:positions,id',
            'photo' => 'required|image|mimes:jpeg,jpg|max:5120',
        ];
    }

    /**
     * @throws UnprocessableContentException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new UnprocessableContentException(
            $validator->errors(),
            'Validation failed'
        );
    }
}
