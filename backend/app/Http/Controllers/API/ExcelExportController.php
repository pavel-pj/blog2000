<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exports\UsersExport;
use App\Exports\AdvancedUsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExcelExportController extends Controller
{
    // Simple export
    public function exportUsers()
    {
        try {
            $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Store file
            Excel::store(new UsersExport, 'exports/' . $filename);
            
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

    // Advanced export with filters
    public function exportAdvancedUsers(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            $filename = 'advanced_users_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            Excel::store(new AdvancedUsersExport($startDate, $endDate), 'exports/' . $filename);
            
            return response()->json([
                'success' => true,
                'message' => 'Advanced users export completed!',
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
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

    // Direct download
    public function downloadUsers()
    {
        $filename = 'users_download_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new UsersExport, $filename);
    }

    // Download specific file
    public function downloadFile($filename)
    {
        $filePath = 'exports/' . $filename;
        
        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        }
        
        return response()->json([
            'success' => false,
            'error' => 'File not found: ' . $filename
        ], 404);
    }

    // List all Excel files
    public function listFiles()
    {
        $files = Storage::files('exports');
        $excelFiles = array_filter($files, function($file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            return in_array($extension, ['xlsx', 'xls']);
        });
        
        return response()->json([
            'success' => true,
            'files' => array_values($excelFiles),
            'total_files' => count($excelFiles)
        ]);
    }

    // Multiple sheets export
    public function exportMultipleSheets()
    {
        try {
            $filename = 'multiple_sheets_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Create multiple sheets in one Excel file
            Excel::store(new class implements \Maatwebsite\Excel\Concerns\WithMultipleSheets {
                public function sheets(): array
                {
                    return [
                        'Users' => new UsersExport(),
                        'Summary' => new class implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
                            public function array(): array
                            {
                                return [
                                    ['Report Summary'],
                                    ['Total Users', \App\Models\User::count()],
                                    ['Verified Users', \App\Models\User::whereNotNull('email_verified_at')->count()],
                                    ['Report Generated', now()->format('Y-m-d H:i:s')]
                                ];
                            }
                            
                            public function title(): string
                            {
                                return 'Summary';
                            }
                        }
                    ];
                }
            }, 'exports/' . $filename);
            
            return response()->json([
                'success' => true,
                'message' => 'Multiple sheets Excel file created!',
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
