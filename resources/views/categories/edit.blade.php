@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-edit me-2 text-warning"></i> Sửa Danh mục: **{{ $category->name }}**
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-1"></i> Thông tin Cập nhật</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT')

                        {{-- Tên Danh mục --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Tên Danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $category->name) }}" 
                                placeholder="Nhập tên danh mục"
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
                                placeholder="Mô tả ngắn gọn về danh mục này.">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ảnh Hiện tại và Ảnh Mới --}}
                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">Ảnh đại diện</label>
                            
                            {{-- Hiển thị ảnh hiện tại --}}
                            @if($category->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$category->image_path) }}" 
                                         alt="Ảnh hiện tại" 
                                         width="120" 
                                         class="img-thumbnail rounded shadow-sm">
                                    <span class="text-muted small ms-2">Ảnh hiện tại</span>
                                </div>
                            @else
                                <div class="text-muted mb-2 small">Chưa có ảnh đại diện.</div>
                            @endif
                            
                            {{-- Input upload ảnh mới --}}
                            <input type="file" name="image" id="image" 
                                class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text text-muted">Tải ảnh mới sẽ thay thế ảnh hiện tại (nếu có).</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                            @php
                                // Logic để chọn giá trị cũ nếu có lỗi validation, nếu không thì dùng giá trị từ DB
                                $current_status = old('status', $category->status);
                            @endphp
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="1" {{ $current_status == 1 ? 'selected' : '' }}>Active (Hoạt động)</option>
                                <option value="0" {{ $current_status == 0 ? 'selected' : '' }}>Inactive (Không hoạt động)</option>
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
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="fas fa-sync-alt me-1"></i> Cập nhật Danh mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection