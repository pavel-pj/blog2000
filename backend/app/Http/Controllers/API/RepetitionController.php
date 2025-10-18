<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RepetitionShowRequest;
use App\Http\Requests\RepetitionIndexRequest;
use App\Repositories\RepetitionRepository;

class RepetitionController extends Controller
{
    private RepetitionRepository $repository;

    public function __construct()
    {
        $this->repository = new RepetitionRepository();
    }


    /**
     * Display a listing of the resource.
     */
    public function index(RepetitionIndexRequest $request, string $subject_id)
    {
        try {
            $validated = $request->validated();
            return response()->json($this->repository->index($subject_id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RepetitionShowRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            return response()->json($this->repository->show($id), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
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
