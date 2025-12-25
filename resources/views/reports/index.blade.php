@extends('admin.master')
@section('title', 'Báo cáo thống kê')

@section('body')
<div class="container-fluid mt-4">

    {{-- 1. THANH CÔNG CỤ & BỘ LỌC --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-chart-pie me-2 text-primary"></i> Báo cáo Hoạt động
        </h3>
        
        <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 shadow-sm p-2 bg-white rounded">
            <select name="month" class="form-select border-0 bg-light fw-bold text-primary">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng {{ $m }}</option>
                @endfor
            </select>
            <select name="year" class="form-select border-0 bg-light fw-bold text-primary">
                @for($y=2024; $y<=2030; $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>Năm {{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary fw-bold px-3">
                <i class="fas fa-filter"></i> Lọc
            </button>
        </form>
    </div>

    {{-- 2. CARDS THỐNG KÊ (OVERVIEW) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Tổng Doanh Thu (Tháng {{ $month }})</p>
                            <h3 class="fw-bold text-success mb-0">{{ number_format($monthlyRevenue, 0, ',', '.') }} <small class="fs-6">đ</small></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                            <i class="fas fa-sack-dollar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Ca Khám Hoàn Thành</p>
                            <h3 class="fw-bold text-info mb-0">{{ $completedExams }} <small class="fs-6">ca</small></h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                            <i class="fas fa-user-md fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Bệnh Nhân Mới</p>
                            <h3 class="fw-bold text-warning mb-0">{{ $newPatients }} <small class="fs-6">người</small></h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. BIỂU ĐỒ (CHARTS) --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2"></i> Biểu đồ doanh thu năm {{ $year }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="annualRevenueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2"></i> Tỷ trọng theo Khoa (Tháng {{ $month }})</h6>
                </div>
                <div class="card-body">
                    @if(count($deptLabels) > 0)
                        <canvas id="deptRevenueChart" style="height: 250px;"></canvas>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-pie fa-3x mb-3 opacity-25"></i>
                            <p>Chưa có dữ liệu doanh thu khoa tháng này.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 4. BẢNG TOP BÁC SĨ --}}
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-medal me-2"></i> Top 5 Bác sĩ tiêu biểu (Tháng {{ $month }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Hạng</th>
                            <th>Bác sĩ</th>
                            <th>Chuyên khoa</th>
                            <th class="text-center">Số ca khám</th>
                            <th class="text-end pe-4">Ước tính Doanh thu</th>
                        </tr>
                    </thead>
                  {{-- Tìm đến phần hiển thị bảng Top Bác sĩ và sửa lại tbody --}}
<tbody>
    @forelse($topDoctors as $index => $item)
        @php
            // Lấy trực tiếp từ thuộc tính đã select (doctor_name, department_name...)
            $fee = $item->exam_fee ?? 200000;
            $estimatedRev = $item->total_exams * $fee;
        @endphp
        <tr>
            <td class="ps-4">
                @if($index == 0) <span class="badge bg-warning text-dark">#1 👑</span>
                @elseif($index == 1) <span class="badge bg-secondary">#2</span>
                @elseif($index == 2) <span class="badge bg-brown" style="background:#cd7f32;color:white">#3</span>
                @else <span class="fw-bold text-muted">#{{ $index + 1 }}</span>
                @endif
            </td>
            <td>
                {{-- SỬA: Gọi trực tiếp doctor_name --}}
                <div class="fw-bold text-dark">{{ $item->doctor_name }}</div>
            </td>
            <td>
                {{-- SỬA: Gọi trực tiếp department_name --}}
                <span class="badge bg-light text-dark border">
                    {{ $item->department_name ?? 'Chưa rõ khoa' }}
                </span>
            </td>
            <td class="text-center fw-bold">{{ $item->total_exams }}</td>
            <td class="text-end pe-4 text-success fw-bold">{{ number_format($estimatedRev) }} đ</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu bác sĩ trong tháng này.</td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- SCRIPT VẼ BIỂU ĐỒ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Biểu đồ Doanh thu năm (Line Chart)
    const ctxAnnual = document.getElementById('annualRevenueChart').getContext('2d');
    new Chart(ctxAnnual, {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($annualRevenueData),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: function(value) { return value.toLocaleString() + ' đ'; } } }
            }
        }
    });

    // 2. Biểu đồ Khoa (Pie Chart) - Chỉ vẽ nếu có dữ liệu
    const ctxDept = document.getElementById('deptRevenueChart');
    if (ctxDept) {
        new Chart(ctxDept, {
            type: 'doughnut',
            data: {
                labels: @json($deptLabels),
                datasets: [{
                    data: @json($deptValues),
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
</script>
@endsection