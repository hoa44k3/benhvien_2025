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
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Nguyễn Văn Nam</h2>
                <div class="text-sm text-gray-500 space-y-1">
                    <span class="block"><i class="fas fa-birthday-cake mr-2 text-teal-600"></i> Sinh: 15/03/1985</span>
                    <span class="block"><i class="fas fa-map-marker-alt mr-2 text-teal-600"></i> Hà Nội | **Mã HS:** HS850315</span>
                </div>
            </div>
        </div>

        <div class="tab-nav flex border-b-2 border-gray-200 mb-6">
            <button class="tab-item active py-3 px-6 font-semibold text-teal-600 border-b-3 border-teal-600 hover:text-teal-700 transition duration-200 focus:outline-none" 
                    data-tab="medical-records" style="border-bottom-width: 3px;">
                <i class="fas fa-book-medical mr-2"></i> Bệnh án (4)
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
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Khám tổng quát - 15/1/2024</div>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Hoàn thành</span>
                </div>

                <div class="text-sm text-gray-600 mb-5">
                    <i class="fas fa-user-md mr-1 text-blue-500"></i> **BS. Nguyễn Văn An** | 
                    <i class="fas fa-stethoscope mr-1 text-blue-500"></i> Chuyên khoa: Tim mạch
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-teal-600 mb-1">Chẩn đoán</h4>
                        <p class="text-gray-700">Tăng huyết áp nhẹ</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-teal-600 mb-1">Điều trị</h4>
                        <p class="text-gray-700">Thuốc hạ huyết áp, chế độ ăn ít muối</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-teal-600 mb-1">Lịch tái khám</h4>
                        <p class="text-gray-700"><i class="far fa-calendar-alt mr-1"></i> 15/2/2024</p>
                    </div>
                </div>
                
                <a href="#" class="inline-flex items-center border border-teal-600 text-teal-600 font-medium px-4 py-2 rounded-lg hover:bg-teal-600 hover:text-white transition duration-300">
                    <i class="fas fa-download mr-2"></i> Tải hồ sơ
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Khám Tai Mũi Họng - 20/11/2023</div>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Hoàn thành</span>
                </div>

                <div class="text-sm text-gray-600 mb-5">
                    <i class="fas fa-user-md mr-1 text-blue-500"></i> **BS. Trần Thị Mai** | 
                    <i class="fas fa-stethoscope mr-1 text-blue-500"></i> Chuyên khoa: Tai Mũi Họng
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-blue-600 mb-1">Chẩn đoán</h4>
                        <p class="text-gray-700">Viêm xoang cấp</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-600 mb-1">Điều trị</h4>
                        <p class="text-gray-700">Kháng sinh, thuốc chống viêm, rửa mũi</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-600 mb-1">Tái khám</h4>
                        <p class="text-gray-700"><i class="far fa-calendar-alt mr-1"></i> 30/11/2023</p>
                    </div>
                </div>
                
                <a href="#" class="inline-flex items-center border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                    <i class="fas fa-download mr-2"></i> Tải hồ sơ
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Nội soi dạ dày - 05/09/2023</div>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Hoàn thành</span>
                </div>

                <div class="text-sm text-gray-600 mb-5">
                    <i class="fas fa-user-md mr-1 text-blue-500"></i> **BS. Lê Văn Hùng** | 
                    <i class="fas fa-stethoscope mr-1 text-blue-500"></i> Chuyên khoa: Tiêu hóa
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-yellow-600 mb-1">Chẩn đoán</h4>
                        <p class="text-gray-700">Viêm loét dạ dày tá tràng nhẹ</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-600 mb-1">Điều trị</h4>
                        <p class="text-gray-700">Thuốc ức chế bơm proton</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-600 mb-1">Tái khám</h4>
                        <p class="text-gray-700">Theo dõi định kỳ</p>
                    </div>
                </div>
                
                <a href="#" class="inline-flex items-center border border-yellow-600 text-yellow-600 font-medium px-4 py-2 rounded-lg hover:bg-yellow-600 hover:text-white transition duration-300">
                    <i class="fas fa-download mr-2"></i> Tải hồ sơ
                </a>
            </div>
            
        </div>

        <div id="prescriptions" class="tab-content hidden space-y-6">
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">
                 <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Đơn thuốc ngày 15/1/2024</div>
                    <a href="#" class="text-sm text-blue-600 font-medium hover:text-blue-700"><i class="fas fa-print mr-1"></i> In đơn thuốc</a>
                </div>

                <div class="text-sm text-gray-600 mb-3"><i class="fas fa-user-md mr-1 text-blue-500"></i> **Bác sĩ BS. Nguyễn Văn An**</div>

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
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Tên thuốc" class="py-3 px-6 text-left whitespace-nowrap font-medium">Amlodipine 5mg</td>
                            <td data-label="Liều dùng" class="py-3 px-6 text-left">1 viên/ngày</td>
                            <td data-label="Thời gian" class="py-3 px-6 text-left">30 ngày</td>
                            <td data-label="Hướng dẫn" class="py-3 px-6 text-left">Uống sau ăn sáng</td>
                        </tr>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Tên thuốc" class="py-3 px-6 text-left whitespace-nowrap font-medium">Losartan 50mg</td>
                            <td data-label="Liều dùng" class="py-3 px-6 text-left">1 viên/ngày</td>
                            <td data-label="Thời gian" class="py-3 px-6 text-left">30 ngày</td>
                            <td data-label="Hướng dẫn" class="py-3 px-6 text-left">Uống trước ăn tối</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 rounded-md text-sm">
                    **Lưu ý:** Theo dõi huyết áp hàng ngày, tái khám sau 1 tháng.
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                 <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <div class="text-xl font-bold text-gray-900">Đơn thuốc ngày 20/11/2023</div>
                    <a href="#" class="text-sm text-blue-600 font-medium hover:text-blue-700"><i class="fas fa-print mr-1"></i> In đơn thuốc</a>
                </div>

                <div class="text-sm text-gray-600 mb-3"><i class="fas fa-user-md mr-1 text-blue-500"></i> **Bác sĩ BS. Trần Thị Mai**</div>

                <table class="responsive-table w-full border-collapse mb-5">
                    <thead>
                        <tr class="bg-blue-50 text-blue-700 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Tên thuốc</th>
                            <th class="py-3 px-6 text-left">Liều dùng</th>
                            <th class="py-3 px-6 text-left">Thời gian</th>
                            <th class="py-3 px-6 text-left">Hướng dẫn</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Tên thuốc" class="py-3 px-6 text-left whitespace-nowrap font-medium">Amoxicillin 500mg</td>
                            <td data-label="Liều dùng" class="py-3 px-6 text-left">2 viên/ngày</td>
                            <td data-label="Thời gian" class="py-3 px-6 text-left">7 ngày</td>
                            <td data-label="Hướng dẫn" class="py-3 px-6 text-left">Uống sau ăn</td>
                        </tr>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td data-label="Tên thuốc" class="py-3 px-6 text-left whitespace-nowrap font-medium">Fexofenadine 60mg</td>
                            <td data-label="Liều dùng" class="py-3 px-6 text-left">1 viên/ngày</td>
                            <td data-label="Thời gian" class="py-3 px-6 text-left">7 ngày</td>
                            <td data-label="Hướng dẫn" class="py-3 px-6 text-left">Uống buổi tối</td>
                        </tr>
                    </tbody>
                </table>
            </div>

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