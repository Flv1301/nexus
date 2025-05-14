<?php

namespace App\Services\Api;

use App\Helpers\LogHelper;
use App\Repositories\Eloquent\Contracts\CadCivilProdepaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CadCivilProdepaService
{
    /**
     * @param CadCivilProdepaRepositoryInterface $repository
     */
    public function __construct(protected CadCivilProdepaRepositoryInterface $repository)
    {
    }

    /**
     * @param array|null $filter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $filter = null, int $perPage = 100): LengthAwarePaginator
    {
        LogHelper::logActivity(
            'search',
            'Pesquisa API Cadastro Civil',
            "Parametros: " . http_build_query($filter, '', ', ')
        );

        return $this->repository->paginate($filter, $perPage);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): object|null
    {
        $result = $this->repository->find($id);
        LogHelper::logActivity('search', 'Pesquisa API Cadastro Civil', "Codigo Pesquisado: {$id}", $result);

        return $result;
    }
}
