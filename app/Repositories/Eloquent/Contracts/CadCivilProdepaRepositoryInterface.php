<?php

namespace App\Repositories\Eloquent\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface CadCivilProdepaRepositoryInterface
{
    public function paginate(?array $filter, int $perPage): LengthAwarePaginator;
}
