@extends('site.master')

@section('title', 'Lịch sử hỗ trợ')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-12">
    <h2 class="text-3xl font-bold mb-6 text-gray-700 border-l-4 border-teal-600 pl-3">Lịch sử gửi hỗ trợ</h2>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        @if($contacts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Ngày gửi</th>
                            <th class="px-6 py-3">Chủ đề</th>
                            <th class="px-6 py-3">Nội dung gửi</th>
                            <th class="px-6 py-3">Trạng thái</th>
                            <th class="px-6 py-3">Phản hồi từ Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $item)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $item->subject }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate" title="{{ $item->message }}">
                                {{ Str::limit($item->message, 50) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status == 'pending')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Đang chờ</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Đã trả lời</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->reply_message)
                                    <div class="bg-teal-50 p-3 rounded border border-teal-100 text-gray-700 text-xs">
                                        <div class="font-bold text-teal-700 mb-1">
                                            <i class="fas fa-reply mr-1"></i> Admin trả lời lúc {{ \Carbon\Carbon::parse($item->replied_at)->format('H:i d/m') }}:
                                        </div>
                                        {!! nl2br(e($item->reply_message)) !!}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Chưa có phản hồi</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $contacts->links() }}
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                <p>Bạn chưa gửi yêu cầu hỗ trợ nào.</p>
                <a href="{{ route('contact') }}" class="text-teal-600 hover:underline mt-2 inline-block">Gửi tin nhắn ngay</a>
            </div>
        @endif
    </div>
</div>
@endsection