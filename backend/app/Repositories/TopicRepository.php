<?php

namespace App\Repositories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class TopicRepository
{
    public function index(string $subjectId): EloquentCollection
    {
        return Topic::where('subject_id', $subjectId)->orderBy('created_at', 'ASC')->get();
    }
    
    public function show(string $id): EloquentCollection
    {

        $item = Topic::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Topic::where('id',$id)->get() ;
        
    }
 
}
