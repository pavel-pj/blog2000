<?php

namespace App\Repositories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class WordRepository
{
    public function index(string $subjectId): EloquentCollection
    {
        return Word::query()->
            where('subject_id',$subjectId)->orderBy('created_at', 'DESC')->get();
    }


    public function show(string $id): EloquentCollection
    {

        $item = Word::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Word::where('id', $id)->get() ;
    }
}
