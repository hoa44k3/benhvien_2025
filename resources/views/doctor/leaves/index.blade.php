@extends('doctor.master')

@section('title', 'Lịch sử nghỉ phép')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-purple-500 pl-3">
                Lịch sử Nghỉ phép
            </h2>
            <p class="text-gray-500 text-sm mt-1">Quản lý các đơn xin nghỉ phép của bạn</p>
        </div>
        <a href="{{ route('doctor.leaves.create') }}" 
           class="flex items-center gap-2 bg-purple-600 text-white px-5 py-2.5 rounded-xl hover:bg-purple-700 transition shadow-lg transform active:scale-95">
            <i class="fas fa-plus"></i> Tạo đơn mới
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Thời gian nghỉ</th>
                        <th class="px-6 py-4 text-left">Lý do</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-left">Ghi chú từ Admin</th>
                        <th class="px-6 py-4 text-center">Ngày gửi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($leaves as $leave)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}
                                </span>
                                <span class="text-xs text-gray-500">đến</span>
                                <span class="font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700 truncate w-48" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($leave->status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                    ⏳ Đang chờ
                                </span>
                            @elseif($leave->status == 'approved')
                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    ✅ Đã duyệt
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                    ❌ Từ chối
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 italic">
                            {{ $leave->admin_note ?? '---' }}
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">
                            {{ $leave->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="far fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>Bạn chưa gửi đơn xin nghỉ nào.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaves->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>
</div>
@endsection