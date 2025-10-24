<?php

namespace App\Repositories;

use App\Models\Word;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Carbon\Carbon;

class WordRepository
{
    public function index(string $subjectId): Array
    {

       $data = Word::query() 
        ->where('subject_id', $subjectId)
        ->select('id', 'name', 'status', 'created_at', 'repeated_at')            
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function ($word) {
            return [
                'id' => $word->id,
                'name' => $word->name,
                'status' => $word->status,
                'created_at' => $word->created_at ? Carbon::parse($word->created_at)->format('Y-m-d') : null,
                'repeated_at' => $word->repeated_at ? Carbon::parse($word->repeated_at)->format('Y-m-d') : null,
            ];
        });
 
        $subject = Subject::findOrFail($subjectId)->only(['id','name' ]) ;    
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
