<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SolusService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.solusvm.api_url');
        $this->apiKey = config('services.solusvm.api_key');
    }

    public function getUsers()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/users", [
                'filter' => [
                    'role_id' => 2,
                ],
            ]);

            if ($response->failed()) {
                Log::error('Error fetching users: ' . $response->reason());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return null;
        }
    }

    public function createUser(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->post("{$this->apiUrl}/users", $data);

            $responseData = $response->json();

            if ($response->failed()) {
                Log::error('Error creating user: ' . $response->reason());
                return null;
            }
            return $responseData;
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return null;
        }
    }

    public function getUser(int $userId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/users/{$userId}");



            if ($response->failed()) {
                Log::error('Error fetching user: ' . $response->reason());
                return null;
            }

            $data  = $response->json();

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return null;
        }
    }

    public function updateUser(int $userId, array $data)
    {
        try {

            $data = $this->filterNullOrEmpty($data);
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

    public function createServer(array $data)
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
                'project' => $this->getUserDefaultProject($data['user'])['data'][0]['id'],
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


            if ($response->failed()) {
                Log::error('Error creating server: ' . $response->reason());
                return null;
            }

            return $response->json();
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


    public function getLanguages() {
        $languages = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->apiUrl}/languages");

        return $languages->json();
    }

    function filterNullOrEmpty(array $data): array {
        return array_filter($data, function($value) {
            return !is_null($value) && $value !== '';
        });
    }


    public function getAllServers()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/servers");

            if ($response->failed()) {
                Log::error('Error fetching servers: ' . $response->reason());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error fetching servers: ' . $e->getMessage());
            return null;
        }

    }

    public function listIpBlocks()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/ip_blocks");

            if ($response->failed()) {
                Log::error('Error fetching IP blocks: ' . $response->reason());
                return null;
            }

            $ipBlocks = $response->json()['data'];
            $availableIps = [];

            foreach ($ipBlocks as $block) {
                $reservedIpsResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])->get("{$this->apiUrl}/ip_blocks/{$block['id']}/ips");

                if ($reservedIpsResponse->failed()) {
                    Log::error('Error fetching reserved IPs: ' . $reservedIpsResponse->reason());
                    continue;
                }

                $reservedIps = array_column($reservedIpsResponse->json()['data'], 'ip');

                list($rangeStart, $rangeEnd) = explode(' - ', $block['total_ips_count']);
                $rangeStart = ip2long(trim($rangeStart));
                $rangeEnd = ip2long(trim($rangeEnd));

                for ($ip = $rangeStart; $ip <= $rangeEnd; $ip++) {
                    $ipStr = long2ip($ip);
                    if (!in_array($ipStr, $reservedIps)) {
                        $availableIps[] = $ipStr;
                    }
                }
            }

            return $availableIps;
        } catch (\Exception $e) {
            Log::error('Error fetching available IPs: ' . $e->getMessage());
            return null;
        }
    }


    public function getOsTemplates()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/os_images");

            if ($response->failed()) {
                Log::error('Error fetching OS: ' . $response->reason());
                return null;
            }
            return $response->json();

        } catch (\Exception $e) {
            Log::error('Error fetching OS: ' . $e->getMessage());
            return null;
        }
    }


    public function getUserDefaultProject(int $userId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/users/{$userId}/projects");

            if ($response->failed()) {
                Log::error('Error fetching user default project: ' . $response->reason());
                return null;
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Error fetching user default project: ' . $e->getMessage());
            return null;
        }
    }


    public function userExists($email)
    {
        $users = $this->getUsers();
        if ($users) {
            foreach ($users['data'] as $user) {
                if ($user['email'] === $email) {
                    return true;
                }
            }
        }
        return false;
    }



}
