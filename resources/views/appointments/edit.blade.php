@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-edit me-2"></i> Chỉnh sửa Lịch hẹn: {{ $appointment->code }}
            </h4>
            <small>Cập nhật thông tin và trạng thái cuộc hẹn.</small>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ====================== THÔNG TIN CƠ BẢN ====================== --}}
                <h5 class="mb-3 text-secondary">Thông tin cơ bản</h5>
                <hr>

                <div class="row g-3">

                    {{-- Mã lịch hẹn --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã lịch hẹn</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" class="form-control" value="{{ $appointment->code }}" readonly>
                        </div>
                    </div>

                    {{-- Tên bệnh nhân --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên bệnh nhân <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-injured"></i></span>
                            <input type="text" name="patient_name" value="{{ $appointment->patient_name }}"
                                   class="form-control" required>
                        </div>
                    </div>

                </div>

                {{-- ====================== CHI TIẾT CUỘC HẸN ====================== --}}
                <h5 class="mt-4 mb-3 text-secondary">Chi tiết cuộc hẹn</h5>
                <hr>

                <div class="row g-3">

                    {{-- Ngày hẹn --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày hẹn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                            <input type="date" name="date" class="form-control"
                                   value="{{ $appointment->date }}" required>
                        </div>
                    </div>

                    {{-- Giờ hẹn --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ hẹn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" name="time" class="form-control"
                                   value="{{ $appointment->time }}" required>
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <select name="status" class="form-select" required>
                                @foreach(['Đang chờ','Đã xác nhận','Đang khám','Hoàn thành','Đã hẹn','Hủy'] as $st)
                                    <option value="{{ $st }}" {{ $appointment->status == $st ? 'selected' : '' }}>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>
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
                                    <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Người đặt lịch --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Người đặt lịch</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <select name="user_id" class="form-select" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $appointment->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Mã bệnh nhân --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã bệnh nhân</label>
                        <input type="text" name="patient_code" class="form-control"
                               value="{{ $appointment->patient_code }}">
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="patient_phone" class="form-control"
                               value="{{ $appointment->patient_phone }}">
                    </div>

                    {{-- Lý do khám --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Lý do khám / Triệu chứng</label>
                        <textarea name="reason" class="form-control" rows="3">{{ $appointment->reason }}</textarea>
                    </div>

                    {{-- Chuẩn đoán --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Chuẩn đoán của bác sĩ</label>
                        <textarea name="diagnosis" class="form-control" rows="3">{{ $appointment->diagnosis }}</textarea>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Ghi chú thêm</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $appointment->notes }}</textarea>
                    </div>

                    {{-- Chuyên khoa --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Chuyên khoa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-stethoscope"></i></span>
                            <select name="department_id" class="form-select" required>
                                <option value="">-- Chọn chuyên khoa --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $appointment->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Người Check-in --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Người Check-in</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                            <select name="checked_in_by" class="form-select">
                                <option value="">-- Chưa xác nhận --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $appointment->checked_in_by == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Người duyệt lịch --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Người Duyệt lịch</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                            <select name="approved_by" class="form-select">
                                <option value="">-- Chưa duyệt --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $appointment->approved_by == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                {{-- ====================== BUTTON ====================== --}}
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-warning shadow-sm text-dark fw-bold">
                        <i class="fas fa-sync-alt me-1"></i> Cập nhật Lịch hẹn
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
