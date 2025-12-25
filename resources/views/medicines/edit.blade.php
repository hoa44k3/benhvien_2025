@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-edit me-2"></i> Cập nhật Thuốc: {{ $medicine->name }}
            </h4>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('medicines.update', $medicine->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="mb-3 text-secondary">Thông tin cơ bản</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Mã thuốc (Disabled) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mã thuốc</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" class="form-control" value="{{ $medicine->code }}" disabled title="Mã thuốc không được phép chỉnh sửa">
                        </div>
                    </div>

                    {{-- Tên thuốc --}}
                    <div class="col-md-6">
                        <label for="medicineName" class="form-label fw-semibold">Tên thuốc <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-capsules"></i></span>
                            <input type="text" name="name" id="medicineName" class="form-control" value="{{ old('name', $medicine->name) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="medicine_category_id" class="form-label fw-semibold">Phân loại</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            <select name="medicine_category_id" id="medicine_category_id" class="form-select">
                                <option value="">-- Chọn phân loại --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('medicine_category_id', $medicine->medicine_category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Đơn vị --}}
                    <div class="col-md-6">
                        <label for="medicine_unit_id" class="form-label fw-semibold">Đơn vị</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                            <select name="medicine_unit_id" id="medicine_unit_id" class="form-select">
                                <option value="">-- Chọn đơn vị --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('medicine_unit_id', $medicine->medicine_unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-warning shadow-sm text-dark fw-bold">
                        <i class="fas fa-sync-alt me-1"></i> Cập nhật Thuốc
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection