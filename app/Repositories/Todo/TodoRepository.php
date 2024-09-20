<?php
namespace App\Repositories\Todo;

use App\Models\Todo;
use App\Repositories\Todo\TodoRepositoryInterface;

class TodoRepository implements TodoRepositoryInterface
{
    public function all($filters = null, $perPage = 10)
    {
        $query = Todo::query();

        if (isset($filters['title']) && $filters['title']) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['created_at']) && $filters['created_at']) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        return $query->paginate($perPage);
    }

    public function paginate($perPage)
    {
        return Todo::paginate($perPage);
    }

    public function find($id)
    {
        return Todo::findOrFail($id);
    }

    public function create(array $data)
    {
        return Todo::create($data);
    }

    public function update($id, array $data)
    {
        $todo = Todo::findOrFail($id);
        $todo->update($data);
        return $todo;
    }

    public function delete($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
    }

    public function updateStatus(int $id, string $status): ?Todo
    {
        $todo = $this->find($id);
        if ($todo) {
            $todo->status = $status;
            $todo->save();
            return $todo;
        }
        return null;
    }
}
