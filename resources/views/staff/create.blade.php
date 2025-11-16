@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-user-plus me-2"></i> Thêm Hồ sơ Nhân viên Mới
            </h4>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    <strong>Lỗi!</strong> Vui lòng kiểm tra lại các trường bên dưới.
                    <ul class="mt-2 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3 text-secondary">Thông tin Cá nhân & Cơ bản</h5>
                <hr>
                <div class="row g-3 mb-4">
                    
                    {{-- Mã nhân viên --}}
                    <div class="col-md-6">
                        <label for="staffCode" class="form-label fw-semibold">Mã nhân viên <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                            <input type="text" name="staff_code" id="staffCode" class="form-control" value="{{ old('staff_code') }}" required placeholder="Ví dụ: NV001">
                        </div>
                    </div>

                    {{-- Họ tên --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Nguyễn Văn A">
                        </div>
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="09xxxxxxxx">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="user@benhvien.com">
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 text-secondary">Vị trí & Chuyên môn</h5>
                <hr>
                <div class="row g-3 mb-4">
                    {{-- Khoa --}}
                    <div class="col-md-6">
                        <label for="departmentId" class="form-label fw-semibold">Khoa</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hospital-alt"></i></span>
                            <select name="department_id" id="departmentId" class="form-select">
                                <option value="">-- Chọn khoa --</option>
                                @foreach($departments as $dep)
                                    <option value="{{ $dep->id }}" @selected(old('department_id') == $dep->id)>{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    {{-- Chức vụ --}}
                    <div class="col-md-6">
                        <label for="position" class="form-label fw-semibold">Chức vụ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                            <input type="text" name="position" id="position" class="form-control" value="{{ old('position') }}" required placeholder="Bác sĩ, Y tá trưởng, Kỹ thuật viên...">
                        </div>
                    </div>
                    
                    {{-- Vai trò (Quyền hạn hệ thống) --}}
                    <div class="col-md-6">
                        <label for="roleId" class="form-label fw-semibold">Vai trò (Quyền hạn)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <select name="role_id" id="roleId" class="form-select">
                                <option value="">-- Chọn vai trò --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Trạng thái</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-power-off"></i></span>
                            <select name="status" id="status" class="form-select">
                                @foreach(['Hoạt động', 'Nghỉ phép', 'Nghỉ việc'] as $status)
                                    <option value="{{ $status }}" @selected(old('status') == $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 text-secondary">Đánh giá Hiệu suất</h5>
                <hr>
                <div class="row g-3">
                    {{-- Số năm kinh nghiệm --}}
                    <div class="col-md-6">
                        <label for="experienceYears" class="form-label fw-semibold">Số năm kinh nghiệm</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="number" name="experience_years" id="experienceYears" class="form-control" 
                                   value="{{ old('experience_years', 0) }}" min="0">
                        </div>
                    </div>

                    {{-- Điểm đánh giá --}}
                    <div class="col-md-6">
                        <label for="rating" class="form-label fw-semibold">Điểm đánh giá (0-5)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-star"></i></span>
                            <input type="number" step="0.1" min="0" max="5" name="rating" id="rating" class="form-control" 
                                   value="{{ old('rating') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('staff.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Hồ sơ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection