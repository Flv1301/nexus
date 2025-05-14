<?php

namespace App\Services\Api;

use App\Models\User;
use App\Repositories\Eloquent\Contracts\UserRepositoryInterface;


class UserService
{
    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(protected UserRepositoryInterface $repository)
    {
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function find(string $id): ?object
    {
        return $this->repository->find($id);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->repository->findByEmail($email);
    }
}
