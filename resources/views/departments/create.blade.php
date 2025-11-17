@extends('admin.master')

@section('title', 'Thêm chuyên khoa')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-plus-circle me-2"></i> 
                {{ isset($department) ? 'Chỉnh sửa Chuyên khoa' : 'Thêm Chuyên khoa Mới' }}
            </h4>
        </div>
        <div class="card-body p-4">

            <form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                
                @csrf
                @if(isset($department))
                    @method('PUT')
                @endif
                
                <h5 class="mb-3 text-secondary">Thông tin chung</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Mã chuyên khoa --}}
                    <div class="col-md-6">
                        <label for="departmentCode" class="form-label fw-semibold">
                            Mã chuyên khoa <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="code" id="departmentCode" 
                                   class="form-control" 
                                   value="{{ old('code', $department->code ?? '') }}" required 
                                   placeholder="Ví dụ: TMH, TIM_MCH">
                        </div>
                    </div>

                    {{-- Tên chuyên khoa --}}
                    <div class="col-md-6">
                        <label for="departmentName" class="form-label fw-semibold">
                            Tên chuyên khoa <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-stethoscope"></i></span>
                            <input type="text" name="name" id="departmentName" 
                                   class="form-control" 
                                   value="{{ old('name', $department->name ?? '') }}" required 
                                   placeholder="Ví dụ: Tim mạch can thiệp">
                        </div>
                    </div>
                    
                    {{-- Trưởng khoa --}}
                   
                        <div class="col-md-6">
    <label for="headDoctor" class="form-label fw-semibold">Trưởng khoa</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
        <select name="user_id" id="headDoctor" class="form-control">
            <option value="">-- Chọn bác sĩ --</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" 
                    {{ old('user_id', $department->user_id ?? '') == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>



                    {{-- Phí khám --}}
                    <div class="col-md-6">
                        <label for="fee" class="form-label fw-semibold">Phí khám (VNĐ)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" name="fee" id="fee" 
                                   class="form-control" 
                                   value="{{ old('fee', $department->fee ?? 0) }}" 
                                   min="0">
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 text-secondary">Thông số nhân sự & cơ sở vật chất</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Số bác sĩ --}}
                    <div class="col-md-4">
                        <label for="numDoctors" class="form-label fw-semibold">Số bác sĩ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                            <input type="number" name="num_doctors" id="numDoctors" 
                                   class="form-control" 
                                   value="{{ old('num_doctors', $department->num_doctors ?? 0) }}" min="0">
                        </div>
                    </div>

                    {{-- Số y tá --}}
                    <div class="col-md-4">
                        <label for="numNurses" class="form-label fw-semibold">Số y tá</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-nurse"></i></span>
                            <input type="number" name="num_nurses" id="numNurses" 
                                   class="form-control" 
                                   value="{{ old('num_nurses', $department->num_nurses ?? 0) }}" min="0">
                        </div>
                    </div>

                    {{-- Số phòng --}}
                    <div class="col-md-4">
                        <label for="numRooms" class="form-label fw-semibold">Số phòng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hospital-symbol"></i></span>
                            <input type="number" name="num_rooms" id="numRooms" 
                                   class="form-control" 
                                   value="{{ old('num_rooms', $department->num_rooms ?? 0) }}" min="0">
                        </div>
                    </div>
                </div>
                
                <h5 class="mt-4 mb-3 text-secondary">Mô tả & Hình ảnh</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Trạng thái --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Trạng thái</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <select name="status" id="status" class="form-select">
                                @php
                                    $currentStatus = old('status', $department->status ?? 'active');
                                @endphp
                                <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ $currentStatus == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                            </select>
                        </div>
                    </div>

                    {{-- Ảnh chuyên khoa --}}
                    <div class="col-md-6">
                        <label for="image" class="form-label fw-semibold">Ảnh chuyên khoa</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if(!empty($department->image))
                            <div class="mt-2 border p-2 rounded-3 d-inline-block">
                                <img src="{{ asset('storage/' . $department->image) }}" alt="Ảnh hiện tại" width="80" class="rounded me-2" style="object-fit: cover;">
                                <small class="text-muted">Ảnh hiện tại</small>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Mô tả --}}
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Mô tả</label>
                        <textarea name="description" id="description" class="form-control" rows="4" 
                                  placeholder="Nhập mô tả chi tiết về chuyên khoa và dịch vụ cung cấp.">{{ old('description', $department->description ?? '') }}</textarea>
                    </div>

                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save me-1"></i> 
                        {{ isset($department) ? 'Cập nhật Chuyên khoa' : 'Lưu Chuyên khoa' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection