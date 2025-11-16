@extends('admin.master')

@section('body')
{{-- ĐÃ THAY ĐỔI: Sử dụng container-xl để form rộng hơn trên các màn hình lớn --}}
<div class="container-xl mt-5 mb-5">
    <div class="card shadow-lg border-0 rounded-3">
        {{-- Card Header --}}
        <div class="card-header bg-gradient-primary text-white py-3 rounded-top-3">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-person-plus-fill me-2"></i>Thêm người dùng mới
            </h4>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            {{-- Hiển thị lỗi Validation --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Lỗi nhập liệu!</h6>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            {{-- Form Thêm người dùng --}}
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4"> {{-- Tăng khoảng cách giữa các hàng --}}

                    {{-- Nhóm thông tin cơ bản --}}
                    <div class="col-12">
                        <h5 class="text-primary mb-3 border-bottom pb-1"><i class="bi bi-info-circle-fill me-2"></i>Thông tin cơ bản</h5>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nhập họ và tên đầy đủ">
                        <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Ví dụ: tennguoidung@domain.com">
                        <div class="invalid-feedback">Vui lòng nhập địa chỉ email hợp lệ.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
                        <div class="invalid-feedback">Vui lòng nhập mật khẩu.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Ví dụ: 0901234567" inputmode="tel">
                    </div>

                    {{-- Nhóm thông tin Y tế/Cá nhân --}}
                    <div class="col-12 mt-4">
                        <h5 class="text-primary mb-3 border-bottom pb-1"><i class="bi bi-clipboard-data-fill me-2"></i>Thông tin hồ sơ</h5>
                    </div>

                    <div class="col-md-4">
                        <label for="patient_code" class="form-label">Mã bệnh nhân</label>
                        <input type="text" id="patient_code" name="patient_code" class="form-control" value="{{ old('patient_code') }}" placeholder="Mã bệnh nhân (nếu có)">
                    </div>

                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">Ngày sinh</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="age" class="form-label">Tuổi</label>
                        <input type="number" id="age" name="age" class="form-control" value="{{ old('age') }}" min="0" placeholder="Chỉ nhập số">
                    </div>

                    <div class="col-md-4">
                        <label for="gender" class="form-label">Giới tính</label>
                        <select id="gender" name="gender" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="cccd" class="form-label">CCCD / Căn cước</label>
                        <input type="text" id="cccd" name="cccd" class="form-control" value="{{ old('cccd') }}" placeholder="Số CCCD/CMND">
                    </div>

                    <div class="col-md-4">
                        <label for="occupation" class="form-label">Nghề nghiệp</label>
                        <input type="text" id="occupation" name="occupation" class="form-control" value="{{ old('occupation') }}" placeholder="Ví dụ: Giáo viên, Kỹ sư,...">
                    </div>
                    
                    <div class="col-md-8">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" placeholder="Địa chỉ chi tiết">
                    </div>

                    <div class="col-md-4">
                        <label for="last_visit" class="form-label">Lần khám cuối</label>
                        <input type="text" id="last_visit" name="last_visit" class="form-control" value="{{ old('last_visit') }}" placeholder="Thông tin về lần khám gần nhất">
                    </div>


                    {{-- Nhóm Quyền và Trạng thái --}}
                    <div class="col-12 mt-4">
                        <h5 class="text-primary mb-3 border-bottom pb-1"><i class="bi bi-shield-lock-fill me-2"></i>Phân quyền & Trạng thái</h5>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select id="status" name="status" class="form-select">
                            <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                            <option value="banned" {{ old('status')=='banned' ? 'selected' : '' }}>Bị cấm</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="avatar" class="form-label">Ảnh đại diện</label>
                        <input type="file" id="avatar" name="avatar" class="form-control">
                        <small class="text-muted">Chọn một tệp hình ảnh (tối đa 2MB).</small>
                    </div>

                    <div class="col-12">
                        <label for="role_ids" class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select id="role_ids" name="role_ids[]" class="form-select" multiple required style="min-height: 150px;"> {{-- Giữ chiều cao của select multiple --}}
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ (collect(old('role_ids'))->contains($role->id)) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Giữ phím **Ctrl** (hoặc **Cmd** trên Mac) để chọn nhiều vai trò. Phải chọn ít nhất một vai trò.</small>
                        <div class="invalid-feedback">Vui lòng chọn ít nhất một vai trò.</div>
                    </div>


                </div> {{-- End row g-4 --}}

                {{-- Nút hành động --}}
                <div class="mt-5 pt-3 border-top d-flex justify-content-end">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-3 px-4">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
                    </a>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-save me-1"></i>Lưu người dùng
                    </button>
                </div>
            </form>
        </div>
        {{-- End Card Body --}}
    </div>
</div>

<style>
    /* CSS nội tuyến để làm đẹp thêm (tùy chọn) */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); /* Blue gradient */
    }
    .card-header h4 {
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }
    .form-label {
        font-weight: 500;
        color: #333;
    }
</style>

{{-- Script để kích hoạt Validation của Bootstrap (tùy chọn, nếu bạn muốn dùng) --}}
<script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

@endsection