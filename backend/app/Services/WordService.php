<?php

namespace App\Services;

use App\Models\Word;
use App\Repositories\WordRepository;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class WordService
{
 
    public function __construct(protected WordRepository $repository){}

    public function index(string $subjectId): Array
    {
        return $this->repository->index($subjectId);
    }

    public function show(string $id): EloquentCollection 
    {
        return $this->repository->show($id);
    }

    public function store(array $validated): Word
    {

        $item =  Word::create($validated);
        if (!$item) {
            throw new \Exception("It is not possible to create new item Article");
        }
        return $item;
    }

    public function update(array $validated, string $id):EloquentCollection 
    {
       
        Word::updateOrInsert(
            ['id' => $id],
            $validated
        );
 
        return Word::where('id', $id)->get()  ;
    }

    public function destroy(string $id): void
    {
     

        $result = Word::destroy($id);
        if (!$result) {
            throw new \Exception("Item could not be deleted");
        }
    }
}
