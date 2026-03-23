<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApprovedOtController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $month = $request->integer('month', now()->month);
            $year = $request->integer('year', now()->year);

            $mapped = OtRequest::approved()
                ->with(['employee', 'approvedBy'])
                ->whereYear('ot_date', $year)
                ->whereMonth('ot_date', $month)
                ->get()
                ->map(fn (OtRequest $r): array => [
                    'employee_code' => $r->employee->employee_code,
                    'employee_name' => $r->employee->name,
                    'department' => $r->employee->department,
                    'position' => $r->employee->position,
                    'ot_date' => $r->ot_date->toDateString(),
                    'hours' => (float) $r->hours,
                    'approved_hours' => (float) $r->approved_hours,
                    'approved_at' => $r->approved_at?->toDateTimeString(),
                    'approved_by_name' => $r->approvedBy?->name,
                ])
                ->values();

            return response()->json([
                'data' => $mapped,
                'month' => $month,
                'year' => $year,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch approved OT records.', [
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return response()->json([
                'message' => 'Lỗi hệ thống',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
