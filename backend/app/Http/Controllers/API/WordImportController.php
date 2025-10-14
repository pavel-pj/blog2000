<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\WordsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;


class WordImportController extends Controller
{
     public function import(Request $request, string $subject_id): JsonResponse
    {

        set_time_limit(300);
        
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new WordsImport($subject_id);
            
            Excel::import($import, $request->file('excel_file'));
            
            $importedCount = $import->getRowCount();
            $failures = $import->failures();
            
            $response = [
                'success' => true,
                'message' => "Successfully imported {$importedCount} words.",
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

    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/words_template.xlsx');
        
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        if (!file_exists($filePath)) {
            $this->createTemplate();
        }
        
        return response()->download($filePath, 'words_template.xlsx');
    }

    private function createTemplate()
    {
        $export = new class {
            use \Maatwebsite\Excel\Concerns\FromArray;
            
            public function array(): array
            {
                return [
                    ['hello', 'hola'],
                    ['goodbye', 'adiós'],
                    ['thank you', 'gracias'],
                    ['please', 'por favor'],
                ];
            }
        };
        
        \Maatwebsite\Excel\Facades\Excel::store($export, 'templates/words_template.xlsx');
    }
}
