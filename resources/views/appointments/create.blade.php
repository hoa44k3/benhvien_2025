@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-calendar-plus me-2"></i> Thêm Lịch hẹn Mới
            </h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <h5 class="mb-3 text-secondary">Thông tin cơ bản</h5>
                <hr>
                <div class="row g-3">
                    {{-- Mã lịch hẹn --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã lịch hẹn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" name="code" value="{{ old('code') }}" class="form-control" placeholder="Ví dụ: LH202501001" required>
                        </div>
                    </div>

                    {{-- Người đặt lịch (user_id) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Người đặt lịch <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Chọn người đặt lịch --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tên bệnh nhân --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên bệnh nhân <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-injured"></i></span>
                            <input type="text" name="patient_name" class="form-control" required placeholder="Nhập tên bệnh nhân" value="{{ old('patient_name') }}">
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-secondary">Chi tiết cuộc hẹn</h5>
                <hr>
                <div class="row g-3">
                    {{-- Ngày hẹn --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày hẹn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                    </div>

                    {{-- Giờ hẹn --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ hẹn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" name="time" class="form-control" required>
                        </div>
                    </div>

                    {{-- Bác sĩ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bác sĩ phụ trách <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                            <select name="doctor_id" class="form-select" required>
                                <option value="">-- Chọn bác sĩ --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
{{-- Mã bệnh nhân --}}
<div class="col-md-6">
    <label class="form-label fw-semibold">Mã bệnh nhân</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
        <input type="text" name="patient_code" class="form-control" placeholder="Ví dụ: BN00123" value="{{ old('patient_code') }}">
    </div>
</div>

{{-- Số điện thoại --}}
<div class="col-md-6">
    <label class="form-label fw-semibold">Số điện thoại</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-phone"></i></span>
        <input type="text" name="patient_phone" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('patient_phone') }}">
    </div>
</div>

{{-- Lý do khám --}}
<div class="col-md-12">
    <label class="form-label fw-semibold">Lý do khám / Triệu chứng</label>
    <textarea name="reason" class="form-control" rows="3" placeholder="Ví dụ: Ho kéo dài, đau lưng dưới...">{{ old('reason') }}</textarea>
</div>

{{-- Ghi chú --}}
<div class="col-md-12">
    <label class="form-label fw-semibold">Ghi chú thêm</label>
    <textarea name="notes" class="form-control" rows="2" placeholder="Ghi chú cho bác sĩ (nếu có)">{{ old('notes') }}</textarea>
</div>

                    {{-- Chuyên khoa --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Chuyên khoa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-stethoscope"></i></span>
                            <select name="department_id" class="form-select" required>
                                <option value="">-- Chọn chuyên khoa --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="Đang chờ" selected>Đang chờ</option>
                            <option value="Đã xác nhận">Đã xác nhận</option>
                            <option value="Hoàn thành">Hoàn thành</option>
                            <option value="Đã hẹn">Đã hẹn</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Lịch hẹn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
