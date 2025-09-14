<?php

namespace App\Services;

use App\Models\Subject;
use App\Repositories\SubjectRepository;
 

class SubjectService
{
   protected SubjectRepository $repository;

    public function __construct()
    {
        $this->repository = new SubjectRepository();
    }

    public function index(): Array
    {
        return $this->repository->index();
    }
 

    public function store(array $validated): Array
    {
        try {
            $validated['user_id'] = auth()->user()->id;
            $item = Subject::create($validated);
            return $item->toArray();
            
        } catch (\Exception $e) {
            throw new \Exception("It is not possible to create new item Subject");
        }
    }

    
    public function show(string $id): Array
    {
        return $this->repository->show($id);
    }

   public function update(array $validated, string $id)
   {
        
        try { 
            $item = Subject::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        Subject::updateOrInsert(
                ['id' => $id],
                $validated
            );
        return   Subject::findOrFail($id);  

    }

    public function destroy(string $id): void
    {

        try {
            $item = Subject::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        $result = Subject::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    }
 
}
