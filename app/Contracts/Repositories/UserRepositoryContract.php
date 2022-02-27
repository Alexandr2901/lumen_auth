<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface UserRepositoryContract extends BaseRepositoryContract
{
    public function getAuthors(): Collection;

    public function getByRefreshToken(string $token): ?\App\Models\User;

    public function getByEmail(string $email): ?\App\Models\User;
}
