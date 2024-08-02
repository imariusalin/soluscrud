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
            'primary_ip' => [
                'required',
                'ip',
                function ($attribute, $value, $fail) {
                    $allowedRange = '10.10.10.';
                    $lastOctet = (int) substr($value, strrpos($value, '.') + 1);
                    if (strpos($value, $allowedRange) !== 0 || $lastOctet < 2 || $lastOctet > 253) {
                        $fail($attribute . ' is not within the allowed range (10.10.10.2 to 10.10.10.253).');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (Servers::where('primary_ip', $value)->exists()) {
                        $fail($attribute . ' must be unique. This IP address is already in use.');
                    }
                }
            ],
        ];
    }

}
