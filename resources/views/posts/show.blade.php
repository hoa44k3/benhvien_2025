@extends('admin.master')

@section('title', 'Chi tiết Bác sĩ')

@section('body')
<div class="container mt-4">

    <div class="card shadow-lg border-0 rounded-3">
        
        {{-- Card Header --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top-3">
            <h4 class="mb-0 fw-bold"><i class="fas fa-user-md me-2"></i> Hồ sơ Bác sĩ</h4>
            <a href="{{ route('doctorsite.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại Danh sách
            </a>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <div class="row">
                
                {{-- CỘT TRÁI: ẢNH VÀ HÀNH ĐỘNG --}}
                <div class="col-md-4 text-center border-end">
                    
                    {{-- Ảnh bác sĩ --}}
                    <div class="mb-3 position-relative">
                        <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : asset('assets/img/default-doctor.png') }}" 
                            class="img-fluid rounded-circle shadow-lg mb-3 object-fit-cover border border-4 border-light" 
                            alt="Ảnh bác sĩ" 
                            style="width: 200px; height: 200px;">
                        
                        <h4 class="fw-bold text-dark mb-0">{{ $doctor->user->name ?? 'Không rõ' }}</h4>
                        <p class="text-muted mb-2">{{ $doctor->user->email ?? '-' }}</p>
                        
                        {{-- Trạng thái --}}
                        @php
                            $status_class = $doctor->status ? 'bg-success' : 'bg-secondary';
                            $status_text = $doctor->status ? 'Đang hoạt động' : 'Đang ẩn';
                            $status_icon = $doctor->status ? 'fas fa-check-circle' : 'fas fa-eye-slash';
                        @endphp
                        <span class="badge {{ $status_class }} fw-semibold px-3 py-2 rounded-pill">
                            <i class="{{ $status_icon }} me-1"></i> {{ $status_text }}
                        </span>
                    </div>

                    <div class="d-grid gap-2 col-10 mx-auto mt-4">
                        <a href="{{ route('doctorsite.edit', $doctor->id) }}" class="btn btn-warning fw-bold shadow-sm">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa Hồ sơ
                        </a>
                        <form action="{{ route('doctorsite.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa bác sĩ {{ $doctor->user->name }}?\nHành động này sẽ xóa vĩnh viễn dữ liệu liên quan!');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm">
                                <i class="fas fa-trash-alt me-1"></i> Xóa Hồ sơ
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CỘT PHẢI: THÔNG TIN CHI TIẾT --}}
                <div class="col-md-8 ps-md-4">
                    
                    {{-- 1. Thông tin chuyên môn --}}
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-stethoscope me-2"></i> Thông tin Chuyên môn</h5>
                    <div class="row mb-4 g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded border-start border-4 border-primary h-100">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem">Khoa làm việc</small>
                                <span class="fw-bold fs-5 text-dark">{{ $doctor->department->name ?? 'Chưa gán' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded border-start border-4 border-info h-100">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem">Chuyên khoa</small>
                                <span class="fw-bold fs-5 text-dark">{{ $doctor->specialization ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded border-start border-4 border-success h-100">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem">Kinh nghiệm</small>
                                <span class="fw-bold fs-5 text-dark">{{ $doctor->experience_years ?? 0 }} Năm</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded border-start border-4 border-warning h-100">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem">Đánh giá</small>
                                <span class="fw-bold fs-5 text-dark">
                                    {{ number_format($doctor->rating, 1) }} <i class="fas fa-star text-warning"></i>
                                    <small class="text-muted fw-normal">({{ $doctor->reviews_count }} lượt)</small>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Thông tin Tài chính (MỚI BỔ SUNG) --}}
                    <h5 class="fw-bold text-success mb-3 border-top pt-4"><i class="fas fa-hand-holding-usd me-2"></i> Chính sách Lương & Hoa hồng</h5>
                    <div class="card border-success mb-4 shadow-sm bg-success bg-opacity-10">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-5 border-end border-success">
                                    <small class="text-success fw-bold text-uppercase">Lương cứng (Base Salary)</small>
                                    <h3 class="fw-bold text-success mt-1 mb-0">
                                        {{ number_format($doctor->base_salary, 0, ',', '.') }} <small class="fs-6">VNĐ</small>
                                    </h3>
                                </div>
                                <div class="col-md-7 ps-md-4 mt-3 mt-md-0">
                                    <div class="d-flex justify-content-between mb-2 border-bottom pb-1 border-success border-opacity-25">
                                        <span class="text-dark fw-semibold">Hoa hồng Khám bệnh:</span>
                                        <span class="badge bg-success">{{ $doctor->commission_exam_percent }}%</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 border-bottom pb-1 border-success border-opacity-25">
                                        <span class="text-dark fw-semibold">Hoa hồng Đơn thuốc:</span>
                                        <span class="badge bg-success">{{ $doctor->commission_prescription_percent }}%</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark fw-semibold">Hoa hồng Dịch vụ (CLS):</span>
                                        <span class="badge bg-success">{{ $doctor->commission_service_percent }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Giới thiệu --}}
                    <h5 class="fw-bold text-secondary mb-3 border-top pt-3"><i class="fas fa-quote-left me-2"></i> Giới thiệu</h5>
                    <div class="bg-light p-3 rounded fst-italic text-muted border">
                        "{{ $doctor->bio ?? 'Bác sĩ chưa cập nhật thông tin giới thiệu.' }}"
                    </div>

                    {{-- 4. Footer info --}}
                    <div class="mt-4 pt-3 border-top text-muted small">
                        <div class="row">
                            <div class="col-6">
                                <i class="far fa-clock me-1"></i> Ngày tạo: {{ $doctor->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-6 text-end">
                                <i class="fas fa-sync-alt me-1"></i> Cập nhật: {{ $doctor->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .object-fit-cover { object-fit: cover; }
    .badge { font-size: 0.9em; }
</style>
@endsection