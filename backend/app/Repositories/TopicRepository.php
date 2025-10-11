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

    public function getTopicsDctionary() 
    {   
         $user_id = auth()->user()->id;
       
        
         $data =collect(Subject::where('user_id', $user_id)->with('topics')->get()->toArray());
         
         $result = $data->map(function($item){

            $topics = collect($item['topics'])
                ->map(function($itemTopic){
                    return [
                        'value'=>  $itemTopic['id'],
                        'label' => $itemTopic['name'],
                    ];
                });


            return [
                'id'=>$item['id'],
                'name' => $item['name'],
                'user_id' => $item['user_id'],
                'topics'=>  $topics 
            ];
         })->values();
            
         return [...$result];

         
        

        
    }
 
}
