<?php

namespace App\Services;

use App\Models\Subject;
use App\Repositories\SubjectRepository;
use Illuminate\Database\Eloquent\Collection as  EloquentCollection;

class SubjectService
{
    

    public function __construct(protected SubjectRepository $repository){}

    public function index(): EloquentCollection
    {
        return $this->repository->index();
    }


    public function store(array $validated): Subject
    {
        try {
            $validated['user_id'] = auth()->user()->id;
            $item = Subject::create($validated);
            return $item;
        } catch (\Exception $e) {
            throw new \Exception("It is not possible to create new item Subject");
        }
    }


    public function show(string $id): EloquentCollection
    {
        return $this->repository->show($id);
    }

    public function update(array $validated, string $id): Subject
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
