@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 fw-bold text-dark">
        <i class="fas fa-history me-2 text-primary"></i> Nhật ký Hệ thống (Audit Log)
    </h3>
    <hr>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Tìm kiếm hành động, đối tượng hoặc IP...">
                    </div>
                </div>
                
                <div class="col-md-7">
                    <div class="d-flex justify-content-end gap-2">
                        <select class="form-select w-auto">
                            <option selected>Lọc theo Người dùng</option>
                            {{-- Thêm logic lọc theo user tại đây --}}
                        </select>
                        <select class="form-select w-auto">
                            <option selected>Lọc theo Hành động</option>
                            <option value="Tạo">Tạo</option>
                            <option value="Cập nhật">Cập nhật</option>
                            <option value="Xóa">Xóa</option>
                            <option value="Đăng nhập">Đăng nhập</option>
                        </select>
                        <select class="form-select w-auto">
                            <option selected>Lọc theo Trạng thái</option>
                            <option value="Thành công">Thành công</option>
                            <option value="Thất bại">Thất bại</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                    <i class="fas fa-user-circle me-1 text-info"></i> 
                                    <span class="fw-semibold">{{ $log->user?->name ?? 'HỆ THỐNG' }}</span>
                                </td>
                                
                                {{-- Cột Hành động --}}
                                <td>
                                    <span class="badge 
                                        @if(str_contains($log->action, 'Tạo') || str_contains($log->action, 'Thêm')) bg-success 
                                        @elseif(str_contains($log->action, 'Cập nhật') || str_contains($log->action, 'Sửa')) bg-warning text-dark
                                        @elseif(str_contains($log->action, 'Xóa')) bg-danger
                                        @else bg-secondary @endif
                                        py-2 px-3">
                                        {{ $log->action }}
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
                                    <span class="badge py-2 px-3 
                                        {{ $log->status == 'Thành công' ? 'bg-success' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $log->status == 'Thành công' ? 'check' : 'times' }}"></i>
                                        {{ $log->status }}
                                    </span>
                                </td>

                                {{-- Cột IP --}}
                                <td>
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i> 
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

    <div class="d-flex justify-content-center mt-4">
        {{ $logs->links() }}
    </div>

</div>
@endsection