<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Servers;

class ServerCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'regex:/^[A-Za-z0-9][A-Za-z0-9-]*[A-Za-z0-9]$/'
            ],
            'password' => 'required|min:8',
            'os' => 'required',
            'user' => 'required',
            'primary_ip' => 'required',
        ];
    }

}
