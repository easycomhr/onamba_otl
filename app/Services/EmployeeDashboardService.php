<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\OtRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class EmployeeDashboardService
{
    public function getDashboardData(User $user): array
    {
        return [
            'otSummary'      => $this->getOtSummary($user),
            'leaveSummary'   => $this->getLeaveSummary($user),
            'recentRequests' => $this->getRecentRequests($user),
            'leaveBalance'   => $user->annual_leave_balance,
        ];
    }

    private function getOtSummary(User $user): array
    {
        $startOfMonth = Carbon::now()->copy()->startOfMonth()->toDateTimeString();
        $endOfMonth = Carbon::now()->copy()->endOfMonth()->toDateTimeString();

        return [
            'pending_count' => OtRequest::where('user_id', $user->id)->pending()->count(),
            'approved_count' => OtRequest::where('user_id', $user->id)->approved()->count(),
            'approved_hours_month' => (float) OtRequest::where('user_id', $user->id)
                ->approved()
                ->whereBetween('ot_date', [$startOfMonth, $endOfMonth])
                ->sum('approved_hours'),
        ];
    }

    private function getLeaveSummary(User $user): array
    {
        $startOfYear = Carbon::now()->copy()->startOfYear()->toDateTimeString();
        $endOfYear = Carbon::now()->copy()->endOfYear()->toDateTimeString();

        return [
            'pending_count' => LeaveRequest::where('user_id', $user->id)->pending()->count(),
            'approved_count' => LeaveRequest::where('user_id', $user->id)->approved()->count(),
            'approved_days_year' => (int) LeaveRequest::where('user_id', $user->id)
                ->approved()
                ->where('from_date', '<=', $endOfYear)
                ->where('to_date', '>=', $startOfYear)
                ->sum('days'),
        ];
    }

    private function getRecentRequests(User $user): Collection
    {
        $otRequests = OtRequest::where('user_id', $user->id)
            ->select('id', 'code', 'status', 'ot_date', 'hours', 'created_at')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(fn (OtRequest $request) => [
                'type' => 'ot',
                'code' => $request->code,
                'status' => $request->status,
                'date' => $request->ot_date,
                'meta' => $request->hours . ' giờ',
                'created_at' => $request->created_at,
            ]);

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->select('id', 'code', 'status', 'from_date', 'days', 'created_at')
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(fn (LeaveRequest $request) => [
                'type' => 'leave',
                'code' => $request->code,
                'status' => $request->status,
                'date' => $request->from_date,
                'meta' => $request->days . ' ngày',
                'created_at' => $request->created_at,
            ]);

        return collect($otRequests)
            ->merge($leaveRequests)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();
    }
}
