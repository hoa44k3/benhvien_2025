@extends('admin.master')

@section('title', 'Chi tiết hồ sơ bệnh án')

@push('styles')
<style>
    .modal-backdrop { z-index: 1040 !important; }
    .modal { z-index: 1050 !important; }
    .status-badge { font-size: 0.9rem; padding: 0.5em 1em; border-radius: 20px; }
    .hover-zoom:hover { transform: scale(1.05); transition: transform 0.3s; cursor: pointer; }
</style>
@endpush

@section('body')
<div class="container-fluid mt-4 mb-5">

    {{-- LOGIC KIỂM TRA LOẠI KHOA ĐỂ ẨN/HIỆN FORM --}}
    @php
        // Danh sách các khoa CHỈ CẦN TƯ VẤN (Không cần upload xét nghiệm, không đo sinh tồn)
        $consultingDepts = ['Tâm lý', 'Dinh dưỡng', 'Tư vấn', 'Hỗ trợ'];
        
        $currentDept = $medical_record->department->name ?? '';
        
        $isConsulting = false;
        foreach ($consultingDepts as $dept) {
            if (\Illuminate\Support\Str::contains($currentDept, $dept)) {
                $isConsulting = true;
                break;
            }
        }
    @endphp

    {{-- HIỂN THỊ THÔNG BÁO LỖI/SUCCESS --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HEADER & TRẠNG THÁI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-file-medical-alt me-2 text-info"></i> Hồ sơ: <span class="text-primary">{{ $medical_record->title }}</span>
            </h3>
            @php
                $statusConfig = [
                    'chờ_khám' => ['class' => 'bg-secondary', 'label' => 'Chờ khám'],
                    'đang_khám' => ['class' => 'bg-primary', 'label' => 'Đang khám'],
                    'đã_khám' => ['class' => 'bg-success', 'label' => 'Hoàn thành'],
                    'hủy' => ['class' => 'bg-danger', 'label' => 'Đã hủy'],
                ];
                $currentStatus = $statusConfig[$medical_record->status] ?? ['class' => 'bg-secondary', 'label' => $medical_record->status];
            @endphp
            <span class="badge {{ $currentStatus['class'] }} status-badge">
                {{ strtoupper($currentStatus['label']) }}
            </span>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('medical_records.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
            <a href="{{ route('patients.timeline', $medical_record->user_id) }}" target="_blank" class="btn btn-info text-white shadow-sm fw-bold">
                <i class="fas fa-history me-2"></i> Xem Lịch sử / Timeline
            </a>

            @if($medical_record->status == 'chờ_khám')
                <form action="{{ route('medical_records.start', $medical_record->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary shadow fw-bold">
                        <i class="fas fa-play me-1"></i> BẮT ĐẦU KHÁM
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- NỘI DUNG CHÍNH --}}
    @if($medical_record->status == 'đang_khám' || $medical_record->status == 'đã_khám')
    <div class="row">
        {{-- CỘT TRÁI --}}
        <div class="col-lg-8">
            
            {{-- 1. THÔNG TIN BỆNH NHÂN --}}
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i> Thông tin hành chính</h6>
                </div>
                <div class="card-body p-3 small">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Bệnh nhân:</strong> {{ $medical_record->user->name }}</p>
                            <p class="mb-1"><strong>SĐT:</strong> {{ $medical_record->user->phone ?? '---' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Bác sĩ:</strong> {{ $medical_record->doctor->name ?? '---' }}</p>
                            <p class="mb-1"><strong>Khoa:</strong> {{ $medical_record->department->name ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. KHO MINH CHỨNG / KẾT QUẢ XÉT NGHIỆM (ẨN NẾU LÀ KHOA TƯ VẤN) --}}
            @if(!$isConsulting) 
            <div class="card shadow-lg border-info border-2 mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i> Minh chứng / Kết quả Xét nghiệm</h5>
                    {{-- Chỉ cho upload khi đang khám --}}
                    @if($medical_record->status == 'đang_khám')
                    <button class="btn btn-sm btn-light text-info fw-bold" data-bs-toggle="modal" data-bs-target="#uploadEvidenceModal">
                        <i class="fas fa-paperclip me-1"></i> Tải lên
                    </button>
                    @endif
                </div>
                <div class="card-body bg-light">
                    @if($medical_record->files && $medical_record->files->count() > 0)
                        <div class="row g-3">
                            @foreach($medical_record->files as $file)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100 shadow-sm border-0 position-relative">
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="ratio ratio-1x1 d-block overflow-hidden rounded-top">
                                            @if(Str::contains($file->file_type ?? $file->mime_type, ['image', 'jpg', 'png', 'jpeg']))
                                                <img src="{{ asset('storage/' . $file->file_path) }}" class="object-fit-cover w-100 h-100 hover-zoom" alt="Evidence">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 bg-white text-secondary">
                                                    <i class="fas fa-file-pdf fa-3x"></i>
                                                </div>
                                            @endif
                                        </a>
                                        <div class="card-footer p-1 bg-white small d-flex justify-content-between align-items-center">
                                            <span class="text-truncate" style="max-width: 80px;" title="{{ $file->original_name }}">{{ $file->original_name }}</span>
                                            @if($medical_record->status == 'đang_khám')
                                            <form action="{{ route('medical_records.delete_file', $file->id) }}" method="POST" onsubmit="return confirm('Xóa file này?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-xs text-danger p-0"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted border border-dashed rounded bg-white">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-secondary opacity-50"></i>
                            <p class="mb-0">Bệnh nhân chưa tải lên kết quả xét nghiệm/hình ảnh nào.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- 3. KẾT LUẬN & ĐIỀU TRỊ (FORM CHÍNH) --}}
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-md me-2"></i> Chẩn đoán & Kết luận</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('medical_records.update', $medical_record->id) }}" method="POST">
                        @csrf @method('PUT')
                        
                        {{-- 3.1 CHỈ SỐ SINH TỒN (Cũng ẨN nốt nếu là khoa Tâm lý) --}}
                        @if(!$isConsulting)
                        <div class="bg-blue-50 p-3 rounded mb-4 border border-blue-100">
                            <h6 class="fw-bold text-primary mb-3 text-uppercase small"><i class="fas fa-heartbeat me-1"></i> Chỉ số sinh tồn (Cập nhật nếu cần)</h6>
                            @php $vitals = json_decode($medical_record->vital_signs, true) ?? []; @endphp
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <label class="form-label small fw-bold text-muted">Huyết áp (mmHg)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="vital_signs[bp]" class="form-control fw-bold" value="{{ $vitals['bp'] ?? '' }}" placeholder="120/80">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small fw-bold text-muted">Mạch (lần/phút)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="vital_signs[hr]" class="form-control fw-bold text-danger" value="{{ $vitals['hr'] ?? '' }}" placeholder="75">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small fw-bold text-muted">Nhiệt độ (°C)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.1" name="vital_signs[temp]" class="form-control fw-bold text-warning" value="{{ $vitals['temp'] ?? '' }}" placeholder="37.0">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small fw-bold text-muted">Cân nặng (kg)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.1" name="vital_signs[weight]" class="form-control fw-bold" value="{{ $vitals['weight'] ?? '' }}" placeholder="60">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 3.2 Chẩn đoán & Triệu chứng --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Triệu chứng / Vấn đề của BN</label>
                                <textarea name="symptoms" class="form-control" rows="3" placeholder="Mô tả vấn đề...">{{ $medical_record->symptoms }}</textarea>
                            </div>
                            
                            {{-- Nếu là Tâm lý, đổi label "Chẩn đoán" thành "Đánh giá tâm lý" --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-danger">
                                    {{ $isConsulting ? 'Đánh giá / Kết luận tư vấn' : 'Chẩn đoán xác định' }}
                                </label>
                                <textarea name="diagnosis" class="form-control fw-bold" rows="2" placeholder="Kết luận của bác sĩ...">{{ $medical_record->diagnosis }}</textarea>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-success">Hướng giải quyết / Lời dặn</label>
                                <textarea name="treatment" class="form-control" rows="2" placeholder="Lời khuyên, hướng dẫn...">{{ $medical_record->treatment }}</textarea>
                            </div>
                        </div>

                        @if($medical_record->status == 'đang_khám')
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-save me-1"></i> Lưu Hồ Sơ
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI --}}
        <div class="col-lg-4">
            {{-- ĐƠN THUỐC --}}
            <div class="card shadow-sm border-success mb-4">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fas fa-pills me-2"></i> Đơn thuốc
                </div>
                <div class="card-body">
                    @if($medical_record->prescriptions->count() > 0)
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-1"></i> Đã kê đơn.
                            <a href="{{ route('prescriptions.edit', $medical_record->prescriptions->first()->id) }}" class="btn btn-sm btn-outline-success w-100 mt-2">Xem / Sửa đơn</a>
                        </div>
                    @else
                        @if($medical_record->status == 'đang_khám')
                            <a href="{{ route('prescriptions.create', ['medical_record_id' => $medical_record->id]) }}" class="btn btn-success w-100">
                                <i class="fas fa-plus me-1"></i> Kê đơn thuốc mới
                            </a>
                        @else
                            <p class="text-muted text-center mb-0">Không có đơn thuốc.</p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- NÚT HOÀN TẤT --}}
            @if($medical_record->status == 'đang_khám')
                <div class="card bg-warning bg-opacity-10 border-warning">
                    <div class="card-body text-center">
                        <p class="small text-muted mb-2">Sau khi đã lưu chẩn đoán và kê đơn:</p>
                        <form action="{{ route('medical_records.complete', $medical_record->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm" onclick="return confirm('Xác nhận hoàn tất?')">
                                <i class="fas fa-check-double me-2"></i> KẾT THÚC KHÁM
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- MODAL UPLOAD MINH CHỨNG (ẨN VỚI KHOA TƯ VẤN) --}}
@if(!$isConsulting)
<div class="modal fade" id="uploadEvidenceModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('medical_records.upload_evidence', $medical_record->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tải lên Minh chứng / Kết quả</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn file ảnh/PDF (Kết quả XN, X-Quang...)</label>
                        <input type="file" name="files[]" class="form-control" multiple accept="image/*,.pdf" required>
                        <div class="form-text">Hỗ trợ JPG, PNG, PDF. Tối đa 10MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tải lên ngay</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection