<?php

namespace App\Repositories;

use App\Contracts\Repositories\NewsRepositoryContract;
use App\Models\News;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class NewsRepository extends BaseRepository implements NewsRepositoryContract
{
    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function paginateAndFilter(int $page = null, int $count = 15, array $users = null, array $tags = null): Paginator
    {
        return Cache::tags(['news', 'tags'])->remember(
            'paginateAndFilter/' . json_encode(func_get_args()),
            3600,
            function () use ($count, $page, $users, $tags) {
                $query = $this->model->query()->with(['tags', 'users']);
                if ($users) {
                    $query->whereHas('users', function ($query) use ($users) {
                        $query->whereIn('id', $users);
                    });
                }
                if ($tags) {
                    $query->whereHas('tags', function ($query) use ($tags) {
                        $query->whereIn('name', $tags);
                    });
                }
                return $query
//                    ->orderBy('created_at')
                    ->simplePaginate($count, ['*'], 'page', $page);
            });
    }
}
