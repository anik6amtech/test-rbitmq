<?php

namespace App\Contracts;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    protected BaseModel $model;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;

    }

    public function add(array $data): string|object
    {
        return $this->model->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->model->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, ?int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->model->with($relations)
            ->when(! empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], ?string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, ?int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('name', 'like', "%$searchValue%")
                    ->orWhere('phone', 'like', "%$searchValue%")
                    ->orWhere('email', 'like', "%$searchValue%");
            })
            ->when($filters['admin_role_id'] && $filters['admin_role_id'] != 'all', function ($query) use ($filters) {
                $query->where('admin_role_id', $filters['admin_role_id']);
            });

        $filters += ['searchValue' => $searchValue];

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(array $params): bool
    {
        $this->model->where($params)->delete();

        return true;
    }
}
