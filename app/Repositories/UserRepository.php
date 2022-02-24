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
}
