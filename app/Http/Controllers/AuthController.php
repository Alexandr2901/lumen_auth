<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RefreshRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\UserResource;
use Exception;
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
        $data['refresh_token'] = Str::random(64);
        $user = $this->userRepository->create($data);

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'refresh_token' => $data['refresh_token'],
                'remember_token' => $this->sendToken($user->id),
            ]
        ], 201);
    }

    private function sendToken(int $id)
    {
        $remember_token = Str::random(60);

        $postdata = http_build_query(
            array(
                'id' => $id,
                'remember_token' => $remember_token
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);

//        $result = file_get_contents('http://lumen-main:8000/api/auth/set-token',
            $result = file_get_contents('http://api-gateway:8080/api/auth/set-token',
                false, $context);
            if (json_decode($result, true) !== $id) {
                throw new Exception('error');

            }
        return $remember_token;
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userRepository->getByEmail($request->input('email'));
        if ($user === null) {
            return response()->json(['error' => true, 'message' => "user not found!"], 401);
        }
        if (Hash::check($request->input('password'), $user->password)) {
            $user->refresh_token = Str::random(64);
            $user->save();
            return response()->json([
                'data' => [
                    'success' => true,
                    'refresh_token' => $user->refresh_token,
                    'remember_token' => $this->sendToken($user->id),
                ]
            ]);
        }
        return response()->json(['error' => true, 'message' => "Invalid Credential"], 401);
    }

    public function loginRefreshToken(RefreshRequest $request)
    {
        $user = $this->userRepository->getByRefreshToken($request->input('refresh_token'));
        $user->refresh_token = Str::random(64);
        $user->save();
//        $this->sendToken($user->id);

        return response()->json([
            'data' => [
                'success' => true,
                'refresh_token' => $user->refresh_token,
                'remember_token' => $this->sendToken($user->id),
            ]
        ]);
    }

//    public function logOut(Request $request): bool
//    {
//        $request->user()->refresh_token = null;
//        return $request->user()->save();
//    }
}
