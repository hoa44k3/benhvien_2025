@extends('admin.master')

@section('title', 'Tạo đơn thuốc')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-plus-circle me-2 text-primary"></i> Tạo Đơn thuốc Mới
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">

                    {{-- Form Tạo Đơn Thuốc --}}
                    <form method="POST" action="{{ route('prescriptions.store') }}" class="needs-validation" novalidate>
                        @csrf

                        {{-- Thông báo lỗi (Validation errors) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Lỗi nhập liệu!</strong> Vui lòng kiểm tra lại các trường đã điền.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Mã đơn thuốc --}}
                        <div class="mb-3">
                            <label for="code" class="form-label fw-bold">Mã đơn thuốc <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code') }}" required placeholder="Ví dụ: DTH-202312001">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            {{-- Bác sĩ --}}
                            <div class="col-md-6">
                                <label for="doctor_id" class="form-label fw-bold">Bác sĩ <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn bác sĩ --</option>
                                    @foreach ($doctors as $d)
                                        <option value="{{ $d->id }}" {{ old('doctor_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bệnh nhân --}}
                            <div class="col-md-6">
                                <label for="patient_id" class="form-label fw-bold">Bệnh nhân <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn bệnh nhân --</option>
                                    @foreach ($patients as $p)
                                        <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Hồ sơ bệnh án --}}
                        <div class="mb-3">
                            <label for="medical_record_id" class="form-label fw-bold">Hồ sơ bệnh án (nếu có)</label>
                            <select name="medical_record_id" id="medical_record_id" class="form-select @error('medical_record_id') is-invalid @enderror">
                                <option value="">-- Không chọn --</option>
                                @foreach ($records as $r)
                                    <option value="{{ $r->id }}" {{ old('medical_record_id') == $r->id ? 'selected' : '' }}>
                                        {{ $r->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medical_record_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chẩn đoán --}}
                        <div class="mb-3">
                            <label for="diagnosis" class="form-label fw-bold">Chẩn đoán</label>
                            <textarea name="diagnosis" id="diagnosis" rows="3"
                                class="form-control @error('diagnosis') is-invalid @enderror"
                                placeholder="Tóm tắt chẩn đoán của bác sĩ">{{ old('diagnosis') }}</textarea>
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ghi chú --}}
                        <div class="mb-3">
                            <label for="note" class="form-label fw-bold">Ghi chú</label>
                            <textarea name="note" id="note" rows="3"
                                class="form-control @error('note') is-invalid @enderror"
                                placeholder="Các ghi chú đặc biệt về đơn thuốc hoặc bệnh nhân">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                {{-- Giữ lại giá trị default 'Đang kê' khi tạo mới --}}
                                <option value="Đang kê" {{ old('status') == 'Đang kê' ? 'selected' : 'selected' }}>
                                    Đang kê
                                </option>
                                <option value="Đã duyệt" {{ old('status') == 'Đã duyệt' ? 'selected' : '' }}>
                                    Đã duyệt
                                </option>
                                <option value="Đã phát thuốc" {{ old('status') == 'Đã phát thuốc' ? 'selected' : '' }}>
                                    Đã phát thuốc
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i> Lưu đơn thuốc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection