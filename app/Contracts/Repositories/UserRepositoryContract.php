<?php

namespace App\Contracts\Repositories;

use Illuminate\Support\Collection;

interface UserRepositoryContract extends BaseRepositoryContract
{
    public function getAuthors(): Collection;
}
