<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection as  EloquentCollection;

class TaskService
{
    public function update(array $validated, string $id)
    {
        Task::updateOrInsert(
            ['id' => $id],
            $validated
        );
        return Task::where('id', $id)->get();
    }
 
}