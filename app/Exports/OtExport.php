<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OtExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $rows) {}

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Mã NV', 'Tên NV', 'Phòng Ban', 'Ngày OT', 'Giờ OT Duyệt'];
    }

    public function map($row): array
    {
        return [
            $row->employee->employee_code ?? '',
            $row->employee->name ?? '',
            $row->employee->department ?? '',
            $row->ot_date,
            $row->approved_hours,
        ];
    }
}
