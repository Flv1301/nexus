<?php

namespace App\Repositories\Eloquent;

use App\Models\CadCivilProdepa;
use App\Repositories\Eloquent\Contracts\CadCivilProdepaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CadCivilProdepaRepositoryEloquenteORM implements CadCivilProdepaRepositoryInterface
{
    protected CadCivilProdepa $cadCivil;

    public function __construct(CadCivilProdepa $cadCivil)
    {
        $this->cadCivil = $cadCivil;
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function findAll(array $columns = ['*']): Collection
    {
        return $this->cadCivil->all($columns);
    }

    /**
     * @param array|null $filter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?array $filter, int $perPage = 15): LengthAwarePaginator
    {
        return $this->cadCivil->when(isset($filter['nome']), function ($query) use ($filter) {
            return $query->where('nome_completo', 'like', '%' . $filter['nome'] . '%');
        })
            ->when(isset($filter['data_nascimento']), function ($query) use ($filter) {
                return $query->where('data_nascimento', '=', $filter['data_nascimento']);
            })
            ->when(isset($filter['cpf']), function ($query) use ($filter) {
                return $query->where('cpf', 'like', '%' . $filter['cpf'] . '%');
            })
            ->when(isset($filter['rg']), function ($query) use ($filter) {
                return $query->where('rg_geral_numerico', 'like', '%' . $filter['rg'] . '%');
            })
            ->when(isset($filter['mae']), function ($query) use ($filter) {
                return $query->where('mae', 'like', '%' . $filter['mae'] . '%');
            })
            ->when(isset($filter['pai']), function ($query) use ($filter) {
                return $query->where('pai', 'like', '%' . $filter['pai'] . '%');
            })
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return CadCivilProdepa|null
     */
    public function find(int $id): ?CadCivilProdepa
    {
        return $this->cadCivil->find($id);
    }
}
