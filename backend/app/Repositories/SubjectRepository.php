<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectRepository
{
    public function index(): Array
    {
        return Subject::orderBy('created_at', 'DESC')->get()->toArray();
    }

    public function show(string $id): Array
    {
  
        $item = Subject::where('id', $id)->exists();
        if (!$item) {
            throw new \Exception("non-existent instance");
        }

        return Subject::where('id', $id)->get()->toArray();
    }
 
}
