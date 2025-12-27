@extends('admin.master')

@section('title', 'Chỉnh sửa Bác sĩ')

@section('body')
<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded-3">
                
                {{-- Card Header --}}
                <div class="card-header bg-warning text-dark py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i> Chỉnh sửa Hồ sơ Bác sĩ</h5>
                        <small class="text-muted">Cập nhật cho: <strong>{{ $doctor->user->name ?? '...' }}</strong></small>
                    </div>
                    <a href="{{ route('doctorsite.index') }}" class="btn btn-sm btn-outline-dark fw-bold">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-4">
                    <form action="{{ route('doctorsite.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- 1. THÔNG TIN CƠ BẢN --}}
                        <h6 class="text-primary fw-bold text-uppercase border-bottom pb-2 mb-3">
                            <i class="fas fa-id-card me-1"></i> Thông tin Tài khoản
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name', $doctor->user->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', $doctor->user->email ?? '') }}" required>
                            </div>
                        </div>
                        
                        {{-- 2. THÔNG TIN CHUYÊN MÔN --}}
                        <h6 class="text-info fw-bold text-uppercase border-bottom pb-2 mb-3">
                            <i class="fas fa-user-md me-1"></i> Hồ sơ Chuyên môn
                        </h6>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Khoa làm việc</label>
                                <select name="department_id" class="form-select">
                                    <option value="">-- Chọn khoa --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $doctor->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Chuyên khoa chính</label>
                                <input type="text" name="specialization" class="form-control" 
                                       value="{{ old('specialization', $doctor->specialization) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Số năm kinh nghiệm</label>
                                <input type="number" name="experience_years" class="form-control" min="0" 
                                       value="{{ old('experience_years', $doctor->experience_years) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Trạng thái hoạt động</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ $doctor->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ $doctor->status == 0 ? 'selected' : '' }}>Tạm ẩn</option>
                                </select>
                            </div>
                        </div>

                        {{-- 3. CHÍNH SÁCH TÀI CHÍNH --}}
                        <div class="bg-success bg-opacity-10 p-3 rounded mb-4 border border-success border-opacity-25">
                            <h6 class="text-success fw-bold text-uppercase border-bottom border-success border-opacity-25 pb-2 mb-3">
                                <i class="fas fa-hand-holding-usd me-1"></i> 3. Lương & Hoa hồng
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-success">Lương cứng hàng tháng (VNĐ)</label>
                                    <input type="number" name="base_salary" class="form-control fw-bold text-success" 
                                           value="{{ old('base_salary', $doctor->base_salary) }}" min="0" step="1000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Hoa hồng Khám bệnh (%)</label>
                                    <input type="number" name="commission_exam_percent" class="form-control" 
                                           value="{{ old('commission_exam_percent', $doctor->commission_exam_percent) }}" min="0" max="100" step="0.1">
                                </div>
                            </div>
                        </div>
<div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Số bệnh nhân tối đa mỗi ngày</label>
                        <input type="number" name="max_patients" class="form-control" value="20" min="1">
                    </div>  
                </div>

                        {{-- 4. THÔNG TIN NGÂN HÀNG --}}
                        <div class="bg-info bg-opacity-10 p-3 rounded mb-4 border border-info border-opacity-25">
                            <h6 class="text-info fw-bold text-uppercase border-bottom border-info border-opacity-25 pb-2 mb-3">
                                <i class="fas fa-university me-1"></i> Thông tin Ngân hàng
                            </h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Tên Ngân hàng</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="VD: Vietcombank"
                                           value="{{ old('bank_name', $doctor->bank_name) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Số tài khoản</label>
                                    <input type="text" name="bank_account_number" class="form-control" placeholder="Số tài khoản..."
                                           value="{{ old('bank_account_number', $doctor->bank_account_number) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Tên chủ thẻ</label>
                                    <input type="text" name="bank_account_holder" class="form-control" placeholder="Tên in hoa"
                                           value="{{ old('bank_account_holder', $doctor->bank_account_holder) }}">
                                </div>
                            </div>
                        </div>

                        {{-- 5. HỒ SƠ NĂNG LỰC & CHỨNG CHỈ (MỚI) --}}
                        <div class="card mb-4 bg-warning bg-opacity-10 border-warning border-opacity-25">
                            <div class="card-header bg-transparent border-warning border-opacity-25 fw-bold text-dark">
                                <i class="fas fa-certificate me-2 text-warning"></i> Hồ sơ năng lực & Chứng chỉ
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Học vị / Bằng cấp</label>
                                        <input type="text" name="degree" class="form-control" 
                                               placeholder="VD: ThS.BS, Tiến sĩ, CKII..." 
                                               value="{{ old('degree', $doctor->degree ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Số chứng chỉ hành nghề (CCHN)</label>
                                        <input type="text" name="license_number" class="form-control" 
                                               placeholder="VD: 001234/BYT-CCHN" 
                                               value="{{ old('license_number', $doctor->license_number ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Nơi cấp / Đơn vị đào tạo</label>
                                        <input type="text" name="license_issued_by" class="form-control" 
                                               placeholder="VD: Đại học Y Hà Nội" 
                                               value="{{ old('license_issued_by', $doctor->license_issued_by ?? '') }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Ảnh chụp chứng chỉ / Bằng cấp (Minh chứng)</label>
                                        <input type="file" name="license_image" class="form-control" accept="image/*,.pdf">
                                        
                                        @if($doctor->license_image)
                                            <div class="mt-2 p-2 bg-white rounded border d-inline-block">
                                                <small class="d-block text-muted mb-1">Chứng chỉ hiện tại:</small>
                                                <a href="{{ asset('storage/'.$doctor->license_image) }}" target="_blank" class="d-flex align-items-center text-decoration-none">
                                                    <i class="fas fa-file-image text-danger fa-2x me-2"></i>
                                                    <span class="fw-bold">Xem file đính kèm</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 6. HÌNH ẢNH & GIỚI THIỆU --}}
                        <h6 class="text-secondary fw-bold text-uppercase border-bottom pb-2 mb-3">
                            <i class="fas fa-image me-1"></i> Hình ảnh & Giới thiệu
                        </h6>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Giới thiệu ngắn (Bio)</label>
                                <textarea name="bio" rows="4" class="form-control">{{ old('bio', $doctor->bio) }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3 text-center">
                                <label class="form-label fw-semibold d-block">Ảnh đại diện</label>
                                <div class="mb-2">
                                    <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : asset('assets/img/default-doctor.png') }}" 
                                         alt="Preview" class="img-thumbnail rounded-circle object-fit-cover shadow-sm" 
                                         style="width: 100px; height: 100px;">
                                </div>
                                <input type="file" name="image" class="form-control form-control-sm">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end pt-3 border-top gap-2">
                            <a href="{{ route('doctorsite.index') }}" class="btn btn-secondary fw-bold">
                                <i class="fas fa-times me-1"></i> Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .object-fit-cover { object-fit: cover; }
</style>
@endsection