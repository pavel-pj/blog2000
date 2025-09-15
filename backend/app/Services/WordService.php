<?php

namespace App\Services;

use App\Models\Word;
use App\Repositories\WordRepository;



class WordService
{
    protected WordRepository $repository;

    public function __construct()
    {
       $this->repository = new WordRepository();
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


        $item =  Word::create($validated);
        if (!$item) {
            throw new \Exception("It is not possible to create new item Article");
        }
        return $item;
    }
 
    public function update(array $validated, string $id)
    {

        try {
            $item = Word::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }
 
        
        Word::updateOrInsert(
                ['id' => $id],
                $validated
                );
        return Word::where('id', $id)->get() ;
 
    }
 
    public function destroy(string $id): void
    {

        try {
            $item = Word::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        $result = Word::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    }
 
}
