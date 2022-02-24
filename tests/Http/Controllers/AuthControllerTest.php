<?php

namespace Http\Controllers;

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testLoginSuccess()
    {
        $email = 'liahsldf1';
        User::factory()->create(['email' => $email,
            'password' => 'password']);
        $this->post('/api/auth/login', [
            'email' => $email,
            'password' => 'password',
        ]);
        $this->seeJsonStructure([
            'data' => [
                'success',
                'token',
            ]]);

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
            'email' => 'example@mail.com',
            'password' => 'password',
        ]);

        $this->seeJsonStructure([
            'data' => ['user' => [
                'name',
                'created_at',
                'updated_at',
                'id',
            ], 'token'
            ]]);
    }
}
