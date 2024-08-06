<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\SolusService;

class ClientCreateRequest extends FormRequest
{
    protected $solusService;

    public function __construct(SolusService $solusService)
    {
        parent::__construct();
        $this->solusService = $solusService;
    }
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->solusService->userExists($value)) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
            'password' => 'required|min:8',
            'language' => 'required',
            'status' => 'required|in:active,inactive',
        ];
    }

}
