<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exports\WordExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class WordExcelExportController extends Controller
{   
    // Simple export
    public function exportWords(string $subject_id)
    {
        try {
            $filename = 'words_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Store file
            Excel::store(new WordExcelExport($subject_id), 'exports/' . $filename);
            
            return response()->json([
                'success' => true,
                'message' => 'Users exported to Excel successfully!',
                'file_path' => 'exports/' . $filename,
                'download_url' => url("/api/excel/download/{$filename}")
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
