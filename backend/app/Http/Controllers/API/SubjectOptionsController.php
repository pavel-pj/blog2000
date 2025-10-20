<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubjectOptions;
use App\Services\SubjectOptionService;
use Illuminate\Http\JsonResponse;

class SubjectOptionsController extends Controller
{
    public SubjectOptionService $service;

    public function __construct()
    {   
        $this->service = new SubjectOptionService;
        
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(SubjectOptionsUpdateRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();    
        try {
             
            return response()->json($this->service->update($validated, $id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

}
