@extends('layouts.admin')

@section('title', 'Chi tiết đơn Nghỉ phép')
@section('page_title', 'Chi tiết Nghỉ phép')

@section('content')
<nav class="mb-6">
    <a href="{{ route('admin.approvals.leave.index') }}" class="flex items-center text-gray-500 hover:text-green-600 transition-colors w-fit">
        <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
        <span class="font-semibold">Quay lại</span>
    </a>
</nav>

<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-green-600 uppercase text-center mb-6 tracking-wide hidden md:block">Chi tiết đơn nghỉ phép</h1>

    <form method="POST" action="{{ route('admin.approvals.leave.approve', $leaveRequest->id) }}">
        @csrf

        <fieldset class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex items-center">
                <div class="bg-gray-200 p-2 rounded-full mr-3 text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <h2 class="text-[17px] font-bold text-gray-800 uppercase tracking-wide">1. Thông tin đơn nghỉ phép</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Mã đơn</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-bold text-gray-900 border border-gray-200">#{{ $leaveRequest->code }}</div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Loại nghỉ phép</label>
                    <div class="bg-green-50 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-bold text-green-700 border border-green-100">{{ $leaveRequest->leave_type_label }}</div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Nhân viên yêu cầu</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-gray-200">
                        <span class="font-bold text-gray-900 mr-2">{{ $leaveRequest->employee->name }}</span>
                        <span class="text-gray-500">({{ $leaveRequest->employee->employee_code }})</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Từ ngày</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-semibold text-gray-800 border border-gray-200">{{ $leaveRequest->from_date->format('d/m/Y') }}</div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Đến ngày</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-semibold text-gray-800 border border-gray-200">{{ $leaveRequest->to_date->format('d/m/Y') }}</div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Tổng số ngày nghỉ</label>
                    <div class="bg-green-50 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-green-100">
                        <span class="text-xl font-bold text-green-600 mr-1">{{ $leaveRequest->days }}</span>
                        <span class="text-green-700 font-medium">ngày</span>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Trạng thái</label>
                    <div class="min-h-[50px] px-4 py-3 flex items-center rounded-xl border font-bold
                        {{ $leaveRequest->status === 'approved' ? 'bg-green-50 border-green-200 text-green-700' : ($leaveRequest->status === 'rejected' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-yellow-50 border-yellow-200 text-yellow-700') }}">
                        {{ $leaveRequest->status_label }}
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Lý do</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 rounded-xl font-medium text-gray-800 border border-gray-200 leading-relaxed">
                        {{ $leaveRequest->reason }}
                    </div>
                </div>
            </div>
        </fieldset>

        @if($leaveRequest->status === 'pending')
        <fieldset class="bg-white rounded-2xl shadow-md border-2 border-green-200 mb-8 overflow-hidden">
            <div class="bg-green-50 px-5 py-4 border-b border-green-100 flex items-center">
                <div class="bg-green-200 p-2 rounded-full mr-3 text-green-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                    </svg>
                </div>
                <h2 class="text-[17px] font-bold text-green-700 uppercase tracking-wide">2. Quyết định phê duyệt</h2>
            </div>
            <div class="p-5">
                <label for="manager_note" class="block text-[16px] font-bold text-gray-800 mb-2">Ghi chú của quản lý <span class="text-gray-400 font-normal">(Tùy chọn)</span></label>
                <textarea id="manager_note" name="manager_note" rows="3" placeholder="Nhập lời nhắn cho nhân viên nếu cần..."
                          class="block w-full p-4 text-[16px] text-gray-800 bg-white border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-colors resize-none">{{ old('manager_note') }}</textarea>
            </div>
        </fieldset>

        <div class="flex flex-col-reverse sm:flex-row gap-4">
            <button type="button" onclick="handleReject()"
                    class="flex-1 flex items-center justify-center min-h-[56px] bg-white border-2 border-red-500 text-red-600 hover:bg-red-50 font-bold text-lg uppercase rounded-xl transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Từ chối
            </button>
            <button type="submit"
                    class="flex-1 flex items-center justify-center min-h-[56px] bg-green-600 text-white hover:bg-green-700 font-bold text-lg uppercase rounded-xl shadow-md transition-colors">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Phê duyệt
            </button>
        </div>
        @else
        {{-- Read-only: đơn đã xử lý --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-5 py-4 border-b border-gray-200">
                <h2 class="text-[17px] font-bold text-gray-800 uppercase tracking-wide">Kết quả xét duyệt</h2>
            </div>
            <div class="p-5 space-y-4">
                @if($leaveRequest->manager_note)
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Ghi chú quản lý</label>
                    <div class="bg-gray-100 px-4 py-3 rounded-xl text-gray-800 border border-gray-200 leading-relaxed">{{ $leaveRequest->manager_note }}</div>
                </div>
                @endif
                @if($leaveRequest->approvedBy)
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Người xử lý</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-gray-200 font-semibold text-gray-800">{{ $leaveRequest->approvedBy->name }}</div>
                </div>
                @endif
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Thời gian xử lý</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-gray-200 text-gray-800">
                        {{ ($leaveRequest->approved_at ?? $leaveRequest->rejected_at)?->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
    function handleReject() {
        const noteVal = document.getElementById('manager_note').value.trim();
        if (!noteVal) {
            alert('Vui lòng nhập lý do từ chối trước khi gửi.');
            document.getElementById('manager_note').focus();
            return;
        }
        const form = document.querySelector('form');
        form.action = '{{ route("admin.approvals.leave.reject", $leaveRequest->id) }}';
        form.submit();
    }
</script>
@endpush
