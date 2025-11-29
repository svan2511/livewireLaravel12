<?php

namespace App\Exports;

use App\Models\Center;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CentersExport implements FromCollection , WithHeadings , ShouldAutoSize , WithStyles, WithMapping
{

    public array $ids = [];
    public function __construct($ids)
    {
        $this->ids = $ids;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'ID','Center Name','Center Address','Total Members',
            'Created At',
        ];
    }

    public function map($center): array
    {
        return [
            $center->id,
            $center->center_name,
            $center->center_address,
            $center->members->count(),
            $center->created_at->format('Y-m-d H:i:s'),
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
        return Center::with('members')
        ->whereIn('id',$this->ids)->get(['id','center_name','center_address','created_at']);
       
    }
}
