@extends('admin.master')

@section('title', 'Thêm Bác sĩ Mới')

@section('body')
<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                
                {{-- Card Header --}}
                <div class="card-header bg-primary text-white py-3 rounded-top-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-md me-2"></i> Thêm Hồ sơ Bác sĩ Mới</h4>
                </div>

                {{-- Card Body (Form) --}}
                <div class="card-body p-4">
                    <form action="{{ route('doctorsite.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        {{-- THÔNG TIN CƠ BẢN --}}
                        <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2">Thông tin tài khoản</h5>
                        <div class="row mb-3">
                            
                            {{-- Chọn User (Tài khoản) --}}
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label fw-semibold">Tài khoản bác sĩ <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option value="" disabled selected>-- Chọn tài khoản --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} (ID: {{ $user->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email của bác sĩ (Nếu khác)" value="{{ old('email') }}">
                                <div class="form-text text-muted">Thường là email đã đăng ký của tài khoản User.</div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- THÔNG TIN CHUYÊN MÔN --}}
                        <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2 mt-4">Thông tin chuyên môn</h5>
                        <div class="row mb-3">
                            
                            {{-- Chuyên khoa --}}
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label fw-semibold">Khoa <span class="text-danger">*</span></label>
                                <select name="department_id" id="department_id" class="form-select" required>
                                    <option value="" disabled selected>-- Chọn chuyên khoa --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Chuyên khoa chính --}}
                            <div class="col-md-6 mb-3">
                                <label for="specialization" class="form-label fw-semibold">Chuyên khoa chính</label>
                                <input type="text" name="specialization" id="specialization" class="form-control" placeholder="VD: Tim mạch, Nội tổng quát" value="{{ old('specialization') }}">
                                @error('specialization')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Số năm kinh nghiệm & Image --}}
                        <div class="row mb-4 align-items-end">
                            {{-- Số năm kinh nghiệm --}}
                            <div class="col-md-4 mb-3">
                                <label for="experience_years" class="form-label fw-semibold">Số năm kinh nghiệm</label>
                                <input type="number" name="experience_years" id="experience_years" min="0" class="form-control" value="{{ old('experience_years', 0) }}">
                                @error('experience_years')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ảnh bác sĩ --}}
                            <div class="col-md-8 mb-3">
                                <label for="image" class="form-label fw-semibold">Ảnh bác sĩ</label>
                                <input type="file" name="image" id="image" class="form-control">
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Giới thiệu --}}
                        <div class="mb-4">
                            <label for="bio" class="form-label fw-semibold">Giới thiệu (Bio)</label>
                            <textarea name="bio" id="bio" rows="4" class="form-control" placeholder="Mô tả về bằng cấp, kinh nghiệm và thành tựu của bác sĩ.">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ĐÁNH GIÁ VÀ TRẠNG THÁI --}}
                        <h5 class="mb-3 text-primary fw-semibold border-bottom pb-2 mt-4">Đánh giá & Trạng thái</h5>
                        <div class="row mb-4">
                            {{-- Điểm đánh giá --}}
                            <div class="col-md-4 mb-3">
                                <label for="rating" class="form-label fw-semibold">Điểm đánh giá (0-5)</label>
                                <input type="number" name="rating" id="rating" min="0" max="5" step="0.1" class="form-control" value="{{ old('rating', 0) }}">
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Số lượt đánh giá --}}
                            <div class="col-md-4 mb-3">
                                <label for="reviews_count" class="form-label fw-semibold">Số lượt đánh giá</label>
                                <input type="number" name="reviews_count" id="reviews_count" min="0" class="form-control" value="{{ old('reviews_count', 0) }}">
                                @error('reviews_count')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4 d-flex align-items-center pt-3">
                                <div class="form-check">
                                    <input type="checkbox" name="status" id="status" value="1" class="form-check-input" {{ old('status', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="status">Hoạt động</label>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nút Submit --}}
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('doctorsite.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold shadow">
                                <i class="fas fa-save me-1"></i> Lưu Hồ sơ Bác sĩ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection