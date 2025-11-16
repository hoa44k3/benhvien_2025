@extends('doctor.master')

@section('title', 'Khám bệnh & Kê đơn thuốc')

@section('body')
<div class="container-fluid py-4">
    @if ($appointment)
        <div class="row g-4">
            {{-- Cột trái: Thông tin bệnh nhân --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="text-primary fw-bold mb-3">👩‍⚕️ Bệnh nhân Đang Khám</h5>

                        <div class="mb-3">
                            <h6 class="fw-semibold text-dark">
                                {{ $appointment->user->name ?? 'Không xác định' }}
                            </h6>
                            <p class="text-muted mb-1">
                                Mã BN: <strong>{{ '000' . $appointment->user->id }}</strong> |
                                Giới tính: {{ $appointment->user->gender ?? '---' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('doctor.videoCall', $appointment->id) }}" 
                               class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-camera-video me-2"></i> Bắt đầu Video Call
                            </a>
                        </div>

                        <div>
                            <label class="fw-semibold text-muted d-block mb-1">Lý do khám:</label>
                            <div class="alert alert-danger p-2 m-0">
                                {{ $appointment->reason ?? 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải: Nhập chẩn đoán và kê đơn --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="text-success fw-bold mb-3">🩺 Nhập Chẩn đoán & Y lệnh</h5>

                        <form action="{{ route('doctor.diagnosis.store', $appointment->id) }}" method="POST">
                            @csrf

                            {{-- Chẩn đoán --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Chẩn đoán (ICD-10):</label>
                                <textarea name="diagnosis" class="form-control" rows="3" 
                                    placeholder="VD: R51 - Đau đầu">{{ old('diagnosis') }}</textarea>
                            </div>

                            {{-- Kê đơn thuốc --}}
                            <div class="border rounded p-3 mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-capsule text-danger fs-5 me-2"></i>
                                    <h6 class="mb-0 text-danger fw-bold">Kê đơn Thuốc Điện tử</h6>
                                </div>

                                <div id="medicine-list">
                                    <div class="row g-2 align-items-center mb-2">
                                        <div class="col-md-5">
                                            <input type="text" name="medicine_name[]" class="form-control" placeholder="Tên thuốc">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="medicine_quantity[]" class="form-control" placeholder="SL">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="medicine_usage[]" class="form-control" placeholder="Liều dùng">
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-medicine">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-medicine" 
                                    class="btn btn-link text-decoration-none text-primary fw-semibold">
                                    <i class="bi bi-plus-circle me-1"></i> Thêm thuốc
                                </button>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="bi bi-send-check me-2"></i> Ký số & Gửi Đơn thuốc (Dược sĩ)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">
            Hiện không có bệnh nhân nào đang chờ khám.
        </div>
    @endif
</div>

{{-- Script thêm/xóa thuốc --}}
@push('scripts')
<script>
document.getElementById('add-medicine').addEventListener('click', function () {
    const newRow = `
        <div class="row g-2 align-items-center mb-2">
            <div class="col-md-5">
                <input type="text" name="medicine_name[]" class="form-control" placeholder="Tên thuốc">
            </div>
            <div class="col-md-2">
                <input type="text" name="medicine_quantity[]" class="form-control" placeholder="SL">
            </div>
            <div class="col-md-4">
                <input type="text" name="medicine_usage[]" class="form-control" placeholder="Liều dùng">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-medicine">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>`;
    document.getElementById('medicine-list').insertAdjacentHTML('beforeend', newRow);
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-medicine')) {
        e.target.closest('.row').remove();
    }
});
</script>
@endpush
@endsection
