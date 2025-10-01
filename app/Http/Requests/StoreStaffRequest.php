<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'password' => ['required','string','min:8'],
        ];
    }

    public function attributes(): array
    {
        return ['name'=>'Nama Lengkap','email'=>'Email','password'=>'Password'];
    }
}
