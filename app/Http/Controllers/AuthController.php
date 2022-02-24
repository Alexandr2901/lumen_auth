<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['remember_token'] = hash('sha256', Str::random(60));
        $user = $this->userRepository->create($data);

        return response()->json(['data' => [
            'user' => new UserResource($user),
            'token' => $data['remember_token'],
        ]
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user === null) {
            return response()->json(['error' => true, 'message' => "user not found!"], 401);
        }

        if (Hash::check($request->input('password'), $user->password)) {

            $user->remember_token = hash('sha256', Str::random(60));

            $user->save();

            return response()->json(['data' => [
                'success' => true, 'token' => $user->remember_token]], 200);

        }
        return response()->json(['error' => true, 'message' => "Invalid Credential"], 401);

    }

    public function logOut(Request $request): bool
    {
        $request->user()->remember_token = null;
        return $request->user()->save();
    }
}
