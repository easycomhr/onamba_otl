@extends('layouts.admin')

@section('title', 'Chi tiết đăng ký OT')
@section('page_title', 'Chi tiết OT')

@section('content')
<nav class="mb-6">
    <a href="{{ route('admin.approvals.ot.index') }}" class="flex items-center text-gray-500 hover:text-blue-600 transition-colors w-fit">
        <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
        <span class="font-semibold">Quay lại</span>
    </a>
</nav>

<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-blue-600 uppercase text-center mb-6 tracking-wide hidden md:block">
        Chi tiết đăng ký OT
    </h1>

    <form method="POST" action="{{ route('admin.approvals.ot.approve', $otRequest->id) }}">
        @csrf

        {{-- Section 1: Thông tin từ nhân viên --}}
        <fieldset class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex items-center">
                <div class="bg-gray-200 p-2 rounded-full mr-3 text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <h2 class="text-[17px] font-bold text-gray-800 uppercase tracking-wide">1. Thông tin từ nhân viên</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Mã đăng ký</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-bold text-gray-900 border border-gray-200">
                        #{{ $otRequest->code }}
                    </div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Ngày tăng ca</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-semibold text-gray-800 border border-gray-200">
                        {{ $otRequest->ot_date->format('d/m/Y') }}
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Nhân viên yêu cầu</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-gray-200">
                        <span class="font-bold text-gray-900 mr-2">{{ $otRequest->employee->name }}</span>
                        <span class="text-gray-500 font-medium">({{ $otRequest->employee->employee_code }})</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Số giờ đăng ký</label>
                    <div class="bg-blue-50 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-blue-100">
                        <span class="text-xl font-bold text-blue-600 mr-1">{{ $otRequest->hours }}</span>
                        <span class="text-blue-700 font-medium">giờ</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Trạng thái</label>
                    <div class="min-h-[50px] px-4 py-3 flex items-center rounded-xl border font-bold
                        {{ $otRequest->status === 'approved' ? 'bg-green-50 border-green-200 text-green-700' : ($otRequest->status === 'rejected' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-yellow-50 border-yellow-200 text-yellow-700') }}">
                        {{ $otRequest->status_label }}
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Nội dung công việc</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 rounded-xl font-medium text-gray-800 border border-gray-200 leading-relaxed whitespace-pre-wrap">
                        {{ $otRequest->reason }}
                    </div>
                </div>
            </div>
        </fieldset>

        @if($otRequest->status === 'pending')
        {{-- Section 2: Quyết định phê duyệt --}}
        <fieldset class="bg-white rounded-2xl shadow-md border-2 border-blue-200 mb-8 overflow-hidden">
            <div class="bg-blue-50 px-5 py-4 border-b border-blue-100 flex items-center">
                <div class="bg-blue-200 p-2 rounded-full mr-3 text-blue-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                </div>
                <h2 class="text-[17px] font-bold text-blue-700 uppercase tracking-wide">2. Quyết định phê duyệt</h2>
            </div>
            <div class="p-5 space-y-5">
                <div>
                    <label for="approved_hours" class="block text-[16px] font-bold text-gray-800 mb-2">
                        Số giờ xét duyệt <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" id="approved_hours" name="approved_hours" step="0.5"
                               value="{{ old('approved_hours', $otRequest->hours) }}" min="0.5" max="24" required
                               class="block w-full min-h-[54px] pl-4 pr-12 text-lg font-bold text-blue-600 bg-white border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-colors">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">giờ</span>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Mặc định là số giờ nhân viên yêu cầu. Bạn có thể điều chỉnh nếu cần.</p>
                    @error('approved_hours')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="manager_note" class="block text-[16px] font-bold text-gray-800 mb-2">
                        Ghi chú của quản lý <span class="text-gray-400 font-normal">(Tùy chọn)</span>
                    </label>
                    <textarea id="manager_note" name="manager_note" rows="3" placeholder="Nhập lời nhắn cho nhân viên nếu cần..."
                              class="block w-full p-4 text-[16px] text-gray-800 bg-white border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-colors resize-none">{{ old('manager_note') }}</textarea>
                </div>
            </div>
        </fieldset>

        <div class="flex flex-col-reverse sm:flex-row gap-4">
            <button type="button" onclick="handleReject()"
                    class="flex-1 flex items-center justify-center min-h-[56px] bg-white border-2 border-red-500 text-red-600 hover:bg-red-50 font-bold text-lg uppercase rounded-xl transition-colors focus:outline-none focus:ring-4 focus:ring-red-100">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Từ chối
            </button>
            <button type="submit"
                    class="flex-1 flex items-center justify-center min-h-[56px] bg-green-600 text-white hover:bg-green-700 font-bold text-lg uppercase rounded-xl shadow-md transition-colors focus:outline-none focus:ring-4 focus:ring-green-200">
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
                @if($otRequest->status === 'approved')
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Số giờ được duyệt</label>
                    <div class="bg-blue-50 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-blue-100">
                        <span class="text-xl font-bold text-blue-600 mr-1">{{ $otRequest->approved_hours }}</span>
                        <span class="text-blue-700 font-medium">giờ</span>
                    </div>
                </div>
                @endif
                @if($otRequest->manager_note)
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Ghi chú quản lý</label>
                    <div class="bg-gray-100 px-4 py-3 rounded-xl text-gray-800 border border-gray-200 leading-relaxed">{{ $otRequest->manager_note }}</div>
                </div>
                @endif
                @if($otRequest->approvedBy)
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Người xử lý</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-gray-200 font-semibold text-gray-800">{{ $otRequest->approvedBy->name }}</div>
                </div>
                @endif
                <div>
                    <label class="block text-[15px] font-bold text-gray-500 mb-1.5">Thời gian xử lý</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl border border-gray-200 text-gray-800">
                        {{ ($otRequest->approved_at ?? $otRequest->rejected_at)?->format('d/m/Y H:i') }}
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
        form.action = '{{ route("admin.approvals.ot.reject", $otRequest->id) }}';
        form.submit();
    }
</script>
@endpush
