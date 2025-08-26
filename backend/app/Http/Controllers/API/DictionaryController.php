<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DictionaryService;
use App\Http\Requests\DictionaryRequest;
use Illuminate\Http\JsonResponse;

class DictionaryController extends Controller
{
    private DictionaryService $service;

    public function __construct(DictionaryService $service)
    {
        $this->service = $service;
    }

    public function index(DictionaryRequest $request)// : JsonResponse
    {
        $typeDictionary = $request->input('typeDictionary');
        $validated = $request->validated();


        return response()->json(
            $this->service->getCandidateDictionaryByParams($validated, $typeDictionary)
        );
    }
}
