<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryContract;
use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAuthors(): Collection
    {
        return $this->model->has('news')->select('name', 'id')->get();
    }

    public function getByRefreshToken(string $token): ?\App\Models\User
    {
        return $this->model->where('refresh_token', $token)->first();
    }

    public function getByEmail(string $email): ?\App\Models\User
    {
        return $this->model->where('email', $email)->first();
    }

}
