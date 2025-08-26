<?php

namespace App\Services;

use App\Models\Catalog;
use App\Repositories\CatalogRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CatalogService
{
    protected CatalogRepository $repository;

    public function __construct()
    {
        $this->repository = new CatalogRepository();
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function show(string $id)
    {
        return $this->repository->show($id);
    }

    public function store(array $validated)
    {
        $item =  Catalog::create($validated);
        if (!$item) {
            throw new \Error("It is not possible to create new item Catalog");
        }
        return $item;
    }

    public function update(array $validated, string $id)
    {

        try {
            $item = Catalog::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        try {
            DB::beginTransaction();

            DB::table('catalogs')
                ->updateOrInsert(
                    ['id' => $id],
                    $validated
                );

            DB::commit();
            return Catalog::where('id', $id)->get() ;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function destroy(string $id): void
    {

        try {
            $item = Catalog::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        $result = Catalog::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    }
}
