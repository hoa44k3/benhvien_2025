@extends('admin.master')

@section('title', 'Quản lý bình luận')

{{-- CSS Tùy chỉnh làm đẹp giao diện --}}
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Avatar chữ cái đầu */
        .avatar-initial {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        
        /* Căn giữa nội dung bảng */
        .table td { vertical-align: middle; }
        
        /* Link bài viết */
        .post-link { 
            font-weight: 600; color: #4e73df; text-decoration: none; 
            transition: all 0.2s;
        }
        .post-link:hover { text-decoration: underline; color: #224abe; }
        
        /* Badges mềm mại (Soft Badges) */
        .badge-soft-success { background-color: #d1e7dd; color: #0f5132; padding: 6px 12px; border-radius: 20px; }
        .badge-soft-warning { background-color: #fff3cd; color: #664d03; padding: 6px 12px; border-radius: 20px; }
        .badge-soft-danger { background-color: #f8d7da; color: #842029; padding: 6px 12px; border-radius: 20px; }
        .badge-soft-secondary { background-color: #e2e3e5; color: #41464b; padding: 6px 12px; border-radius: 20px; }

        /* Hiệu ứng dòng bảng */
        tbody tr { transition: background-color 0.2s; }
        tbody tr:hover { background-color: #f8f9fc; }
        
        /* Highlight dòng chưa duyệt */
        .row-pending { background-color: #fffdf0 !important; border-left: 4px solid #ffc107; }
    </style>
@endsection

@section('body')
<div class="container-fluid py-4">
    
    {{-- Header Card --}}
    <div class="card shadow mb-4 border-0 rounded-3">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-comments me-2"></i> Quản lý bình luận
            </h5>
            <button class="btn btn-light btn-sm shadow-sm border" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Tải lại
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <thead class="bg-light text-secondary text-uppercase small font-weight-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3" width="5%">#</th>
                            <th width="20%">Người gửi</th>
                            <th width="35%">Nội dung</th>
                            <th width="20%">Bài viết liên quan</th>
                            <th width="10%" class="text-center">Trạng thái</th>
                            <th width="10%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                        <tr id="comment-{{ $comment->id }}" class="border-bottom {{ $comment->status == 'pending' ? 'row-pending' : '' }}">
                            
                            <td class="ps-4 fw-bold text-secondary">{{ $comment->id }}</td>
                            
                            {{-- Cột Người gửi --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-initial">
                                        {{ strtoupper(substr($comment->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">
                                            {{ $comment->name }}
                                            @if($comment->user_id) 
                                                <i class="fas fa-check-circle text-info small ms-1" title="Thành viên đã đăng ký"></i> 
                                            @endif
                                        </div>
                                        <small class="text-muted d-block">{{ $comment->email }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Cột Nội dung --}}
                            <td>
                                @if($comment->parent_id)
                                    <div class="text-muted small mb-1 fst-italic bg-light d-inline-block px-2 rounded border">
                                        <i class="fas fa-reply fa-rotate-180 me-1"></i> Trả lời: 
                                        <strong>{{ $comment->parent->name ?? 'Người dùng cũ' }}</strong>
                                    </div>
                                @endif

                                <div class="text-dark mb-1" style="font-size: 0.95rem;">
                                    {{ Str::limit($comment->content, 80) }}
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>{{ $comment->created_at->format('d/m/Y H:i') }}
                                </small>
                            </td>

                            {{-- Cột Bài viết --}}
                            <td>
                                @if($comment->post)
                                    <a href="{{ route('site.postshow', $comment->post->id) }}" target="_blank" class="post-link small d-flex align-items-center">
                                        <i class="far fa-file-alt me-2 text-secondary"></i>
                                        <span class="text-truncate" style="max-width: 180px;">{{ $comment->post->title }}</span>
                                        <i class="fas fa-external-link-alt ms-1 text-xs opacity-50"></i>
                                    </a>
                                @else
                                    <span class="badge badge-soft-secondary">Bài viết đã xóa</span>
                                @endif
                            </td>

                            {{-- Cột Trạng thái & Toggle --}}
                            <td class="text-center">
                                <div class="d-flex flex-col align-items-center gap-2">
                                    {{-- Nút duyệt --}}
                                    @if($comment->status == 'pending')
                                        <form method="POST" action="{{ route('comments.approve', $comment->id) }}">
                                            @csrf
                                            <button class="btn btn-warning btn-sm fw-bold shadow-sm text-dark" style="font-size: 0.75rem;">
                                                <i class="fas fa-check me-1"></i> Duyệt ngay
                                            </button>
                                        </form>
                                    @elseif($comment->status == 'approved')
                                        <span class="badge badge-soft-success"><i class="fas fa-check me-1"></i> Đã duyệt</span>
                                    @else
                                        <span class="badge badge-soft-danger">Spam</span>
                                    @endif

                                    {{-- Toggle Ẩn/Hiện --}}
                                    <form method="POST" action="{{ route('comments.toggle', $comment->id) }}">
                                        @csrf
                                        <button class="btn btn-sm border-0 bg-transparent p-0" title="Bấm để Ẩn/Hiện">
                                            @if($comment->is_visible)
                                                <i class="fas fa-toggle-on text-success fa-2x"></i>
                                            @else
                                                <i class="fas fa-toggle-off text-secondary fa-2x opacity-50"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>

                            {{-- Cột Hành động --}}
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    {{-- Xóa --}}
                                    <button class="btn btn-white border text-danger btn-sm btn-delete hover-bg-light" 
                                            data-url="{{ route('comments.destroy', $comment->id) }}" 
                                            title="Xóa vĩnh viễn">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="60" class="mb-3 opacity-25">
                                <p class="text-muted">Chưa có bình luận nào.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Footer Phân trang --}}
        @if($comments->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-end">
            {{-- 🔥 QUAN TRỌNG: Sửa lỗi mũi tên khổng lồ bằng cách dùng view Bootstrap --}}
            {{ $comments->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Script xử lý xóa (SweetAlert2) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script>
    $(document).ready(function() {
        $('.btn-delete').click(function (e) {
            e.preventDefault();
            let url = $(this).data('url'); 
            let row = $(this).closest('tr');

            Swal.fire({
                title: 'Xóa bình luận này?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa ngay',
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
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Đã xóa thành công'
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