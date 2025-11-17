@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 fw-bold text-dark">
        <i class="fas fa-history me-2 text-primary"></i> Nhật ký Hệ thống (Audit Log)
    </h3>
    <hr>

    {{-- Thẻ Lọc & Tìm kiếm --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form id="audit-log-filters" method="GET">
                <div class="row g-3 align-items-center">
                    {{-- Ô Tìm kiếm --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                placeholder="Tìm kiếm hành động, đối tượng hoặc IP..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    {{-- Các Dropdown Lọc --}}
                    <div class="col-md-7 col-lg-8">
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            
                            {{-- Lọc theo Người dùng --}}
                            <select name="user_id" class="form-select w-auto">
                                <option value="" selected>Lọc theo Người dùng</option>
                                {{-- Thay thế bằng vòng lặp thực tế: foreach($users as $user) --}}
                                @isset($users)
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="SYSTEM" {{ request('user_id') == 'SYSTEM' ? 'selected' : '' }}>HỆ THỐNG</option>
                                    <option value="ADMIN" {{ request('user_id') == 'ADMIN' ? 'selected' : '' }}>Administrator</option>
                                @endisset
                            </select>
                            
                            {{-- Lọc theo Hành động --}}
                            <select name="action" class="form-select w-auto">
                                <option value="" selected>Lọc theo Hành động</option>
                                @php
                                    $actions = ['Tạo', 'Cập nhật', 'Xóa', 'Đăng nhập', 'Đăng xuất'];
                                @endphp
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                                @endforeach
                            </select>
                            
                            {{-- Lọc theo Trạng thái --}}
                            <select name="status" class="form-select w-auto">
                                <option value="" selected>Lọc theo Trạng thái</option>
                                <option value="Thành công" {{ request('status') == 'Thành công' ? 'selected' : '' }}>Thành công</option>
                                <option value="Thất bại" {{ request('status') == 'Thất bại' ? 'selected' : '' }}>Thất bại</option>
                            </select>

                            {{-- Nút Áp dụng bộ lọc (Tùy chọn) --}}
                            {{-- <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Lọc</button> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng Nhật ký --}}
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 150px;">Thời gian</th>
                            <th style="width: 180px;">Người dùng</th>
                            <th style="width: 150px;">Hành động</th>
                            <th>Đối tượng (Mô tả)</th>
                            <th class="text-center" style="width: 100px;">Trạng thái</th>
                            <th style="width: 120px;">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="log-row">
                                {{-- Cột Thời gian --}}
                                <td>
                                    <div class="fw-bold">{{ $log->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}</div>
                                    <small class="text-muted">{{ $log->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</small>
                                </td>

                                {{-- Cột Người dùng --}}
                                <td>
                                    @if($log->user)
                                        <i class="fas fa-user-circle me-1 text-info"></i> 
                                        <span class="fw-semibold">{{ $log->user->name }}</span>
                                    @else
                                        <i class="fas fa-server me-1 text-secondary"></i> 
                                        <span class="fw-semibold text-muted">HỆ THỐNG</span>
                                    @endif
                                </td>
                                
                                {{-- Cột Hành động --}}
                                <td>
                                    @php
                                        $action = $log->action;
                                        $badge_class = 'bg-secondary';
                                        if (str_contains($action, 'Tạo') || str_contains($action, 'Thêm')) {
                                            $badge_class = 'bg-success';
                                        } elseif (str_contains($action, 'Cập nhật') || str_contains($action, 'Sửa')) {
                                            $badge_class = 'bg-warning text-dark';
                                        } elseif (str_contains($action, 'Xóa')) {
                                            $badge_class = 'bg-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $badge_class }} py-2 px-3 fw-normal">
                                        {{ $action }}
                                    </span>
                                </td>
                                
                                {{-- Cột Đối tượng (Target) --}}
                                <td>
                                    <span class="text-truncate d-block" style="max-width: 350px;">
                                        <i class="fas fa-cube me-1 text-secondary"></i> {{ $log->target }}
                                    </span>
                                </td>

                                {{-- Cột Trạng thái --}}
                                <td class="text-center">
                                    @if($log->status == 'Thành công')
                                        <span class="badge py-2 px-3 bg-success">
                                            <i class="fas fa-check me-1"></i> Thành công
                                        </span>
                                    @else
                                        <span class="badge py-2 px-3 bg-danger">
                                            <i class="fas fa-times me-1"></i> Thất bại
                                        </span>
                                    @endif
                                </td>

                                {{-- Cột IP --}}
                                <td>
                                    <i class="fas fa-globe me-1 text-muted"></i> 
                                    {{ $log->ip_address }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-clipboard-list me-2"></i> Không tìm thấy nhật ký hệ thống nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Phân trang Bootstrap chuẩn --}}
    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
        <div class="text-muted">
            Hiển thị {{ $logs->firstItem() }} đến {{ $logs->lastItem() }} trong tổng số {{ $logs->total() }} kết quả.
        </div>
        <div>
            {{-- Sử dụng phân trang chuẩn của Laravel/Bootstrap, loại bỏ các nút lớn không cần thiết --}}
            {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

{{-- Script để tự động gửi form khi thay đổi bộ lọc --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lắng nghe sự kiện thay đổi trên tất cả các thẻ select trong thẻ form
        document.querySelectorAll('#audit-log-filters select').forEach(function(select) {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // Tùy chọn: Lắng nghe sự kiện "Enter" trên ô tìm kiếm
        document.querySelector('#audit-log-filters input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush
@endsection