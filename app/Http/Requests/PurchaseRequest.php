<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'method' => 'required',
            'address' => 'required',
        ];
    }
    public function messages(){
        return [
            'method.required' => '支払い方法を選択してください',
            'address.required' => '住所を指定してください',
        ];
    }

    public function __get($key)
    {
        return $this->input($key);
    }
}
