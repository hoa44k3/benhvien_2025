@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">
    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-tachometer-alt me-2"></i> Bảng điều khiển Quản trị
    </h3>
    <hr>

    <div class="row g-4 mb-5">
        
        {{-- 1. TỔNG BỆNH NHÂN --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Tổng bệnh nhân</div>
                            <h2 class="display-4 fw-bolder">{{ number_format($totalPatients) }}</h2>
                        </div>
                        <i class="fas fa-user-injured fa-4x opacity-50"></i>
                    </div>
                </div>
                {{-- Link đến danh sách User --}}
                <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-white bg-opacity-10">
                    <a class="small text-white text-decoration-none" href="{{ route('users.index') }}">Xem chi tiết</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        {{-- 2. LỊCH HẸN HÔM NAY --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Lịch hẹn hôm nay</div>
                            <h2 class="display-4 fw-bolder">{{ number_format($todayAppointments) }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-4x opacity-50"></i>
                    </div>
                </div>
                {{-- Link đến danh sách Lịch hẹn --}}
                <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-dark bg-opacity-10">
                    <a class="small text-dark text-decoration-none" href="{{ route('appointments.index') }}">Xem danh sách</a>
                    <i class="fas fa-angle-right text-dark"></i>
                </div>
            </div>
        </div>

      
{{-- 3. DOANH THU THÁNG (MỚI) --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75 small">Doanh thu tháng {{ date('m') }}</div>
                            <h3 class="fw-bolder mb-0">{{ number_format($monthlyRevenue, 0, ',', '.') }} đ</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-hand-holding-usd fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 bg-black bg-opacity-10 py-2">
                    <a class="text-white text-decoration-none small d-flex justify-content-between align-items-center" href="{{ route('reports.index') }}">
                        Xem báo cáo <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        {{-- 4. Bácbsix --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75 small">Đội ngũ Bác sĩ</div>
                            <h2 class="display-5 fw-bolder mb-0">{{ $totalDoctors }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-user-md fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 bg-black bg-opacity-10 py-2">
                    {{-- Giả sử route quản lý bác sĩ là doctors.index --}}
                    <a class="text-white text-decoration-none small d-flex justify-content-between align-items-center" href="{{ Route::has('doctors.index') ? route('doctors.index') : '#' }}">
                        Quản lý bác sĩ <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        {{-- TRẠNG THÁI PHÒNG --}}
       {{-- [MỚI] TÌNH HÌNH ĐƠN THUỐC (Thay cho Phòng khám) --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 rounded-3 border-start border-3 border-primary" style="border-color: #6610f2 !important;"> {{-- Màu tím --}}
                <div class="card-header bg-white fw-bold" style="color: #6610f2;">
                    <i class="fas fa-file-prescription me-2"></i> Tình hình Đơn thuốc
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Đơn tạo hôm nay:
                            <span class="badge rounded-pill" style="background-color: #6610f2;">{{ $todayPrescriptions }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Đang kê / Chờ xử lý:
                            <span class="badge bg-warning text-dark rounded-pill">{{ $pendingPrescriptions }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Đã phát thuốc (Hoàn thành):
                            <span class="badge bg-success rounded-pill">{{ $completedPrescriptions }}</span>
                        </li>
                    </ul>
                    <div class="mt-3 text-center">
                        <a href="{{ route('prescriptions.create') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-plus"></i> Tạo đơn mới
                        </a>
                    </div>
                </div>
                <div class="card-footer text-end bg-white">
                    <a href="{{ route('prescriptions.index') }}" class="btn btn-sm" style="color: #6610f2; border-color: #6610f2;">Quản lý đơn thuốc</a>
                </div>
            </div>
        </div>

        {{-- CẢNH BÁO KHO THUỐC --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 rounded-3 border-start border-3 border-danger">
                <div class="card-header bg-white fw-bold text-danger">
                    <i class="fas fa-prescription-bottle-alt me-2"></i> Cảnh báo kho thuốc
                </div>
                <div class="card-body">
                    {{-- Cảnh báo thuốc sắp hết --}}
                    <div class="alert alert-warning d-flex align-items-center mb-2" role="alert">
                        <i class="fas fa-exclamation-triangle flex-shrink-0 me-2"></i>
                        <div>
                            <strong class="me-1">Sắp hết hàng:</strong> 
                            <span class="fs-5 fw-bold">{{ $lowMedicines }}</span> loại
                        </div>
                        {{-- Link lọc thuốc sắp hết --}}
                        <a href="{{ route('medicines.index', ['alert' => 'low_stock']) }}" class="btn btn-sm btn-warning ms-auto">Xem</a>
                    </div>

                    {{-- Cảnh báo thuốc hết hạn --}}
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-calendar-times flex-shrink-0 me-2"></i>
                        <div>
                            <strong class="me-1">Đã hết hạn:</strong> 
                            <span class="fs-5 fw-bold">{{ $expiredMedicines }}</span> loại
                        </div>
                        {{-- Link lọc thuốc hết hạn --}}
                        <a href="{{ route('medicines.index', ['alert' => 'expired']) }}" class="btn btn-sm btn-danger ms-auto text-white">Xem</a>
                    </div>
                </div>
                <div class="card-footer text-end bg-white">
                    <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-outline-danger">Vào kho thuốc</a>
                </div>
            </div>
        </div>

        {{-- HOẠT ĐỘNG GẦN ĐÂY --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100 rounded-3 border-start border-3 border-secondary">
                <div class="card-header bg-white fw-bold text-secondary">
                    <i class="fas fa-history me-2"></i> Hoạt động gần đây
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $log)
                            <div class="list-group-item">
                                <p class="mb-1">
                                    <span class="fw-bold text-dark">{{ $log->user?->name ?? 'Hệ thống' }}</span> 
                                    <span class="text-muted small">– {{ Str::limit($log->action, 30) }}</span>
                                </p>
                                <small class="text-muted" style="font-size: 0.8rem">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $log->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m H:i') }}
                                </small>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="fas fa-box-open d-block mb-2 text-secondary opacity-50" style="font-size: 2rem;"></i>
                                Không có hoạt động gần đây
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer text-end bg-white">
                    <a href="{{ route('audit_log.index') }}" class="btn btn-sm btn-outline-secondary">Xem toàn bộ</a>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection