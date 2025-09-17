<?php

namespace App\Repositories;

use App\Models\Word;

class WordRepository
{
    public function index(string $subjectId)
    {
        return Word::query()->
            where('subject_id',$subjectId)->orderBy('created_at', 'DESC')->get();
    }


    public function show(string $id)
    {

        $item = Word::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Word::where('id', $id)->get();
    }
}
