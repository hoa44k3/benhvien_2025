@extends('admin.master')

@section('title', 'Quản lý đơn thuốc')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-file-medical me-2 text-primary"></i> Danh sách Đơn thuốc
        </h3>
        <a href="{{ route('prescriptions.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="fas fa-plus me-2"></i> Thêm Đơn thuốc Mới
        </a>
    </div>

    {{-- Thông báo success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Thông báo error nếu có --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover table-striped align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 30px;">#</th>
                            <th style="width: 100px;">Mã đơn</th>
                            <th style="width: 180px;">Bác sĩ</th>
                            <th style="width: 180px;">Bệnh nhân</th>
                            <th style="width: 120px;">Hồ sơ</th>
                            <th style="width: 250px;">Chẩn đoán</th>
                            <th>Ghi chú</th>
                            <th class="text-center" style="width: 120px;">Trạng thái</th>
                            <th style="width: 150px;">Ngày tạo</th>
                            <th class="text-center" style="width: 140px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $p)
                        <tr>
                            {{-- Số thứ tự --}}
                            <td class="text-center">
                                {{ $prescriptions->firstItem() + $loop->index }}
                            </td>

                            {{-- Mã đơn --}}
                            <td class="fw-bold">
                                <strong class="text-primary">{{ $p->code }}</strong>
                            </td>

                            {{-- Bác sĩ --}}
                            <td>
                                <span class="text-dark">{{ $p->doctor->name ?? '---' }}</span>
                            </td>

                            {{-- Bệnh nhân --}}
                            <td>
                                <span class="text-dark">{{ $p->patient->name ?? '---' }}</span>
                            </td>

                            {{-- Hồ sơ bệnh án --}}
                            <td>
                                <span class="badge bg-secondary text-truncate d-inline-block" style="max-width: 100px;">
                                    {{ $p->medicalRecord->title ?? 'Chưa gán' }}
                                </span>
                            </td>

                            {{-- Chẩn đoán --}}
                            <td>
                                <span class="text-muted">{{ $p->diagnosis ? Str::limit($p->diagnosis, 60) : '-' }}</span>
                            </td>

                            {{-- Ghi chú --}}
                            <td>
                                <span class="text-muted">{{ $p->note ? Str::limit($p->note, 60) : '-' }}</span>
                            </td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @php
                                    $badgeClass = '';
                                    $statusText = $p->status;
                                    $iconClass = '';
                                    if ($p->status == 'Đang kê') {
                                        $badgeClass = 'bg-warning text-dark';
                                        $iconClass = 'fas fa-pen';
                                    } elseif ($p->status == 'Đã duyệt') {
                                        $badgeClass = 'bg-info';
                                        $iconClass = 'fas fa-clipboard-check';
                                    } else { // Đã hoàn thành (Giả sử)
                                        $badgeClass = 'bg-success';
                                        $iconClass = 'fas fa-file-signature';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} fw-medium">
                                    <i class="{{ $iconClass }} me-1"></i> {{ $statusText }}
                                </span>
                            </td>

                            {{-- Ngày tạo --}}
                            <td>
                                <small class="text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</small>
                            </td>

                            {{-- Hành động --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('prescriptions.show', $p->id) }}" class="btn btn-sm btn-info text-white me-1" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('prescriptions.edit', $p->id) }}" class="btn btn-sm btn-warning me-1" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('prescriptions.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa đơn thuốc {{ $p->code }}? Hành động này không thể hoàn tác.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-2"></i> Không tìm thấy dữ liệu đơn thuốc nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Card - Phân trang --}}
        @if($prescriptions->total() > $prescriptions->perPage())
            <div class="card-footer bg-light border-top pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Hiển thị **{{ $prescriptions->firstItem() }}** đến **{{ $prescriptions->lastItem() }}** trong tổng số **{{ $prescriptions->total() }}** đơn thuốc.
                    </div>
                    {{-- Laravel Pagination Links --}}
                    <div>
                        {{ $prescriptions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Điều chỉnh font-size cho các ô để tăng mật độ thông tin */
    .table.small td, .table.small th {
        font-size: 0.85rem; 
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa; /* Light background on hover */
    }
    /* Đảm bảo text-truncate hoạt động tốt */
    .text-truncate {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
@endsection