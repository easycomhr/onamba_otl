<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeaveExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $rows) {}

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Mã NV', 'Tên NV', 'Phòng Ban', 'Loại nghỉ', 'Từ ngày', 'Đến ngày', 'Số ngày'];
    }

    public function map($row): array
    {
        return [
            $row->employee->employee_code ?? '',
            $row->employee->name ?? '',
            $row->employee->department ?? '',
            LeaveRequest::LEAVE_TYPES[$row->leave_type] ?? $row->leave_type,
            $row->from_date,
            $row->to_date,
            $row->days,
        ];
    }
}
