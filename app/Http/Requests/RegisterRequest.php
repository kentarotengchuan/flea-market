<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => ['required','email'],
            'password' => ['required','min:8','confirmed'],
            'password_confirmation' => ['required','min:8'],
        ];
    }
    public function messages(){
        return [
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレス形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワードと一致しません',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $customErrors = [];

        foreach ($errors->messages() as $field => $messages) {
            if ($field === 'password') {
                foreach ($messages as $message) {
                    if ($message === __('パスワードと一致しません')) {
                        session()->flash('password_confirmation_error', $message);
                    } else {
                        if (!session()->has('password_error')) {
                            session()->flash('password_error', $message);
                        }
                    }
                }
            } else {
                $customErrors[$field] = $messages;
            }
        }

        if (!empty($customErrors)) {
            session()->flash('validation_errors', $customErrors);
        }

        throw new ValidationException($validator);
    }
}
