@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-hand-holding-medical me-2 text-primary"></i> Quản lý Dịch vụ
        </h3>
        <a href="{{ route('services.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm dịch vụ mới
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
            <form id="service-filters" method="GET" action="{{ route('services.index') }}">
                <div class="row g-2 align-items-center">
                    {{-- Ô Tìm kiếm --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                placeholder="Tìm kiếm theo tên dịch vụ..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    {{-- Các Dropdown Lọc --}}
                    <div class="col-md-7 col-lg-8">
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            {{-- Lọc theo Danh mục (Category) --}}
                            <select name="category_id" class="form-select w-auto">
                                <option value="" selected>Lọc theo Danh mục</option>
                                {{-- Giả định bạn có biến $categories chứa danh sách Category --}}
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            {{-- Lọc theo Chuyên khoa (Department) --}}
                            <select name="department_id" class="form-select w-auto">
                                <option value="" selected>Lọc theo Chuyên khoa</option>
                                {{-- Giả định bạn có biến $departments chứa danh sách Department --}}
                                @if(isset($departments))
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            {{-- Lọc theo Trạng thái --}}
                            <select name="status" class="form-select w-auto">
                                <option value="" selected>Lọc theo Trạng thái</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng Dịch vụ --}}
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="width: 150px;">Tên dịch vụ</th>
                            <th style="width: 80px;">Ảnh</th>
                            <th style="width: 120px;">Phí / TG</th>
                            <th>Danh mục / Chuyên khoa</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th class="text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr id="service-row-{{ $service->id }}">
                            <td>{{ $service->id }}</td>
                            <td class="fw-semibold">
                                {{ $service->name }}
                                <div class="text-muted small mt-1">
                                    {{ Str::limit($service->description, 30) }}
                                </div>
                            </td>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset('storage/'.$service->image) }}" alt="Ảnh dịch vụ" width="60" class="img-fluid rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-success">
                                    @if(!$service->fee || $service->fee == 0)
                                        Liên hệ
                                    @else
                                        {{ number_format($service->fee, 0, ',', '.') }} VNĐ
                                    @endif
                                </div>
                                <div class="small text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    @if($service->duration == 0 || $service->duration === null)
                                        Liên tục
                                    @else
                                        {{ $service->duration }} phút
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-info small"><i class="fas fa-tags me-1"></i> {{ $service->category->name ?? 'N/A' }}</div>
                                <div class="text-secondary small"><i class="fas fa-hospital me-1"></i> {{ $service->department->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @if($service->status)
                                    <span class="badge bg-success py-2 px-3"><i class="fas fa-check-circle"></i> Active</span>
                                @else
                                    <span class="badge bg-secondary py-2 px-3"><i class="fas fa-minus-circle"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/admin/services/{{ $service->id }}/show" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-warning me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger delete-service-btn" 
                                        data-id="{{ $service->id }}" 
                                        data-name="{{ $service->name }}"
                                        title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                {{-- Form cho việc xóa (dùng cho AJAX) --}}
                                <form id="delete-form-{{ $service->id }}" action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-exclamation-circle me-2"></i> Không tìm thấy dịch vụ nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer - Phân trang --}}
        @if($services->lastPage() > 1)
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị {{ $services->firstItem() }} đến {{ $services->lastItem() }} trong tổng số {{ $services->total() }} dịch vụ.
                </div>
                <div>
                    {{-- Phân trang Bootstrap 5 chuẩn, giữ lại các tham số lọc --}}
                    {{ $services->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal xác nhận xóa --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmationModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa dịch vụ **<span id="service-name-to-delete"></span>**? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    // 1. Tự động gửi form lọc khi thay đổi dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('service-filters');
        
        document.querySelectorAll('#service-filters select').forEach(function(select) {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        document.querySelector('#service-filters input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterForm.submit();
            }
        });
    });

    // 2. Xử lý Xóa bằng Modal & AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        let serviceIdToDelete = null;

        document.querySelectorAll('.delete-service-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                serviceIdToDelete = this.dataset.id;
                const serviceName = this.dataset.name;
                document.getElementById('service-name-to-delete').textContent = serviceName;
                modal.show();
            });
        });

        confirmDeleteButton.addEventListener('click', function() {
            if (serviceIdToDelete) {
                // Sử dụng form đã tạo sẵn để submit (Laravel's way)
                const deleteForm = document.getElementById(`delete-form-${serviceIdToDelete}`);
                
                // Gửi form
                fetch(deleteForm.action, {
                    method: 'POST',
                    body: new FormData(deleteForm),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Thường được Laravel sử dụng để nhận dạng AJAX
                    }
                })
                .then(response => {
                    modal.hide();
                    if (response.ok) {
                         // Xóa hàng khỏi bảng mà không cần tải lại trang
                        const row = document.getElementById(`service-row-${serviceIdToDelete}`);
                        if (row) {
                            row.remove();
                        }
                        // Hiển thị thông báo thành công (Có thể dùng thư viện toastr nếu cần)
                        alert('Xóa dịch vụ thành công!'); 
                        location.reload(); // Tải lại để cập nhật phân trang và tổng số mục
                    } else {
                        return response.json().then(err => {
                            alert('Lỗi khi xóa dịch vụ: ' + (err.message || 'Không rõ lỗi.'));
                        });
                    }
                })
                .catch(error => {
                    modal.hide();
                    alert('Đã xảy ra lỗi kết nối: ' + error);
                });
            }
        });
    });
</script>
@endpush
@endsection