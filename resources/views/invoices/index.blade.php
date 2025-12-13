@extends('admin.master')

@section('title', 'Danh sách hóa đơn')

@section('body')
<div class="container-fluid mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-file-invoice-dollar me-2 text-primary"></i> Danh sách Hóa đơn
        </h3>
        {{-- <a href="{{ route('invoices.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="fas fa-plus me-1"></i> Tạo hóa đơn mới
        </a> --}}
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3 ps-3">Mã HĐ</th>
                            <th class="py-3">Bệnh nhân</th>
                            <th class="py-3">Hồ sơ / Lý do</th>
                            <th class="py-3 text-end">Tổng tiền</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3">Ngày tạo</th>
                            <th class="py-3 text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td class="ps-3 fw-bold text-primary">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-decoration-none">
                                    {{ $invoice->code }}
                                </a>
                            </td>
                            <td>
                                {{-- Lấy tên bệnh nhân từ quan hệ user (patient) --}}
                                <div class="fw-bold text-dark">{{ $invoice->patient->name ?? 'Khách vãng lai' }}</div>
                                <small class="text-muted">{{ $invoice->patient->phone ?? '' }}</small>
                            </td>
                            <td>
                                @if($invoice->medicalRecord)
                                    <span class="badge bg-info text-dark" title="Hồ sơ bệnh án">
                                        <i class="fas fa-notes-medical me-1"></i> {{ Str::limit($invoice->medicalRecord->title, 20) }}
                                    </span>
                                @else
                                    <span class="text-muted small fst-italic">Thu phí trực tiếp</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($invoice->total) }} đ
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-success',
                                        'unpaid' => 'bg-warning text-dark',
                                        'refunded' => 'bg-danger',
                                        'cancelled' => 'bg-secondary'
                                    ];
                                    $statusLabels = [
                                        'paid' => 'Đã thanh toán',
                                        'unpaid' => 'Chưa thanh toán',
                                        'refunded' => 'Hoàn tiền',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$invoice->status] ?? 'bg-secondary' }} rounded-pill px-3">
                                    {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $invoice->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        {{-- Dòng phụ: Hiển thị nhanh danh sách items (Optional) --}}
                        <tr>
                            <td colspan="7" class="bg-light p-2 ps-4 small border-bottom">
                                <i class="fas fa-list-ul me-2 text-muted"></i> 
                                <strong>Chi tiết:</strong>
                                @foreach($invoice->items as $item)
                                    <span class="text-muted me-3">
                                        • {{ $item->item_name }} (x{{ $item->quantity }})
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-file-invoice fa-3x mb-3 opacity-50"></i>
                                <p>Chưa có hóa đơn nào được tạo.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Phân trang --}}
        @if($invoices->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection