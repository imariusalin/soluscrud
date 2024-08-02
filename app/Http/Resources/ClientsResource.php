<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'user_type' => $this->user_type,
            'status' => $this->status,
            'billing_token' => $this->billing_token,
            'billing_user_id' => $this->billing_user_id,
            'language_id' => $this->language_id,
            'roles' => $this->roles,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
