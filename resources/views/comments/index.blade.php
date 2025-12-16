@extends('admin.master')

@section('title','Quản lý bình luận')

{{-- Đồng bộ CSS và thư viện SweetAlert2 với trang Bài viết --}}
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .avatar-initial {
            width: 35px; height: 35px;
            background-color: #f0f2f5; color: #5e6c84;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; margin-right: 10px;
        }
        .table td { vertical-align: middle; }
        .post-link { font-weight: 500; color: #495057; text-decoration: none; }
        .post-link:hover { color: #0d6efd; text-decoration: underline; }
        
        /* Badge mềm mại (Soft Badges) giống trang bài viết */
        .badge-soft-success { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
        .badge-soft-warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .badge-soft-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .badge-soft-secondary { background-color: rgba(108, 117, 125, 0.1); color: #6c757d; }
    </style>
@endsection

@section('body')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-comments me-2"></i>Danh sách bình luận</h5>
        {{-- Nút làm mới hoặc bộ lọc có thể đặt ở đây --}}
        <button class="btn btn-light btn-sm border" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Tải lại
        </button>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4" width="5%">#</th>
                        <th width="20%">Người gửi</th>
                        <th width="30%">Nội dung</th>
                        <th width="20%">Bài viết</th>
                        <th width="10%" class="text-center">Trạng thái</th>
                        <th width="5%" class="text-center">Hiển thị</th>
                        <th width="10%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
@forelse($comments as $comment)
<tr id="comment-{{ $comment->id }}" class="{{ $comment->status == 'pending' ? 'table-warning' : '' }}">
    {{-- Tô màu vàng nhạt nếu đang chờ duyệt để dễ thấy --}}
    
    <td class="ps-4 fw-bold text-muted">{{ $comment->id }}</td>
    
    {{-- Cột Người gửi --}}
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar-initial">
                {{ strtoupper(substr($comment->name, 0, 1)) }}
            </div>
            <div>
                <div class="fw-bold text-dark small">
                    {{ $comment->name }}
                    @if($comment->user_id) <i class="fas fa-check-circle text-primary small" title="Thành viên"></i> @endif
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">{{ $comment->email }}</small>
            </div>
        </div>
    </td>

    {{-- Cột Nội dung (CÓ SỬA ĐỔI) --}}
    <td>
        {{-- Nếu có parent_id => Đây là câu trả lời --}}
        @if($comment->parent_id)
            <div class="text-muted small mb-1 fst-italic">
                <i class="fas fa-reply fa-rotate-180 me-1"></i> Trả lời cho: 
                <strong>{{ $comment->parent->name ?? 'Người dùng cũ' }}</strong>
            </div>
        @endif

        <div class="text-dark small mb-1" title="{{ $comment->content }}">
            {{ Str::limit($comment->content, 60) }}
        </div>
        <small class="text-muted">
            <i class="far fa-clock me-1"></i>{{ $comment->created_at->format('d/m/Y H:i') }}
        </small>
    </td>

    {{-- Cột Bài viết --}}
    <td>
        @if($comment->post)
            <a href="{{ route('site.postshow', $comment->post->id) }}" target="_blank" class="post-link small">
                <i class="far fa-file-alt me-1"></i>{{ Str::limit($comment->post->title, 25) }}
            </a>
        @else
            <span class="badge badge-soft-secondary">Bài viết đã xóa</span>
        @endif
    </td>

    {{-- Cột Trạng thái (Duyệt) --}}
    <td class="text-center">
        @if($comment->status == 'pending')
            <form method="POST" action="{{ route('comments.approve', $comment->id) }}">
                @csrf
                <button class="btn btn-sm btn-warning py-0 px-2 small font-weight-bold shadow-sm" title="Bấm để duyệt ngay">
                    <i class="fas fa-check me-1"></i>Duyệt
                </button>
            </form>
        @elseif($comment->status == 'approved')
            <span class="badge badge-soft-success">Đã duyệt</span>
        @else
            <span class="badge badge-soft-danger">Spam</span>
        @endif
    </td>

    {{-- Cột Ẩn/Hiện --}}
    <td class="text-center">
        <form method="POST" action="{{ route('comments.toggle', $comment->id) }}">
            @csrf
            <button class="btn btn-sm border-0 text-secondary">
                @if($comment->is_visible)
                    <i class="fas fa-toggle-on text-success fa-lg"></i>
                @else
                    <i class="fas fa-toggle-off text-muted fa-lg"></i>
                @endif
            </button>
        </form>
    </td>

    {{-- Cột Hành động --}}
    <td class="text-center">
        <div class="btn-group" role="group">
            <a href="{{ route('comments.show', $comment->id) }}" class="btn btn-light text-info btn-sm" title="Chi tiết / Trả lời">
                <i class="fas fa-comment-dots"></i>
            </a>
            {{-- Nút xóa (Code của bạn đã đúng, giữ nguyên) --}}
            <button class="btn btn-light text-danger btn-sm btn-delete" 
                    data-url="{{ route('comments.destroy', $comment->id) }}" 
                    title="Xóa vĩnh viễn">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5 text-muted">Không có dữ liệu.</td>
</tr>
@endforelse
</tbody>
            </table>
        </div>
    </div>
    
    @if($comments->hasPages())
    <div class="card-footer bg-white d-flex justify-content-end">
        {{ $comments->links() }}
    </div>
    @endif
</div>

{{-- Script xóa đồng bộ với trang Bài Viết --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Sử dụng class chung btn-delete để bắt sự kiện
        $('.btn-delete').click(function (e) {
            e.preventDefault();
            let url = $(this).data('url'); // Lấy link xóa từ attribute data-url
            let row = $(this).closest('tr');

            Swal.fire({
                title: 'Xóa bình luận này?',
                text: "Bạn sẽ không thể khôi phục lại!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Vâng, xóa đi!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            row.fadeOut(300, function(){ $(this).remove(); });
                            
                            // Toast thông báo nhỏ góc trên
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Đã xóa bình luận thành công'
                            });
                        },
                        error: function(err) {
                            Swal.fire('Lỗi!', 'Không thể xóa. Vui lòng thử lại.', 'error');
                        }
                    });
                }
            })
        });
    });
</script>
@endsection