@extends('doctor.master') 

@section('title', 'Danh sách bệnh nhân')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    
    {{-- Header & Tìm kiếm --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-blue-600 pl-3">
            Danh sách Bệnh nhân
        </h2>
        
        <form action="{{ route('doctor.patients.index') }}" method="GET" class="flex w-full md:w-1/3">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   placeholder="Tìm tên hoặc SĐT...">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    {{-- Bảng danh sách --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-5 py-3 text-left">Bệnh nhân</th>
                        <th class="px-5 py-3 text-left">Thông tin</th>
                        <th class="px-5 py-3 text-left">Ngày hẹn</th>
                        <th class="px-5 py-3 text-center">Trạng thái</th>
                        <th class="px-5 py-3 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($appointments as $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        {{-- Cột Bệnh nhân --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-lg">
                                    {{ substr($item->patient_name ?? 'BN', 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 font-bold">{{ $item->patient_name }}</p>
                                    <p class="text-gray-500 text-xs italic">{{ $item->reason ?? 'Không có lý do' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Cột Thông tin --}}
                        <td class="px-5 py-4">
                            <p class="text-gray-900"><i class="fas fa-id-card mr-1 text-gray-400"></i> {{ $item->patient_code ?? '---' }}</p>
                            <p class="text-gray-500"><i class="fas fa-phone mr-1 text-gray-400"></i> {{ $item->patient_phone }}</p>
                        </td>

                        {{-- Cột Ngày hẹn --}}
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800">{{ $item->time }}</p>
                            <p class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</p>
                        </td>

                        {{-- Cột Trạng thái --}}
                        <td class="px-5 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'Đang chờ'      => 'bg-yellow-100 text-yellow-800',
                                    'Đã xác nhận'   => 'bg-blue-100 text-blue-800',
                                    'Đang khám'     => 'bg-indigo-100 text-indigo-800',
                                    'Hoàn thành'    => 'bg-green-100 text-green-800',
                                    'Hủy hẹn'       => 'bg-red-100 text-red-800',
                                ];
                                $cls = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cls }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        {{-- Cột Hành động --}}
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                
                                {{-- Nút KHÁM (Chỉ hiện khi chưa hoàn thành) --}}
                                @if(in_array($item->status, ['Đang chờ', 'Đã xác nhận', 'Đang khám']))
                                    <a href="{{ route('doctor.diagnosis.show', $item->id) }}" 
                                       class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition" 
                                       title="Vào phòng khám">
                                        <i class="fas fa-stethoscope"></i>
                                    </a>
                                @endif

                                {{-- Nút Xem Chi Tiết --}}
                                <a href="{{ route('doctor.patients.show', $item->id) }}" 
                                   class="bg-gray-200 text-gray-700 p-2 rounded hover:bg-gray-300 transition" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                                <p>Không tìm thấy bệnh nhân nào.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Phân trang --}}
        <div class="px-5 py-4 bg-white border-t">
            {{ $appointments->links() }} 
        </div>
    </div>
</div>
@endsection