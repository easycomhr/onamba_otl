<?php

namespace App\Services;

use App\Models\OtRequest;
use Illuminate\Support\Facades\DB;

class OtRegisterService
{
    public function store(array $data): OtRequest
    {
        return DB::transaction(function () use ($data) {
            $exists = OtRequest::where('user_id', $data['employee_id'])
                ->whereDate('ot_date', $data['ot_date'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                throw new \RuntimeException(
                    'Duplicate OT: user_id=' . $data['employee_id'] . ' ot_date=' . $data['ot_date']
                );
            }

            return OtRequest::create([
                'user_id' => $data['employee_id'],
                'ot_date' => $data['ot_date'],
                'hours' => $data['hours'],
                'reason' => $data['reason'],
                'status' => OtRequest::STATUS_PENDING,
            ]);
        });
    }
}
