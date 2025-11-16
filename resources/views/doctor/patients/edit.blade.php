@extends('doctor.master')
@section('title', 'Chỉnh sửa bệnh nhân')

@section('body')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Cập nhật thông tin bệnh nhân</h4>
        </div>

        <form action="{{ route('doctor.patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã bệnh nhân</label>
                        <input type="text" name="patient_code" value="{{ old('patient_code', $patient->patient_code) }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="Nam" {{ $patient->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ $patient->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" value="{{ old('address', $patient->address) }}" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Ghi chú y tế (nếu có)</label>
                        <textarea name="note" class="form-control" rows="3">{{ old('note', $patient->note) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light text-end">
                <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
