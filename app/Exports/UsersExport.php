<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings , ShouldAutoSize , WithStyles
{
    /**
     * Create a new class instance.
     */
    public array $ids = [];

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

     public function headings(): array
    {
        return [
            'ID','Name','Email'
        ];
    }


     public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function collection()
    {
        return User::whereIn('id',$this->ids)
        ->get(['id','name','email']);
    }

}
