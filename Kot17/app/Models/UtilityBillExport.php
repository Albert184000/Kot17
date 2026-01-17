<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UtilityBillExport implements FromCollection, WithHeadings, WithStyles
{
    protected $bill;

    public function __construct($bill)
    {
        $this->bill = $bill;
    }

    public function collection()
    {
        return $this->bill->readings->map(function($r, $index) {
            return [
                $index + 1,
                $r->room_name,
                $r->people_count,
                $r->meter_no,
                $r->old_reading,
                $r->new_reading,
                $r->usage_units,
                $r->common_share_units,
                $r->total_units,
                $r->amount_final,
                $r->amount_per_person,
                $r->paid_amount,
                $r->balance_amount,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Room', 'People', 'Meter', 'Old', 'New', 'Usage', 'Common', 'Total Units', 'Amount(Room)', 'Amount(Person)', 'Paid', 'Balance'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
