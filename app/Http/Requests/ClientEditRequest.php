<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientEditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'language' => 'nullable',
            'status' => 'required|in:active,locked,suspended',
        ];
    }
}
