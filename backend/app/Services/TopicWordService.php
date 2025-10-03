<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Exception;
use App\Models\TopicWord;
use App\Models\Word;
 
class TopicWordService
{
     
    public function store(array $validated)
    {
    
        $topicWord = TopicWord::create($validated);
        if (!$topicWord){
            throw new Exception ("Could not create topic_word item");
        }

        return  $topicWord;
 
    } 
}