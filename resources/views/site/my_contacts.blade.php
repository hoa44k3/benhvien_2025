@extends('site.master')

@section('title', 'Lịch sử hỗ trợ')

@section('body')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto max-w-5xl px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-800">Lịch sử gửi hỗ trợ</h2>
            <a href="{{ route('contact') }}" class="text-sm font-semibold text-primary hover:underline"><i class="fas fa-plus mr-1"></i> Gửi yêu cầu mới</a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            @if($contacts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Thời gian</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Chủ đề</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-1/3">Phản hồi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($contacts as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                    {{ $item->created_at->format('d/m/Y') }} <br>
                                    <span class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 mb-1">{{ $item->subject }}</div>
                                    <p class="text-xs text-slate-500 line-clamp-1 italic" title="{{ $item->message }}">{{ $item->message }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span> Đang chờ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Đã trả lời
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->reply_message)
                                        <div class="bg-green-50 p-3 rounded-xl border border-green-100">
                                            <p class="text-[10px] font-bold text-green-700 mb-1 uppercase tracking-wide">
                                                Admin • {{ \Carbon\Carbon::parse($item->replied_at)->format('d/m') }}
                                            </p>
                                            <p class="text-sm text-slate-700 leading-snug">{!! nl2br(e($item->reply_message)) !!}</p>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-sm italic">-- Chưa có phản hồi --</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $contacts->links() }}
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-3xl"></i>
                    </div>
                    <p class="text-slate-500 mb-4">Bạn chưa có yêu cầu hỗ trợ nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection