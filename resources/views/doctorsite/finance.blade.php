@extends('admin.master') 
@section('title', 'Bảng lương chi tiết')

@section('body')
<div class="container mt-4">

    {{-- Header & Bộ lọc --}}
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
        <div>
            <h4 class="mb-0 text-success fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> Bảng lương: BS. {{ $doctor->user->name }}</h4>
            <a href="{{ route('doctorsite.index') }}" class="small text-muted text-decoration-none"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
        </div>

        <form action="" method="GET" class="d-flex gap-2">
            <select name="month" class="form-select w-auto">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>Tháng {{ $i }}</option>
                @endfor
            </select>
            <select name="year" class="form-select w-auto">
                <option value="2024" {{ $year == 2024 ? 'selected' : '' }}>2024</option>
                <option value="2025" {{ $year == 2025 ? 'selected' : '' }}>2025</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Xem</button>
        </form>
    </div>

    {{-- KHỐI 1: TỔNG QUAN THU NHẬP --}}
    <div class="row g-4 mb-4">
        {{-- Cột Trái: Con số tổng --}}
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 shadow">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="text-uppercase opacity-75 fw-bold text-xs">TỔNG THỰC NHẬN (NET)</div>
                    <h2 class="display-5 fw-bold my-2">{{ number_format($totalIncome, 0, ',', '.') }} đ</h2>
                    <small><i class="fas fa-check-circle"></i> Đã tính công & hoa hồng</small>
                </div>
            </div>
        </div>

        {{-- Cột Phải: Chi tiết cấu thành --}}
        <div class="col-md-8">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold text-muted border-bottom pb-2">CHI TIẾT LƯƠNG</h6>

                    {{-- 1. Lương cứng (Logic Chấm công) --}}
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom bg-light px-2 rounded mb-2">
                        <div>
                            <span class="fw-bold text-dark">1. Lương cứng (Theo chấm công)</span>
                            <div class="text-muted small mt-1">
                                <i class="far fa-calendar-check me-1"></i> Đi làm: 
                                <strong class="{{ $actualWorkDays < $standardWorkDays ? 'text-danger' : 'text-success' }} fs-6">
                                    {{ $actualWorkDays }}/{{ $standardWorkDays }}
                                </strong> công.
                                @if($deductedSalary > 0)
                                    <br><span class="text-danger fst-italic"><i class="fas fa-exclamation-circle"></i> Bị trừ: -{{ number_format($deductedSalary) }} đ (Do nghỉ/quên chấm công)</span>
                                @endif
                            </div>
                        </div>
                        <span class="fw-bold fs-5 text-primary">{{ number_format($realBaseSalary, 0, ',', '.') }} đ</span>
                    </div>

                    {{-- 2. Hoa hồng Khám --}}
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom px-2">
                        <div>
                            <span class="fw-bold text-dark">2. Hoa hồng Khám bệnh</span>
                            <span class="badge bg-secondary ms-2">{{ $doctor->commission_exam_percent }}%</span>
                            <div class="text-muted small">Doanh thu: {{ number_format($totalExamRevenue) }} đ</div>
                        </div>
                        <span class="fw-bold text-success">+ {{ number_format($commissionExam, 0, ',', '.') }} đ</span>
                    </div>

                    {{-- 3. Hoa hồng Thuốc --}}
                    <div class="d-flex justify-content-between align-items-center py-2 px-2">
                        <div>
                            <span class="fw-bold text-dark">3. Hoa hồng Đơn thuốc</span>
                            <span class="badge bg-secondary ms-2">{{ $doctor->commission_prescription_percent }}%</span>
                            <div class="text-muted small">Doanh thu: {{ number_format($totalDrugRevenue) }} đ</div>
                        </div>
                        <span class="fw-bold text-success">+ {{ number_format($commissionDrug, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KHỐI 2: DANH SÁCH BỆNH NHÂN (NGUỒN TIỀN HOA HỒNG) --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list-ul me-2"></i> Danh sách ca khám tính lương ({{ $completedAppointments->count() }} ca)</span>
                    <span class="badge bg-light text-primary">Chỉ tính ca "Đã hoàn thành"</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered mb-0 table-hover table-striped">
                            <thead class="sticky-top bg-light text-dark">
                                <tr>
                                    <th width="15%">Thời gian</th>
                                    <th width="15%">Mã BN</th>
                                    <th width="25%">Bệnh nhân</th>
                                    <th width="25%">Nội dung khám</th>
                                    <th class="text-end" width="20%">Hoa hồng nhận</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedAppointments as $appt)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($appt->date)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ substr($appt->time, 0, 5) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $appt->patient_code ?? '---' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $appt->patient_name }}</div>
                                        <small class="text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $appt->patient_phone }}</small>
                                    </td>
                                    <td>
                                        <div class="small">{{ Str::limit($appt->reason, 40) }}</div>
                                        <small class="text-muted fst-italic">Phí khám: {{ number_format($examFee) }} đ</small>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        + {{ number_format($examFee * ($doctor->commission_exam_percent / 100), 0, ',', '.') }} đ
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i> Không có ca khám hoàn thành nào trong tháng này.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection