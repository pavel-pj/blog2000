<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdvancedUsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private $startDate;
    private $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = User::query();
        
        if ($this->startDate) {
            $query->where('created_at', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->where('created_at', '<=', $this->endDate);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Full Name', 
            'Email',
            'Status',
            'Registered On',
            'Days Since Registration'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->email_verified_at ? 'Verified' : 'Pending',
            $user->created_at->format('Y-m-d'),
            now()->diffInDays($user->created_at)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3498DB']
                ]
            ],
            'A' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // User ID
            'B' => 25,  // Full Name
            'C' => 30,  // Email
            'D' => 15,  // Status
            'E' => 20,  // Registered On
            'F' => 25,  // Days Since Registration
        ];
    }

    public function title(): string
    {
        return 'Users Report';
    }
}