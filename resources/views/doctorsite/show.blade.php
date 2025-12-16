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

        @if(auth()->check() && auth()->id() === $doctor->user_id)
            {{-- (Phần code chấm công giữ nguyên như cũ) --}}
            @php
                $todayAttendance = \App\Models\DoctorAttendance::where('doctor_id', auth()->id())
                    ->where('date', now()->toDateString())
                    ->first();
            @endphp
            <div class="card mt-4 border-success mx-4">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fas fa-calendar-check me-2"></i> Chấm công hôm nay
                </div>
                <div class="card-body">
                    @if(!$todayAttendance)
                        <form method="POST" action="{{ route('doctor_attendances.checkin') }}">
                            @csrf <button class="btn btn-success"><i class="fas fa-sign-in-alt"></i> Check-in</button>
                        </form>
                    @elseif(!$todayAttendance->check_out)
                        <div class="alert alert-info">Đã check-in lúc: <strong>{{ $todayAttendance->check_in }}</strong></div>
                        <form method="POST" action="{{ route('doctor_attendances.checkout') }}">
                            @csrf <button class="btn btn-warning"><i class="fas fa-sign-out-alt"></i> Check-out</button>
                        </form>
                    @else
                        <div class="alert alert-success">Hôm nay bạn đã chấm công đầy đủ.</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Card Body --}}
        <div class="card-body p-4">
            <div class="row">
                
                {{-- Cột Trái: Ảnh và Hành động --}}
                <div class="col-md-4 text-center border-end">
                    <div class="mb-3">
                        <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : asset('assets/img/default-doctor.png') }}" 
                            class="img-fluid rounded-circle shadow-lg mb-3 object-fit-cover" 
                            alt="Ảnh bác sĩ" style="width: 180px; height: 180px;">
                        <h4 class="fw-bold text-dark">{{ $doctor->user->name ?? 'Không rõ' }}</h4>
                        <p class="text-muted mb-1">{{ $doctor->user->email ?? '-' }}</p>
                        
                        @php
                            $status_class = $doctor->status ? 'bg-success' : 'bg-secondary';
                            $status_text = $doctor->status ? 'Hoạt động' : 'Ẩn';
                        @endphp
                        <span class="badge {{ $status_class }} fw-semibold">{{ $status_text }}</span>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <a href="{{ route('doctorsite.edit', $doctor->id) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa Hồ sơ
                        </a>
                        <form action="{{ route('doctorsite.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa?');" class="d-inline w-100">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt me-1"></i> Xóa Hồ sơ
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Cột Phải: Thông tin chi tiết --}}
                <div class="col-md-8">
                    
                    {{-- Chuyên môn --}}
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-user-md me-1"></i> Chuyên môn & Đánh giá</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Khoa:</strong> {{ $doctor->department->name ?? 'Chưa gán' }}</p>
                            <p class="mb-2"><strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Kinh nghiệm:</strong> <span class="text-success fw-bold">{{ $doctor->experience_years ?? 0 }}</span> năm</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Đánh giá:</strong> <span class="fw-bold text-warning">{{ number_format($doctor->rating, 1) }} <i class="fas fa-star"></i></span></p>
                            <p class="mb-2"><strong>Lượt đánh giá:</strong> {{ $doctor->reviews_count ?? 0 }}</p>
                        </div>
                    </div>

                    {{-- Tài chính & Ngân hàng (MỚI) --}}
                    <h5 class="fw-bold text-success mb-3 border-top pt-3"><i class="fas fa-wallet me-1"></i> Thông tin Tài chính</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="fw-bold text-success">Lương & Hoa hồng</h6>
                                <p class="mb-1">Lương cứng: <strong>{{ number_format($doctor->base_salary) }} đ</strong></p>
                                <small class="text-muted d-block">HH Khám: {{ $doctor->commission_exam_percent }}%</small>
                                <small class="text-muted d-block">HH Thuốc: {{ $doctor->commission_prescription_percent }}%</small>
                                <small class="text-muted d-block">HH Dịch vụ: {{ $doctor->commission_service_percent }}%</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="fw-bold text-info">Tài khoản Ngân hàng</h6>
                                @if($doctor->bank_account_number)
                                    <p class="mb-1">Ngân hàng: <strong>{{ $doctor->bank_name }}</strong></p>
                                    <p class="mb-1">STK: <strong class="text-primary font-monospace">{{ $doctor->bank_account_number }}</strong></p>
                                    <p class="mb-0">Chủ TK: {{ $doctor->bank_account_holder }}</p>
                                @else
                                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin ngân hàng.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-secondary mb-3 border-top pt-3"><i class="fas fa-info-circle me-1"></i> Giới thiệu</h5>
                    <div class="alert alert-light border p-3">
                        <p class="mb-0 text-muted">{{ $doctor->bio ?? 'Không có thông tin giới thiệu.' }}</p>
                    </div>

                    <div class="text-muted small text-end mt-4">
                        Ngày tạo: {{ $doctor->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style> .object-fit-cover { object-fit: cover; } </style>
@endsection