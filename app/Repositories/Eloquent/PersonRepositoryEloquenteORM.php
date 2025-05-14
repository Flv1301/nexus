<?php

namespace App\Repositories\Eloquent;

use App\Models\Person\Person;
use App\Repositories\Eloquent\Contracts\PersonRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PersonRepositoryEloquenteORM implements PersonRepositoryInterface
{

    /**
     * @param Person $person
     */
    public function __construct(protected Person $person)
    {
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function findAll(array $columns = ['*']): Collection
    {
        return $this->person->all($columns);
    }

    /**
     * @param array|null $filter
     * @param array $columns
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?array $filter, array $columns, int $perPage = 15): LengthAwarePaginator
    {
        return Person::when(isset($filter['name']), function ($query) use ($filter) {
            $name = $filter['name'];
            return $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('nickname', 'like', '%' . $name . '%');
        })
            ->when(isset($filter['document']), function ($query) use ($filter) {
                $document = $filter['document'];
                return $query->where('cpf', 'like', '%' . $document . '%')
                    ->orWhere('rg', 'like', '%' . $document . '%')
                    ->orWhere('cnh', 'like', '%' . $document . '%')
                    ->orWhere('passport', 'like', '%' . $document . '%')
                    ->orWhere('voter_registration', 'like', '%' . $document . '%');
            })
            ->when(isset($filter['father']), function ($query) use ($filter) {
                return $query->where('father', 'like', '%' . $filter['father'] . '%');
            })
            ->when(isset($filter['mother']), function ($query) use ($filter) {
                return $query->where('mother', 'like', '%' . $filter['mother'] . '%');
            })
            ->when(isset($filter['birthDate']), function ($query) use ($filter) {
                $birthDate = Carbon::createFromFormat('d/m/Y', $filter['birthDate'])->toDateString();
                return $query->where('birth_date', $birthDate);
            })
            ->when(isset($filter['tattoo']), function ($query) use ($filter) {
                return $query->where('tattoo', 'like', '%' . $filter['tattoo'] . '%');
            })
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        return $this->person->with([])->find($id);
    }
}
