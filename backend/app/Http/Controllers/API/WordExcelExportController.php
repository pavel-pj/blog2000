<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exports\WordExcelExport;
use App\Exports\RepetitionNewExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SubjectOptions;
use App\Enums\RepetitionType;


class WordExcelExportController extends Controller
{   
    // Simple export
    public function exportWords(string $subject_id)
    {
        try {
          
            $filename = 'words_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filePath = 'private/exports/' . $filename;  
             

            $repetition_type =  SubjectOptions::where('subject_id',$subject_id)->value('repetition_type');

            return match($repetition_type){
                RepetitionType::NEW => Excel::download(new RepetitionNewExport($subject_id), $filename),
                default =>  Excel::download(new RepetitionNewExport($subject_id), $filename)   
            };
            
            
          
            /*
            return response()->json([
                'success' => true,
                'message' => 'Users exported to Excel successfully!',
                'file_path' => 'exports/' . $filename,
                'download_url' => url("/api/excel/download/{$filename}")
            ]);*/
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
