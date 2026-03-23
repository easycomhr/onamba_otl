<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreOtRequest;
use App\Models\OtRequest;
use App\Services\OtService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtController extends Controller
{
    public function __construct(private OtService $service)
    {
    }

    public function index()
    {
        $requests = OtRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('employee.ot.list', compact('requests'));
    }

    public function create()
    {
        return view('employee.ot.register');
    }

    public function store(StoreOtRequest $request)
    {
        try {
            $this->service->store($request->validated(), Auth::user());

            return redirect()->route('employee.ot.index')
                ->with('success', 'Đăng ký tăng ca thành công. Đang chờ duyệt.');
        } catch (\Throwable $e) {
            Log::error('Employee OT store error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'error' => 'Đã xảy ra lỗi khi đăng ký tăng ca.',
            ]);
        }
    }
}
