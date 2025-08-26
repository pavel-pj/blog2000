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
    public function index()
    {
        try {
            return response()->json($this->service->index(), 201);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleCreateRequest $request): JsonResponse
    {

        $validated = $request->validated();

        try {
            return response()->json($this->service->store($validated), 201);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json($this->service->show($id), 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
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
    public function update(ArticleUpdateRequest $request, string $id)
    {

        $validated = $request->validated();
        try {
            return response()->json($this->service->update($validated, $id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $this->service->destroy($id);
            return response()->json("Item was deleted successfully", 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
