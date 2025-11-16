@extends('doctor.master')

@section('title', 'Thêm lịch khám mới')

@section('body')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i> Thêm lịch khám mới
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Thông báo thành công --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('doctor.schedule.store') }}" method="POST">
                        @csrf

                        {{-- 1. Thông tin cơ bản --}}
                        <h5 class="text-secondary mb-3"><i class="fas fa-user-circle me-1"></i> Thông tin bệnh nhân</h5>
                        <div class="mb-4">
                            <label for="patient_name" class="form-label fw-bold">Tên bệnh nhân <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control form-control-lg" placeholder="Nhập tên bệnh nhân" required>
                        </div>

                        <hr class="my-4">

                        {{-- 2. Chi tiết thời gian và địa điểm --}}
                        <h5 class="text-secondary mb-3"><i class="fas fa-clock me-1"></i> Chi tiết lịch hẹn</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="date" class="form-label fw-bold">Ngày khám <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control" required value="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-4">
                                <label for="time" class="form-label fw-bold">Giờ khám <span class="text-danger">*</span></label>
                                <input type="time" name="time" id="time" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="room" class="form-label fw-bold">Phòng khám</label>
                                <input type="text" name="room" id="room" class="form-control" placeholder="Ví dụ: P.203, Tầng 2">
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- 3. Trạng thái và Ghi chú --}}
                        <h5 class="text-secondary mb-3"><i class="fas fa-info-circle me-1"></i> Tùy chọn & Trạng thái</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-bold">Trạng thái</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="Đang chờ" selected>🟢 Đang chờ</option>
                                    <option value="Đang khám">🟡 Đang khám</option>
                                    <option value="Hoàn thành">✅ Hoàn thành</option>
                                    <option value="Hủy hẹn">❌ Hủy hẹn</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="priority" class="form-label fw-bold">Độ ưu tiên</label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="Thấp" selected>Thấp</option>
                                    <option value="Trung bình">Trung bình</option>
                                    <option value="Cao">Cao</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">Ghi chú / Triệu chứng ban đầu</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Ghi chú về tình trạng bệnh nhân hoặc thông tin cần thiết khác..."></textarea>
                        </div>

                        <hr class="my-4">

                        {{-- Nút hành động --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('doctor.schedule.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-1"></i> **Lưu lịch khám**
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lưu ý: Bạn cần đảm bảo đã thêm thư viện Font Awesome (fas) vào master layout để hiển thị các icon. --}}
@endsection