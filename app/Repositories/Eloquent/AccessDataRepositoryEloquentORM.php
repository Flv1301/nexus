<?php

namespace App\Repositories\Eloquent;

use App\Dto\Contract\AccessDataDtoInterface;
use App\Models\AccessData;
use Illuminate\Support\Collection;

class AccessDataRepositoryEloquentORM
{
    /**
     * @param AccessData $accessData
     */
    public function __construct(protected AccessData $accessData)
    {
    }

    /**
     * @param AccessDataDtoInterface $dto
     * @return void
     */
    public function create(AccessDataDtoInterface $dto): void
    {
        $this->accessData->create($dto->toArray());
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function find(int $id): Collection
    {
       return $this->accessData->where('user_id', $id)->orderBy('created_at', 'desc')->limit(5)->get();
    }
}
