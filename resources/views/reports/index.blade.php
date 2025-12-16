@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">
            <i class="fas fa-chart-pie me-2"></i>Báo cáo & Thống kê
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Báo cáo</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-light rounded-3">
            <div class="row g-3 align-items-end">
                {{-- Chọn loại báo cáo --}}
                <div class="col-md-4">
                    <label for="reportType" class="form-label fw-bold text-secondary">Loại báo cáo</label>
                    <select id="reportType" class="form-select border-primary shadow-sm">
                        <option value="service_revenue">💰 Doanh thu: Khám vs Thuốc</option>
                        <option value="doctor_kpi">👨‍⚕️ KPI Bác sĩ (Số ca & Doanh thu)</option>
                        <option value="medicine_stock">💊 Cảnh báo Kho thuốc (Top 10 sắp hết)</option>
                    </select>
                </div>

                {{-- Chọn thời gian --}}
                <div class="col-md-3">
                    <label for="timeType" class="form-label fw-bold text-secondary">Thời gian</label>
                    <select id="timeType" class="form-select border-primary shadow-sm">
                        <option value="month">Tháng này</option>
                        <option value="quarter">Quý này</option>
                        <option value="year">Năm nay</option>
                    </select>
                </div>

                {{-- Nút Xem --}}
                <div class="col-md-3">
                    <button class="btn btn-primary w-100 fw-bold shadow-sm" id="btnView">
                        <i class="fas fa-eye me-2"></i> Xem biểu đồ
                    </button>
                </div>

                {{-- Nút Xuất PDF --}}
                 <div class="col-md-2">
                    <a href="{{ route('reports.export') }}" class="btn btn-outline-danger w-100 fw-bold shadow-sm">
                        <i class="fas fa-file-pdf me-2"></i> Xuất PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-bold text-dark" id="chartTitle">Kết quả thống kê</h5>
        </div>
        <div class="card-body">
            <div class="position-relative" style="height: 450px; width: 100%;">
                <canvas id="reportChart"></canvas>
            </div>
            
            <div id="summarySection" class="mt-4 row g-3 text-center d-none">
                <div class="col-md-12">
                    <div class="p-3 bg-soft-success rounded-3 border border-success border-opacity-25">
                        <h6 class="text-success text-uppercase fw-bold mb-1">Tổng Doanh thu ước tính</h6>
                        <h2 class="fw-bolder text-dark mb-0" id="totalRevenueDisplay">0 ₫</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    let chartInstance = null; // Biến lưu instance của biểu đồ để destroy khi vẽ lại

    // Sự kiện click nút Xem
    document.getElementById('btnView').addEventListener('click', async function() {
        const reportType = document.getElementById('reportType').value;
        const timeType = document.getElementById('timeType').value;
        const btn = this;
        const summarySection = document.getElementById('summarySection');
        const chartTitle = document.getElementById('chartTitle');
        
        // 1. Loading State
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang tải...';
        btn.disabled = true;

        try {
            // 2. Gọi API lấy dữ liệu
            const response = await fetch('{{ route('reports.view') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ report_type: reportType, time_type: timeType })
            });

            if (!response.ok) throw new Error('Lỗi kết nối server');

            const data = await response.json();

            // 3. Xử lý dữ liệu và vẽ biểu đồ
            if (data.length === 0) {
                alert("Không có dữ liệu nào trong khoảng thời gian này!");
                summarySection.classList.add('d-none');
            } else {
                renderChart(reportType, data);
                renderSummary(data);
                summarySection.classList.remove('d-none');
                
                // Cập nhật tiêu đề card
                const reportName = document.getElementById('reportType').options[document.getElementById('reportType').selectedIndex].text;
                chartTitle.innerText = reportName;
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu báo cáo.');
        } finally {
            // 4. Reset nút bấm
            btn.innerHTML = '<i class="fas fa-eye me-2"></i> Xem biểu đồ';
            btn.disabled = false;
        }
    });

    // Hàm vẽ biểu đồ chính
    function renderChart(type, data) {
        const ctx = document.getElementById('reportChart').getContext('2d');
        
        // Hủy biểu đồ cũ nếu tồn tại
        if (chartInstance) {
            chartInstance.destroy();
        }

        const labels = data.map(item => item.label);
        let datasets = [];
        let options = {};

        // Cấu hình chung cho tooltip tiền tệ
        const currencyTooltip = {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) label += ': ';
                    if (context.parsed.y !== null) {
                        // Nếu là trục tiền tệ thì format VNĐ
                        if(context.dataset.yAxisID === 'y1' || type === 'service_revenue') {
                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                        } else {
                            label += context.parsed.y;
                        }
                    }
                    return label;
                }
            }
        };

        // --- CẤU HÌNH THEO LOẠI BÁO CÁO ---

        if (type === 'doctor_kpi') {
            // TRƯỜNG HỢP 1: KPI BÁC SĨ (Dual Axis - 2 Trục)
            datasets = [
                {
                    label: 'Số ca khám (Ca)',
                    data: data.map(item => item.total_appointments),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    type: 'bar',
                    yAxisID: 'y', // Trục trái
                    order: 2
                },
                {
                    label: 'Doanh thu (VNĐ)',
                    data: data.map(item => item.total_revenue),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    type: 'line',
                    yAxisID: 'y1', // Trục phải
                    tension: 0.4, // Đường cong mềm mại
                    order: 1
                }
            ];

            options = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { tooltip: currencyTooltip },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Số lượng (Ca)' },
                        grid: { drawOnChartArea: true }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Doanh thu (VNĐ)' },
                        grid: { drawOnChartArea: false }, // Ẩn lưới trục phải cho đỡ rối
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { notation: "compact" }).format(value) + '₫';
                            }
                        }
                    }
                }
            };

        } else if (type === 'service_revenue') {
            // TRƯỜNG HỢP 2: DOANH THU DỊCH VỤ (Bar Chart đơn giản)
            datasets = [{
                label: 'Doanh thu',
                data: data.map(item => item.total),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Xanh ngọc
                    'rgba(255, 206, 86, 0.6)', // Vàng
                    'rgba(153, 102, 255, 0.6)' // Tím
                ],
                borderWidth: 1
            }];

            options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { tooltip: currencyTooltip },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { notation: "compact" }).format(value) + '₫';
                            }
                        }
                    }
                }
            };

        } else {
            // TRƯỜNG HỢP 3: KHO THUỐC (Mặc định)
            datasets = [{
                label: 'Số lượng tồn kho',
                data: data.map(item => item.total),
                backgroundColor: data.map(item => item.total <= 10 ? 'rgba(255, 99, 132, 0.7)' : 'rgba(54, 162, 235, 0.7)'), // Đỏ nếu < 10
                borderColor: data.map(item => item.total <= 10 ? 'rgba(255, 99, 132, 1)' : 'rgba(54, 162, 235, 1)'),
                borderWidth: 1
            }];

            options = {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            };
        }

        // Tạo biểu đồ mới
        chartInstance = new Chart(ctx, {
            type: 'bar', // Mặc định là bar
            data: { labels: labels, datasets: datasets },
            options: options
        });
    }

    // Hàm hiển thị tổng kết dưới biểu đồ
    function renderSummary(data) {
        let totalVal = 0;
        
        data.forEach(d => {
            // Cộng dồn doanh thu (ưu tiên trường total_revenue, nếu ko có thì dùng total)
            totalVal += parseFloat(d.total_revenue || d.total || 0);
        });

        const display = document.getElementById('totalRevenueDisplay');
        
        // Nếu là báo cáo kho thuốc thì hiển thị label khác (Số lượng)
        const type = document.getElementById('reportType').value;
        if(type === 'medicine_stock') {
            display.innerText = new Intl.NumberFormat('vi-VN').format(totalVal) + ' Sản phẩm';
            display.previousElementSibling.innerText = "Tổng tồn kho các thuốc top đầu";
        } else {
            display.innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalVal);
            display.previousElementSibling.innerText = "Tổng Doanh thu trong kỳ";
        }
    }
</script>

<style>
    /* Một chút CSS bổ trợ */
    .bg-soft-success {
        background-color: #d1e7dd;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection