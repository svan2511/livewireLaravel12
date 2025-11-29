<?php

namespace App\Exports;

use App\Models\Center;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection , WithHeadings , ShouldAutoSize , WithStyles,WithMapping
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
            'ID','Name','Center','Disbursment Amount','Tenour','Disbursment date'
        ];
    }

     public function map($member): array
    {
        return [
            $member->id,
            $member->mem_name,
            $member->center->center_name,
            $member->disb_amount,
            $member->mem_tenor,
            $member->disb_date,
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
        return Member::with('center')
        ->whereIn('id',$this->ids)
        ->get(['id','mem_name','center_id','disb_amount','mem_tenor','disb_date']);
    }
}
