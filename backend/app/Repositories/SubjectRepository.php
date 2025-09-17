<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectRepository
{
    public function index(): array
    {

        $user_id = auth()->user()->id;

        return Subject::where('user_id', $user_id)->orderBy('created_at', 'ASC')->get()->toArray();
    }

    public function show(string $id): array
    {

        $item = Subject::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Subject::where('id', $id)->get()->toArray();
    }
}
