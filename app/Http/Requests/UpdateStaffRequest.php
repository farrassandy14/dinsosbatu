<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = optional($this->route('user'))->id;

        return [
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150', Rule::unique('users','email')->ignore($id)],
            'password' => ['nullable','string','min:8'],
        ];
    }

    public function attributes(): array
    {
        return ['name'=>'Nama Lengkap','email'=>'Email','password'=>'Password'];
    }
}
