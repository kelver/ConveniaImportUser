<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterValidate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->id ?? '';

        return [
            'name' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'min:5', 'max:255', "unique:users,email,{$id},id"],
            'password' => ['required', 'min:8']
        ];
    }
}
