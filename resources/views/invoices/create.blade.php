@extends('admin.master')
@section('title','Tạo hóa đơn mới')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tạo hóa đơn mới</h1>

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Bệnh án</label>
            <select name="medical_record_id" class="form-select" required>
                @foreach(App\Models\MedicalRecord::all() as $record)
                    <option value="{{ $record->id }}">{{ $record->title }} - {{ $record->user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tổng tiền</label>
            <input type="number" name="total_amount" class="form-control" required>
        </div>

        <h3 class="mt-4 mb-2">Items</h3>
        <div id="items-container">
            <div class="item-row mb-2">
                <input type="text" name="items[0][description]" placeholder="Mô tả" class="form-control mb-1" required>
                <input type="number" name="items[0][quantity]" placeholder="Số lượng" class="form-control mb-1" value="1" required>
                <input type="number" name="items[0][unit_price]" placeholder="Đơn giá" class="form-control mb-1" required>
                <select name="items[0][item_type]" class="form-select mb-1">
                    <option value="service">Dịch vụ</option>
                    <option value="medicine">Thuốc</option>
                    <option value="package">Gói</option>
                    <option value="other">Khác</option>
                </select>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-item-btn">Thêm item</button>
        <br>

        <button type="submit" class="btn btn-primary">Lưu hóa đơn</button>
    </form>
</div>

<script>
let itemIndex = 1;
document.getElementById('add-item-btn').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const div = document.createElement('div');
    div.classList.add('item-row', 'mb-2');
    div.innerHTML = `
        <input type="text" name="items[${itemIndex}][description]" placeholder="Mô tả" class="form-control mb-1" required>
        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Số lượng" class="form-control mb-1" value="1" required>
        <input type="number" name="items[${itemIndex}][unit_price]" placeholder="Đơn giá" class="form-control mb-1" required>
        <select name="items[${itemIndex}][item_type]" class="form-select mb-1">
            <option value="service">Dịch vụ</option>
            <option value="medicine">Thuốc</option>
            <option value="package">Gói</option>
            <option value="other">Khác</option>
        </select>
    `;
    container.appendChild(div);
    itemIndex++;
});
</script>
@endsection
