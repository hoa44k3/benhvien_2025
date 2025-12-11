@extends('admin.master')

@section('title', 'Chỉnh sửa hồ sơ bệnh án')

@section('body')
<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-edit me-2 text-warning"></i> Chỉnh sửa hồ sơ bệnh án
        </h3>
        <a href="{{ route('medical_records.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning bg-opacity-10 text-dark py-3 border-warning border-opacity-25">
            <h5 class="card-title mb-0 fw-bold">
                <i class="fas fa-info-circle me-2"></i> Cập nhật thông tin: #{{ $medical_record->id }}
            </h5>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('medical_records.update', $medical_record) }}" method="POST" id="medicalRecordForm">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Bệnh nhân --}}
                    <div class="col-md-6">
                        <label for="user_id" class="form-label fw-bold">Bệnh nhân <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Chọn bệnh nhân --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ old('user_id', $medical_record->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} (ID: {{ $user->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ngày khám --}}
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-bold">Ngày khám <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" 
                               class="form-control @error('date') is-invalid @enderror" 
                               value="{{ old('date', optional($medical_record->date)->format('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tiêu đề --}}
                    <div class="col-12">
                        <label for="title" class="form-label fw-bold">Tiêu đề hồ sơ <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               placeholder="Ví dụ: Khám tổng quát tháng 10..." 
                               value="{{ old('title', $medical_record->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bác sĩ & Khoa --}}
                    <div class="col-md-6">
                        <label for="doctor_id" class="form-label fw-bold">Bác sĩ phụ trách</label>
                        <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                            <option value="">-- Chọn bác sĩ --</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" 
                                {{ old('doctor_id', $medical_record->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="department_id" class="form-label fw-bold">Khoa</label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">-- Chọn khoa --</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}" 
                                {{ old('department_id', $medical_record->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Chẩn đoán chính & phụ --}}
                    <div class="col-md-6">
                        <label for="diagnosis_primary" class="form-label fw-bold">Chẩn đoán chính</label>
                        <input type="text" name="diagnosis_primary" id="diagnosis_primary" 
                               class="form-control @error('diagnosis_primary') is-invalid @enderror" 
                               value="{{ old('diagnosis_primary', $medical_record->diagnosis_primary) }}">
                        @error('diagnosis_primary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="diagnosis_secondary" class="form-label fw-bold">Chẩn đoán phụ</label>
                        <input type="text" name="diagnosis_secondary" id="diagnosis_secondary" 
                               class="form-control @error('diagnosis_secondary') is-invalid @enderror" 
                               value="{{ old('diagnosis_secondary', $medical_record->diagnosis_secondary) }}">
                        @error('diagnosis_secondary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Triệu chứng & Chỉ số sinh tồn --}}
                    <div class="col-md-6">
                        <label for="symptoms" class="form-label fw-bold">Triệu chứng</label>
                        <textarea name="symptoms" id="symptoms" rows="3" 
                                  class="form-control @error('symptoms') is-invalid @enderror"
                                  placeholder="Mô tả triệu chứng bệnh nhân...">{{ old('symptoms', $medical_record->symptoms) }}</textarea>
                        @error('symptoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="vital_signs" class="form-label fw-bold">Chỉ số sinh tồn (JSON)</label>
                        <textarea name="vital_signs" id="vital_signs" rows="3" 
                                  class="form-control @error('vital_signs') is-invalid @enderror"
                                  placeholder='Ví dụ: {"temp":"37","bp":"120/80","hr":"72"}'>{{ old('vital_signs', $medical_record->vital_signs) }}</textarea>
                        @error('vital_signs')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Nhập JSON với các key: temp, bp, hr...</div>
                    </div>

                    {{-- Điều trị --}}
                    <div class="col-12">
                        <label for="treatment" class="form-label fw-bold">Phương pháp điều trị / Đơn thuốc</label>
                        <textarea name="treatment" id="treatment" rows="3" 
                                  class="form-control @error('treatment') is-invalid @enderror"
                                  placeholder="Mô tả phương pháp điều trị...">{{ old('treatment', $medical_record->treatment) }}</textarea>
                        @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ngày tái khám & Lịch hẹn --}}
                    <div class="col-md-6">
                        <label for="next_checkup" class="form-label fw-bold">Ngày tái khám</label>
                        <input type="date" name="next_checkup" id="next_checkup" class="form-control @error('next_checkup') is-invalid @enderror" 
                               value="{{ old('next_checkup', optional(\Carbon\Carbon::parse($medical_record->next_checkup))->format('Y-m-d')) }}">
                        @error('next_checkup')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="appointment_id" class="form-label fw-bold">ID Lịch hẹn</label>
                        <input type="number" name="appointment_id" id="appointment_id" 
                               class="form-control @error('appointment_id') is-invalid @enderror" 
                               value="{{ old('appointment_id', $medical_record->appointment_id) }}">
                        @error('appointment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Bỏ trống nếu hồ sơ không liên kết với lịch hẹn.</div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="chờ_khám" {{ old('status', $medical_record->status) == 'chờ_khám' ? 'selected' : '' }}>Chờ khám</option>
                            <option value="đang_khám" {{ old('status', $medical_record->status) == 'đang_khám' ? 'selected' : '' }}>Đang khám</option>
                            <option value="đã_khám" {{ old('status', $medical_record->status) == 'đã_khám' ? 'selected' : '' }}>Đã khám</option>
                            <option value="hủy" {{ old('status', $medical_record->status) == 'hủy' ? 'selected' : '' }}>Hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('medical_records.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">
                        <i class="fas fa-save me-1"></i> Cập nhật hồ sơ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
