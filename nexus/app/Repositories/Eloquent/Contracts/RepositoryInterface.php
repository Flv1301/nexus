<?php

namespace App\Repositories\Eloquent\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return Collection
     */
    public function findAll(array $columns = ['*']): Collection;

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object;

    /**
     * @param array|null $filter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?array $filter, int $perPage): LengthAwarePaginator;

}
