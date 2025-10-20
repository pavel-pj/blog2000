<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\SubjectOptions;
use App\Repositories\SubjectRepository;
use Illuminate\Database\Eloquent\Collection as  EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Exception;

class SubjectService
{
    

    public function __construct(protected SubjectRepository $repository){}

    public function index(): EloquentCollection
    {
        return $this->repository->index();
    }


    public function store(array $validated)//: Builder
    {
        /*
        try {
            $validated['user_id'] = auth()->user()->id;
            $item = Subject::create($validated);
            return $item;
        } catch (\Exception $e) {
            throw new \Exception("It is not possible to create new item Subject");
        }
            */
        try {
            DB::beginTransaction();

            $validated['user_id'] = auth()->user()->id;
            $subject = Subject::create($validated);

            if (!$subject) {
                throw new \Exception("It is not possible to create new item Subject");
            }

            $subjectOption = SubjectOptions::create(['subject_id' => $subject->id]);

            DB::commit();

            $subject =  Subject::find($subject->id);//->with('options')->get();
            return $subject->load('options');
            

        } catch (Exception $e) {
            DB::rollBack(); 
        
            throw new \Exception($e->getMessage());
        }

    }


    public function show(string $id): Subject
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
