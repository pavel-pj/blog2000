<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function index()
    {
        return Article::orderBy('created_at', 'DESC')->get();
    }


    public function show(int $id)
    {

        $item = Article::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Article::where('id', $id)->get();
    }
}
