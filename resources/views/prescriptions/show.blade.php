@extends('admin.master')

@section('title', 'Chi tiết đơn thuốc: ' . $prescription->code)

@section('body')
<div class="container-fluid mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-file-invoice me-2 text-primary"></i> 
            Chi tiết Đơn thuốc: 
            <span class="text-info">{{ $prescription->code }}</span>
        </h3>

        <div class="d-flex space-x-2">
            {{-- In PDF --}}
            @if(Route::has('prescriptions.pdf'))
            <a href="{{ route('prescriptions.pdf', $prescription->id) }}"
                class="btn btn-danger shadow-sm fw-bold me-2">
                <i class="fas fa-file-pdf me-1"></i> In PDF
            </a>
            @endif

            {{-- Sửa --}}
            <a href="{{ route('prescriptions.edit', $prescription->id) }}"
                class="btn btn-warning shadow-sm fw-bold">
                <i class="fas fa-edit me-1"></i> Sửa
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- THÔNG TIN CHUNG --}}
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i> Thông tin chung
                    </h5>
                </div>

                <div class="card-body p-4 small">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-user-md me-1"></i> Bác sĩ kê đơn:</strong>
                            <p class="mb-0 text-dark">{{ $prescription->doctor->name ?? '---' }}</p>
                        </div>

                        <div class="col-md-6">
                            <strong><i class="fas fa-hospital-user me-1"></i> Bệnh nhân:</strong>
                            <p class="mb-0 text-dark">{{ $prescription->patient->name ?? '---' }}</p>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt me-1"></i> Ngày kê:</strong>
                            <p class="mb-0 text-dark">{{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="col-md-6">
                            <strong><i class="fas fa-tag me-1"></i> Trạng thái:</strong>
                            @php
                                $badgeClass = $prescription->status === 'Đang kê' ? 'bg-warning text-dark' :
                                             ($prescription->status === 'Đã duyệt' ? 'bg-info' : 'bg-success');
                            @endphp
                            <span class="badge {{ $badgeClass }} fw-medium">
                                <i class="fas fa-circle me-1"></i> 
                                {{ $prescription->status }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <strong><i class="fas fa-notes-medical me-1"></i> Chẩn đoán:</strong>
                        <p class="text-dark bg-light p-2 border rounded">{{ $prescription->diagnosis ?? '---' }}</p>
                    </div>

                    <div>
                        <strong><i class="fas fa-comment-dots me-1"></i> Ghi chú:</strong>
                        <p class="text-dark bg-light p-2 border rounded">{{ $prescription->note ?? '---' }}</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- DANH SÁCH THUỐC --}}
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-prescription-bottle-alt me-2"></i> Danh sách thuốc
                    </h5>
                    <a href="{{ route('prescription_items.create', $prescription->id) }}"
                        class="btn btn-light btn-sm fw-bold shadow-sm">
                        <i class="fas fa-plus me-1"></i> Thêm Thuốc
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover table-striped align-middle small">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="p-2">Tên thuốc</th>
                                    <th class="p-2">Hàm lượng</th>
                                    <th class="p-2">Đơn vị</th>
                                    <th class="p-2">Số lượng</th>
                                    <th class="p-2">Liều dùng</th>
                                    <th class="p-2">Lần/ngày</th>
                                    <th class="p-2">Thời gian</th>
                                    <th class="p-2">Cách dùng</th>
                                    <th class="p-2">Thành tiền</th>
                                    <th class="p-2">Hành động</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($prescription->items as $item)
                                <tr class="text-center">
                                    <td class="p-2 text-start fw-medium">{{ $item->medicine_name }}</td>
                                    <td class="p-2">{{ $item->strength ?? '-' }}</td>
                                    <td class="p-2">{{ $item->unit ?? '-' }}</td>
                                    <td class="p-2 fw-bold text-success">{{ $item->quantity }}</td>
                                    <td class="p-2 text-start">{{ $item->dosage }}</td>
                                    <td class="p-2">{{ $item->times_per_day ?? '-' }}</td>
                                    <td class="p-2">{{ $item->duration ?? '-' }}</td>
                                    <td class="p-2 text-start">{{ $item->instruction ?? '-' }}</td>
                                    <td class="p-2 fw-bold text-danger">
                                        {{ $item->price ? number_format($item->price * $item->quantity) . ' đ' : '---' }}
                                    </td>
                                    <td class="p-2">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('prescription_items.edit', $item->id) }}"
                                                class="btn btn-sm btn-warning me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('prescription_items.destroy', $item->id) }}" 
                                                method="POST"
                                                onsubmit="return confirm('Xác nhận xóa {{ $item->medicine_name }}?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-3 text-muted">
                                        <i class="fas fa-info-circle me-2"></i> Chưa có thuốc nào.
                                        <a href="{{ route('prescription_items.create', $prescription->id) }}"
                                            class="text-primary fw-medium">Thêm ngay</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                            @if($prescription->items->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-end p-2 fs-6">TỔNG TIỀN:</th>
                                    <th class="p-2 fs-6 text-danger fw-bold">
                                        {{ number_format($prescription->items->sum(fn($i)=>$i->price * $i->quantity)) }} đ
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
