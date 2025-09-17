<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\DB;

class ArticleService
{
  
    public function __construct( protected ArticleRepository $repository){}

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


        $item =  Article::create($validated);
        if (!$item) {
            throw new \Exception("It is not possible to create new item Article");
        }
        return $item;
    }

    public function update(array $validated, string $id)
    {

        try {
            $item = Article::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }


        try {
            DB::beginTransaction();

            DB::table('articles')
                ->updateOrInsert(
                    ['id' => $id],
                    $validated
                );

            DB::commit();
            return Article::where('id', $id)->get() ;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function destroy(string $id): void
    {

        try {
            $item = Article::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        $result = Article::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    }
}
