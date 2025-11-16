@extends('admin.master')

@section('body')
<div class="container">
    <h3 class="fw-bold mb-3">Báo cáo Hiệu suất Bác sĩ</h3>

    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <label class="form-label">Chọn Bác sĩ:</label>
            <select name="doctor_id" class="form-select">
                <option value="">-- Tất cả bác sĩ --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $doctorId == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Thời gian:</label>
            <select name="period" class="form-select">
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Theo tháng</option>
                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Theo quý</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Theo năm</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Năm:</label>
            <input type="number" name="year" class="form-control" value="{{ $year }}">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Xem báo cáo
            </button>
        </div>
    </form>

    @if(count($chartData))
        <canvas id="doctorChart" height="120"></canvas>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Bác sĩ</th>
                    <th>Số ca khám</th>
                    <th>Tổng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chartData as $row)
                    <tr>
                        <td>{{ $row['doctor'] }}</td>
                        <td>{{ $row['appointments'] }}</td>
                        <td>{{ number_format($row['revenue'], 0, ',', '.') }} VNĐ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted text-center mt-5">Không có dữ liệu báo cáo trong khoảng thời gian này.</p>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = @json($chartData);

new Chart(document.getElementById('doctorChart'), {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.doctor),
        datasets: [
            {
                label: 'Doanh thu (VNĐ)',
                data: chartData.map(d => d.revenue),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            },
            {
                label: 'Số ca khám',
                data: chartData.map(d => d.appointments),
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
