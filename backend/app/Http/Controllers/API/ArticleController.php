<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public ArticleService $service;

    public function __construct()
    {
        $this->service = new ArticleService();
    }

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
    public function store(ArticleCreateRequest $request): JsonResponse
    {


        //error_log('DEBUG: ARTICLE - ' . json_encode($request, JSON_PRETTY_PRINT));

        try {
            $validated = $request->validated();
            // error_log('DEBUG: ARTICLE after VALIDATION');
            return response()->json($this->service->store($validated), 201);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->service->show($id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $request, string $id): JsonResponse
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
}
