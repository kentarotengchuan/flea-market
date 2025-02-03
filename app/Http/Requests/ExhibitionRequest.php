<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'description' => ['required','max:255'],
            'image' => ['required','mimes:jpeg,png'],
            'options' => 'required',
            'condition' => 'required',
            'price' => ['required','int','min:1'],
        ];
    }
    public function messages(){
        return [
            'name.required' => '名前を入力してください',
            'description.required' => '説明文を入力してください',
            'description.max' => '説明文は255文字以内で入力してください',
            'image.required' => '画像を送信してください',
            'image.mimes' => '画像は.jpegもしくは.png形式で送信してください',
            'options.required' => 'カテゴリを選択してください',
            'condition.required' => '状態を選択してください',
            'price.required' => '価格を入力してください',
            'price.int' => '価格は数値形式で入力してください', 
            'price.min' => '価格は0円以上で入力してください',
        ];
    }
}
