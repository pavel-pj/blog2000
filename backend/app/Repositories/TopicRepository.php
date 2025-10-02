<?php

namespace App\Repositories;

use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class TopicRepository
{
    public function index(string $subjectId): Array
    {
        $data =  Topic::query()
            ->where('subject_id', $subjectId)->orderBy('created_at', 'ASC')->get();
        $subject = Subject::findOrFail($subjectId)->only(['id','name']) ;  

        return [
            'subject' => $subject,
            'data' => $data,
        ] ;  
    }
    
    public function show(string $id): Topic
    {

        $item = Topic::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Topic::where('id',$id)->first() ;
        
    }
 
}
