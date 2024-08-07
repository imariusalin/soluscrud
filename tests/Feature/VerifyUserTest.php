<?php

namespace Tests\Feature;

use App\Services\SolusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SolusServiceTest extends TestCase
{
    use RefreshDatabase;

    private $solusService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solusService = new SolusService();
    }

    public function test_get_users()
    {
        Http::fake([
            '*' => Http::response(['data' => [['email' => 'test@example.com']]], 200)
        ]);

        $users = $this->solusService->getUsers();

        $this->assertNotNull($users);
        $this->assertEquals('test@example.com', $users['data'][0]['email']);
    }

    public function test_create_user()
    {
        $userData = ['email' => 'test@example.com', 'password' => 'password'];

        Http::fake([
            '*' => Http::response($userData, 201)
        ]);

        $response = $this->solusService->createUser($userData);

        $this->assertNotNull($response);
        $this->assertEquals('test@example.com', $response['email']);
    }

    public function test_get_user()
    {
        $userId = 1;

        Http::fake([
            '*' => Http::response(['email' => 'test@example.com'], 200)
        ]);

        $user = $this->solusService->getUser($userId);

        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user['email']);
    }

    public function test_update_user()
    {
        $userId = 1;
        $userData = ['email' => 'updated@example.com'];

        Http::fake([
            '*' => Http::response(null, 200)
        ]);

        $response = $this->solusService->updateUser($userId, $userData);

        $this->assertNull($response);
    }

    public function test_delete_user()
    {
        $userId = 1;

        Http::fake([
            '*' => Http::response(null, 200)
        ]);

        $response = $this->solusService->deleteUser($userId);

        $this->assertNull($response);
    }

    public function test_user_exists()
    {
        Http::fake([
            '*' => Http::response(['data' => [['email' => 'test@example.com']]], 200)
        ]);

        $exists = $this->solusService->userExists('test@example.com');

        $this->assertTrue($exists);
    }

}
