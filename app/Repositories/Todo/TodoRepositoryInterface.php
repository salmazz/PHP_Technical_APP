<?php
namespace App\Repositories\Todo;

use App\Models\Todo;

interface TodoRepositoryInterface
{
    public function all();
    public function paginate($perPage);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateStatus(int $id, string $status): ?Todo;
}
