<?php
namespace App\Services;

use App\Http\Requests\ClientCreateRequest;
use App\Http\Resources\ClientsResource;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\SolusService;

class ClientsService
{
    protected $solusService;

    public function __construct(SolusService $solusService)
    {
        $this->solusService = $solusService;
    }

    public function getAllClients()
    {
        $query = User::with('clients')
            ->where('user_type', 'client')
            ->paginate(20);

        return ClientsResource::collection($query);
    }

    public function create($request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'client',
                'status' =>  $request->status,
            ]);

            Clients::create([
                'user_id' => $user->id,
                'billing_token' => null,
                'billing_user_id' => null,
                'language_id' => $request->language,
                'roles' => 2,
                'status' => $request->status,
            ]);

            $a = $this->solusService->createUser([
                'email' => $request->email,
                'status' => $request->status,
                'language_id' => (int)$request->language,
                'password' => $request->password,
                'limit_group_id' => $request?->limit_group_id,
                'roles' => [
                    2
                ],
                'billing_user_id' => $request?->billing_user_id,
                'billing_token' => $request?->billing_token,
                'allowed_ips' => $request?->allowed_ips,
            ],$user);
        } catch (\Throwable $e) {
            Log::error('Error creating user and client: ' . $e->getMessage());
        }
    }

    public function store(ClientCreateRequest $request)
    {
    }

    public function show(Clients $clients)
    {
    }

    public function getClientsById(User $user)
    {
        return collect(User::where('id', $user->id)->with('clients')->first());
    }

    public function updateClients($data, User $user)
    {
        try {

            $filteredData = array_filter($data, function ($value) {
                return !is_null($value) && $value !== '';
            });


            $allowedKeys = ['name', 'email', 'password', 'status'];
            $filteredDataUser = array_intersect_key($filteredData, array_flip($allowedKeys));

            $user->update($filteredDataUser);

            $clients = Clients::where('user_id', $user->id)->first();
            $clients->update([
                'language_id' => $filteredData['language'],
                'status' => $filteredData['status'],
            ]);

        } catch (\Throwable $e) {
            Log::error('Error updating user and client: ' . $e->getMessage());
        }
    }


    public function delete(User $user)
    {
        try {
            Clients::where('user_id', $user->id)->delete();
            $user->delete();
        } catch (\Throwable $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
        }
    }
}
