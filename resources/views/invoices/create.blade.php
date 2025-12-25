@extends('admin.master')
@section('title','Tạo hóa đơn mới')

@section('body')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Tạo Hóa Đơn Thủ Công</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Chọn Khách hàng <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Chọn bệnh nhân --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="unpaid">Chưa thanh toán</option>
                            <option value="paid">Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold">Tổng tiền (VNĐ)</label>
                        <input type="number" name="total_amount" id="grand_total" class="form-control fw-bold text-danger" value="200000" readonly>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered bg-light">
                        <thead>
                            <tr>
                                <th>Nội dung dịch vụ</th>
                                <th width="150">Đơn giá</th>
                                <th width="100">SL</th>
                                <th width="150" class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="items[0][description]" class="form-control" value="Phí khám & Tư vấn chuyên khoa" required>
                                </td>
                                <td>
                                    <input type="number" name="items[0][unit_price]" class="form-control price" value="200000" onchange="calcTotal()">
                                </td>
                                <td>
                                    <input type="number" name="items[0][quantity]" class="form-control qty" value="1" onchange="calcTotal()">
                                </td>
                                <td class="text-end fw-bold row-total pt-2">200.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success fw-bold px-4">Lưu Hóa Đơn</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function calcTotal() {
        let price = document.querySelector('.price').value;
        let qty = document.querySelector('.qty').value;
        let total = price * qty;
        document.querySelector('.row-total').innerText = new Intl.NumberFormat().format(total);
        document.getElementById('grand_total').value = total;
    }
</script>
@endsection