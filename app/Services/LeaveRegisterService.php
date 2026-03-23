<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\DB;

class LeaveRegisterService
{
    public function store(array $data): LeaveRequest
    {
        return DB::transaction(function () use ($data) {
            $exists = LeaveRequest::where('user_id', $data['employee_id'])
                ->whereDate('from_date', '<=', $data['to_date'])
                ->whereDate('to_date', '>=', $data['from_date'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                throw new \RuntimeException(
                    'Duplicate leave: user_id=' . $data['employee_id'] . ' ' . $data['from_date'] . '~' . $data['to_date']
                );
            }

            return LeaveRequest::create([
                'user_id' => $data['employee_id'],
                'leave_type' => $data['leave_type'],
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
                'reason' => $data['reason'],
                'status' => LeaveRequest::STATUS_PENDING,
            ]);
        });
    }
}
