@extends('layouts.admin')

@section('title', 'Duyệt đăng ký OT')
@section('page_title', 'Duyệt OT')

@section('content')
<nav class="mb-6">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-500 hover:text-blue-600 transition-colors w-fit">
        <svg class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
        </svg>
        <span class="font-semibold">Quay lại Dashboard</span>
    </a>
</nav>

<h1 class="text-xl font-bold text-gray-800 uppercase mb-6 hidden md:block">Danh sách OT cần duyệt</h1>

<div class="mb-6 flex items-center text-gray-600">
    <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
    </svg>
    <span>Đang có <strong class="text-blue-600">{{ $otRequests->where('status', 'pending')->count() }}</strong> yêu cầu chờ bạn xử lý.</span>
</div>

{{-- Filter bar --}}
<form method="GET" action="{{ route('admin.approvals.ot.index') }}" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc mã NV..."
           class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-300 rounded-xl text-[15px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-xl text-[15px] focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Tất cả trạng thái</option>
        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Chờ duyệt</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
    </select>
    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors text-[15px]">Lọc</button>
</form>

<div class="md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="hidden md:table-header-group bg-gray-50 border-b border-gray-200">
        <tr>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm">Mã ĐK</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm">Nhân viên</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm">Ngày OT</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm text-center">Số giờ</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm">Nội dung</th>
            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wider text-sm text-center">Hành động</th>
        </tr>
        </thead>

        <tbody class="block md:table-row-group">
        @forelse($otRequests as $req)
        <tr class="block md:table-row bg-white rounded-2xl shadow-sm mb-5 md:mb-0 md:rounded-none md:shadow-none md:border-b border-gray-100 hover:bg-gray-50 transition-colors">
            <td class="flex justify-between md:table-cell px-5 py-3 md:px-6 md:py-4 border-b border-gray-50 md:border-none">
                <span class="md:hidden font-medium text-gray-500">Mã ĐK:</span>
                <span class="font-bold text-gray-800 md:text-blue-600">#{{ $req->code }}</span>
            </td>
            <td class="flex justify-between items-center md:table-cell px-5 py-3 md:px-6 md:py-4 border-b border-gray-50 md:border-none">
                <span class="md:hidden font-medium text-gray-500">Nhân viên:</span>
                <div class="text-right md:text-left">
                    <p class="font-bold text-gray-800">{{ $req->employee->name }}</p>
                    <p class="text-sm text-gray-500">{{ $req->employee->employee_code }}</p>
                </div>
            </td>
            <td class="flex justify-between md:table-cell px-5 py-3 md:px-6 md:py-4 border-b border-gray-50 md:border-none">
                <span class="md:hidden font-medium text-gray-500">Ngày OT:</span>
                <span class="font-semibold text-gray-800">{{ $req->ot_date->format('d/m/Y') }}</span>
            </td>
            <td class="flex justify-between md:table-cell px-5 py-3 md:px-6 md:py-4 border-b border-gray-50 md:border-none md:text-center">
                <span class="md:hidden font-medium text-gray-500">Số giờ:</span>
                <span class="inline-flex items-center justify-center px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg">
                    {{ $req->hours }} h
                </span>
            </td>
            <td class="flex justify-between md:table-cell px-5 py-3 md:px-6 md:py-4 border-b border-gray-50 md:border-none">
                <span class="md:hidden font-medium text-gray-500 min-w-[80px]">Nội dung:</span>
                <span class="text-gray-600 truncate max-w-[180px] md:max-w-[200px] text-right md:text-left">{{ $req->reason }}</span>
            </td>
            <td class="block md:table-cell px-5 py-4 md:px-6 bg-gray-50/50 md:bg-transparent border-t md:border-none border-gray-100">
                @if($req->status === 'pending')
                <div class="grid grid-cols-3 gap-3 md:flex md:justify-center md:gap-2">
                    <a href="{{ route('admin.approvals.ot.show', $req->id) }}"
                       class="flex flex-col md:flex-row items-center justify-center py-2 md:px-3 border border-gray-300 md:border-transparent rounded-xl md:rounded-lg text-gray-600 hover:bg-gray-100 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 md:mr-1 mb-1 md:mb-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-xs md:text-sm font-semibold">Chi tiết</span>
                    </a>
                    <button type="button" onclick="openApproveModal('{{ $req->id }}', {{ $req->hours }})"
                            class="flex flex-col md:flex-row items-center justify-center py-2 md:px-4 bg-green-50 text-green-700 border border-green-200 hover:bg-green-600 hover:text-white rounded-xl md:rounded-lg transition-colors">
                        <svg class="w-5 h-5 md:mr-1 mb-1 md:mb-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-xs md:text-sm font-bold">Duyệt</span>
                    </button>
                    <button type="button" onclick="openRejectModal('{{ $req->id }}')"
                            class="flex flex-col md:flex-row items-center justify-center py-2 md:px-4 bg-red-50 text-red-700 border border-red-200 hover:bg-red-600 hover:text-white rounded-xl md:rounded-lg transition-colors">
                        <svg class="w-5 h-5 md:mr-1 mb-1 md:mb-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-xs md:text-sm font-bold">Từ chối</span>
                    </button>
                </div>
                @else
                <div class="flex items-center justify-center gap-2">
                    <span @class([
                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold',
                        'bg-green-100 text-green-700' => $req->status === 'approved',
                        'bg-red-100 text-red-700'     => $req->status === 'rejected',
                    ])>{{ $req->status_label }}</span>
                    <a href="{{ route('admin.approvals.ot.show', $req->id) }}"
                       class="flex flex-col md:flex-row items-center justify-center py-2 md:px-3 border border-gray-300 md:border-transparent rounded-xl md:rounded-lg text-gray-600 hover:bg-gray-100 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5 md:mr-1 mb-1 md:mb-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-xs md:text-sm font-semibold">Chi tiết</span>
                    </a>
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr class="block md:table-row">
            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Không có yêu cầu OT nào.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($otRequests->hasPages())
<div class="mt-4">{{ $otRequests->links() }}</div>
@endif

{{-- Approve Modal --}}
<div id="approveModal" class="fixed inset-0 bg-black/60 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div id="approveCard" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex items-center justify-between">
            <div class="flex items-center text-green-700">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-bold uppercase">Xác nhận duyệt OT</h3>
            </div>
            <button type="button" onclick="closeApproveModal()" class="text-green-600 hover:text-green-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="approveForm" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Số giờ duyệt thực tế <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="approved_hours" id="approve_hours" step="0.5" min="0.5" max="24" required
                               class="block w-full min-h-[48px] pl-4 pr-12 text-lg font-bold text-blue-600 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">giờ</span>
                        </div>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Ghi chú <span class="text-gray-400 font-normal">(Tùy chọn)</span></label>
                    <textarea name="manager_note" rows="2" placeholder="VD: Nhớ tắt điện trước khi về"
                              class="block w-full p-3 text-[15px] text-gray-800 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="closeApproveModal()"
                            class="w-full min-h-[48px] bg-white border-2 border-gray-300 text-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50">Đóng</button>
                    <button type="submit"
                            class="w-full min-h-[48px] bg-green-600 text-white font-bold uppercase rounded-xl shadow-md hover:bg-green-700">Xác nhận duyệt</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black/60 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div id="rejectCard" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300">
        <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex items-center justify-between">
            <div class="flex items-center text-red-700">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-lg font-bold uppercase">Từ chối OT</h3>
            </div>
            <button type="button" onclick="closeRejectModal()" class="text-red-600 hover:text-red-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-[15px] font-bold text-gray-800 mb-2">Lý do từ chối <span class="text-red-500">*</span></label>
                    <textarea name="manager_note" rows="3" placeholder="Vui lòng nhập lý do từ chối..." required
                              class="block w-full p-3 text-[15px] text-gray-800 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="w-full min-h-[48px] bg-white border-2 border-gray-300 text-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50">Đóng</button>
                    <button type="submit"
                            class="w-full min-h-[48px] bg-red-600 text-white font-bold uppercase rounded-xl shadow-md hover:bg-red-700">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const elApproveModal = document.getElementById('approveModal');
    const elApproveCard  = document.getElementById('approveCard');
    const elRejectModal  = document.getElementById('rejectModal');
    const elRejectCard   = document.getElementById('rejectCard');

    function openApproveModal(id, hours) {
        document.getElementById('approve_hours').value = hours;
        document.getElementById('approveForm').action = '/admin/approvals/ot/' + id + '/approve';
        elApproveModal.classList.remove('hidden');
        setTimeout(() => { elApproveModal.classList.remove('opacity-0'); elApproveCard.classList.remove('scale-95'); }, 10);
    }
    function closeApproveModal() {
        elApproveModal.classList.add('opacity-0'); elApproveCard.classList.add('scale-95');
        setTimeout(() => elApproveModal.classList.add('hidden'), 300);
    }
    function openRejectModal(id) {
        document.getElementById('rejectForm').action = '/admin/approvals/ot/' + id + '/reject';
        elRejectModal.classList.remove('hidden');
        setTimeout(() => { elRejectModal.classList.remove('opacity-0'); elRejectCard.classList.remove('scale-95'); }, 10);
    }
    function closeRejectModal() {
        elRejectModal.classList.add('opacity-0'); elRejectCard.classList.add('scale-95');
        setTimeout(() => elRejectModal.classList.add('hidden'), 300);
    }
    elApproveModal.addEventListener('click', e => { if(e.target === elApproveModal) closeApproveModal(); });
    elRejectModal.addEventListener('click', e => { if(e.target === elRejectModal) closeRejectModal(); });
</script>
@endpush
