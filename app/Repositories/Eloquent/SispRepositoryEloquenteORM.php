<?php

namespace App\Repositories\Eloquent;

use App\Models\Sisp\Bop;
use App\Models\Sisp\BopEnv;
use App\Repositories\Eloquent\Contracts\SispRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SispRepositoryEloquenteORM implements SispRepositoryInterface
{

    /**
     * @param Bop $sisp
     */
    public function __construct(protected Bop $sisp)
    {
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function findAll(array $columns = ['*']): Collection
    {
        return $this->sisp->all($columns);
    }

    /**
     * @param array|null $filter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?array $filter = null, int $perPage = 15): LengthAwarePaginator
    {
        return BopEnv::when(isset($filter['nome']), function ($query) use ($filter) {
            return $query->where('nm_envolvido', 'like', '%' . $filter['nome'] . '%');
        })
            ->when(isset($filter['cpf']), function ($query) use ($filter) {
                return $query->where('cpf', 'like', '%' . $filter['cpf'] . '%');
            })
            ->when(isset($filter['pai']), function ($query) use ($filter) {
                return $query->where('pai', 'like', '%' . $filter['pai'] . '%');
            })
            ->when(isset($filter['mae']), function ($query) use ($filter) {
                return $query->where('mae', 'like', '%' . $filter['mae'] . '%');
            })
            ->when(isset($filter['data_nascimento']), function ($query) use ($filter) {
                return $query->where('nascimento', $filter['data_nascimento']);
            })
            ->orderBy('nm_envolvido')
            ->distinct()
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        return $this->sisp->with(['bopenv', 'boprel'])->find($id);
    }
}
