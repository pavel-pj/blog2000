<?php

namespace App\Repositories;

use App\Models\Word;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class WordRepository
{
    public function index(string $subjectId): Array
    {

        $data = Word::query() 
            ->where('subject_id',$subjectId)->orderBy('created_at', 'DESC')->get();
        $subject = Subject::findOrFail($subjectId)->only(['id','name']) ;    
        return [
            'subject' => $subject,
            'data' => $data,
        ] ;  
    }


    public function show(string $id): Word
    {

        $item = Word::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Word::where('id', $id)->with('topics')->first() ;
    }
}
