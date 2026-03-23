<?php

namespace App\Services;

use App\Models\OtRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OtService
{
    public function store(array $data, User $user): OtRequest
    {
        return DB::transaction(function () use ($data, $user) {
            $exists = OtRequest::where('user_id', $user->id)
                ->whereDate('ot_date', $data['ot_date'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                throw new \RuntimeException('Duplicate OT: user_id=' . $user->id . ' ot_date=' . $data['ot_date']);
            }

            return OtRequest::create([
                'user_id' => $user->id,
                'ot_date' => $data['ot_date'],
                'hours' => $data['hours'],
                'reason' => $data['reason'],
                'status' => OtRequest::STATUS_PENDING,
            ]);
        });
    }
}
