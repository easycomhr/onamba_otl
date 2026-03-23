<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::with('employee')
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->q, fn ($q, $search) =>
                $q->whereHas('employee', fn ($eq) =>
                    $eq->where('name', 'like', "%{$search}%")
                       ->orWhere('employee_code', 'like', "%{$search}%")
                )
            )
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.approvals.leave-list', compact('leaveRequests'));
    }

    public function show(int $id)
    {
        $leaveRequest = LeaveRequest::with(['employee', 'approvedBy'])->findOrFail($id);

        return view('admin.approvals.leave-detail', compact('leaveRequest'));
    }

    public function approve(Request $request, int $id)
    {
        $leaveRequest = LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->findOrFail($id);

        $request->validate([
            'manager_note' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status'       => LeaveRequest::STATUS_APPROVED,
            'approved_by'  => Auth::id(),
            'manager_note' => $request->manager_note,
            'approved_at'  => now(),
        ]);

        // Trừ số ngày phép của nhân viên
        $leaveRequest->employee->decrement('annual_leave_balance', $leaveRequest->days);

        return back()->with('success', 'Đã duyệt đơn nghỉ phép.');
    }

    public function reject(Request $request, int $id)
    {
        $leaveRequest = LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->findOrFail($id);

        $request->validate(['manager_note' => 'required|string|max:500']);

        $leaveRequest->update([
            'status'       => LeaveRequest::STATUS_REJECTED,
            'approved_by'  => Auth::id(),
            'manager_note' => $request->manager_note,
            'rejected_at'  => now(),
        ]);

        return back()->with('success', 'Đã từ chối đơn nghỉ phép.');
    }
}