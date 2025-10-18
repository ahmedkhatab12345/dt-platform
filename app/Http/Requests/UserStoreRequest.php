<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255', 'unique:users'],
            'phone'    => ['nullable','string','max:30'],
            'status' => ['nullable', 'in:active,inactive'],
            'password' => ['required','string','min:8'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Email is required.',
            'status.in' => 'The status must be either active or inactive.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
