<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CatalogService;
use App\Http\Requests\CatalogCreateRequest;
use App\Http\Requests\CatalogUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    protected CatalogService $service;

    public function __construct()
    {
        $this->service = new CatalogService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CatalogCreateRequest $request)//: JsonResponse
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
    public function update(CatalogUpdateRequest $request, string $id)
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
