<?php

namespace App\Repositories;

use App\Models\Catalog;
use Illuminate\Support\Facades\DB;

class CatalogRepository
{
    public function index()
    {
        return Catalog::orderBy('created_at', 'DESC')->get();
    }

    public function show(int $id)
    {

        $item = Catalog::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Catalog::where('id', $id)->get();
    }

    public function getCatalogDictionary()
    {

        $query = DB::table('catalogs')
            ->select("id as code", "name")
            ->orderBy('name')->get();

        return $query;
    }
}
