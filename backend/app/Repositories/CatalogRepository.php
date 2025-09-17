<?php

namespace App\Repositories;

use App\Models\Catalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CatalogRepository
{
    public function index(): EloquentCollection
    {
        return Catalog::orderBy('created_at', 'DESC')->get();
    }

    public function show(string $id): Collection
    {

        $item = Catalog::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Catalog::where('id', $id)->get();
    }

    public function getCatalogDictionary(): Collection
    {

        $query =Catalog::query()->select("id as code", "name")
            ->orderBy('name')->get();

        return $query;
    }
}
