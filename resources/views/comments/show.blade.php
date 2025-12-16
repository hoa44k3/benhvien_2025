@extends('admin.master')
@section('title','Chi tiết bình luận')

@section('body')
<div class="container-fluid">
    <a href="{{ route('comments.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Quay lại</a>

    {{-- 1. BÌNH LUẬN GỐC --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Bình luận gốc</h6>
        </div>
        <div class="card-body">
            <h5 class="font-weight-bold">Bài viết: <a href="{{ route('site.postshow', $comment->post->id) }}" target="_blank">{{ $comment->post->title }}</a></h5>
            
            <div class="media border p-3 rounded bg-light mt-3">
                <div class="mr-3">
                    <div class="btn btn-circle btn-primary font-weight-bold" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($comment->name, 0, 1)) }}
                    </div>
                </div>
                <div class="media-body">
                    <h5 class="mt-0 font-weight-bold">{{ $comment->name }} <small class="text-muted">({{ $comment->email }})</small></h5>
                    <p class="lead mb-1">{{ $comment->content }}</p>
                    <small class="text-muted"><i class="far fa-clock"></i> {{ $comment->created_at->format('d/m/Y H:i') }}</small>
                    
                    <div class="mt-2">
                        @if($comment->status == 'pending') <span class="badge badge-warning">Chờ duyệt</span>
                        @elseif($comment->status == 'approved') <span class="badge badge-success">Đã duyệt</span>
                        @endif
                        
                        @if(!$comment->is_visible) <span class="badge badge-secondary">Đang ẩn</span> @endif
                    </div>
                </div>
            </div>

            {{-- FORM TRẢ LỜI CỦA ADMIN --}}
            <div class="mt-4 p-3 bg-gray-100 rounded">
                <h6 class="font-weight-bold text-dark">Trả lời bình luận này (với tư cách Admin):</h6>
                <form action="{{ route('comments.reply', $comment->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control" rows="2" placeholder="Nhập nội dung trả lời..." required></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Gửi trả lời</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. DANH SÁCH CÁC CÂU TRẢ LỜI (Fix lỗi view not found) --}}
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Các câu trả lời liên quan ({{ $comment->replies->count() }})</h6>
        </div>
        <div class="card-body">
            @forelse($comment->replies as $reply)
                <div class="media mb-3 border-bottom pb-3">
                    <div class="mr-3">
                        <div class="btn btn-circle btn-info btn-sm" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                            {{ strtoupper(substr($reply->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="mt-0 font-weight-bold">
                            {{ $reply->name }}
                            @if($reply->name == 'Admin') <span class="badge badge-primary">Admin</span> @endif
                            <small class="text-muted ml-2">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                        </h6>
                        <p class="mb-1">{{ $reply->content }}</p>
                        
                        {{-- Hiển thị các câu trả lời con (Cấp 3) nếu có --}}
                        @if($reply->replies->count() > 0)
                            <div class="mt-2 ml-4 pl-3 border-left" style="border-left: 3px solid #e3e6f0;">
                                @foreach($reply->replies as $subReply)
                                    <div class="media mt-2">
                                        <div class="media-body">
                                            <strong>{{ $subReply->name }}:</strong> {{ $subReply->content }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">Chưa có câu trả lời nào.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection