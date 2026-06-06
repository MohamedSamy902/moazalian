<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function getModel(): Model;
    public function find(int $id, array $relations = []): ?Model;
    public function all(array $relations = []): Collection;
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator;
    public function create(array $data): Model;
    public function update(Model $model, array $data): bool;
    public function delete(Model $model): bool;
}
