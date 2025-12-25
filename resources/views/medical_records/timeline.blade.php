@extends('admin.master')

@section('title', 'Lộ trình điều trị: ' . $patient->name)

@section('body')
{{-- CSS RIÊNG CHO TIMELINE NGANG --}}
<style>
    /* 1. THANH CUỘN NGANG */
    .timeline-nav-wrapper {
        position: relative;
        margin-bottom: 30px;
        padding: 10px 0;
    }
    
    .timeline-scroll-container {
        display: flex;
        overflow-x: auto;
        padding: 20px 10px;
        gap: 0;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #dee2e6 #fff;
    }
    
    .timeline-nav-wrapper::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background: #e9ecef;
        transform: translateY(-50%);
        z-index: 0;
        border-radius: 4px;
    }

    /* Node Item */
    .timeline-step {
        position: relative;
        z-index: 1;
        flex: 0 0 auto;
        width: 140px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.7;
    }

    .timeline-step:hover { opacity: 1; transform: translateY(-2px); }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 4px solid #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.2rem;
        color: #6c757d;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .step-content {
        background: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        display: inline-block;
        border: 1px solid #f1f1f1;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .step-date { font-weight: 700; font-size: 0.9rem; color: #495057; display: block; }
    .step-label { font-size: 0.75rem; color: #6c757d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px; }

    /* Active State */
    .timeline-step.active { opacity: 1; }
    .timeline-step.active .step-icon {
        border-color: #0d6efd; background: #0d6efd; color: #fff; transform: scale(1.15); box-shadow: 0 0 0 6px rgba(13, 110, 253, 0.2);
    }
    .timeline-step.active .step-content { border-color: #0d6efd; background: #f0f7ff; }
    .timeline-step.active .step-date { color: #0d6efd; }

    /* 2. HIỆU ỨNG HIỆN CHI TIẾT */
    .record-detail-card { display: none; animation: slideUpFade 0.4s ease-out forwards; }
    .record-detail-card.active { display: block; }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Style cho file đính kèm */
    .file-preview-card {
        transition: transform 0.2s;
        border: 1px solid #dee2e6;
    }
    .file-preview-card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-color: #0d6efd;
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER BỆNH NHÂN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="me-3 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm" style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                {{ substr($patient->name, 0, 1) }}
            </div>
            <div>
                <h3 class="fw-bold mb-0 text-dark">{{ $patient->name }}</h3>
                <div class="text-muted small">
                    <span class="me-3"><i class="fas fa-id-card me-1"></i> BN{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span><i class="fas fa-phone me-1"></i> {{ $patient->phone }}</span>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('medical_records.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            <a href="{{ route('medical_records.create') }}?user_id={{ $patient->id }}" class="btn btn-primary btn-sm shadow-sm ms-2">
                <i class="fas fa-plus me-1"></i> Khám mới
            </a>
        </div>
    </div>

    {{-- 1. THANH TIMELINE NGANG --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="timeline-nav-wrapper">
                <div class="timeline-scroll-container">
                    @forelse($history as $index => $record)
                        <div class="timeline-step {{ $index == 0 ? 'active' : '' }}" 
                             onclick="showRecord('record-{{ $record->id }}', this)">
                            <div class="step-icon">
                                @if($index == 0) <i class="fas fa-star"></i>
                                @else <i class="fas fa-stethoscope"></i> @endif
                            </div>
                            <div class="step-content">
                                <span class="step-date">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</span>
                                <div class="step-label" title="{{ $record->title }}">{{ Str::limit($record->title, 15) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center w-100 py-3 text-muted">Chưa có dữ liệu lịch sử.</div>
                    @endforelse
                </div>
            </div>
            <div class="text-center pb-3 text-muted small fst-italic">
                <i class="fas fa-hand-pointer me-1"></i> Nhấn vào các mốc thời gian ở trên để xem chi tiết
            </div>
        </div>
    </div>

    {{-- 2. KHU VỰC HIỂN THỊ CHI TIẾT --}}
    <div class="row justify-content-center">
        <div class="col-lg-12"> {{-- Mở rộng full width để chứa ảnh --}}
            
            @foreach($history as $index => $record)
                <div id="record-{{ $record->id }}" class="record-detail-card {{ $index == 0 ? 'active' : '' }}">
                    
                    <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                        {{-- Header Card --}}
                        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary me-2 fs-6">
                                    <i class="fas fa-calendar-day me-1"></i> {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                                </span>
                                <span class="fw-bold text-dark fs-5 align-middle">{{ $record->title }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="text-end me-3 d-none d-md-block">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem">Bác sĩ phụ trách</small>
                                    <span class="fw-bold text-primary">{{ $record->doctor->name ?? '---' }}</span>
                                </div>
                                <img src="{{ $record->doctor->image ? asset('storage/'.$record->doctor->image) : 'https://ui-avatars.com/api/?name='.urlencode($record->doctor->name ?? 'BS') }}" 
                                     class="rounded-circle border" width="45" height="45">
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                {{-- CỘT 1: THÔNG TIN LÂM SÀNG --}}
                                <div class="col-md-5">
                                    <h6 class="text-uppercase text-secondary fw-bold small border-bottom pb-2 mb-3">
                                        <i class="fas fa-clipboard-list me-1"></i> Thông tin lâm sàng
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="fw-bold text-dark mb-1">Triệu chứng:</label>
                                        <div class="bg-light p-2 rounded border border-start-0 border-end-0 fst-italic text-secondary small">
                                            "{{ $record->symptoms ?? 'Không có mô tả' }}"
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold text-dark mb-1">Chẩn đoán:</label>
                                        <div class="alert alert-info border-0 border-start border-4 border-info shadow-sm text-dark fw-bold py-2 mb-0">
                                            <i class="fas fa-virus me-2"></i> {{ $record->diagnosis ?? 'Đang cập nhật...' }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold text-dark mb-1">Hướng điều trị:</label>
                                        <p class="text-muted mb-0 small">{{ $record->treatment ?? 'Tuân thủ đơn thuốc.' }}</p>
                                    </div>

                                    {{-- Chỉ số sinh tồn --}}
                                    @if($record->vital_signs)
                                        @php $vitals = json_decode($record->vital_signs, true); @endphp
                                        <div class="mt-4 p-3 bg-slate-50 rounded border border-dashed">
                                            <h6 class="text-xs fw-bold text-uppercase text-muted mb-2">Chỉ số sinh tồn</h6>
                                            <div class="d-flex justify-content-between text-center">
                                                <div><small class="d-block text-muted">HA</small><strong>{{ $vitals['bp'] ?? '-' }}</strong></div>
                                                <div><small class="d-block text-muted">Mạch</small><strong class="text-danger">{{ $vitals['hr'] ?? '-' }}</strong></div>
                                                <div><small class="d-block text-muted">Nhiệt</small><strong class="text-warning">{{ $vitals['temp'] ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- CỘT 2: MINH CHỨNG / FILE ĐÍNH KÈM (PHẦN MỚI QUAN TRỌNG) --}}
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <h6 class="text-uppercase text-secondary fw-bold small border-bottom pb-2 mb-3">
                                        <i class="fas fa-images me-1"></i> Minh chứng / Kết quả XN
                                    </h6>

                                    @if($record->files && $record->files->count() > 0)
                                        <div class="row g-2">
                                            @foreach($record->files as $file)
                                                <div class="col-6">
                                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-decoration-none">
                                                        <div class="card h-100 file-preview-card">
                                                            <div class="ratio ratio-4x3 bg-light rounded-top overflow-hidden">
                                                                @if(Str::contains($file->file_type ?? $file->mime_type, ['image', 'jpg', 'png', 'jpeg']))
                                                                    <img src="{{ asset('storage/' . $file->file_path) }}" class="object-fit-cover w-100 h-100" alt="File">
                                                                @else
                                                                    <div class="d-flex align-items-center justify-content-center h-100 text-secondary">
                                                                        <i class="fas fa-file-pdf fa-2x"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="p-1 text-center bg-white border-top">
                                                                <small class="d-block text-truncate" style="font-size: 10px;" title="{{ $file->original_name }}">
                                                                    {{ $file->original_name }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 bg-light rounded border border-dashed">
                                            <i class="fas fa-file-upload text-muted opacity-25 fa-2x mb-2"></i>
                                            <p class="mb-0 small text-muted">Không có file đính kèm.</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- CỘT 3: ĐƠN THUỐC --}}
                                <div class="col-md-3 border-start-md ps-md-4">
                                    <h6 class="text-uppercase text-secondary fw-bold small border-bottom pb-2 mb-3">
                                        <i class="fas fa-pills me-1"></i> Đơn thuốc
                                    </h6>
                                    @php $prescription = $record->prescriptions->first(); @endphp
                                    @if($prescription)
                                        <div class="card bg-warning bg-opacity-10 border-warning mb-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <strong class="text-dark small">Mã: {{ $prescription->code }}</strong>
                                                    <a href="{{ route('prescriptions.show', $prescription->id) }}" class="text-primary small" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                                </div>
                                                <div class="small text-muted">{{ $prescription->items->count() }} loại thuốc</div>
                                            </div>
                                        </div>
                                        
                                        {{-- List thuốc nhanh --}}
                                        <ul class="list-group list-group-flush small">
                                            @foreach($prescription->items->take(3) as $item)
                                                <li class="list-group-item bg-transparent px-0 py-1 d-flex justify-content-between">
                                                    <span class="text-truncate" style="max-width: 120px;">{{ $item->medicine->name ?? 'Thuốc' }}</span>
                                                    <span class="fw-bold">{{ $item->quantity }}</span>
                                                </li>
                                            @endforeach
                                            @if($prescription->items->count() > 3)
                                                <li class="list-group-item bg-transparent px-0 py-1 text-center text-muted fst-italic">...và còn nữa</li>
                                            @endif
                                        </ul>
                                    @else
                                        <div class="text-muted small fst-italic border rounded p-2 text-center bg-light">Không kê đơn.</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                            <div>
                                @if($record->next_checkup)
                                    <span class="badge bg-success">
                                        <i class="fas fa-calendar-check me-1"></i> Hẹn tái khám: {{ \Carbon\Carbon::parse($record->next_checkup)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">Không có lịch tái khám.</span>
                                @endif
                            </div>
                            <a href="{{ route('medical_records.show', $record->id) }}" class="btn btn-sm btn-outline-primary fw-bold">
                                Xem hồ sơ đầy đủ <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach

            @if($history->isEmpty())
                <div class="alert alert-warning text-center p-5">
                    <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                    <h5>Chưa có hồ sơ nào!</h5>
                    <p>Bệnh nhân này chưa có lịch sử khám bệnh.</p>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    function showRecord(recordId, element) {
        // 1. Reset Active trên Timeline
        document.querySelectorAll('.timeline-step').forEach(step => {
            step.classList.remove('active');
        });
        element.classList.add('active');

        // 2. Ẩn tất cả Card chi tiết
        document.querySelectorAll('.record-detail-card').forEach(card => {
            card.classList.remove('active');
        });

        // 3. Hiện Card tương ứng
        const target = document.getElementById(recordId);
        if (target) {
            target.classList.add('active');
        }
        
        // 4. Scroll mốc thời gian vào giữa
        element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
</script>
@endsection