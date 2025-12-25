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
            {{-- (Phần code chấm công cho chính bác sĩ đó xem) --}}
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
                        <div class="alert alert-info">Đã check-in lúc: <strong>{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</strong></div>
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
                        
                        {{-- Tên kèm Học vị --}}
                        <h4 class="fw-bold text-dark">
                            @if($doctor->degree) <span class="text-primary">{{ $doctor->degree }}</span> @endif
                            {{ $doctor->user->name ?? 'Không rõ' }}
                        </h4>
                        <p class="text-muted mb-2">{{ $doctor->user->email ?? '-' }}</p>
                        
                        @php
                            $status_class = $doctor->status ? 'bg-success' : 'bg-secondary';
                            $status_text = $doctor->status ? 'Hoạt động' : 'Đang ẩn';
                        @endphp
                        <span class="badge {{ $status_class }} fw-semibold mb-2">{{ $status_text }}</span>

                        {{-- Trạng thái chứng chỉ --}}
                        @if($doctor->license_number)
                            <div class="mt-2">
                                <span class="badge bg-white text-success border border-success">
                                    <i class="fas fa-check-circle me-1"></i> Đã xác thực CCHN
                                </span>
                            </div>
                        @else
                            <div class="mt-2">
                                <span class="badge bg-white text-warning border border-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Chưa có CCHN
                                </span>
                            </div>
                        @endif
                    </div>
                    <hr>
                    <div class="mt-3">
                        <a href="{{ route('doctorsite.edit', $doctor->id) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa Hồ sơ
                        </a>
                        <form action="{{ route('doctorsite.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa hồ sơ bác sĩ này? Dữ liệu liên quan có thể bị ảnh hưởng.');" class="d-inline w-100">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt me-1"></i> Xóa Hồ sơ
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Cột Phải: Thông tin chi tiết --}}
                <div class="col-md-8 ps-md-4">
                    
                    {{-- 1. Chuyên môn & Chứng chỉ (CẬP NHẬT) --}}
                    <h5 class="fw-bold text-primary mb-3 text-uppercase small border-bottom pb-2">
                        <i class="fas fa-certificate me-1"></i> Hồ sơ Chuyên môn & Pháp lý
                    </h5>
                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-blue-50 rounded h-100 border border-blue-100">
                                <p class="mb-2"><strong>Khoa:</strong> {{ $doctor->department->name ?? 'Chưa gán' }}</p>
                                <p class="mb-2"><strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Kinh nghiệm:</strong> <span class="text-success fw-bold">{{ $doctor->experience_years ?? 0 }}</span> năm</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-warning bg-opacity-10 rounded h-100 border border-warning border-opacity-25">
                                <p class="mb-2"><strong>Số CCHN:</strong> <span class="font-monospace text-dark">{{ $doctor->license_number ?? 'Chưa cập nhật' }}</span></p>
                                <p class="mb-2"><strong>Nơi cấp:</strong> {{ $doctor->license_issued_by ?? '---' }}</p>
                                @if($doctor->license_image)
                                    <a href="{{ asset('storage/'.$doctor->license_image) }}" target="_blank" class="btn btn-sm btn-outline-warning text-dark fw-bold bg-white mt-1">
                                        <i class="fas fa-file-image me-1 text-danger"></i> Xem ảnh chứng chỉ
                                    </a>
                                @else
                                    <span class="text-muted small fst-italic"><i class="fas fa-times-circle me-1"></i> Chưa upload ảnh</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 2. Tài chính & Ngân hàng --}}
                    <h5 class="fw-bold text-success mb-3 text-uppercase small border-bottom pb-2">
                        <i class="fas fa-wallet me-1"></i> Thông tin Tài chính
                    </h5>
                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-success small text-uppercase">Chính sách Lương</h6>
                                <p class="mb-1">Lương cứng: <strong class="fs-5">{{ number_format($doctor->base_salary) }} đ</strong></p>
                                <hr class="my-2">
                                <small class="text-muted d-block">Hoa hồng Khám: <strong>{{ $doctor->commission_exam_percent }}%</strong></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-info small text-uppercase">Tài khoản Nhận lương</h6>
                                @if($doctor->bank_account_number)
                                    <p class="mb-1 fw-bold">{{ $doctor->bank_name }}</p>
                                    <p class="mb-1 font-monospace text-primary fs-5">{{ $doctor->bank_account_number }}</p>
                                    <p class="mb-0 text-uppercase small text-muted">{{ $doctor->bank_account_holder }}</p>
                                @else <p class="text-muted small fst-italic">Chưa cập nhật.</p> @endif
                            </div>
                        </div>
                    </div>

                    {{-- 3. Giới thiệu --}}
                    <h5 class="fw-bold text-secondary mb-3 text-uppercase small border-bottom pb-2">
                        <i class="fas fa-info-circle me-1"></i> Giới thiệu (Bio)
                    </h5>
                    <div class="bg-light border p-3 rounded text-muted">
                        {{ $doctor->bio ?? 'Chưa có thông tin giới thiệu.' }}
                    </div>

                    <div class="text-muted small text-end mt-4">
                        <i class="far fa-clock me-1"></i> Ngày tạo hồ sơ: {{ $doctor->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style> 
    .object-fit-cover { object-fit: cover; } 
    .bg-blue-50 { background-color: #f0f7ff; }
    .border-blue-100 { border-color: #cfe2ff; }
</style>
@endsection