<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Services\LegacyLeaveTypeMapper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApprovedLeavesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $month = $request->integer('month', now()->month);
            $year = $request->integer('year', now()->year);

            $mapped = LeaveRequest::approved()
                ->with(['employee', 'approvedBy'])
                ->where(function ($query) use ($month, $year) {
                    $query->where(function ($fromDateQuery) use ($month, $year) {
                        $fromDateQuery
                            ->whereYear('from_date', $year)
                            ->whereMonth('from_date', $month);
                    })->orWhere(function ($toDateQuery) use ($month, $year) {
                        $toDateQuery
                            ->whereYear('to_date', $year)
                            ->whereMonth('to_date', $month);
                    });
                })
                ->get()
                ->map(fn (LeaveRequest $leaveRequest): array => [
                    'legacy_leavetype_id' => LegacyLeaveTypeMapper::toLegacyId($leaveRequest->leave_type),
                    'leave_type_key' => $leaveRequest->leave_type,
                    'leave_type_label' => LeaveRequest::LEAVE_TYPES[$leaveRequest->leave_type] ?? $leaveRequest->leave_type,
                    'employee_code' => $leaveRequest->employee->employee_code,
                    'employee_name' => $leaveRequest->employee->name,
                    'department' => $leaveRequest->employee->department,
                    'position' => $leaveRequest->employee->position,
                    'from_date' => $leaveRequest->from_date->toDateString(),
                    'to_date' => $leaveRequest->to_date->toDateString(),
                    'days' => $leaveRequest->days,
                    'reason' => $leaveRequest->reason,
                    'approved_at' => $leaveRequest->approved_at?->toDateTimeString(),
                    'approved_by_name' => $leaveRequest->approvedBy?->name,
                    'otlms_code' => $leaveRequest->code,
                ])
                ->values();

            return response()->json([
                'data' => $mapped,
                'month' => $month,
                'year' => $year,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch approved leaves for legacy integration', [
                'month' => $request->integer('month', now()->month),
                'year' => $request->integer('year', now()->year),
                'exception' => $exception,
            ]);

            return response()->json([
                'message' => 'Lỗi hệ thống',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
