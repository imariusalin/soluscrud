<?php

namespace App\Observers;

use App\Models\User;
use App\Services\SolusService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    protected $solusService;

    public function __construct(SolusService $solusService)
    {
        $this->solusService = $solusService;
    }


    public function updated(User $user): void
    {
        Log::info('User updated obs: ' . $user->id);
        $this->solusService->updateUser((int)$user->solusvm_uid, [
            'status' => $user->status,
            'limit_group_id' => null,
            'language_id' => $user?->clients()->first()->language_id ?? 1,
            'roles' => [
                2
            ],
            'allowed_ips' => null,
        ]);
    }


    public function deleted(User $user): void
    {
        $this->solusService->deleteUser((int)$user->solusvm_uid);
    }


}
