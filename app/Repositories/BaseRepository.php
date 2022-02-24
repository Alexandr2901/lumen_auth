<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseRepositoryContract
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $fields): bool
    {
        return $this->find($id)->update($fields);
    }

    public function find(string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function destroy(string $id): bool
    {
        return $this->find($id)->delete();
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate($page = null,
                             $count = 15
    ): Paginator
    {
        return $this->model->simplePaginate($count, ['*'], 'page', $page);
    }
}
