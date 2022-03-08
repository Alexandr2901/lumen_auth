<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testLoginSuccess()
    {
        $email = 'liahsldf2';
        User::factory()->create([
            'email' => $email,
            'password' => 'password'
        ]);
        $this->post('/api/auth/login', [
            'email' => $email,
            'password' => 'password',
        ]);
        $this->seeJsonStructure([
            'data' => [
                'success',
                'refresh_token',
                'remember_token',
            ]
        ]);
    }

    public function testRefreshSuccess()
    {
        $user = User::factory()->create();
        $user->refresh_token = Str::random(64);
        $user->save();

        $this->post('/api/auth/refresh', [
            'refresh_token' => $user->refresh_token,
        ]);
        $this->seeJsonStructure([
            'data' => [
                'success',
                'refresh_token',
                'remember_token',
            ]
        ]);
    }

    public function testLogOutSuccess()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/api/auth/logout');
        $this->assertTrue((bool)$this->response->content());
    }

    public function testStoreSuccess()
    {
        $this->post('/api/auth', [
            'name' => 'required|string',
            'email' => 'example32@mail.com',
            'password' => 'password',
        ]);

        $this->seeJsonStructure([
            'data' => [
                'user' => [
                    'name',
                    'created_at',
                    'updated_at',
                    'id',
                ],
                'remember_token',
                'refresh_token'
            ]
        ]);
    }
}
