<?php

namespace App\Services;

use App\Contracts\Repositories\TagRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Contracts\Services\FiltersServiceContract;
use Illuminate\Support\Facades\Cache;

class FiltersService implements FiltersServiceContract
{

    public function __construct(
        TagRepositoryContract  $tagRepository,
        UserRepositoryContract $userRepository
    )
    {
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;

    }

    public function get(): ?array
    {
        return Cache::tags(['news', 'tags'])->remember(
            'FiltersService->get',
            3600,
            function () {
                return [
                    'users' => $this->userRepository->getAuthors(),
                    'tags' => $this->tagRepository->all()->pluck('name')
                ];
            });
    }
}
