@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">
    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-tachometer-alt me-2"></i> Bảng điều khiển Quản trị
    </h3>
    <hr>

    <div class="row g-4 mb-5">
        
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
                <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-white bg-opacity-10">
                    <a class="small text-white text-decoration-none" href="#">Xem chi tiết</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

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
                <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-dark bg-opacity-10">
                    <a class="small text-dark text-decoration-none" href="#">Xem danh sách</a>
                    <i class="fas fa-angle-right text-dark"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Doanh thu tháng {{ now()->month }}</div>
                            <h2 class="fw-bolder fs-1">{{ number_format($monthlyRevenue, 0, ',', '.') }} VNĐ</h2>
                        </div>
                        <i class="fas fa-dollar-sign fa-4x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-white bg-opacity-10">
                    <a class="small text-white text-decoration-none" href="#">Báo cáo tài chính</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Nhân viên trực</div>
                            <h2 class="display-4 fw-bolder">{{ $activeStaff }}</h2>
                        </div>
                        <i class="fas fa-user-tie fa-4x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between border-0 bg-white bg-opacity-10">
                    <a class="small text-white text-decoration-none" href="#">Xem ca làm việc</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 rounded-3 border-start border-3 border-primary">
                <div class="card-header bg-white fw-bold text-primary">
                    <i class="fas fa-bed me-2"></i> Tình trạng phòng khám
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tổng số phòng:
                            <span class="badge bg-secondary rounded-pill">{{ $totalRooms }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Phòng trống:
                            <span class="badge bg-success rounded-pill fs-6">{{ $availableRooms }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Phòng đang sử dụng:
                            <span class="badge bg-primary rounded-pill">{{ $usedRooms }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Phòng bảo trì:
                            <span class="badge bg-warning rounded-pill">{{ $maintenanceRooms }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 rounded-3 border-start border-3 border-danger">
                <div class="card-header bg-white fw-bold text-danger">
                    <i class="fas fa-prescription-bottle-alt me-2"></i> Cảnh báo kho thuốc
                </div>
                <div class="card-body">
                    <div class="alert alert-danger d-flex align-items-center mb-2" role="alert">
                        <i class="fas fa-exclamation-triangle flex-shrink-0 me-2"></i>
                        <div>
                            <strong class="me-1">Thuốc sắp hết:</strong> 
                            <span class="fs-5">{{ $lowMedicines }}</span> loại
                        </div>
                    </div>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-calendar-times flex-shrink-0 me-2"></i>
                        <div>
                            <strong class="me-1">Thuốc hết hạn:</strong> 
                            <span class="fs-5">{{ $expiredMedicines }}</span> loại
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-white">
                    <a href="#" class="btn btn-sm btn-outline-danger">Quản lý kho</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100 rounded-3 border-start border-3 border-secondary">
                <div class="card-header bg-white fw-bold text-secondary">
                    <i class="fas fa-history me-2"></i> Hoạt động gần đây
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse(collect($recentActivities)->take(5) as $log)
                            <div class="list-group-item">
                                <p class="mb-1">
                                    <span class="fw-bold">{{ $log->user?->name ?? 'Hệ thống' }}</span> 
                                    <span class="text-muted">– {{ $log->action }}</span>
                                </p>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $log->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="fas fa-box-open d-block mb-2"></i>
                                Không có hoạt động gần đây
                            </div>
                        @endforelse
                    </div>
                </div>
                @if(count($recentActivities) > 0)
                <div class="card-footer text-end bg-white">
                    <a href="#" class="btn btn-sm btn-outline-secondary">Xem toàn bộ</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection