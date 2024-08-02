<?php

namespace App\Services;

use App\Models\Servers;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SolusService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.solusvm.api_url');
        $this->apiKey = config('services.solusvm.api_key');
    }

    public function createUser(array $data,User $user)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->post("{$this->apiUrl}/users", $data);

            if ($response->failed()) {
                Log::error('Error creating user: ' . $response->reason());
                return null;
            }

            $id = json_decode($response->body())->data->id;
            $user->update(['solusvm_uid' => $id]);


            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return null;
        }
    }

    public function updateUser(int $userId, array $data)
    {
        try {
            Log::info('User updated solusservice: ' . $userId);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->put("{$this->apiUrl}/users/{$userId}", $data);

            if ($response->failed()) {
                Log::error('Error updating user: ' . $response->reason());
                return null;
            }
        } catch (\Throwable $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return null;
        }
    }


    public function deleteUser(int $userId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->delete("{$this->apiUrl}/users/{$userId}");

            if ($response->failed()) {
                Log::error('Error deleting user: ' . $response->reason());
                return null;
            }
        } catch (\Throwable $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return null;
        }
    }

    public function createServer(array $data,Servers $servers)
    {
        try {
            $payload = [
                'name' => $data['name'],
                'location' => 1,
                'description' => '1 vCPU, 1024 MiB RAM, 15 GiB Disk',
                'plan' => 1,
                'ssh_keys' => [],
                'password' => $data['password'],
                'user' => $data['user'],
                'project' => 14,
                'backup_settings' => [
                    'enabled' => false,
                    'schedule' => [
                        'type' => 'daily',
                        'time' => [
                            'hour' => 0,
                            'minutes' => 0
                        ],
                        'days' => []
                    ]
                ],
                'ip_types' => [
                    'IPv4'
                ],
                'additional_ip_count' => 0,
                'additional_ipv6_count' => 0,
                'additional_disks' => [],
                'os' => $data['os'],
                'primary_ip' => $data['primary_ip'],
                'compute_resource' => 1
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->post("{$this->apiUrl}/servers", $payload);

            $id = json_decode($response->body())->data->id;

            Log::info('Server created solusservice: ' . $id);


            //update solsus_server_id in servers table
            $servers->update(['solus_server_id' => $id]);

            Log::info('Server created solusservice: ' . $servers->fresh());





        } catch (\Throwable $e) {
            Log::error('Error creating server: ' . $e->getMessage());
            return null;
        }
    }

    public function editServer(int $serverId, array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->put("{$this->apiUrl}/servers/{$serverId}", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error editing server: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteServer(int $serverId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->delete("{$this->apiUrl}/servers/{$serverId}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error deleting server: ' . $e->getMessage());
            return null;
        }
    }
}
