<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SubjectCreateRequest;
use App\Http\Requests\SubjectUpdateRequest;
use App\Services\SubjectService;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubjectController extends Controller
{
     
    public function __construct(protected SubjectService $service){}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json($this->service->index(), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectCreateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            return response()->json($this->service->store($validated), 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
 
        try {
            $this->isSubjectAllowedToShow($id);
            return response()->json($this->service->show($id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectUpdateRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        try {
            return response()->json($this->service->update($validated, $id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->service->destroy($id);
            return response()->json("Item was deleted successfully", 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    public function isSubjectAllowedToShow(string $id)  {

        if(Subject::findOrFail($id)->user_id !== auth()->user()->id){
            throw new HttpException(404, 'The requested resource could not be found');
        }

    }
}
