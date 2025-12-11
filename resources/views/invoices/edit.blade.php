@extends('site.master')
@section('title','Chỉnh sửa hóa đơn')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Chỉnh sửa hóa đơn #{{ $invoice->id }}</h1>

    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Bệnh án</label>
            <select name="medical_record_id" class="form-select" required>
                @foreach(App\Models\MedicalRecord::all() as $record)
                    <option value="{{ $record->id }}" {{ $invoice->medical_record_id == $record->id ? 'selected' : '' }}>
                        {{ $record->title }} - {{ $record->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tổng tiền</label>
            <input type="number" name="total_amount" class="form-control" value="{{ $invoice->total_amount }}" required>
        </div>

        <h3 class="mt-4 mb-2">Items</h3>
        <div id="items-container">
            @foreach($invoice->items as $index => $item)
            <div class="item-row mb-2">
                <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" placeholder="Mô tả" class="form-control mb-1" required>
                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" placeholder="Số lượng" class="form-control mb-1" required>
                <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" placeholder="Đơn giá" class="form-control mb-1" required>
                <select name="items[{{ $index }}][item_type]" class="form-select mb-1">
                    <option value="service" {{ $item->item_type=='service' ? 'selected' : '' }}>Dịch vụ</option>
                    <option value="medicine" {{ $item->item_type=='medicine' ? 'selected' : '' }}>Thuốc</option>
                    <option value="package" {{ $item->item_type=='package' ? 'selected' : '' }}>Gói</option>
                    <option value="other" {{ $item->item_type=='other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-item-btn">Thêm item</button>
        <br>

        <button type="submit" class="btn btn-primary">Cập nhật hóa đơn</button>
    </form>
</div>

<script>
let itemIndex = {{ $invoice->items->count() }};
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
