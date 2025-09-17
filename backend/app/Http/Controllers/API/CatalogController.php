<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CatalogService;
use App\Http\Requests\CatalogCreateRequest;
use App\Http\Requests\CatalogUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Catalog;

class CatalogController extends Controller
{
  
    public function __construct(protected CatalogService $service){}

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
    public function store(CatalogCreateRequest $request): JsonResponse
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
            return response()->json($this->service->show($id), 200);
        } catch (\Exception $e) {
            //error_log("ОШИБКА КОНТРОЛЕЛЕН КАТАЛОГ");
            //error_log($e->getMessage());
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CatalogUpdateRequest $request, string $id): JsonResponse
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
