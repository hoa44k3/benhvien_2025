@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-eye me-2 text-info"></i> Chi tiết Dịch vụ: **{{ $service->name }}**
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-1"></i> Thông tin Dịch vụ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Cột bên trái: Dữ liệu cơ bản --}}
                        <div class="col-md-7 border-end pe-4">

                            {{-- Tên & ID --}}
                            <div class="mb-4 pb-2 border-bottom">
                                <h4 class="fw-bold text-primary">{{ $service->name }}</h4>
                                <p class="text-muted small mb-0">ID: {{ $service->id }}</p>
                            </div>

                            {{-- Mô tả --}}
                            <div class="mb-4">
                                <h6 class="fw-semibold text-dark"><i class="fas fa-file-signature me-2"></i> Mô tả ngắn:</h6>
                                <p class="text-muted ps-4 border-start border-3 border-secondary">{{ $service->description ?? 'Không có mô tả.' }}</p>
                            </div>
                            
                            {{-- Chi tiết nội dung --}}
                            <div class="mb-4">
                                <h6 class="fw-semibold text-dark"><i class="fas fa-list-alt me-2"></i> Chi tiết nội dung:</h6>
                                {{-- Sử dụng {!! !!} nếu nội dung có thể chứa HTML --}}
                                <div class="p-3 bg-light rounded text-break">
                                    {!! $service->content ?? 'Không có chi tiết nội dung.' !!}
                                </div>
                            </div>
                            
                        </div>

                        {{-- Cột bên phải: Thông tin chi tiết & Ảnh --}}
                        <div class="col-md-5 ps-4">
                            
                            {{-- Thông tin chi tiết --}}
                            <div class="mb-4">
                                <h6 class="fw-semibold text-dark"><i class="fas fa-sliders-h me-2"></i> Thuộc tính:</h6>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Phí:</span>
                                        <span class="text-success fw-bold">
                                            @if(!$service->fee || $service->fee == 0)
                                                <i class="fas fa-phone me-1"></i> Liên hệ
                                            @else
                                                {{ number_format($service->fee, 0, ',', '.') }} VNĐ
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Thời gian:</span>
                                        <span>
                                            @if($service->duration == 0 || $service->duration === null)
                                                <span class="badge bg-warning text-dark">Liên tục</span>
                                            @else
                                                <span class="text-muted">{{ $service->duration }} phút</span>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Trạng thái:</span>
                                        <span>
                                            @if($service->status)
                                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-minus-circle"></i> Inactive</span>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Danh mục:</span>
                                        <span class="text-info">{{ $service->category->name ?? '-' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Chuyên khoa:</span>
                                        <span class="text-primary">{{ $service->department->name ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>

                            {{-- Ảnh --}}
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-semibold text-dark mb-3"><i class="fas fa-image me-2"></i> Ảnh minh họa:</h6>
                                @if($service->image)
                                    <img src="{{ asset('storage/'.$service->image) }}" alt="Ảnh dịch vụ" 
                                         class="img-fluid rounded shadow-sm border" style="max-height: 250px;">
                                @else
                                    <div class="text-muted small p-3 border rounded bg-light text-center">Không có ảnh.</div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between bg-light border-0 py-3">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-warning text-dark">
                        <i class="fas fa-edit me-1"></i> Sửa Dịch vụ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection