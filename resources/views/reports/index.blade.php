@extends('admin.master')

@section('body')
<div class="container mt-4">
    <h4 class="mb-4">Báo cáo Tài chính & Hoạt động</h4>

    <div class="d-flex align-items-center gap-2 mb-3">
        <select id="reportType" class="form-select w-auto">
            <option value="revenue">Báo cáo Doanh thu</option>
            <option value="doctor_performance">Báo cáo Hiệu suất Bác sĩ</option>
            <option value="medicine_stock">Báo cáo Tình trạng Kho thuốc</option>
        </select>

        <select id="timeType" class="form-select w-auto">
            <option value="month">Theo Tháng</option>
            <option value="quarter">Theo Quý</option>
            <option value="year">Theo Năm</option>
        </select>

        <button class="btn btn-primary" id="btnView">Xem báo cáo</button>
        <a href="{{ route('reports.export') }}" class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf"></i> Tải PDF
        </a>
    </div>

    <div id="chartArea" class="p-4 border rounded bg-light text-center text-muted">
        Khu vực hiển thị Biểu đồ báo cáo chi tiết (sử dụng Chart.js)
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
let chart; // giữ biến để update lại biểu đồ

document.getElementById('btnView').addEventListener('click', async function() {
    const reportType = document.getElementById('reportType').value;
    const timeType = document.getElementById('timeType').value;

    const response = await fetch('{{ route('reports.view') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            report_type: reportType,
            time_type: timeType
        })
    });

    const rawData = await response.json();

    // ✅ Chuyển dữ liệu trả về từ controller thành labels và values để Chart.js hiểu được
    const labels = rawData.map(item => item.label || item.name || item.doctor?.name || '');
    const values = rawData.map(item => item.total || item.stock || item.total_appointments || 0);

    // ✅ Xác định tiêu đề biểu đồ tự động
    let chartTitle = '';
    if (reportType === 'revenue') chartTitle = 'Doanh thu theo ' + (timeType === 'month' ? 'Tháng' : timeType === 'quarter' ? 'Quý' : 'Năm');
    else if (reportType === 'doctor_performance') chartTitle = 'Hiệu suất làm việc của bác sĩ';
    else if (reportType === 'medicine_stock') chartTitle = 'Tồn kho thuốc hiện tại';

    // ✅ Render lại biểu đồ
    const chartArea = document.getElementById('chartArea');
    chartArea.innerHTML = '<canvas id="reportChart" height="100"></canvas>';
    const ctx = document.getElementById('reportChart');

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: chartTitle,
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: {
                    display: true,
                    text: chartTitle
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection
