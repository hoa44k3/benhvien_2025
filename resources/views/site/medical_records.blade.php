@extends('site.master')

@section('title','Hồ sơ bệnh án')

@section('body')
    <section class="bg-gradient-to-r from-teal-600 to-teal-800 text-white py-16 mb-8 shadow-xl">
        <div class="container mx-auto max-w-7xl px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">Hồ sơ bệnh án điện tử</h1>
            <p class="text-lg opacity-90">Quản lý và theo dõi sức khỏe cá nhân của bạn</p>
        </div>
    </section>

    <div class="container mx-auto max-w-7xl px-4 pb-12">
        
        {{-- Thông tin cá nhân --}}
        <div class="flex items-center p-6 bg-white rounded-xl shadow-lg mb-8">
            <div class="w-20 h-20 bg-teal-50 text-teal-600 rounded-full flex justify-center items-center text-3xl mr-5 flex-shrink-0">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                <div class="text-sm text-gray-500 space-y-1">
                    <span class="block"><i class="fas fa-phone mr-2 text-teal-600"></i> {{ $user->phone ?? 'Chưa cập nhật' }}</span>
                    <span class="block"><i class="fas fa-envelope mr-2 text-teal-600"></i> {{ $user->email }}</span>
                </div>
            </div>
        </div>

        {{-- Menu Tabs --}}
        <div class="tab-nav flex border-b-2 border-gray-200 mb-6 overflow-x-auto">
            <button class="tab-item active py-3 px-6 font-semibold text-teal-600 border-b-3 border-teal-600 whitespace-nowrap focus:outline-none" 
                    data-tab="medical-records" style="border-bottom-width: 3px;">
                <i class="fas fa-book-medical mr-2"></i> Bệnh án ({{ $medicalRecords->count() }})
            </button>
            <button class="tab-item py-3 px-6 font-semibold text-gray-600 border-b-3 border-transparent whitespace-nowrap focus:outline-none hover:text-teal-600" 
                    data-tab="prescriptions">
                <i class="fas fa-prescription-bottle-alt mr-2"></i> Đơn thuốc ({{ $prescriptions->count() }})
            </button>
            <button class="tab-item py-3 px-6 font-semibold text-gray-600 border-b-3 border-transparent whitespace-nowrap focus:outline-none hover:text-teal-600" 
                    data-tab="test-results">
                <i class="fas fa-vial mr-2"></i> Xét nghiệm ({{ $testResults->count() }})
            </button>
        </div>
 
        {{-- TAB 1: BỆNH ÁN --}}
        <div id="medical-records" class="tab-content active space-y-6">
            @if($medicalRecords->isEmpty())
                <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-xl">
                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-400"></i>
                    <p>Chưa có hồ sơ bệnh án nào.</p>
                </div>
            @endif

            @foreach($medicalRecords as $record)
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600 hover:shadow-xl transition-shadow relative">
                {{-- Header Card --}}
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">
                        {{ $record->title }} - {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                    </div>
                    @php
                        $statusColors = [
                            'đã_khám' => 'bg-green-100 text-green-700',
                            'đang_khám' => 'bg-blue-100 text-blue-700',
                            'chờ_khám' => 'bg-gray-100 text-gray-700',
                            'hủy' => 'bg-red-100 text-red-700',
                            'completed' => 'bg-green-100 text-green-700'
                        ];
                        $statusKey = strtolower($record->status);
                    @endphp
                    <span class="{{ $statusColors[$statusKey] ?? 'bg-gray-100' }} px-3 py-1 rounded-full text-sm font-semibold capitalize">
                        {{ str_replace('_', ' ', $record->status) }}
                    </span>
                </div>

                {{-- Thông tin bác sĩ --}}
                <div class="text-sm text-gray-600 mb-5 flex flex-wrap gap-4">
                    <span><i class="fas fa-user-md mr-1 text-teal-600"></i> BS. {{ $record->doctor->user->name ?? ($record->doctor->name ?? '---') }}</span>
                    <span><i class="fas fa-clinic-medical mr-1 text-teal-600"></i> Khoa: {{ $record->department->name ?? '---' }}</span>
                </div>

                {{-- Nội dung khám --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-teal-700 mb-1"><i class="fas fa-stethoscope mr-2"></i> Chẩn đoán</h4>
                        <p class="text-gray-700">{{ $record->diagnosis ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-teal-700 mb-1"><i class="fas fa-notes-medical mr-2"></i> Điều trị</h4>
                        <p class="text-gray-700">{{ $record->treatment ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>

                {{-- PHẦN ĐÁNH GIÁ (LOGIC MỚI) --}}
                @if($record->status == 'completed' || $record->diagnosis)
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end items-center">
                        {{-- Kiểm tra xem ca khám này đã có review chưa (nhờ relationship hasOne trong Model) --}}
                        @if($record->review)
                            {{-- ĐÃ ĐÁNH GIÁ: HIỂN THỊ KẾT QUẢ --}}
                            <div class="flex items-center bg-yellow-50 px-4 py-2 rounded-lg border border-yellow-200">
                                <span class="text-gray-600 text-sm mr-2 font-medium">Bạn đã đánh giá:</span>
                                <div class="flex text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $record->review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                @if($record->review->comment)
                                    <span class="text-xs text-gray-500 italic border-l border-gray-300 pl-2">
                                        "{{ Str::limit($record->review->comment, 30) }}"
                                    </span>
                                @endif
                            </div>
                        @else
                            {{-- CHƯA ĐÁNH GIÁ: HIỆN NÚT --}}
                            <button onclick="openReviewModal({{ $record->doctor_id }}, {{ $record->id }}, '{{ $record->doctor->user->name ?? 'Bác sĩ' }}')" 
                                    class="flex items-center px-4 py-2 bg-white border border-yellow-400 text-yellow-600 rounded-lg hover:bg-yellow-50 transition-all duration-200 shadow-sm font-semibold">
                                <i class="far fa-star mr-2"></i> Viết đánh giá bác sĩ
                            </button>
                        @endif
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- TAB 2: ĐƠN THUỐC (Giữ nguyên) --}}
        <div id="prescriptions" class="tab-content hidden space-y-6">
            @if($prescriptions->isEmpty())
                <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-xl">
                    <i class="fas fa-prescription-bottle-alt text-4xl mb-3 text-gray-400"></i>
                    <p>Chưa có đơn thuốc nào.</p>
                </div>
            @endif

            @foreach ($prescriptions as $prescription)
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Đơn thuốc: {{ $prescription->code }}</h3>
                            <p class="text-sm text-gray-500">{{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="text-sm font-medium px-3 py-1 bg-blue-50 text-blue-700 rounded-lg">
                            BS. {{ $prescription->doctor->user->name ?? ($prescription->doctor->name ?? '---') }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Tên thuốc</th>
                                    <th class="px-4 py-3">SL</th>
                                    <th class="px-4 py-3">Liều dùng</th>
                                    <th class="px-4 py-3">Hướng dẫn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prescription->items as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item->medicine_name }}</td>
                                        <td class="px-4 py-3">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3">{{ $item->dosage ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-500 italic">{{ $item->instruction ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($prescription->note)
                        <div class="mt-4 p-3 bg-yellow-50 text-yellow-800 text-sm rounded-lg border border-yellow-200">
                            <i class="fas fa-info-circle mr-1"></i> <strong>Lưu ý:</strong> {{ $prescription->note }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div> 

        {{-- TAB 3: KẾT QUẢ XÉT NGHIỆM (Giữ nguyên) --}}
        <div id="test-results" class="tab-content hidden space-y-6">
            @if($testResults->isEmpty())
                <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-xl">
                    <i class="fas fa-microscope text-4xl mb-3 text-gray-400"></i>
                    <p>Chưa có kết quả xét nghiệm nào.</p>
                </div>
            @endif

            @foreach($testResults as $test)
             <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600 hover:shadow-xl transition-shadow">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-gray-200 mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $test->test_name }}</h3>
                        <p class="text-sm text-gray-500">Ngày: {{ \Carbon\Carbon::parse($test->date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="mt-2 md:mt-0">
                        @if($test->file_path)
                            <a href="{{ asset('storage/'.$test->file_path) }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                                <i class="fas fa-file-download mr-2"></i> Xem File KQ
                            </a>
                        @endif
                    </div>
                </div>

                <div class="text-sm text-gray-600 mb-4 flex flex-wrap gap-4">
                    <span><i class="fas fa-flask mr-1 text-purple-600"></i> Phòng Lab: {{ $test->lab_name ?? 'Tại chỗ' }}</span>
                    <span><i class="fas fa-user-md mr-1 text-purple-600"></i> BS Chỉ định: {{ $test->doctor->user->name ?? ($test->doctor->name ?? '---') }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 border border-gray-200 rounded-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-purple-50">
                            <tr>
                                <th class="px-6 py-3 w-1/3">Kết quả</th>
                                <th class="px-6 py-3 w-1/3">Đơn vị / Chỉ số</th>
                                <th class="px-6 py-3 w-1/3">Đánh giá / Kết luận</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900 text-lg">
                                    {{ $test->result ?? 'Đang chờ...' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $test->unit ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $test->diagnosis ?? 'Chưa có đánh giá' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        {{-- MODAL ĐÁNH GIÁ --}}
        <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
            <div class="bg-white p-6 rounded-2xl w-full max-w-md shadow-2xl transform transition-all scale-100">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl font-bold text-gray-800">Đánh giá bác sĩ</h3>
                    <button type="button" onclick="document.getElementById('reviewModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <p class="mb-4 text-sm text-gray-600">Bác sĩ: <span id="reviewDoctorName" class="font-bold text-primary text-base"></span></p>

                {{-- Cập nhật Form Action --}}
                <form action="{{ route('review.store') }}" method="POST">
                    @csrf
                    {{-- Input ẩn ID Bác sĩ --}}
                    <input type="hidden" name="doctor_id" id="reviewDoctorId">
                    {{-- Input ẩn ID Bệnh án (QUAN TRỌNG) --}}
                    <input type="hidden" name="medical_record_id" id="reviewMedicalRecordId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700">Mức độ hài lòng:</label>
                        <select name="rating" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                            <option value="5" selected>⭐⭐⭐⭐⭐ - Tuyệt vời</option>
                            <option value="4">⭐⭐⭐⭐ - Tốt</option>
                            <option value="3">⭐⭐⭐ - Bình thường</option>
                            <option value="2">⭐⭐ - Tệ</option>
                            <option value="1">⭐ - Rất tệ</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold mb-2 text-gray-700">Nhận xét của bạn:</label>
                        <textarea name="comment" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" rows="4" placeholder="Hãy chia sẻ trải nghiệm khám bệnh của bạn..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('reviewModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">Hủy</button>
                        <button type="submit" class="px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 shadow-md transition transform active:scale-95">Gửi đánh giá</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Script xử lý --}}
    <script>
        // Hàm mở modal đánh giá (Nhận thêm recordId)
        function openReviewModal(doctorId, recordId, doctorName) {
            document.getElementById('reviewDoctorId').value = doctorId;
            document.getElementById('reviewMedicalRecordId').value = recordId; // Set ID bệnh án
            document.getElementById('reviewDoctorName').innerText = doctorName;
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        // Script chuyển tab
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-item');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Xóa class active cũ
                    tabs.forEach(t => {
                        t.classList.remove('active', 'text-teal-600', 'border-teal-600');
                        t.classList.add('text-gray-600', 'border-transparent', 'hover:text-teal-600');
                        t.style.borderBottomWidth = "3px"; 
                    });
                    
                    contents.forEach(c => c.classList.add('hidden'));

                    // Thêm class active mới
                    tab.classList.add('active', 'text-teal-600', 'border-teal-600');
                    tab.classList.remove('text-gray-600', 'border-transparent', 'hover:text-teal-600');
                    
                    const targetId = tab.getAttribute('data-tab');
                    document.getElementById(targetId).classList.remove('hidden');
                });
            });
        });
    </script>
@endsection