<?php

namespace App\Repositories;

use App\Models\Topic;
use Illuminate\Support\Facades\DB;

class TopicRepository
{
    public function index(string $subjectId): Array
    {
        return Topic::where('subject_id', $subjectId)->orderBy('created_at', 'ASC')->get()->toArray();
    }
    /*
    public function show(string $id): array
    {

        $item = Subject::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Subject::where('id', $id)->get()->toArray();
    }
        */
}
