<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $employeeService) {}

    public function index(Request $request)
    {
        $employees = $this->employeeService->getEmployeeList($request->q);

        return view('admin.employees.index', compact('employees'));
    }

    public function edit(int $id)
    {
        $employee = $this->employeeService->findEmployee($id);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, int $id)
    {
        try {
            $employee = $this->employeeService->findEmployee($id);
            $this->employeeService->updateEmployee($employee, $request->validated());

            return redirect()->route('admin.employees.index')
                ->with('success', 'Đã cập nhật thông tin nhân viên.');
        } catch (\Throwable $e) {
            Log::error('Employee update failed', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Cập nhật thất bại, vui lòng thử lại.');
        }
    }

    public function changePassword(ChangePasswordRequest $request, int $id)
    {
        try {
            $employee = $this->employeeService->findEmployee($id);
            $this->employeeService->changePassword($employee, $request->validated('password'));

            return back()->with('success', 'Đã đổi mật khẩu thành công.');
        } catch (\Throwable $e) {
            Log::error('Employee password change failed', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Đổi mật khẩu thất bại, vui lòng thử lại.');
        }
    }
}