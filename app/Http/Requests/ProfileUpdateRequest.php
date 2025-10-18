<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'  => ['required','string','max:255'],
            'email' => ['required','email', Rule::unique('users','email')->ignore($this->user()->id)],
            'phone' => ['nullable','string','max:30'],
            'current_password' => ['nullable','current_password'],
            'password' => ['nullable','string','min:8','confirmed'],
        ];
    }
}
