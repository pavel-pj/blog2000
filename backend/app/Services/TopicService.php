<?php

namespace App\Services;

use App\Models\Topic;
use App\Repositories\TopicRepository;

class TopicService
{
 
    public function __construct(protected TopicRepository $repository){} 
    
    public function index(string $subjectId): Array
    {
        return $this->repository->index($subjectId);
    }
     /*
    public function show(string $id)
    {
        return $this->repository->show($id);
    }
    */
    public function store(array $validated): Array
    {
 
        $item =  Topic::create($validated);
        if (!$item) {
            throw new \Exception("It is not possible to create new item Article");
        }
        return $item->toArray();
    }
    /*
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
    }*/
}
