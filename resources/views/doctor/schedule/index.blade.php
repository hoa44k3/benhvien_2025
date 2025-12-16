@extends('doctor.master')

@section('title', 'Trang chủ Bác sĩ')

@section('body')
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-blue-600">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <img class="h-16 w-16 rounded-full object-cover border-2 border-blue-200" 
                         src="{{ isset($doctorProfile->image) && $doctorProfile->image ? asset('storage/'.$doctorProfile->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                         alt="Avatar">
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $doctorProfile->specialization ?? 'Bác sĩ Đa khoa' }}</p>
                </div>
            </div>
            
            <div class="space-y-2 text-sm bg-gray-50 p-3 rounded-lg">
                <div class="flex justify-between border-b border-gray-200 pb-1">
                    <span class="text-gray-500">Ngân hàng:</span>
                    <span class="font-semibold text-gray-800">{{ $doctorProfile->bank_name ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-1">
                    <span class="text-gray-500">Số tài khoản:</span>
                    <span class="font-semibold text-gray-800">{{ $doctorProfile->bank_account_number ?? '---' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Chủ TK:</span>
                    <span class="font-semibold text-gray-800">{{ $doctorProfile->bank_account_holder ?? '---' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-600 to-teal-700 text-white p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <h3 class="text-lg font-semibold mb-1 flex items-center"><i data-lucide="wallet" class="w-5 h-5 mr-2"></i> Thu nhập tháng {{ date('m/Y') }}</h3>
            <p class="text-xs opacity-80 mb-4 italic">Cập nhật theo thời gian thực</p>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-green-100 text-sm">Số công chấm:</span>
                    <span class="font-bold bg-white/20 px-2 py-0.5 rounded">{{ $salaryStats['work_days'] }} ngày</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-green-100 text-sm">Lương cứng:</span>
                    <span class="font-semibold">{{ number_format($salaryStats['base_salary']) }} đ</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-green-100 text-sm">Hoa hồng:</span>
                    <span class="font-semibold">+ {{ number_format($salaryStats['commission']) }} đ</span>
                </div>
                <div class="pt-3 border-t border-white/20 mt-2">
                    <div class="flex justify-between items-end">
                        <span class="font-bold uppercase text-sm">Tổng cộng:</span>
                        <span class="text-2xl font-extrabold text-yellow-300">{{ number_format($salaryStats['total']) }} đ</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-orange-500 flex flex-col justify-center">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2 text-orange-600"></i> Chấm công
                </h3>
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">{{ date('d/m/Y') }}</span>
            </div>

            @if(!$todayAttendance)
                {{-- TRẠNG THÁI 1: CHƯA CHECK-IN --}}
                <div class="text-center">
                    <form action="{{ route('doctor_attendances.checkin') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select name="shift" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 bg-gray-50">
                                <option value="day">☀️ Ca Hành Chính (Ngày)</option>
                                <option value="night">🌙 Ca Trực Đêm (Online)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg animate-pulse flex items-center justify-center">
                            <i data-lucide="fingerprint" class="w-5 h-5 mr-2"></i> CHECK-IN NGAY
                        </button>
                    </form>
                </div>

            @elseif(!$todayAttendance->check_out)
                {{-- TRẠNG THÁI 2: ĐANG LÀM VIỆC --}}
                <div class="text-center">
                    <div class="mb-4 bg-green-50 p-3 rounded-lg border border-green-100">
                        <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold mb-2 uppercase">
                            ● Đang Online
                        </span>
                        <p class="text-gray-700 font-medium">
                            Giờ vào: <span class="text-blue-600 text-xl font-bold">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $todayAttendance->shift == 'day' ? 'Ca Ngày' : 'Ca Đêm' }}
                            @if($todayAttendance->status == 'late') <span class="text-red-500 font-bold">(Đi muộn)</span> @endif
                        </p>
                    </div>
                    <form action="{{ route('doctor_attendances.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition shadow flex items-center justify-center" onclick="return confirm('Bạn có chắc muốn kết thúc ca làm việc?')">
                            <i data-lucide="log-out" class="w-5 h-5 mr-2"></i> CHECK-OUT (TAN CA)
                        </button>
                    </form>
                </div>

            @else
                {{-- TRẠNG THÁI 3: ĐÃ HOÀN THÀNH --}}
                <div class="text-center py-2">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <i data-lucide="check-circle-2" class="w-12 h-12 text-green-500 mx-auto mb-2"></i>
                        <h4 class="text-gray-800 font-bold">Đã hoàn thành!</h4>
                        <div class="text-sm mt-2 text-gray-600">
                            {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }} 
                            <i data-lucide="arrow-right" class="w-3 h-3 inline mx-1"></i> 
                            {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}
                        </div>
                        <div class="text-blue-600 font-bold text-xs mt-1">
                            Tổng: {{ $todayAttendance->total_hours ?? 0 }} giờ
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <section id="lich-kham" class="content-section">
        <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
            <i data-lucide="calendar-days" class="w-6 h-6 mr-2 text-blue-600"></i> Lịch khám hôm nay ({{ \Carbon\Carbon::parse($today)->format('d/m/Y') }})
        </h3>
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            @if($appointments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bệnh nhân</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phòng/Loại</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($appointments as $appt)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-blue-600">{{ \Carbon\Carbon::parse($appt->time)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $appt->patient_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appt->notes ?? 'Không có ghi chú' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($appt->room)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Phòng {{ $appt->room }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Online
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'Đang chờ' => 'bg-yellow-100 text-yellow-800',
                                            'Đang khám' => 'bg-blue-100 text-blue-800',
                                            'Hoàn thành' => 'bg-green-100 text-green-800',
                                            'Hủy hẹn' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[$appt->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ $appt->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    @if($appt->status == 'Đang chờ')
                                        <form action="{{ route('doctor.appointments.updateStatus', $appt->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="Đang khám">
                                            <button class="text-blue-600 hover:text-blue-900 font-bold border border-blue-200 bg-blue-50 px-3 py-1 rounded">Gọi khám</button>
                                        </form>
                                    @elseif($appt->status == 'Đang khám')
                                        <a href="#" class="text-green-600 hover:text-green-900 font-bold border border-green-200 bg-green-50 px-3 py-1 rounded">Kê đơn</a>
                                    @else
                                        <span class="text-gray-400">---</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                    <p>Hôm nay chưa có lịch hẹn nào.</p>
                </div>
            @endif
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
@endsection