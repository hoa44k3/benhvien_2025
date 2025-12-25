@extends('admin.master')
@section('title', 'Tạo Đơn Thuốc Mới')

@section('body')
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-prescription me-2"></i> Kê Đơn Thuốc</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('prescriptions.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- 1. MÃ ĐƠN THUỐC (Tự động sinh) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mã đơn thuốc</label>
                        <input type="text" name="code" class="form-control bg-light" value="{{ $newCode ?? old('code') }}" readonly>
                    </div>

                    {{-- 2. HỒ SƠ BỆNH ÁN (Ẩn đi nếu đã có) --}}
                    @if(isset($prefilled))
                        <input type="hidden" name="medical_record_id" value="{{ $prefilled['medical_record_id'] }}">
                    @else
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hồ sơ bệnh án (Tùy chọn)</label>
                            <select name="medical_record_id" class="form-select">
                                <option value="">-- Không theo hồ sơ --</option>
                                {{-- Loop records nếu cần --}}
                            </select>
                        </div>
                    @endif

                    {{-- 3. BÁC SĨ (Tự điền) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Bác sĩ kê đơn</label>
                        @if(isset($prefilled))
                            {{-- Hiển thị tên (chỉ xem) --}}
                            <input type="text" class="form-control bg-light" value="{{ $prefilled['doctor_name'] }}" readonly>
                            {{-- Gửi ID ngầm --}}
                            <input type="hidden" name="doctor_id" value="{{ $prefilled['doctor_id'] }}">
                        @else
                            <select name="doctor_id" class="form-select">
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    {{-- 4. BỆNH NHÂN (Tự điền) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Bệnh nhân</label>
                        @if(isset($prefilled))
                            <input type="text" class="form-control bg-light" value="{{ $prefilled['patient_name'] }}" readonly>
                            <input type="hidden" name="patient_id" value="{{ $prefilled['patient_id'] }}">
                        @else
                            <select name="patient_id" class="form-select">
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    {{-- 5. CHẨN ĐOÁN (Lấy từ hồ sơ sang) --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Chẩn đoán</label>
                        <input type="text" name="diagnosis" class="form-control" 
                               value="{{ isset($prefilled) ? $prefilled['diagnosis'] : old('diagnosis') }}">
                    </div>

                    {{-- 6. GHI CHÚ --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Lời dặn / Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fas fa-save me-1"></i> Lưu & Kê thuốc chi tiết
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection