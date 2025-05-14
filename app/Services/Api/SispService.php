<?php

namespace App\Services\Api;

use App\Helpers\LogHelper;
use App\Repositories\Eloquent\Contracts\SispRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SispService
{
    /**
     * @param SispRepositoryInterface $repository
     */
    public function __construct(protected SispRepositoryInterface $repository)
    {
    }


    /**
     * @param array|null $filter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?array $filter, int $perPage = 100): LengthAwarePaginator
    {
        LogHelper::logActivity(
            'search',
            'Pesquisa API SISP',
            "Parametros: " . http_build_query($filter, '', ', ')
        );

        return $this->repository->paginate($filter, $perPage);
    }

    /**
     * @param int $id
     * @return object
     */
    public function find(int $id): object
    {
        $result = $this->repository->find($id);
        LogHelper::logActivity('search', 'Pesquisa API SISP', "Codigo Pesquisado: {$id}", $result);
        return $result;
    }
}
