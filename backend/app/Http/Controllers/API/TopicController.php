<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TopicCreateRequest;
use App\Services\TopicService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TopicIndexRequest;

class TopicController extends Controller
{
 
    public function __construct(public TopicService $service){}
    /**
     * Display a listing of the resource.
     */
    public function index(TopicIndexRequest $request, string $subjectId): JsonResponse
    {   
         
        try {
            $validated = $request->validated();
            return response()->json($this->service->index($subjectId), 200);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json($e->getMessage(), 404);
        }
       
    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(TopicCreateRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            return response()->json($this->service->store($validated), 201);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
