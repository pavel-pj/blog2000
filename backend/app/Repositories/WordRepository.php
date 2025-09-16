<?php

namespace App\Repositories;

use App\Models\Word;

class WordRepository
{
    public function index()
    {
        return Word::orderBy('created_at', 'DESC')->get();
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
