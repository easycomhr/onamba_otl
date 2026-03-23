@extends('layouts.employee')

@section('title', 'Lịch sử đăng ký OT - OTMS')

@section('content')

<div class="sticky top-0 z-50 bg-white shadow-sm flex items-center p-4">
    <a href="{{ route('employee.dashboard') }}" class="absolute left-4 flex items-center text-gray-600 hover:text-blue-600 active:scale-95 transition-all p-2 -ml-2 rounded-lg">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
    </a>
    <h1 class="text-[19px] sm:text-xl font-bold text-blue-600 uppercase w-full text-center tracking-wide">Lịch sử đăng ký OT</h1>
</div>

<div class="flex-1 overflow-y-auto p-4 sm:p-5">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-sm text-red-700">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('employee.ot.create', ['ref' => 'list']) }}"
           class="flex items-center justify-center w-full min-h-[56px] bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-lg uppercase rounded-xl shadow-md transition-all duration-200 active:scale-[0.98]">
            <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Đăng ký mới
        </a>
    </div>

    <div class="space-y-4 pb-6">

        @forelse($requests ?? [] as $req)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 {{ $req->status === 'approved' ? 'relative overflow-hidden' : '' }}">
            @if($req->status === 'approved')
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-green-500 rounded-l-2xl"></div>
            @elseif($req->status === 'rejected')
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500 rounded-l-2xl"></div>
            @endif

            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-3 {{ in_array($req->status, ['approved','rejected']) ? 'pl-2' : '' }}">
                <span class="text-[17px] font-bold text-gray-800">#{{ $req->code }}</span>
                @php
                    $badge = match($req->status) {
                        'approved' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Đã duyệt'],
                        'rejected' => ['class' => 'bg-red-100 text-red-700', 'label' => 'Từ chối'],
                        default    => ['class' => 'bg-yellow-100 text-yellow-700', 'label' => 'Chờ duyệt'],
                    };
                @endphp
                <span class="{{ $badge['class'] }} px-3 py-1 rounded-full text-[14px] font-bold uppercase tracking-wider">
                    {{ $badge['label'] }}
                </span>
            </div>

            <div class="space-y-2.5 text-[16px]">
                <div class="flex justify-between items-start gap-4">
                    <span class="text-gray-500 shrink-0">Ngày ĐK:</span>
                    <span class="text-gray-800 font-semibold text-right">{{ $req->ot_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-start gap-4">
                    <span class="text-gray-500 shrink-0">Giờ đăng ký:</span>
                    <span class="text-gray-800 font-semibold text-right">{{ $req->hours }} giờ</span>
                </div>
                @if($req->status === 'approved')
                <div class="flex justify-between items-start gap-4 bg-blue-50 -mx-4 px-4 py-2 rounded-lg">
                    <span class="text-gray-600 font-medium shrink-0">Số giờ duyệt:</span>
                    <span class="text-blue-600 font-bold text-[17px] text-right">{{ $req->approved_hours }} giờ</span>
                </div>
                @elseif($req->status === 'rejected')
                <div class="flex justify-between items-start gap-4 bg-red-50 -mx-4 px-4 py-2 rounded-lg">
                    <span class="text-red-600 font-medium shrink-0">Lý do từ chối:</span>
                    <span class="text-red-700 font-bold text-right text-[15px]">{{ $req->reject_reason }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        {{-- Demo data --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5">
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-3">
                <span class="text-[17px] font-bold text-gray-800">#OT-001</span>
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[14px] font-bold uppercase">Chờ duyệt</span>
            </div>
            <div class="space-y-2.5 text-[16px]">
                <div class="flex justify-between"><span class="text-gray-500">Ngày ĐK:</span><span class="font-semibold">15/03/2026</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Giờ đăng ký:</span><span class="font-semibold">2.5 giờ</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Nội dung:</span><span class="font-medium">Hỗ trợ xưởng A</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-green-500 rounded-l-2xl"></div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-3 pl-2">
                <span class="text-[17px] font-bold text-gray-800">#OT-002</span>
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[14px] font-bold uppercase">Đã duyệt</span>
            </div>
            <div class="space-y-2.5 text-[16px] pl-2">
                <div class="flex justify-between"><span class="text-gray-500">Ngày ĐK:</span><span class="font-semibold">10/03/2026</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Giờ đăng ký:</span><span class="font-semibold">3 giờ</span></div>
                <div class="flex justify-between items-start gap-4 bg-blue-50 -mx-4 px-4 py-2 rounded-lg">
                    <span class="text-gray-600 font-medium">Số giờ duyệt:</span>
                    <span class="text-blue-600 font-bold text-[17px]">3 giờ</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500 rounded-l-2xl"></div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-3 pl-2">
                <span class="text-[17px] font-bold text-gray-800">#OT-003</span>
                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[14px] font-bold uppercase">Từ chối</span>
            </div>
            <div class="space-y-2.5 text-[16px] pl-2">
                <div class="flex justify-between"><span class="text-gray-500">Ngày ĐK:</span><span class="font-semibold">05/03/2026</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Giờ đăng ký:</span><span class="font-semibold">4 giờ</span></div>
                <div class="flex justify-between items-start gap-4 bg-red-50 -mx-4 px-4 py-2 rounded-lg">
                    <span class="text-red-600 font-medium">Lý do từ chối:</span>
                    <span class="text-red-700 font-bold text-[15px]">Đã đủ người, không cần làm thêm.</span>
                </div>
            </div>
        </div>
        @endforelse

    </div>
</div>
@endsection
