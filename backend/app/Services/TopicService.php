<?php

namespace App\Services;

use App\Models\Topic;
use App\Repositories\TopicRepository;
use Illuminate\Database\Eloquent\Collection as  EloquentCollection;

class TopicService
{
 
    public function __construct(protected TopicRepository $repository){} 
    
    public function index(string $subjectId): EloquentCollection
    {
        return $this->repository->index($subjectId);
    }
   
    public function show(string $id)
    {
        return $this->repository->show($id);
    }
   
    public function store(array $validated): Topic
    {
 
        $item =  Topic::create($validated);
        if (!$item) {
            throw new \Exception("It is not possible to create new item Article");
        }
        return $item;
    }
  
    public function update(array $validated, string $id)
    {
 
        Topic::updateOrInsert(
            ['id' => $id],
            $validated
        );
        return Topic::where('id', $id)->get() ;
    }
 
    public function destroy(string $id): void
    {
        /*
        try {
            $item = Topic::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }*/

        $result = Topic::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    } 
}
