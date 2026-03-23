@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân viên')
@section('page_title', 'Sửa nhân viên')

@section('content')
<nav class="mb-6">
    <a href="{{ route('admin.employees.index') }}" class="flex items-center text-gray-500 hover:text-blue-600 transition-colors w-fit">
        <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
        <span class="font-semibold">Quay lại danh sách</span>
    </a>
</nav>

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Thông tin nhân viên --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200">
            <h2 class="text-[17px] font-bold text-gray-800 uppercase tracking-wide">Thông tin nhân viên</h2>
        </div>
        <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}" class="p-5 space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Mã nhân viên</label>
                    <div class="bg-gray-100 min-h-[50px] px-4 py-3 flex items-center rounded-xl font-bold text-gray-600 border border-gray-200 text-[15px]">{{ $employee->employee_code }}</div>
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                           class="block w-full min-h-[50px] px-4 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                           class="block w-full min-h-[50px] px-4 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Bộ phận <span class="text-red-500">*</span></label>
                    <input type="text" name="department" value="{{ old('department', $employee->department) }}" required
                           class="block w-full min-h-[50px] px-4 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
                    @error('department')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Chức vụ <span class="text-red-500">*</span></label>
                    <input type="text" name="position" value="{{ old('position', $employee->position) }}" required
                           class="block w-full min-h-[50px] px-4 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
                    @error('position')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Quỹ phép năm</label>
                    <div class="relative">
                        <input type="number" name="annual_leave_balance" value="{{ old('annual_leave_balance', $employee->annual_leave_balance) }}" min="0"
                               class="block w-full min-h-[50px] pl-4 pr-14 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">ngày</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.employees.index') }}"
                   class="flex-1 flex items-center justify-center min-h-[50px] bg-white border-2 border-gray-300 text-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 transition-colors">
                    Hủy
                </a>
                <button type="submit"
                        class="flex-1 min-h-[50px] bg-blue-600 text-white font-bold uppercase rounded-xl shadow-md hover:bg-blue-700 transition-colors">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    {{-- Đổi mật khẩu --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200">
            <h2 class="text-[17px] font-bold text-gray-800 uppercase tracking-wide">Đổi mật khẩu</h2>
        </div>
        <form method="POST" action="{{ route('admin.employees.change-password', $employee->id) }}" class="p-5 space-y-5">
            @csrf
            <div>
                <label class="block text-[15px] font-bold text-gray-800 mb-2">Mật khẩu mới <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="8"
                       class="block w-full min-h-[50px] px-4 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
            </div>
            <div>
                <label class="block text-[15px] font-bold text-gray-800 mb-2">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required minlength="8"
                       class="block w-full min-h-[50px] px-4 text-[15px] text-gray-800 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-0 focus:border-blue-500 transition-colors">
            </div>
            <button type="submit"
                    class="w-full min-h-[50px] bg-gray-800 text-white font-bold uppercase rounded-xl shadow-md hover:bg-gray-900 transition-colors">
                Đổi mật khẩu
            </button>
        </form>
    </div>

</div>
@endsection