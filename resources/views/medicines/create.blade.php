@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-plus-circle me-2"></i> Thêm Thuốc Mới Vào Kho
            </h4>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('medicines.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3 text-secondary">Thông tin cơ bản</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Mã thuốc --}}
                    <div class="col-md-6">
                        <label for="medicineCode" class="form-label fw-semibold">Mã thuốc <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" name="code" id="medicineCode" class="form-control" value="{{ old('code') }}" required placeholder="Mã SKU hoặc mã vạch">
                        </div>
                    </div>

                    {{-- Tên thuốc --}}
                    <div class="col-md-6">
                        <label for="medicineName" class="form-label fw-semibold">Tên thuốc <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-capsules"></i></span>
                            <input type="text" name="name" id="medicineName" class="form-control" value="{{ old('name') }}" required placeholder="Tên thương mại hoặc hoạt chất">
                        </div>
                    </div>
                    
                    {{-- Phân loại --}}
                    <div class="col-md-6">
                        <label for="category" class="form-label fw-semibold">Phân loại</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            <input type="text" name="category" id="category" class="form-control" value="{{ old('category') }}" placeholder="Ví dụ: Kháng sinh, Giảm đau">
                        </div>
                    </div>

                    {{-- Đơn vị --}}
                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold">Đơn vị</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                            <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', 'Viên') }}" placeholder="Viên, Hộp, Chai, Vỉ...">
                        </div>
                    </div>

                </div>
                
                <h5 class="mt-4 mb-3 text-secondary">Quản lý kho & Giá</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Số lượng tồn --}}
                    <div class="col-md-4">
                        <label for="stock" class="form-label fw-semibold">Số lượng tồn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-warehouse"></i></span>
                            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}" required min="0">
                        </div>
                    </div>

                    {{-- Tồn tối thiểu --}}
                    <div class="col-md-4">
                        <label for="minStock" class="form-label fw-semibold">Tồn tối thiểu (Cảnh báo)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                            <input type="number" name="min_stock" id="minStock" class="form-control" value="{{ old('min_stock') }}" min="0">
                        </div>
                    </div>

                    {{-- Giá --}}
                    <div class="col-md-4">
                        <label for="price" class="form-label fw-semibold">Giá bán (VNĐ)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" step="1" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0">
                        </div>
                    </div>
                    
                    {{-- Hạn sử dụng --}}
                    <div class="col-md-6">
                        <label for="expiryDate" class="form-label fw-semibold">Hạn sử dụng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="expiry_date" id="expiryDate" class="form-control" value="{{ old('expiry_date') }}">
                        </div>
                    </div>
                    
                    {{-- Nhà cung cấp --}}
                    <div class="col-md-6">
                        <label for="supplier" class="form-label fw-semibold">Nhà cung cấp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                            <input type="text" name="supplier" id="supplier" class="form-control" value="{{ old('supplier') }}" placeholder="Tên công ty hoặc đại lý">
                        </div>
                    </div>
                    
                    {{-- Trạng thái (Tạm thời không cần, vì trạng thái nên dựa vào Stock và Hạn sử dụng) --}}
                    <div class="col-md-6 d-none">
                        <label for="status" class="form-label fw-semibold">Trạng thái (Tự động)</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Trống">Trống</option>
                            <option value="Sắp hết">Sắp hết</option>
                            <option value="Hết hạn">Hết hạn</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('medicines.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Thuốc
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection