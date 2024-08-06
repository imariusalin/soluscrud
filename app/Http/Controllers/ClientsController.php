<?php
namespace App\Http\Controllers;

use App\Http\Requests\ClientEditRequest;
use App\Models\Clients;
use App\Http\Requests\ClientCreateRequest;
use App\Models\User;
use App\Services\SolusService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;
use Throwable;

class ClientsController extends Controller
{
    protected $solusService;

    public function __construct(SolusService $solusService)
    {
        $this->solusService = $solusService;
    }

    public function index()
    {
        try {
            $response = $this->solusService->getUsers();
            $clients = array_map(function ($client) {
                return [
                    'id' => $client['id'],
                    'email' => $client['email'],
                    'status' => $client['status'],
                    'language' => $client['language']['name'] ?? 'N/A',
                    'servers' => $client['limit_usage']['servers'],
                ];
            }, $response['data']);
            return view('clients.index', compact('clients'));
        } catch (Throwable $e) {
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
            $request->validated();

            $this->solusService->createUser([
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
            ]);
            return redirect()->route('clients.index')->with('success', 'Clients created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create client.']);
        }
    }

    public function show(Clients $clients)
    {
        try {
            return view('clients.show', compact('clients'));
        } catch (Throwable $e) {
            Log::error('Error fetching Clients: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error fetching Clients.');
        }
    }

    public function edit($id)
    {
        try {

            $clientData = $this->solusService->getUser($id);
            $languages = $this->solusService->getLanguages();
            return view('clients.edit', [
                'client' => $clientData,
                'languages' => $languages,
            ]);
        } catch (Throwable $e) {
            Log::error('Error fetching Clients for edit: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error fetching Clients for edit.');
        }
    }

    public function update(ClientEditRequest $request, $id)
    {
        try {
            $data = [
                'email' => $request?->email,
                'status' => $request?->status,
                'language' => $request?->language,
                'password' => $request?->password,
            ];
            $this->solusService->updateUser($id, $data);
            return redirect()->route('clients.index')->with('success', 'Clients updated successfully.');
        } catch (Throwable $e) {
            Log::error('Error updating Clients: ' . $e->getMessage());
            return redirect()->route('clients.edit', $user->id)->withErrors('Error updating Clients.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->solusService->deleteUser($id);
            return redirect()->route('clients.index')->with('success', 'Clients deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Error deleting Clients: ' . $e->getMessage());
            return redirect()->route('clients.index')->withErrors('Error deleting Clients.');
        }
    }
}
