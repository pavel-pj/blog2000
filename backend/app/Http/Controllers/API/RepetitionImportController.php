<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\WordsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Imports\RepetitionSimpleImport;
use Illuminate\Support\Facades\Log;


class RepetitionImportController extends Controller
{
    public function importRepetitionSimple(Request $request, string $subject_id): JsonResponse
    {
 
        set_time_limit(1000);
        
        $request->validate([
            'excel_file' =>  'required|file|mimes:xlsx,xlsb,xls,csv,txt,application/vnd.ms-excel,text/plain,text/csv,application/octet-stream|max:10240'
        ]);

        try {
            $import = new RepetitionSimpleImport($subject_id);
            
            Excel::import($import, $request->file('excel_file'));
            
            $importedCount = $import->getRowCount();
            $failures = $import->failures();
            
            $response = [
                'success' => true,
                'message' => "Successfully imported {$importedCount} rows.",
                'data' => [
                    'imported_count' => $importedCount,
                    'failure_count' => count($failures),
                ]
            ];
            
            if (count($failures) > 0) {
                $failureDetails = [];
                
                foreach ($failures as $failure) {
                    $failureDetails[] = [
                        'row' => $failure->row(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values()
                    ];
                }
                
                $response['data']['failures'] = $failureDetails;
                $response['message'] .= " " . count($failures) . " rows failed to import.";
            }
            
            return response()->json($response, 200);
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $failureDetails = [];
            
            foreach ($failures as $failure) {
                $failureDetails[] = [
                    'row' => $failure->row(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values()
                ];
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred during import.',
                'errors' => $failureDetails
            ], 422);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage()
            ], 500);
        }
    }
}
