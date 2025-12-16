@extends('admin.master')

@section('title','Quản lý bài viết')

{{-- Thêm CSS và thư viện SweetAlert2 --}}
@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .avatar-initial {
            width: 35px; height: 35px;
            background-color: #e9ecef; color: #495057;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-weight: bold; font-size: 14px; margin-right: 10px;
        }
        .table td { vertical-align: middle; }
        .post-title { font-weight: 600; color: #2c3e50; text-decoration: none; display: block;}
        .post-title:hover { color: #007bff; }
        .badge-soft-success { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
        .badge-soft-secondary { background-color: rgba(108, 117, 125, 0.1); color: #6c757d; }
    </style>
@endsection

@section('body')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-newspaper me-2"></i>Danh sách bài viết</h5>
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Thêm bài viết
        </a>
    </div>

    <div class="card-body p-0">
        {{-- Thông báo Flash (nếu có) --}}
        @if(session('success'))
            <div class="alert alert-success m-3 alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4" width="5%">#</th>
                        <th width="30%">Tiêu đề</th>
                        <th width="20%">Người đăng</th>
                        <th width="10%" class="text-center">Nổi bật</th>
                        <th width="10%" class="text-center">Views</th>
                        <th width="10%" class="text-center">Trạng thái</th>
                        <th width="15%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($posts as $key => $post)
                    <tr id="post-{{ $post->id }}">
                        <td class="ps-4 fw-bold text-muted">{{ $key + 1 }}</td>
                        
                        {{-- Cột Tiêu đề --}}
                        <td>
                            <a href="{{ route('posts.show', $post->id) }}" class="post-title" title="{{ $post->title }}">
                                {{ Str::limit($post->title, 50) }}
                            </a>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ $post->created_at->format('d/m/Y') }}</small>
                        </td>

                        {{-- Cột Người đăng (Có Avatar) --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial">
                                    {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">{{ $post->user->name ?? 'Admin' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Cột Nổi bật --}}
                        <td class="text-center">
                            @if($post->is_featured)
                                <i class="fas fa-star text-warning" title="Bài viết nổi bật"></i>
                            @else
                                <i class="far fa-star text-muted" style="opacity: 0.3"></i>
                            @endif
                        </td>

                        {{-- Cột Views --}}
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">
                                <i class="far fa-eye me-1"></i>{{ $post->views }}
                            </span>
                        </td>

                        {{-- Cột Trạng thái --}}
                        <td class="text-center">
                            @if(strtolower($post->status) == 'published' || strtolower($post->status) == 'active') 
                                {{-- Giả sử status là active/published --}}
                                <span class="badge badge-soft-success px-2 py-1">Công khai</span>
                            @else
                                <span class="badge badge-soft-secondary px-2 py-1">Bản nháp</span>
                            @endif
                        </td>

                        {{-- Cột Hành động --}}
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-light text-info btn-sm" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-light text-warning btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Nút xóa gọi Ajax --}}
                                <button class="btn btn-light text-danger btn-sm btn-delete" 
                                        data-id="{{ $post->id }}" 
                                        data-url="{{ route('posts.destroy', $post->id) }}"
                                        title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="far fa-folder-open fa-3x mb-3"></i>
                            <p>Chưa có bài viết nào.</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-outline-primary btn-sm">Tạo bài viết mới</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Phân trang --}}
    @if($posts->hasPages())
    <div class="card-footer bg-white d-flex justify-content-end">
        {{ $posts->links() }}
    </div>
    @endif
</div>

{{-- Script xử lý Xóa bằng SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.btn-delete').click(function (e) {
            e.preventDefault();
            let url = $(this).data('url'); // Lấy URL từ data-url
            let row = $(this).closest('tr');

            Swal.fire({
                title: 'Xóa bài viết này?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Vâng, xóa nó!',
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
                            Swal.fire(
                                'Đã xóa!',
                                'Bài viết đã được xóa thành công.',
                                'success'
                            );
                        },
                        error: function(err) {
                            Swal.fire('Lỗi!', 'Không thể xóa bài viết này. Hãy thử lại.', 'error');
                            console.log(err);
                        }
                    });
                }
            })
        });
    });
</script>
@endsection