@extends('admin.master')

@section('title', 'Chi tiết kết quả xét nghiệm')

@section('body')
<div class="container mt-4 mb-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="fas fa-file-medical-alt me-2 text-primary"></i> Chi tiết Xét nghiệm
            </h3>
            <p class="text-muted mb-0">Mã phiếu: #{{ $testResult->id }}</p>
        </div>
        
        <div>
            <a href="{{ route('test_results.edit', $testResult->id) }}" class="btn btn-warning shadow-sm fw-bold">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('test_results.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-flask me-2"></i> Kết quả phân tích</h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted fw-bold small">TÊN XÉT NGHIỆM</label>
                            <h5 class="text-dark fw-bold">{{ $testResult->test_name }}</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted fw-bold small">NGÀY THỰC HIỆN</label>
                            <p class="fs-5">{{ \Carbon\Carbon::parse($testResult->date)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted fw-bold small">KẾT QUẢ</label>
                        <div class="alert {{ $testResult->result ? 'alert-info' : 'alert-secondary' }} border-0 shadow-sm">
                            @if($testResult->result)
                                <span class="fw-bold fs-5">{{ $testResult->result }}</span>
                                @if($testResult->unit) <span class="text-muted">({{ $testResult->unit }})</span> @endif
                            @else
                                <em class="text-muted">Chưa cập nhật kết quả</em>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted fw-bold small">ĐÁNH GIÁ CỦA BÁC SĨ / KẾT LUẬN</label>
                        <div class="bg-light p-3 rounded border">
                            @if($testResult->evaluation || $testResult->diagnosis)
                                <p class="mb-0 text-dark">{{ $testResult->evaluation ?? $testResult->diagnosis }}</p>
                            @else
                                <em class="text-muted small">Chưa có đánh giá</em>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-muted fw-bold small mb-2">FILE ĐÍNH KÈM (KẾT QUẢ GỐC)</label>
                        @if($testResult->file_main)
                            <div class="d-flex align-items-center p-3 border rounded bg-white">
                                <i class="fas fa-file-pdf text-danger fs-2 me-3"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-0 fw-bold text-truncate" style="max-width: 300px;">{{ basename($testResult->file_main) }}</p>
                                    <small class="text-muted">Nhấn để xem hoặc tải về</small>
                                </div>
                                <a href="{{ asset('storage/'.$testResult->file_main) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Xem
                                </a>
                            </div>
                        @else
                            <p class="text-muted fst-italic mb-0">Không có file đính kèm.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: THÔNG TIN HÀNH CHÍNH --}}
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">Thông tin hành chính</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Trạng thái</span>
                            @php
                                $statusClass = match($testResult->status) {
                                    'completed' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'reviewed' => 'bg-primary',
                                    'archived' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                };
                                $statusLabel = match($testResult->status) {
                                    'completed' => 'Đã có KQ',
                                    'pending' => 'Chờ KQ',
                                    'reviewed' => 'Đã duyệt',
                                    'archived' => 'Lưu trữ',
                                    default => $testResult->status
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} rounded-pill">{{ $statusLabel }}</span>
                        </li>
                        <li class="list-group-item px-0">
                            <small class="text-muted d-block">Bệnh nhân</small>
                            <span class="fw-bold text-primary">{{ $testResult->patient->name ?? $testResult->user->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item px-0">
                            <small class="text-muted d-block">Bác sĩ chỉ định</small>
                            <span class="fw-bold">{{ $testResult->doctor->name ?? '---' }}</span>
                        </li>
                        <li class="list-group-item px-0">
                            <small class="text-muted d-block">Phòng thực hiện</small>
                            <span class="fw-bold">{{ $testResult->lab_name ?? 'Tại chỗ' }}</span>
                        </li>
                        <li class="list-group-item px-0">
                            <small class="text-muted d-block">Ghi chú</small>
                            <span>{{ $testResult->note ?? 'Không có' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- LIÊN KẾT HỒ SƠ --}}
            @if($testResult->medical_record_id)
            <div class="card shadow-sm border-info border-2">
                <div class="card-body text-center">
                    <h6 class="text-info fw-bold mb-2">Thuộc Hồ sơ bệnh án</h6>
                    <p class="small text-muted mb-3">Mã hồ sơ: #{{ $testResult->medical_record_id }}</p>
                    <a href="{{ route('medical_records.show', $testResult->medical_record_id) }}" class="btn btn-outline-info btn-sm w-100">
                        Xem Hồ sơ gốc
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection