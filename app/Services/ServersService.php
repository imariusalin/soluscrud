<?php

namespace App\Services;

use App\Models\Servers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ServersService
{
    protected $solusService;

    public function __construct(SolusService $solusService)
    {
        $this->solusService = $solusService;
    }
    public function getAllServers()
    {
        try {
            $servers =  Servers::query()
                ->with('user')
                ->get();
            return Collection::make($servers);
        } catch (\Throwable $e) {
            Log::error('Error fetching servers: ' . $e->getMessage());
            return collect();
        }
    }

    public function createServer($data)
    {
        try {
            $server = Servers::create($data);
            $this->solusService->createServer($data, $server->fresh());
        } catch (\Throwable $e) {
            Log::error('Error creating server: ' . $e->getMessage());
            return null;
        }
    }

    public function updateServer(Request $request, Servers $server)
    {
        try {
            $server->update($request->all());
            return $server;
        } catch (\Throwable $e) {
            Log::error('Error updating server: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteServer(Servers $server): bool
    {
        try {
            $server->delete();
            $this->solusService->deleteServer($server->solus_server_id);
            return true;
        } catch (\Throwable $e) {
            Log::error('Error deleting server: ' . $e->getMessage());
            return false;
        }
    }
}
