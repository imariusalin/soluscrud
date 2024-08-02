<?php
namespace App\Http\Controllers;

use App\Http\Requests\ClientEditRequest;
use App\Models\Clients;
use App\Http\Requests\ClientCreateRequest;
use App\Models\User;
use App\Services\clientsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\Concerns\Has;

class ClientsController extends Controller
{
    protected $clientsService;

    public function __construct(clientsService $clientsService)
    {
        $this->clientsService = $clientsService;
    }

    public function index()
    {
        try {
            $clients = $this->clientsService->getAllClients();
            return view('clients.index', compact('clients'));
        } catch (\Throwable $e) {
            Log::error('Error fetching Clients: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error fetching Clients.');
        }
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientCreateRequest $request)
    {
        try {
            $this->clientsService->create($request);
            return redirect()->route('clients.index')->with('success', 'Clients created successfully.');
        } catch (\Throwable $e) {
            Log::error('Error creating Clients: ' . $e->getMessage());
            return redirect()->route('clients.create')->withErrors('Error creating Clients.');
        }
    }

    public function show(Clients $clients)
    {
        try {
            return view('clients.show', compact('clients'));
        } catch (\Throwable $e) {
            Log::error('Error fetching Clients: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error fetching Clients.');
        }
    }

    public function edit(User $user)
    {
        try {
            $clientData = $this->clientsService->getClientsById($user);
            return view('clients.edit', compact('clientData'));
        } catch (\Throwable $e) {
            Log::error('Error fetching Clients for edit: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error fetching Clients for edit.');
        }
    }

    public function update(ClientEditRequest $request, User $user)
    {
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
                'language' => $request->language,
                'password' => $request->password ? Hash::make($request->password) : null,
            ];
            $this->clientsService->updateClients($data, $user);
            return redirect()->route('clients.index')->with('success', 'Clients updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Error updating Clients: ' . $e->getMessage());
            return redirect()->route('clients.edit', $user->id)->withErrors('Error updating Clients.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->clientsService->delete($user);
            return redirect()->route('clients.index')->with('success', 'Clients deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Error deleting Clients: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error deleting Clients.');
        }
    }
}
