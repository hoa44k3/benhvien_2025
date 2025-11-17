@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-cubes me-2 text-primary"></i> Danh sách Danh mục
        </h3>
        <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm danh mục mới
        </a>
    </div>

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
    @endif

    {{-- Thẻ Lọc & Tìm kiếm --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <form id="category-filters" method="GET" action="{{ route('categories.index') }}">
                <div class="row g-2 align-items-center">
                    {{-- Ô Tìm kiếm --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                placeholder="Tìm kiếm theo tên, mô tả..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    {{-- Dropdown Lọc theo Trạng thái --}}
                    <div class="col-md-7 col-lg-8">
                        <div class="d-flex justify-content-end gap-2">
                            <select name="status" class="form-select w-auto">
                                <option value="" selected>Lọc theo Trạng thái</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            {{-- Bạn có thể thêm nút submit nếu muốn, nhưng tôi sẽ dùng JS để tự động lọc --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng Danh sách --}}
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 200px;">Tên</th>
                            <th style="width: 100px;">Ảnh</th>
                            <th>Mô tả</th>
                            <th style="width: 120px;">Trạng thái</th>
                            <th class="text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td class="fw-semibold">{{ $cat->name }}</td>
                            <td>
                                @if($cat->image_path)
                                    <img src="{{ asset('storage/'.$cat->image_path) }}" alt="{{ $cat->name }}" width="60" class="img-fluid rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ Str::limit($cat->description, 80) }}
                            </td>
                            <td>
                                @if($cat->status)
                                    <span class="badge bg-success py-2 px-3"><i class="fas fa-check-circle me-1"></i> Active</span>
                                @else
                                    <span class="badge bg-secondary py-2 px-3"><i class="fas fa-minus-circle me-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-warning me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục {{ $cat->name }} không?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-exclamation-circle me-2"></i> Không tìm thấy danh mục nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Footer - Phân trang --}}
        @if($categories->lastPage() > 1)
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị {{ $categories->firstItem() }} đến {{ $categories->lastItem() }} trong tổng số {{ $categories->total() }} danh mục.
                </div>
                <div>
                    {{-- Phân trang Bootstrap 5 chuẩn, giữ lại các tham số lọc --}}
                    {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Script để tự động gửi form khi thay đổi bộ lọc --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('category-filters');
        
        // Tự động gửi form khi thay đổi trạng thái lọc
        filterForm.querySelector('select[name="status"]').addEventListener('change', function() {
            filterForm.submit();
        });

        // Tùy chọn: Gửi form khi nhấn Enter trong ô tìm kiếm
        filterForm.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Ngăn hành vi submit mặc định nếu có nhiều input
                filterForm.submit();
            }
        });
    });
</script>
@endpush
@endsection