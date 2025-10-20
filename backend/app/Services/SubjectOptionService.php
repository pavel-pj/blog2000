<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\SubjectOptions;
use Illuminate\Database\Eloquent\Collection as  EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Exception;

class SubjectOptionService
{
 

    public function update(array $validated, string $id): SubjectOptions
    {

       try {
            $item = SubjectOptions::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("non-existent instance");
        }

        SubjectOptions::updateOrInsert(
            ['id' => $id],
            $validated
        );
        return   SubjectOptions::findOrFail($id);
    }
 

}
    