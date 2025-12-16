@extends('doctor.master') 
{{-- Lưu ý: Kế thừa đúng layout bác sĩ của bạn --}}

@section('title', 'Danh sách chờ khám')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-blue-500 pl-3">
            📋 Danh sách Bệnh nhân chờ khám
        </h2>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">STT</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Bệnh nhân</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Thời gian hẹn</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Lý do khám</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $app)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">{{ $loop->iteration }}</td>
                    <td class="px-5 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 font-bold">
                                {{ substr($app->patient_name ?? 'BN', 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-900 font-bold whitespace-no-wrap">{{ $app->patient_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $app->patient_phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm">
                        <p class="text-gray-900 font-semibold">{{ $app->time }}</p>
                        <p class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($app->date)->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-sm text-gray-600 italic">
                        {{ Str::limit($app->reason, 50) }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-center">
                        @if($app->status == 'Đang chờ')
                            <span class="px-3 py-1 font-semibold text-yellow-700 bg-yellow-100 rounded-full text-xs">Đang chờ</span>
                        @elseif($app->status == 'Đã xác nhận')
                            <span class="px-3 py-1 font-semibold text-blue-700 bg-blue-100 rounded-full text-xs">Đợi khám</span>
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 text-center">
                        <a href="{{ route('doctor.diagnosis.show', $app->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded hover:bg-blue-700 transition shadow-sm">
                            <i class="fas fa-stethoscope mr-2"></i> Khám ngay
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-clipboard-check text-4xl mb-3 text-gray-300"></i>
                            <p>Không có bệnh nhân nào đang chờ.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection