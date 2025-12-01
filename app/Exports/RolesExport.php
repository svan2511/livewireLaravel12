<?php

namespace App\Exports;

use App\Models\Center;
use App\Models\Member;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RolesExport implements FromCollection , WithHeadings , ShouldAutoSize , WithStyles,WithMapping
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
            'ID','Label','Permissions'
        ];
    }

     public function map($role): array
    {
        return [
            $role->id,
            $role->label,
            $role->permissions->pluck('label'),
           
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
        return Role::with('permissions')
        ->whereIn('id',$this->ids)
        ->get(['id','label']);
    }
}
