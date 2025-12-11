@extends('admin.master')

@section('title', 'Sửa thuốc trong đơn')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-edit me-2 text-warning"></i> Chỉnh sửa Thuốc trong Đơn: <span class="text-info">{{ $item->prescription->code }}</span>
        </h3>
        {{-- Quay lại trang chi tiết đơn thuốc --}}
        <a href="{{ route('prescriptions.edit', $item->prescription_id) }}" class="btn btn-secondary shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i> Quay lại Đơn thuốc
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">

                    {{-- Form Chỉnh Sửa Thuốc --}}
                    <form method="POST" action="{{ route('prescription_items.update', $item->id) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Thông báo lỗi (Validation errors) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Lỗi nhập liệu!</strong> Vui lòng kiểm tra lại các trường đã điền.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Chọn thuốc từ kho --}}
                        <div class="mb-3">
                            <label for="medicine_select" class="form-label fw-bold">
                                <i class="fas fa-search me-1"></i> Thay đổi thuốc (Nếu cần)
                            </label>
                            <select id="medicine_select" name="medicine_id" class="form-select">
                                <option value="">-- Chọn thuốc --</option>
                                @foreach($medicines as $m)
                                    <option value="{{ $m->id }}"
                                        data-name="{{ $m->name }}"
                                        data-price="{{ $m->price }}"
                                        {{ old('medicine_id', $item->medicine_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->code }} - {{ $m->name }} ({{ number_format($m->price) }} đ)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Việc chọn thuốc mới sẽ tự động cập nhật Tên thuốc và Giá bán.</small>
                        </div>

                        {{-- Tên thuốc --}}
                        <div class="mb-3">
                            <label for="medicine_name" class="form-label fw-bold">Tên thuốc <span class="text-danger">*</span></label>
                            <input type="text" id="medicine_name" name="medicine_name"
                                class="form-control @error('medicine_name') is-invalid @enderror"
                                value="{{ old('medicine_name', $item->medicine_name) }}" required placeholder="Tên thuốc">
                            @error('medicine_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Giá bán và Số lượng (Hàng ngang) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-bold">Giá bán (Đơn vị)</label>
                                <input type="number" id="price" name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $item->price) }}" placeholder="Giá bán/đơn vị">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label fw-bold">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" id="quantity" name="quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity', $item->quantity) }}" required min="1" placeholder="Tổng số lượng cần phát">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Liều dùng và Tần suất (Hàng ngang) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="dosage" class="form-label fw-bold">Liều dùng</label>
                                <input type="text" name="dosage" id="dosage"
                                    class="form-control @error('dosage') is-invalid @enderror"
                                    value="{{ old('dosage', $item->dosage) }}" placeholder="VD: 1 viên/lần">
                                @error('dosage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="frequency" class="form-label fw-bold">Số lần dùng/ngày</label>
                                <input type="text" name="frequency" id="frequency"
                                    class="form-control @error('frequency') is-invalid @enderror"
                                    value="{{ old('frequency', $item->frequency) }}" placeholder="VD: 2 lần/ngày">
                                @error('frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Thời gian sử dụng --}}
                        <div class="mb-3">
                            <label for="duration" class="form-label fw-bold">Thời gian dùng</label>
                            <input type="text" name="duration" id="duration"
                                class="form-control @error('duration') is-invalid @enderror"
                                value="{{ old('duration', $item->duration) }}" placeholder="VD: 5 ngày, 7 buổi, 3 tuần">
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Hướng dẫn sử dụng / Ghi chú --}}
                        <div class="mb-4">
                            <label for="instruction" class="form-label fw-bold">Hướng dẫn sử dụng / Ghi chú</label>
                            <textarea name="instruction" id="instruction" rows="3"
                                class="form-control @error('instruction') is-invalid @enderror"
                                placeholder="Ghi chú chi tiết cách dùng thuốc (trước/sau ăn, chống chỉ định...)">{{ old('instruction', $item->instruction) }}</textarea>
                            @error('instruction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('medicine_select').addEventListener('change', function(){
    const selectElement = this;
    const opt = selectElement.options[selectElement.selectedIndex];

    const medicineNameInput = document.getElementById('medicine_name');
    const priceInput = document.getElementById('price');

    if(!opt.value) {
        // Nếu chọn '-- Chọn thuốc --'
        // Không xóa mà để người dùng tự điền lại dựa trên giá trị cũ đã load
        return;
    }

    // Tự động điền giá trị từ data attributes
    medicineNameInput.value = opt.dataset.name || '';
    priceInput.value = opt.dataset.price || '';

    // Loại bỏ trạng thái lỗi nếu có
    if (medicineNameInput.classList.contains('is-invalid')) {
        medicineNameInput.classList.remove('is-invalid');
    }
    if (priceInput.classList.contains('is-invalid')) {
        priceInput.classList.remove('is-invalid');
    }
});
</script>

@endsection