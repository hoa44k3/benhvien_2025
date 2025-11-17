@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-plus-circle me-2 text-success"></i> Thêm Dịch vụ Mới
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-hand-holding-medical me-1"></i> Thông tin Dịch vụ</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Dữ liệu cơ bản --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Tên dịch vụ <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" 
                                    placeholder="Nhập tên dịch vụ"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="fee" class="form-label fw-semibold">Phí (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="fee" id="fee" 
                                    class="form-control @error('fee') is-invalid @enderror" 
                                    value="{{ old('fee', 0) }}" 
                                    placeholder="Ví dụ: 500000"
                                    required>
                                @error('fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="duration" class="form-label fw-semibold">Thời gian (phút)</label>
                                <input type="number" name="duration" id="duration" 
                                    class="form-control @error('duration') is-invalid @enderror" 
                                    value="{{ old('duration') }}"
                                    placeholder="Ví dụ: 30">
                                <small class="form-text text-muted">Nhập 0 nếu dịch vụ là "Liên tục".</small>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Mô tả ngắn</label>
                            <textarea name="description" id="description" rows="2" 
                                class="form-control @error('description') is-invalid @enderror" 
                                placeholder="Tóm tắt ngắn gọn dịch vụ.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chi tiết nội dung --}}
                        <div class="mb-3">
                            <label for="content" class="form-label fw-semibold">Chi tiết nội dung</label>
                            <textarea name="content" id="content" rows="4" 
                                class="form-control @error('content') is-invalid @enderror"
                                placeholder="Nội dung chi tiết của dịch vụ (có thể là HTML/Editor).">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trường liên kết và Trạng thái --}}
                        <div class="row">
                            {{-- Danh mục --}}
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                                <select name="category_id" id="category_id" 
                                    class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Chuyên khoa --}}
                            <div class="col-md-4 mb-3">
                                <label for="department_id" class="form-label fw-semibold">Chuyên khoa</label>
                                <select name="department_id" id="department_id" 
                                    class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">-- Chọn chuyên khoa --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Trạng thái --}}
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label fw-semibold">Trạng thái</label>
                                @php
                                    $current_status = old('status', 1); // Mặc định là Active (1)
                                @endphp
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="1" {{ $current_status == 1 ? 'selected' : '' }}>Active (Hoạt động)</option>
                                    <option value="0" {{ $current_status == 0 ? 'selected' : '' }}>Inactive (Không hoạt động)</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Ảnh minh họa --}}
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">Ảnh minh họa (Tùy chọn)</label>
                            
                            <input type="file" name="image" id="image" 
                                class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text text-muted">Tải lên ảnh đại diện cho dịch vụ.</div>
                            
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- Loại bỏ phần hiển thị ảnh cũ (vì form này là tạo mới) --}}
                            {{-- @if(isset($service) && $service->image) <img src="{{ asset('storage/'.$service->image) }}" alt="Ảnh dịch vụ" width="150" class="mt-2"> @endif --}}
                        </div>

                        {{-- Nút Hành động --}}
                        <div class="d-flex justify-content-end border-top pt-3">
                            <a href="{{ route('services.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Lưu Dịch vụ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection