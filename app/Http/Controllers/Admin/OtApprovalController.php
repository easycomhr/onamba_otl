<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtApprovalController extends Controller
{
    public function index(Request $request)
    {
        $otRequests = OtRequest::with('employee')
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

        return view('admin.approvals.ot-list', compact('otRequests'));
    }

    public function show(int $id)
    {
        $otRequest = OtRequest::with(['employee', 'approvedBy'])->findOrFail($id);

        return view('admin.approvals.ot-detail', compact('otRequest'));
    }

    public function approve(Request $request, int $id)
    {
        $otRequest = OtRequest::where('status', OtRequest::STATUS_PENDING)->findOrFail($id);

        $request->validate([
            'approved_hours' => 'required|numeric|min:0.5|max:24',
            'manager_note'   => 'nullable|string|max:500',
        ]);

        $otRequest->update([
            'status'         => OtRequest::STATUS_APPROVED,
            'approved_by'    => Auth::id(),
            'approved_hours' => $request->approved_hours,
            'manager_note'   => $request->manager_note,
            'approved_at'    => now(),
        ]);

        return back()->with('success', 'Đã duyệt đơn tăng ca.');
    }

    public function reject(Request $request, int $id)
    {
        $otRequest = OtRequest::where('status', OtRequest::STATUS_PENDING)->findOrFail($id);

        $request->validate(['manager_note' => 'required|string|max:500']);

        $otRequest->update([
            'status'       => OtRequest::STATUS_REJECTED,
            'approved_by'  => Auth::id(),
            'manager_note' => $request->manager_note,
            'rejected_at'  => now(),
        ]);

        return back()->with('success', 'Đã từ chối đơn tăng ca.');
    }
}