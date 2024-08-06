<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServerCreateRequest;
use App\Services\SolusService;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    protected $solusService;
    public function __construct(SolusService $solusService)
    {
        $this->solusService = $solusService;
    }
    public function index()
    {
        try {
            $response = $this->solusService->getAllServers();
            $servers = $response['data'];
            return view('servers.index', compact('servers'));
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to fetch servers.']);
        }
    }


    public function create()
    {
        $ipBlocks = $this->solusService->listIpBlocks();
        $users = $this->solusService->getUsers();
        $osTemplates = $this->solusService->getOsTemplates();
        return view('servers.create', [
            'ipBlocks' => $ipBlocks,
            'solusUsers' => $users,
            'osTemplates' => $osTemplates,
        ]);
    }


    public function store(ServerCreateRequest $request)
    {
        try {
            $data = $request->validated();

            $server = $this->solusService->createServer($data);
            return redirect()->route('servers.index')->with('success', 'Server created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create server.']);
        }
    }


    public function destroy($id)
    {
        $serverDelete = $this->solusService->deleteServer($id);
        if ($serverDelete) {
            return redirect()->route('servers.index')->with('success', 'Server deleted successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to delete server.']);
        }
    }
}
