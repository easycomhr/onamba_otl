<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreLeaveRequest;
use App\Services\LeaveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    public function __construct(private LeaveService $service) {}

    public function index()
    {
        $user         = Auth::user();
        $requests     = \App\Models\LeaveRequest::where('user_id', $user->id)->latest()->get();
        $leaveBalance = $user->annual_leave_balance;

        return view('employee.leave.list', compact('requests', 'leaveBalance'));
    }

    public function create()
    {
        $leaveBalance = Auth::user()->annual_leave_balance;

        return view('employee.leave.register', compact('leaveBalance'));
    }

    public function store(StoreLeaveRequest $request)
    {
        try {
            $this->service->store($request->validated(), Auth::user());

            return redirect()->route('employee.leave.index')
                ->with('success', 'Đơn xin nghỉ đã được gửi. Đang chờ duyệt.');
        } catch (\Throwable $e) {
            Log::error('Employee leave store error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi gửi đơn nghỉ phép.']);
        }
    }
}
