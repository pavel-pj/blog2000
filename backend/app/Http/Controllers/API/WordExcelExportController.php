<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exports\WordExcelExport;
use App\Exports\RepetitionNewExport;
use App\Exports\RepetitionRepeatedExport;
use App\Exports\RepetitionImportantExport;
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
            $repetition_enum = RepetitionType::from($repetition_type);

            return match($repetition_enum){
                RepetitionType::NEW => Excel::download(new RepetitionNewExport($subject_id), $filename),
                RepetitionType::IMPORTANT => Excel::download(new RepetitionImportantExport($subject_id), $filename),
                RepetitionType::REPEATED => Excel::download(new RepetitionRepeatedExport($subject_id), $filename),
                default =>  Excel::download(new RepetitionRepeatedExport($subject_id), $filename)   
            };
    
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
