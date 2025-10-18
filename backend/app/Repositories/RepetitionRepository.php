<?php

namespace App\Repositories;

use App\Models\Repetition;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class RepetitionRepository
{

    public function index(string $subject_id): Array
    {
        $data =  Repetition::query()
            ->where('subject_id', $subject_id)->orderBy('created_at', 'ASC')->get();

        $subject = Subject::findOrFail($subject_id)->only(['id','name']) ;  

        return [
            'subject' => $subject,
            'data' => $data,
        ] ;  
    }
 
    public function show(string $id): Repetition
    {
        $repetition = Repetition::where('id', $id)->exists();
        if (!$repetition) {
            throw new \Exception("non-existent instance");
        }

        return Repetition::with('tasks')->find($id);
    }
}
