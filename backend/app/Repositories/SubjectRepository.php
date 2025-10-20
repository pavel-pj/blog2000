<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class SubjectRepository
{
    public function index(): EloquentCollection
    {

        $user_id = auth()->user()->id;

        return Subject::where('user_id', $user_id)->orderBy('created_at', 'ASC')->get() ;
    }

    public function show(string $id): Subject
    {

        $item = Subject::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Subject::with('options')->find($id);
    }
}
