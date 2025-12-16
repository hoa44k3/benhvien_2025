@extends('admin.master')

@section('title', 'Chỉnh sửa Bác sĩ')

@section('body')
<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                
                {{-- Card Header --}}
                <div class="card-header bg-warning text-dark py-3 rounded-top-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i> Chỉnh sửa Hồ sơ Bác sĩ</h4>
                    <span class="small text-muted">Chỉnh sửa thông tin cho bác sĩ: <strong>{{ $doctor->user->name ?? 'Không rõ' }}</strong></span>
                </div>

                {{-- Card Body (Form) --}}
                <div class="card-body p-4">
                    <form action="{{ route('doctorsite.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- THÔNG TIN TÀI KHOẢN --}}
                        <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2">Thông tin tài khoản</h5>
                        <div class="row mb-3">
                            {{-- Tên User --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Họ tên hiển thị</label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $doctor->user->name ?? '') }}" 
                                       class="form-control" required>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email đăng nhập</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email', $doctor->user->email ?? '') }}" 
                                       class="form-control" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        {{-- THÔNG TIN CHUYÊN MÔN --}}
                        <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2 mt-4">Thông tin chuyên môn</h5>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label fw-semibold">Khoa</label>
                                <select name="department_id" id="department_id" class="form-select">
                                    <option value="">-- Chọn chuyên khoa --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $doctor->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="specialization" class="form-label fw-semibold">Chuyên khoa chính</label>
                                <input type="text" name="specialization" id="specialization" 
                                       value="{{ old('specialization', $doctor->specialization) }}" 
                                       class="form-control" placeholder="VD: Tim mạch">
                                @error('specialization') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Kinh nghiệm & Ảnh --}}
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label for="experience_years" class="form-label fw-semibold">Kinh nghiệm (năm)</label>
                                <input type="number" name="experience_years" id="experience_years" min="0" 
                                       value="{{ old('experience_years', $doctor->experience_years) }}" class="form-control">
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="image" class="form-label fw-semibold">Ảnh đại diện</label>
                                <input type="file" name="image" id="image" class="form-control mb-2">
                                @if($doctor->image)
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/'.$doctor->image) }}" class="rounded-circle object-fit-cover me-2" width="40" height="40">
                                        <small class="text-muted">Ảnh hiện tại</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 🔥 PHẦN MỚI: TÀI CHÍNH & HOA HỒNG --}}
                        <h5 class="mb-3 text-success fw-semibold border-bottom pb-2 mt-4">
                            <i class="fas fa-hand-holding-usd me-1"></i> Cấu hình Lương & Hoa hồng
                        </h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="base_salary" class="form-label fw-semibold">Lương cứng (VNĐ/tháng)</label>
                                <div class="input-group">
                                    <input type="number" name="base_salary" id="base_salary" 
                                           value="{{ old('base_salary', $doctor->base_salary) }}" 
                                           class="form-control" min="0">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('base_salary') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-primary">% Hoa hồng Khám</label>
                                <div class="input-group">
                                    <input type="number" name="commission_exam_percent" 
                                           value="{{ old('commission_exam_percent', $doctor->commission_exam_percent) }}" 
                                           class="form-control" min="0" max="100" step="0.1">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-success">% Hoa hồng Thuốc</label>
                                <div class="input-group">
                                    <input type="number" name="commission_prescription_percent" 
                                           value="{{ old('commission_prescription_percent', $doctor->commission_prescription_percent) }}" 
                                           class="form-control" min="0" max="100" step="0.1">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-info">% Hoa hồng Dịch vụ</label>
                                <div class="input-group">
                                    <input type="number" name="commission_service_percent" 
                                           value="{{ old('commission_service_percent', $doctor->commission_service_percent) }}" 
                                           class="form-control" min="0" max="100" step="0.1">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Giới thiệu --}}
                        <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2 mt-4">Thông tin khác</h5>
                        <div class="mb-4">
                            <label for="bio" class="form-label fw-semibold">Giới thiệu (Bio)</label>
                            <textarea name="bio" id="bio" rows="3" class="form-control">{{ old('bio', $doctor->bio) }}</textarea>
                        </div>

                        {{-- Đánh giá & Trạng thái --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Rating</label>
                                <input type="number" name="rating" value="{{ old('rating', $doctor->rating) }}" class="form-control" step="0.1" max="5">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Lượt đánh giá</label>
                                <input type="number" name="review_count" value="{{ old('review_count', $doctor->review_count) }}" class="form-control">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="status" id="status" value="1" class="form-check-input" {{ old('status', $doctor->status) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="status">Đang hoạt động</label>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('doctorsite.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning fw-bold shadow">
                                <i class="fas fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection