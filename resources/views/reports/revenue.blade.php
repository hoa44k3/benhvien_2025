@extends('admin.master')

@section('body')
<div class="container">
    <h3 class="fw-bold mb-3">Báo cáo Doanh thu - Tháng {{ $month }}</h3>

    @if($revenues->count() > 0)
        <canvas id="revenueChart" height="120"></canvas>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Doanh thu (VNĐ)</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenues as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
                    <td>{{ number_format($r->amount, 0, ',', '.') }}</td>
                    <td>{{ $r->note }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted text-center mt-5">Không có dữ liệu doanh thu cho tháng này.</p>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($revenues->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m')));
const data = @json($revenues->pluck('amount'));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: data,
            borderWidth: 2,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
