@extends('admin.master')

@section('title', 'Chi tiết chuyên khoa')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-info text-white py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-eye me-2"></i> Chi tiết Chuyên khoa: {{ $department->name }}
            </h4>
        </div>
        <div class="card-body p-4">
            
            <div class="row">
                
                <div class="col-md-8 border-end">
                    <h5 class="text-primary mb-3">Thông tin tổng quan</h5>
                    <dl class="row mb-0">
                        
                        <dt class="col-sm-3 text-muted">Mã chuyên khoa:</dt>
                        <dd class="col-sm-9 fw-bold">{{ $department->code }}</dd>

                        <dt class="col-sm-3 text-muted">Trưởng khoa:</dt>
                        <dd class="col-sm-9">{{ $department->head_name ?? 'Chưa chỉ định' }}</dd>

                        <dt class="col-sm-3 text-muted">Trạng thái:</dt>
                        <dd class="col-sm-9">
                            @if($department->status == 'active')
                                <span class="badge bg-success py-2 px-3">Đang hoạt động</span>
                            @else
                                <span class="badge bg-warning text-dark py-2 px-3">Tạm dừng</span>
                            @endif
                        </dd>
                    </dl>

                    <h5 class="text-primary mt-4 mb-3">Nhân sự & Dịch vụ</h5>
                    <dl class="row mb-0">
                        
                        <dt class="col-sm-3 text-muted">Phí khám:</dt>
                        <dd class="col-sm-9 fw-bold text-success">{{ number_format($department->fee, 0, ',', '.') }} VNĐ</dd>

                        <dt class="col-sm-3 text-muted">Số bác sĩ:</dt>
                        <dd class="col-sm-9">
                            <i class="fas fa-user-md me-1 text-primary"></i> {{ number_format($department->num_doctors) }}
                        </dd>

                        <dt class="col-sm-3 text-muted">Số y tá:</dt>
                        <dd class="col-sm-9">
                            <i class="fas fa-user-nurse me-1 text-info"></i> {{ number_format($department->num_nurses) }}
                        </dd>

                        <dt class="col-sm-3 text-muted">Số phòng:</dt>
                        <dd class="col-sm-9">
                            <i class="fas fa-hospital-symbol me-1 text-secondary"></i> {{ number_format($department->num_rooms) }}
                        </dd>
                    </dl>

                    <h5 class="text-primary mt-4 mb-3">Mô tả</h5>
                    <div class="border p-3 bg-light rounded-3">
                        {{ $department->description ?? 'Không có mô tả chi tiết.' }}
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <h5 class="text-primary mb-3">Hình ảnh đại diện</h5>
                    @if($department->image)
                        <img src="{{ asset('storage/' . $department->image) }}" 
                             class="img-fluid rounded-3 shadow-sm border p-1" 
                             alt="Ảnh chuyên khoa" 
                             style="max-height: 250px; object-fit: cover;">
                    @else
                        <div class="bg-light p-5 rounded-3 border text-muted">
                            <i class="fas fa-camera fa-4x mb-2"></i>
                            <p>Chưa có ảnh đại diện</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="card-footer bg-light text-end">
            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection