@extends('admin.master')
@section('title', 'Chi tiết Hóa đơn')

@section('body')
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold text-primary">
                <i class="fas fa-file-invoice me-2"></i> Hóa đơn #{{ $invoice->code }}
            </h4>
            <div>
                {{-- NÚT IN PDF --}}
                <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="btn btn-danger fw-bold shadow-sm">
                    <i class="fas fa-file-pdf me-1"></i> Xuất PDF
                </a>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary ms-2">Quay lại</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted fw-bold small">Khách hàng</h6>
                    <h5 class="fw-bold">{{ $invoice->user->name ?? 'Khách vãng lai' }}</h5>
                    <p class="mb-1"><i class="fas fa-phone-alt me-2 text-muted"></i> {{ $invoice->user->phone ?? '---' }}</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i> {{ $invoice->user->address ?? '---' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6 class="text-uppercase text-muted fw-bold small">Thông tin hóa đơn</h6>
                    <p class="mb-1">Ngày lập: <strong>{{ $invoice->created_at->format('d/m/Y H:i') }}</strong></p>
                    <p class="mb-1">Trạng thái: 
                        @if($invoice->status == 'paid')
                            <span class="badge bg-success">ĐÃ THANH TOÁN</span>
                        @elseif($invoice->status == 'refunded')
                            <span class="badge bg-danger">HOÀN TIỀN</span>
                        @else
                            <span class="badge bg-warning text-dark">CHƯA THANH TOÁN</span>
                        @endif
                    </p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Nội dung dịch vụ</th>
                        <th class="text-center" style="width: 100px;">SL</th>
                        <th class="text-end" style="width: 150px;">Đơn giá</th>
                        <th class="text-end" style="width: 150px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->price) }}</td>
                        <td class="text-end fw-bold">{{ number_format($item->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="text-end fw-bold text-danger fs-5">{{ number_format($invoice->total) }} đ</td>
                    </tr>
                </tfoot>
            </table>

            <hr>
            {{-- Form Cập nhật trạng thái --}}
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" class="mt-4 p-3 bg-light rounded">
                @csrf @method('PUT')
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="fw-bold mb-2">Cập nhật trạng thái:</label>
                        <select name="status" class="form-select">
                            <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="refunded" {{ $invoice->status == 'refunded' ? 'selected' : '' }}>Hoàn tiền</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold mb-2">Phương thức:</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash" {{ $invoice->payment_method == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                            <option value="bank" {{ $invoice->payment_method == 'bank' ? 'selected' : '' }}>Chuyển khoản</option>
                            <option value="momo" {{ $invoice->payment_method == 'momo' ? 'selected' : '' }}>Momo</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Lưu trạng thái</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection