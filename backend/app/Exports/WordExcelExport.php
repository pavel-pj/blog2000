<?php

namespace App\Exports;

use App\Models\Word;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WordExcelExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private string $subject_id)
    {
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Word::where('subject_id', $this->subject_id)
            ->select('id', 'name', 'translation', 'status')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'name',
            'translation', 
            'status',
          //  'created_at',
          //  'repeated_at'
        ];
    }

    public function map($word): array
    {
        return [
            $word->id,
            $word->name,
            $word->translation,
            $word->status,
           // $word->created_at->format('M d, Y H:i'),
           // $word->repeated_at->format('M d, Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E6E6FA']
                ]
            ],
        ];
    }
}
