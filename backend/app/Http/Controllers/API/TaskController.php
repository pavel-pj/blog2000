<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TaskUpdateRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private TaskService $service;

    public function __construct()
    {
        $this->service = new TaskService ();
        
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, string $id) : JsonResponse
    {
        try {
            $validated = $request->validated();
            return response()->json($this->service->update($validated, $id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
       
    }
}
