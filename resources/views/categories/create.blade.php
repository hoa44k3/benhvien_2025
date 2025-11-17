@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-plus-circle me-2 text-success"></i> Thêm Danh mục Mới
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-tag me-1"></i> Thông tin Danh mục</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Tên Danh mục --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Tên Danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" 
                                placeholder="Nhập tên danh mục (ví dụ: Tim mạch, Nha khoa...)"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Mô tả</label>
                            <textarea name="description" id="description" rows="3" 
                                class="form-control @error('description') is-invalid @enderror" 
                                placeholder="Mô tả ngắn gọn về danh mục này.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ảnh --}}
                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">Ảnh đại diện (Tùy chọn)</label>
                            <input type="file" name="image" id="image" 
                                class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text text-muted">Kích thước khuyến nghị: 600x400px.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active (Hoạt động)</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive (Không hoạt động)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút Hành động --}}
                        <div class="d-flex justify-content-end border-top pt-3">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Lưu Danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection