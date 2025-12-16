@extends('doctor.master')

@section('title', 'Thống kê & Báo cáo')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-green-500 pl-3">📊 Thống kê Hoạt động</h2>

    {{-- 1. Cards Thống kê --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full text-blue-600 mr-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tổng Bệnh nhân</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalPatients }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full text-green-600 mr-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Ca khám hoàn thành</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $completedAppointments }} / {{ $totalAppointments }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full text-yellow-600 mr-4">
                    <i class="fas fa-coins text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Hoa hồng (Tháng này)</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($estimatedIncome) }}đ</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full text-purple-600 mr-4">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Đánh giá trung bình</p>
                    <h3 class="text-2xl font-bold text-gray-800">4.8 / 5</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Biểu đồ Chart.js --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Số lượng bệnh nhân 7 ngày qua</h3>
        <canvas id="patientsChart" height="100"></canvas>
    </div>
</div>

{{-- Script vẽ biểu đồ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('patientsChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!}, // Ngày
            datasets: [{
                label: 'Số lượng bệnh nhân',
                data: {!! json_encode($data) !!}, // Số liệu
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.4, // Đường cong mềm mại
                fill: true
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection