<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WordService;
use App\Http\Requests\WordCreateRequest;
use App\Http\Requests\WordUpdateRequest;
use App\Http\Requests\WordIndexRequest;
use App\Http\Requests\WordShowRequest;
use App\Http\Requests\WordDeleteRequest;
use Illuminate\Http\JsonResponse;
use App\Imports\WordsImport;
use App\Http\Requests\WordUpdateStatusRequest;

class WordController extends Controller
{
 
    public function __construct(public WordService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(WordIndexRequest $request, string $subjectId): JsonResponse
    {
        try {
            $validated = $request->validated();
            return response()->json($this->service->index($subjectId), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(WordCreateRequest $request) : JsonResponse
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
    public function show(WordShowRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            return response()->json($this->service->show($id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WordUpdateRequest $request, string $id): JsonResponse
    {
         
        try {
            $validated = $request->validated();
    
            return response()->json($this->service->update($validated, $id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    public function updateStatus(WordUpdateStatusRequest $request ): JsonResponse
    {
         
        try {
            $validated = $request->validated();
    
            return response()->json($this->service->updateStatus($validated ), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WordDeleteRequest $request, string $id): JsonResponse
    {
         $validated = $request->validated();
        try {
            $this->service->destroy($id);
            return response()->json("Item was deleted successfully", 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }
  
}
