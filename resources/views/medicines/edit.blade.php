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
                    
                    {{-- Phân loại --}}
                    <div class="col-md-6">
                        <label for="category" class="form-label fw-semibold">Phân loại</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $medicine->category) }}">
                        </div>
                    </div>

                    {{-- Đơn vị --}}
                    <div class="col-md-6">
                        <label for="unit" class="form-label fw-semibold">Đơn vị</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                            <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $medicine->unit) }}">
                        </div>
                    </div>

                </div>
                
                <h5 class="mt-4 mb-3 text-secondary">Quản lý kho & Giá</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Tồn kho (Có thể là trường hợp điều chỉnh thủ công) --}}
                    <div class="col-md-4">
                        <label for="stock" class="form-label fw-semibold">Tồn kho</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-warehouse"></i></span>
                            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $medicine->stock) }}" min="0">
                        </div>
                    </div>

                    {{-- Tồn tối thiểu --}}
                    <div class="col-md-4">
                        <label for="minStock" class="form-label fw-semibold">Tồn tối thiểu (Cảnh báo)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                            <input type="number" name="min_stock" id="minStock" class="form-control" value="{{ old('min_stock', $medicine->min_stock) }}" min="0">
                        </div>
                    </div>

                    {{-- Giá --}}
                    <div class="col-md-4">
                        <label for="price" class="form-label fw-semibold">Giá bán (VNĐ)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" step="1" name="price" id="price" class="form-control" value="{{ old('price', $medicine->price) }}" min="0">
                        </div>
                    </div>
                    
                    {{-- Hạn sử dụng --}}
                    <div class="col-md-6">
                        <label for="expiryDate" class="form-label fw-semibold">Hạn sử dụng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="expiry_date" id="expiryDate" class="form-control" value="{{ old('expiry_date', $medicine->expiry_date) }}">
                        </div>
                    </div>
                    
                    {{-- Nhà cung cấp --}}
                    <div class="col-md-6">
                        <label for="supplier" class="form-label fw-semibold">Nhà cung cấp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                            <input type="text" name="supplier" id="supplier" class="form-control" value="{{ old('supplier', $medicine->supplier) }}">
                        </div>
                    </div>
                    
                    {{-- Trạng thái (Tùy chọn, nên để tự động) --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Trạng thái (Cảnh báo)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <select name="status" id="status" class="form-select">
                                @php
                                    $currentStatus = old('status', $medicine->status);
                                @endphp
                                <option value="Trống" {{ $currentStatus == 'Trống' ? 'selected' : '' }}>Trống</option>
                                <option value="Sắp hết" {{ $currentStatus == 'Sắp hết' ? 'selected' : '' }}>Sắp hết</option>
                                <option value="Hết hạn" {{ $currentStatus == 'Hết hạn' ? 'selected' : '' }}>Hết hạn</option>
                                <option value="Bình thường" {{ $currentStatus == 'Bình thường' ? 'selected' : '' }}>Bình thường</option>
                            </select>
                            <div class="form-text mt-0">Thường được tính toán tự động dựa trên tồn kho và HSD.</div>
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