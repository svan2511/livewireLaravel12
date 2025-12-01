<?php

namespace App\Exports;

use App\Models\Center;
use App\Models\Permission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermissionsExport implements FromCollection , WithHeadings , ShouldAutoSize , WithStyles, WithMapping
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
            'ID','Label','Description','Module',
            'Created At',
        ];
    }

    public function map($permission): array
    {
        return [
            $permission->id,
            $permission->label,
            $permission->desc,
            $permission->module,
            $permission->created_at->format('Y-m-d H:i:s'),
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
        return Permission::whereIn('id',$this->ids)->get();
       
    }
}
