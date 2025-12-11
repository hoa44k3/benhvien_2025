@extends('admin.master')

@section('title', 'Chi tiết Bác sĩ')

@section('body')
<div class="container mt-4">

    <div class="card shadow-lg border-0 rounded-3">
        
        {{-- Card Header --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top-3">
            <h4 class="mb-0 fw-bold"><i class="fas fa-id-card-alt me-2"></i> Hồ sơ Bác sĩ</h4>
            <a href="{{ route('doctorsite.index') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại Danh sách
            </a>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <div class="row">
                
                {{-- Cột Ảnh và Hành động --}}
                <div class="col-md-4 text-center border-end">
                    
                    {{-- Ảnh bác sĩ --}}
                    <div class="mb-3">
                        <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : asset('assets/img/default-doctor.png') }}" 
                            class="img-fluid rounded-circle shadow-lg mb-3 object-fit-cover" 
                            alt="Ảnh bác sĩ" 
                            style="width: 180px; height: 180px;">
                        <h4 class="fw-bold text-dark">{{ $doctor->user->name ?? 'Không rõ' }}</h4>
                        <p class="text-muted mb-1">{{ $doctor->user->email ?? '-' }}</p>
                        
                        {{-- Trạng thái --}}
                        @php
                            $status_class = $doctor->status ? 'bg-success' : 'bg-secondary';
                            $status_text = $doctor->status ? 'Hoạt động' : 'Ẩn';
                            $status_icon = $doctor->status ? 'fas fa-check-circle' : 'fas fa-eye-slash';
                        @endphp
                        <span class="badge {{ $status_class }} fw-semibold"><i class="{{ $status_icon }} me-1"></i> {{ $status_text }}</span>
                    </div>

                    <hr>
                    
                    {{-- Nút Hành động --}}
                    <div class="mt-3">
                        <a href="{{ route('doctorsite.edit', $doctor->id) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa Hồ sơ
                        </a>
                        <form action="{{ route('doctorsite.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa bác sĩ {{ $doctor->user->name ?? $doctor->id }}? Hành động này không thể hoàn tác.');" class="d-inline w-100">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt me-1"></i> Xóa Hồ sơ
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Cột Thông tin chi tiết --}}
                <div class="col-md-8">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Chi tiết Chuyên môn</h5>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Khoa:</strong> <span class="fw-semibold">{{ $doctor->department->name ?? 'Chưa gán' }}</span></p>
                            <p class="mb-2"><strong>Chuyên khoa chính:</strong> <span class="fw-semibold">{{ $doctor->specialization ?? 'N/A' }}</span></p>
                            <p class="mb-2"><strong>Kinh nghiệm:</strong> <span class="fw-semibold text-success">{{ $doctor->experience_years ?? 0 }}</span> năm</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Đánh giá:</strong> 
                                <span class="fw-bold text-warning"><i class="fas fa-star me-1"></i> {{ number_format($doctor->rating, 1) }}/5</span>
                            </p>
                            <p class="mb-2">
                                <strong>Lượt đánh giá:</strong> 
                                <span class="fw-semibold">{{ $doctor->reviews_count ?? 0 }}</span>
                            </p>
                        </div>
                    </div>

                    <h5 class="fw-bold text-primary mb-3 border-top pt-3"><i class="fas fa-file-alt me-1"></i> Giới thiệu</h5>
                    <div class="alert alert-light border p-3">
                        <p class="mb-0 text-muted">{{ $doctor->bio ?? 'Không có thông tin giới thiệu.' }}</p>
                    </div>

                    <h5 class="fw-bold text-primary mb-3 border-top pt-3"><i class="fas fa-history me-1"></i> Lịch sử Hệ thống</h5>
                    <div class="row small text-muted">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>ID Hồ sơ:</strong> {{ $doctor->id }}</p>
                            <p class="mb-1"><strong>Ngày tạo:</strong> {{ $doctor->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Cập nhật bởi ID User:</strong> {{ $doctor->user_id }}</p>
                            <p class="mb-1"><strong>Cập nhật lần cuối:</strong> {{ $doctor->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endsection