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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">#</th>
                            <th style="width: 100px;">Mã Đơn</th>
                            <th style="width: 150px;">Bác sĩ</th>
                            <th style="width: 150px;">Bệnh nhân</th>
                            <th style="width: 130px;">Hồ sơ</th>
                            <th style="width: 250px;">Chẩn đoán</th>
                            <th style="width: 200px;">Ghi chú</th>
                            <th class="text-center" style="width: 110px;">SL Thuốc</th>
                            <th class="text-center" style="width: 130px;">Tổng tiền</th>
                            <th class="text-center" style="width: 110px;">Trạng thái</th>
                            <th style="width: 150px;">Ngày tạo</th>
                            <th style="width: 150px;">Ngày cập nhật</th>
                            <th class="text-center" style="width: 140px;">Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($prescriptions as $p)
                        <tr>
                            <td class="text-center">{{ $prescriptions->firstItem() + $loop->index }}</td>

                            <td class="fw-bold text-primary">{{ $p->code }}</td>

                            <td>{{ $p->doctor->name ?? '---' }}</td>

                            <td>{{ $p->patient->name ?? '---' }}</td>

                            <td>
                                <span class="badge bg-secondary text-truncate d-inline-block" style="max-width: 110px;">
                                    {{ $p->medicalRecord->title ?? 'Chưa gán' }}
                                </span>
                            </td>

                            <td>{{ $p->diagnosis ? Str::limit($p->diagnosis, 60) : '-' }}</td>

                            <td>{{ $p->note ? Str::limit($p->note, 60) : '-' }}</td>

                            {{-- Số lượng thuốc --}}
                            <td class="text-center fw-bold">
                                {{ $p->items->count() ?? 0 }}
                            </td>

                            {{-- Tổng tiền --}}
               
                            <td class="text-center fw-bold text-danger">
                                {{ number_format($p->total_amount, 0, ',', '.') }} đ
                            </td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @php
                                    $statusClass = match($p->status) {
                                        'Đang kê' => 'bg-warning text-dark',
                                        'Đã duyệt' => 'bg-info',
                                        'Hoàn thành', 'Đã hoàn thành' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $p->status }}</span>
                            </td>

                            <td><small class="text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</small></td>

                            <td><small class="text-muted">{{ $p->updated_at->format('d/m/Y H:i') }}</small></td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('prescriptions.show', $p->id) }}" class="btn btn-sm btn-info text-white me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('prescriptions.edit', $p->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('prescriptions.destroy', $p->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Xác nhận xóa đơn thuốc {{ $p->code }}? Hành động này không thể hoàn tác.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i> Không có đơn thuốc.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($prescriptions->total() > $prescriptions->perPage())
            <div class="card-footer bg-light border-top pt-3">
                <div class="d-flex justify-content-between">
                    <div class="text-muted small">
                        Hiển thị {{ $prescriptions->firstItem() }}–{{ $prescriptions->lastItem() }}
                        / {{ $prescriptions->total() }} đơn thuốc.
                    </div>
                    <div>{{ $prescriptions->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    .table.small td, .table.small th {
        font-size: 0.84rem;
    }
    .text-truncate {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

@endsection
