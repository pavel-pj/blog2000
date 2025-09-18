<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ArticleRepository
{
    public function index(): EloquentCollection
    {
        return Article::orderBy('created_at', 'DESC')->get();
    }


    public function show(string $id): Collection
    {

        $item = Article::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Article::where('id', $id)->get();
    }
}
