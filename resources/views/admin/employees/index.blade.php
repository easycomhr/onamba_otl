@extends('layouts.admin')

@section('title', 'Quản lý nhân viên')
@section('page_title', 'Nhân viên')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-tight hidden md:block">Quản lý nhân viên</h1>
</div>

{{-- Search bar --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.employees.index') }}" class="flex gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" name="q" placeholder="Tìm kiếm theo tên, mã NV..." value="{{ request('q') }}"
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-[15px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors text-[15px]">Tìm kiếm</button>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm">Mã NV</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm">Họ tên</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm hidden md:table-cell">Bộ phận</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm hidden md:table-cell">Chức vụ</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm text-center hidden md:table-cell">Quỹ phép</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm text-center">Hành động</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($employees as $emp)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-bold text-blue-600">{{ $emp->employee_code }}</td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 text-sm flex-shrink-0">
                        {{ mb_substr($emp->name, 0, 1) }}
                    </div>
                    <span class="font-semibold text-gray-800">{{ $emp->name }}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-gray-600 hidden md:table-cell">{{ $emp->department }}</td>
            <td class="px-6 py-4 text-gray-600 hidden md:table-cell">{{ $emp->position }}</td>
            <td class="px-6 py-4 text-center hidden md:table-cell">
                <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 font-bold rounded-lg text-sm">{{ $emp->annual_leave_balance }} ngày</span>
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('admin.employees.edit', $emp->id) }}"
                       class="flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>
                        Sửa
                    </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Không tìm thấy nhân viên nào.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($employees->hasPages())
<div class="mt-4">{{ $employees->links() }}</div>
@endif

@endsection