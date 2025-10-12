<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at')
                  ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Email Address', 
            'Email Verified',
            'Registration Date',
            'Last Updated'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->email_verified_at ? 'Yes' : 'No',
            $user->created_at->format('M d, Y H:i'),
            $user->updated_at->format('M d, Y H:i')
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