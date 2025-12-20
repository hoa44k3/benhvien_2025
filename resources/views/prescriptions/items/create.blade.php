@extends('admin.master')

@section('title', 'Thêm thuốc vào đơn')

@section('body')
<div class="container-fluid mt-4">

    {{-- Tiêu đề trang --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-prescription-bottle-alt me-2 text-primary"></i>
            Thêm Thuốc vào Đơn: <span class="text-info">{{ $prescription->code }}</span>
        </h3>
        <a href="{{ route('prescriptions.edit', $prescription->id) }}"
           class="btn btn-outline-secondary shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i> Quay lại
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white fw-bold py-3 rounded-top-4">
                    <i class="fas fa-plus-circle me-2"></i> Thêm thuốc vào đơn thuốc
                </div>

                <div class="card-body p-4">

                    {{-- Hiển thị lỗi validate --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <strong>⚠ Lỗi nhập liệu!</strong> Vui lòng kiểm tra lại thông tin.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('prescription_items.store', $prescription->id) }}">
                        @csrf

                     {{-- CHỌN THUỐC TRONG KHO --}}
<div class="mb-3">
    <label for="medicine_select" class="form-label fw-bold">
        <i class="fas fa-pills me-1"></i> Chọn thuốc có sẵn
    </label>
    <select id="medicine_select" name="medicine_id" class="form-select shadow-sm">
        <option value="">— Chọn thuốc —</option>
        @foreach($medicines as $m)
           @php
    if ($m->expiry_date) {
        // QUAN TRỌNG: Thêm ->startOfDay() để reset giờ về 00:00:00
        $expiry = \Carbon\Carbon::parse($m->expiry_date)->startOfDay();
        $now = \Carbon\Carbon::now()->startOfDay();
        
        // Tính số ngày chẵn
        $daysLeft = $now->diffInDays($expiry, false); 
        
        $isExpired = $daysLeft < 0; 
        $isNearExpiry = $daysLeft >= 0 && $daysLeft <= 60; // Dưới 60 ngày

        $dateStr = $expiry->format('d/m/Y');
    } else {
        $daysLeft = 9999;
        $isExpired = false;
        $isNearExpiry = false;
        $dateStr = 'N/A';
    }

    // Hiển thị
    if ($isExpired) {
        $warningText = " (ĐÃ HẾT HẠN - $dateStr)";
        $styleClass = "text-danger fw-bold bg-danger-subtle"; 
        $status = 'expired';
    } elseif ($isNearExpiry) {
        // Số ngày giờ đây đã tròn trịa (Ví dụ: Còn 10 ngày)
        $warningText = " (CẬN DATE - Còn $daysLeft ngày)";
        $styleClass = "text-warning fw-bold bg-warning-subtle text-dark"; 
        $status = 'near_expiry';
    } else {
        $warningText = "";
        $styleClass = "";
        $status = 'ok';
    }
@endphp

            <option value="{{ $m->id }}"
                class="{{ $styleClass }}"
                data-name="{{ $m->name }}"
                data-price="{{ $m->price }}"
                data-status="{{ $status }}" {{-- Trạng thái: expired, near_expiry, ok --}}
                data-expiry-info="{{ $warningText }}">
                
                {{ $m->code }} — {{ $m->name }} {{ $warningText }}
            </option>
        @endforeach
    </select>
    
    {{-- ALERT: Hết hạn (Đỏ) --}}
    <div id="alert_expired" class="alert alert-danger mt-2 d-none fade show" role="alert">
        <i class="fas fa-ban me-2"></i> 
        <strong>KHÔNG ĐƯỢC KÊ:</strong> Thuốc này đã hết hạn sử dụng<span class="expiry-detail"></span>.
    </div>

    {{-- ALERT: Sắp hết hạn (Vàng) --}}
    <div id="alert_near" class="alert alert-warning mt-2 d-none fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> 
        <strong>CẢNH BÁO:</strong> Thuốc này sắp hết hạn<span class="expiry-detail"></span>. Cân nhắc kỹ thời gian dùng thuốc của bệnh nhân!
    </div>
    
    <small class="text-muted fst-italic">Hệ thống sẽ chặn kê thuốc hết hạn hoặc còn hạn dưới 60 ngày.</small>
</div>

                        {{-- TÊN THUỐC --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên thuốc <span class="text-danger">*</span></label>
                            <input type="text" id="medicine_name" name="medicine_name"
                                class="form-control shadow-sm @error('medicine_name') is-invalid @enderror"
                                value="{{ old('medicine_name') }}" required>
                            @error('medicine_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- GIÁ + SỐ LƯỢNG --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá bán</label>
                                <input type="number" id="price" name="price"
                                    class="form-control shadow-sm @error('price') is-invalid @enderror"
                                    value="{{ old('price') }}" placeholder="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" min="1" id="quantity" name="quantity"
                                    class="form-control shadow-sm @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LIỀU DÙNG --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Liều dùng</label>
                                <input type="text" name="dosage" id="dosage"
                                    class="form-control shadow-sm @error('dosage') is-invalid @enderror"
                                    value="{{ old('dosage') }}">
                                @error('dosage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số lần dùng/ngày</label>
                                <input type="text" name="frequency" id="frequency"
                                    class="form-control shadow-sm @error('frequency') is-invalid @enderror"
                                    value="{{ old('frequency') }}">
                                @error('frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- THỜI GIAN DÙNG --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian dùng</label>
                            <input type="text" name="duration" id="duration"
                                class="form-control shadow-sm @error('duration') is-invalid @enderror"
                                value="{{ old('duration') }}" placeholder="VD: 5 ngày">
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- GHI CHÚ / HƯỚNG DẪN --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Hướng dẫn dùng thuốc</label>
                            <textarea name="instruction" id="instruction" rows="3"
                                class="form-control shadow-sm @error('instruction') is-invalid @enderror"
                                placeholder="Nhập hướng dẫn chi tiết...">{{ old('instruction') }}</textarea>
                            @error('instruction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-success fw-bold shadow-sm px-4">
                                <i class="fas fa-plus-circle me-2"></i> Thêm thuốc
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Script tự điền giá + tên --}}
<script>
document.getElementById('medicine_select').addEventListener('change', function(){
    const opt = this.options[this.selectedIndex];

    document.getElementById('medicine_name').value = opt.dataset.name || '';
    document.getElementById('price').value = opt.dataset.price || '';
});


document.getElementById('medicine_select').addEventListener('change', function(){
    const opt = this.options[this.selectedIndex];
    
    const alertExpired = document.getElementById('alert_expired');
    const alertNear = document.getElementById('alert_near');
    
    // Reset form
    alertExpired.classList.add('d-none');
    alertNear.classList.add('d-none');

    if(opt.value) {
        // Điền dữ liệu
        document.getElementById('medicine_name').value = opt.dataset.name || '';
        document.getElementById('price').value = opt.dataset.price || '';

        // Kiểm tra trạng thái
        const status = opt.dataset.status;
        const info = opt.dataset.expiryInfo || '';

        if (status === 'expired') {
            alertExpired.querySelector('.expiry-detail').innerText = info;
            alertExpired.classList.remove('d-none');
            // Có thể reset value nếu muốn bắt buộc chọn lại ngay
            // this.value = ""; 
        } else if (status === 'near_expiry') {
            alertNear.querySelector('.expiry-detail').innerText = info;
            alertNear.classList.remove('d-none');
        }
    }
});
</script>


@endsection
