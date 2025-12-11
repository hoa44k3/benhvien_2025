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
        
        <div class="flex items-center p-6 bg-white rounded-xl shadow-lg mb-8">
            <div class="w-20 h-20 bg-teal-50 text-teal-600 rounded-full flex justify-center items-center text-3xl mr-5 flex-shrink-0">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                <div class="text-sm text-gray-500 space-y-1">
                    <span class="block"><i class="fas fa-birthday-cake mr-2 text-teal-600"></i> Sinh: 15/03/1985</span>
                    <span class="block"><i class="fas fa-map-marker-alt mr-2 text-teal-600"></i> Hà Nội | **Mã HS:** HS850315</span>
                </div>
            </div>
        </div>

        <div class="tab-nav flex border-b-2 border-gray-200 mb-6">
            <button class="tab-item active py-3 px-6 font-semibold text-teal-600 border-b-3 border-teal-600 hover:text-teal-700 transition duration-200 focus:outline-none" 
                    data-tab="medical-records" style="border-bottom-width: 3px;">
                <i class="fas fa-book-medical mr-2"></i> Bệnh án ({{ $medicalRecords->count() }})

            </button>
            <button class="tab-item py-3 px-6 font-semibold text-gray-600 border-b-3 border-transparent hover:text-teal-600 hover:border-teal-600 transition duration-200 focus:outline-none" 
                    data-tab="prescriptions" style="border-bottom-width: 3px;">
                <i class="fas fa-prescription-bottle-alt mr-2"></i> Đơn thuốc (3)
            </button>
            <button class="tab-item py-3 px-6 font-semibold text-gray-600 border-b-3 border-transparent hover:text-teal-600 hover:border-teal-600 transition duration-200 focus:outline-none" 
                    data-tab="test-results" style="border-bottom-width: 3px;">
                <i class="fas fa-vial mr-2"></i> Xét nghiệm (5)
            </button>
        </div>
 
        <div id="medical-records" class="tab-content active space-y-6">
            @foreach($medicalRecords as $record)
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">{{ $record->title }} - {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</div>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $record->status }}</span>
                </div>

                <div class="text-sm text-gray-600 mb-5">
                    <i class="fas fa-user-md mr-1 text-blue-500"></i> {{ $record->doctor_name }} | 
                    <i class="fas fa-stethoscope mr-1 text-blue-500"></i> Chuyên khoa: {{ is_array($record->department) ? $record->department['name'] : json_decode($record->department)->name }}

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-teal-600 mb-1">Chẩn đoán</h4>
                        <p class="text-gray-700">{{ $record->diagnosis }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-teal-600 mb-1">Điều trị</h4>
                        <p class="text-gray-700">{{ $record->treatment }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-teal-600 mb-1">Lịch tái khám</h4>
                        <p class="text-gray-700"><i class="far fa-calendar-alt mr-1"></i> {{ $record->next_checkup }}</p>
                    </div>
                </div>

                <a href="{{ route('medical_records.download', $record->id) }}" 
                class="inline-flex items-center border border-teal-600 text-teal-600 font-medium px-4 py-2 rounded-lg hover:bg-teal-600 hover:text-white transition duration-300">
                    <i class="fas fa-download mr-2"></i> Tải hồ sơ
                </a>
            </div>
            @endforeach
        </div>
       {{-- <div id="prescriptions" class="tab-content hidden space-y-6">

    {{-- Nếu không có đơn thuốc --}}
    @if ($prescriptions->count() === 0)
        <div class="text-center py-10 text-gray-500">
            <i class="fas fa-prescription-bottle-alt text-4xl mb-3 text-gray-400"></i>
            <p class="text-lg">Bạn chưa có đơn thuốc nào</p>
        </div>
    @endif

    @foreach ($prescriptions as $prescription)
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">

            <!-- Header -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                <div class="text-xl font-bold text-gray-900">
                    Đơn thuốc ngày {{ $prescription->created_at->format('d/m/Y') }}
                </div>

                <a href="{{ route('prescriptions.show', $prescription->id) }}"
                   class="text-sm text-blue-600 font-medium hover:text-blue-700">
                    <i class="fas fa-eye mr-1"></i> Xem chi tiết
                </a>
            </div>

            <!-- Doctor -->
            <div class="text-sm text-gray-600 mb-3">
                <i class="fas fa-user-md mr-1 text-blue-500"></i>
                Bác sĩ: <b>{{ $prescription->doctor->name ?? 'Không xác định' }}</b>
            </div>

            <!-- Table -->
            <table class="responsive-table w-full border-collapse mb-5">
                <thead>
                    <tr class="bg-teal-50 text-teal-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Tên thuốc</th>
                        <th class="py-3 px-6 text-left">Liều dùng</th>
                        <th class="py-3 px-6 text-left">Thời gian</th>
                        <th class="py-3 px-6 text-left">Hướng dẫn</th>
                    </tr>
                </thead>

                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($prescription->items as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 font-medium">{{ $item->medicine_name }}</td>
                            <td class="py-3 px-6">{{ $item->dosage }}</td>
                            <td class="py-3 px-6">{{ $item->duration }}</td>
                            <td class="py-3 px-6">{{ $item->instruction }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Note -->
            @if ($prescription->note)
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 rounded-md text-sm">
                    <b>Lưu ý:</b> {{ $prescription->note }}
                </div>
            @endif
        </div>
    @endforeach

</div> 



        <div id="test-results" class="tab-content hidden space-y-6">

             <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Xét nghiệm sinh hóa máu - 10/1/2024</div>
                    <a href="#" class="text-sm text-blue-600 font-medium hover:text-blue-700"><i class="fas fa-download mr-1"></i> Tải kết quả</a>
                </div>

                <div class="text-sm text-gray-600 mb-3"><i class="fas fa-flask mr-1 text-blue-500"></i> **Khoa Xét nghiệm Tổng quát**</div>

                <table class="responsive-table w-full border-collapse mb-5">
                    <thead>
                        <tr class="bg-teal-50 text-teal-700 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Chỉ số</th>
                            <th class="py-3 px-6 text-left">Kết quả</th>
                            <th class="py-3 px-6 text-left">Đơn vị</th>
                            <th class="py-3 px-6 text-left">Bình thường</th>
                            <th class="py-3 px-6 text-left">Đánh giá</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Chỉ số" class="py-3 px-6 text-left whitespace-nowrap font-medium">Glucose</td>
                            <td data-label="Kết quả" class="py-3 px-6 text-left">95</td>
                            <td data-label="Đơn vị" class="py-3 px-6 text-left">mg/dL</td>
                            <td data-label="Bình thường" class="py-3 px-6 text-left">70-100</td>
                            <td data-label="Đánh giá" class="py-3 px-6 text-left text-green-600 font-semibold">Bình thường</td>
                        </tr>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Chỉ số" class="py-3 px-6 text-left whitespace-nowrap font-medium">Cholesterol</td>
                            <td data-label="Kết quả" class="py-3 px-6 text-left text-red-500 font-bold">220</td>
                            <td data-label="Đơn vị" class="py-3 px-6 text-left">mg/dL</td>
                            <td data-label="Bình thường" class="py-3 px-6 text-left">&lt;200</td>
                            <td data-label="Đánh giá" class="py-3 px-6 text-left text-red-500 font-semibold">Cao</td>
                        </tr>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Chỉ số" class="py-3 px-6 text-left whitespace-nowrap font-medium">Creatinine</td>
                            <td data-label="Kết quả" class="py-3 px-6 text-left">0.9</td>
                            <td data-label="Đơn vị" class="py-3 px-6 text-left">mg/dL</td>
                            <td data-label="Bình thường" class="py-3 px-6 text-left">0.6-1.2</td>
                            <td data-label="Đánh giá" class="py-3 px-6 text-left text-green-600 font-semibold">Bình thường</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Siêu âm bụng tổng quát - 01/10/2023</div>
                    <a href="#" class="inline-flex items-center text-sm border border-blue-600 text-blue-600 font-medium px-3 py-1 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                        <i class="fas fa-images mr-1"></i> Xem ảnh
                    </a>
                </div>

                <div class="text-sm text-gray-600 mb-3"><i class="fas fa-x-ray mr-1 text-blue-500"></i> **Khoa Chẩn đoán hình ảnh**</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-blue-600 mb-1">Kết luận</h4>
                        <p class="text-gray-700">Không phát hiện bất thường đáng kể.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-600 mb-1">Chi tiết</h4>
                        <p class="text-gray-700">Gan, thận, lách: Kích thước và cấu trúc bình thường. Không có dịch ổ bụng.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">X-quang phổi - 12/07/2023</div>
                    <a href="#" class="inline-flex items-center text-sm border border-yellow-600 text-yellow-600 font-medium px-3 py-1 rounded-lg hover:bg-yellow-600 hover:text-white transition duration-300">
                        <i class="fas fa-images mr-1"></i> Xem ảnh
                    </a>
                </div>

                <div class="text-sm text-gray-600 mb-3"><i class="fas fa-x-ray mr-1 text-blue-500"></i> **Khoa Chẩn đoán hình ảnh**</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-yellow-600 mb-1">Kết luận</h4>
                        <p class="text-gray-700">Hình ảnh phổi và tim trong giới hạn bình thường.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-600 mb-1">Chi tiết</h4>
                        <p class="text-gray-700">Không có dấu hiệu thâm nhiễm hoặc tràn dịch màng phổi.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection