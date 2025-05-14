<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Eloquent\Contracts\UserRepositoryInterface;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepositoryEloquenteORM implements UserRepositoryInterface
{
    public function __construct(protected User $user)
    {
    }


    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        return $this->user->find($id);
    }


    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    /**
     * @throws NotFound
     */
    public function findAll(array $columns = ['*']): Collection
    {
        throw new NotFound('Metódo não encontrado.');
    }

    /**
     * @throws NotFound
     */
    public function paginate(?array $filter, int $perPage): LengthAwarePaginator
    {
        throw new NotFound('Metódo não encontrado.');
    }
}
