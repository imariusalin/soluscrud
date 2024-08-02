<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServerCreateRequest;
use App\Models\Servers;
use App\Services\ServersService;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    protected $serversService;
    public function __construct(ServersService $serversService)
    {
        $this->serversService = $serversService;
    }

    public function index()
    {
        $servers = $this->serversService->getAllServers();
        return view('servers.index', compact('servers'));
    }


    public function create()
    {
        return view('servers.create');
    }


    public function store(ServerCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $server = $this->serversService->createServer($data);
            return redirect()->route('servers.index')->with('success', 'Server created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create server.']);
        }
    }



    public function show(Servers $servers)
    {
        //
    }


    public function edit(Servers $servers)
    {
        $servers = $this->serversService->getServer($servers);
        return view('servers.edit', compact('servers'));
    }


    public function update(Request $request, Servers $servers)
    {
        $server = $this->serversService->updateServer($request, $servers);
    }

    public function destroy(Servers $server)
    {
        $serverDelete = $this->serversService->deleteServer($server);
        if ($serverDelete) {
            return redirect()->route('servers.index')->with('success', 'Server deleted successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to delete server.']);
        }
    }
}
