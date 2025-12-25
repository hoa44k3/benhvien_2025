@extends('admin.master') 
@section('title', 'Bảng lương chi tiết')

@section('body')
<div class="container mt-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border">
        <div>
            <h4 class="mb-0 text-success fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> Bảng lương: {{ $doctor->degree }} {{ $doctor->user->name }}</h4>
            <a href="{{ route('doctorsite.index') }}" class="small text-muted text-decoration-none"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        </div>
        <form action="" method="GET" class="d-flex gap-2 align-items-center">
            <select name="month" class="form-select w-auto">
                @for($i=1; $i<=12; $i++) <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>Tháng {{ $i }}</option> @endfor
            </select>
            <select name="year" class="form-select w-auto">
                <option value="2024" {{ $year == 2024 ? 'selected' : '' }}>2024</option>
                <option value="2025" {{ $year == 2025 ? 'selected' : '' }}>2025</option>
            </select>
            <button type="submit" class="btn btn-primary fw-bold">Xem</button>
        </form>
    </div>

    {{-- Tổng quan Thu nhập --}}
    <div class="row g-4 mb-4">
        {{-- Card Tổng Lĩnh --}}
        <div class="col-md-4">
            <div class="card bg-gradient-success text-white h-100 shadow border-0" style="background: linear-gradient(45deg, #198754, #20c997);">
                <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                    <div class="text-uppercase opacity-75 fw-bold" style="font-size: 0.8rem;">THỰC LĨNH THÁNG {{ $month }}/{{ $year }}</div>
                    <h2 class="display-5 fw-bold my-2">{{ number_format($totalIncome, 0, ',', '.') }} đ</h2>
                    <small class="opacity-75">Lương cứng + Hoa hồng</small>
                </div>
            </div>
        </div>

        {{-- Card Chi tiết --}}
        <div class="col-md-8">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary text-uppercase small border-bottom pb-2 mb-3">CHI TIẾT THU NHẬP</h6>
                    
                    {{-- 1. Lương cứng --}}
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom bg-light px-3 rounded mb-2">
                        <div>
                            <span class="fw-bold text-dark">1. Lương cứng (Cố định)</span>
                            <div class="text-muted small mt-1">
                                <i class="fas fa-money-bill me-1"></i> Mức lương thỏa thuận hàng tháng.
                            </div>
                        </div>
                        <span class="fw-bold fs-5 text-primary">{{ number_format($fixedSalary, 0, ',', '.') }} đ</span>
                    </div>

                    {{-- 2. Hoa hồng Khám --}}
                    <div class="d-flex justify-content-between align-items-center py-3 px-3">
                        <div>
                            <span class="fw-bold text-dark">2. Hoa hồng Khám bệnh</span>
                            <span class="badge bg-warning text-dark ms-2 border">{{ $doctor->commission_exam_percent }}%</span>
                            <div class="text-muted small mt-1">Doanh thu: <strong>{{ number_format($totalExamRevenue) }} đ</strong> ({{ $completedAppointments->count() }} ca)</div>
                        </div>
                        <span class="fw-bold fs-5 text-success">+ {{ number_format($commissionExam, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Thống kê hiệu suất (KPI - Chỉ để xem) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-info bg-opacity-10">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-info mb-1"><i class="fas fa-chart-line me-2"></i> Hiệu suất hoạt động (KPI)</h6>
                        <small class="text-muted">Số liệu chấm công dùng để đánh giá chuyên cần.</small>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="text-center">
                            <h5 class="fw-bold mb-0 text-dark">{{ number_format($actualHours, 1) }}</h5>
                            <small class="text-muted uppercase" style="font-size: 0.7rem;">GIỜ ONLINE</small>
                        </div>
                        <div class="border-start mx-2"></div>
                        <div class="text-center">
                            <h5 class="fw-bold mb-0 text-dark">{{ $activeDays }}</h5>
                            <small class="text-muted uppercase" style="font-size: 0.7rem;">NGÀY TRỰC</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách chi tiết --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list-ul me-2"></i> Danh sách ca khám tính hoa hồng</span>
            <span class="badge bg-white text-primary">Chỉ tính ca "Hoàn thành"</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered mb-0 table-hover table-striped align-middle">
                    <thead class="sticky-top bg-light text-dark">
                        <tr>
                            <th>Thời gian</th>
                            <th>Mã HS</th>
                            <th>Bệnh nhân</th>
                            <th>Nội dung</th>
                            <th class="text-end">Phí khám</th>
                            <th class="text-end">Hoa hồng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedAppointments as $appt)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($appt->date)->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ substr($appt->time, 0, 5) }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $appt->code }}</span></td>
                            <td>
                                <div class="fw-bold text-primary">{{ $appt->patient_name }}</div>
                                <small class="text-muted">{{ $appt->patient_phone }}</small>
                            </td>
                            <td><div class="small text-truncate" style="max-width: 200px;">{{ $appt->reason }}</div></td>
                            <td class="text-end text-muted">{{ number_format($examFee) }} đ</td>
                            <td class="text-end fw-bold text-success">+ {{ number_format($examFee * ($doctor->commission_exam_percent / 100), 0, ',', '.') }} đ</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Chưa có ca khám hoàn thành.</td></tr>
                        @endforelse
                    </tbody>
                    @if($completedAppointments->count() > 0)
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end">TỔNG HOA HỒNG:</td>
                            <td class="text-end text-success fs-6">{{ number_format($commissionExam, 0, ',', '.') }} đ</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection