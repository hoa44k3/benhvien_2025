   @extends('pharmacist.master')
@section('title','Dượt sĩ')
@section('body')
 <!-- Welcome Card -->
        <div class="bg-white p-6 rounded-2xl shadow-xl mb-8 border-l-8 border-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Dược sĩ: Nguyễn Thị Mai Linh 💊</h2>
                    <p class="text-gray-500 mt-1">Phòng: Kho Dược Trung tâm • Ca: Sáng (7:30 - 16:30)</p>
                </div>
                <div class="text-4xl primary-text font-extrabold hidden sm:block">
                    <span id="current-time">10:30</span>
                </div>
            </div>
        </div>

        <!-- Content Sections (Các phần chức năng) -->

        <!-- 2.1. Tổng quan Công việc -->
        <section id="tong-quan" class="content-section">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="layout-dashboard" class="w-6 h-6 mr-2"></i> Tổng quan Công việc
            </h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Đơn thuốc Chờ duyệt -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Đơn thuốc Chờ duyệt</p>
                            <p class="text-3xl font-bold text-gray-800">8</p>
                        </div>
                        <i data-lucide="clipboard-list" class="w-10 h-10 primary-text opacity-50"></i>
                    </div>
                </div>
                <!-- Cảnh báo Tương tác -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Cảnh báo Tương tác (Hôm nay)</p>
                            <p class="text-3xl font-bold text-gray-800">2</p>
                        </div>
                        <i data-lucide="alert-triangle" class="w-10 h-10 text-red-500 opacity-50"></i>
                    </div>
                </div>
                <!-- Thuốc Sắp hết hạn (trong 30 ngày) -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Mục hàng sắp hết hạn</p>
                            <p class="text-3xl font-bold text-gray-800">14</p>
                        </div>
                        <i data-lucide="calendar-x" class="w-10 h-10 text-yellow-500 opacity-50"></i>
                    </div>
                </div>
                <!-- Thuốc Tồn kho Thấp (dưới min) -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Mục hàng Tồn kho thấp</p>
                            <p class="text-3xl font-bold text-gray-800">7</p>
                        </div>
                        <i data-lucide="package-minus" class="w-10 h-10 text-blue-500 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <h4 class="font-bold text-xl mb-3 border-b pb-2 primary-text">Danh sách Đơn thuốc quan trọng chờ duyệt</h4>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bệnh nhân</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BS kê đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="prescription-queue-body" class="bg-white divide-y divide-gray-200">
                        <!-- Dữ liệu mẫu sẽ được chèn bằng JS -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 2.2. Duyệt Đơn thuốc -->
        <section id="duyet-don-thuoc" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="clipboard-list" class="w-6 h-6 mr-2"></i> Đơn thuốc Chờ duyệt (8 Đơn)
            </h3>
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Cột Danh sách đơn -->
                <div class="lg:col-span-1 bg-white p-4 rounded-2xl shadow-xl space-y-3 h-[70vh] overflow-y-auto">
                    <h4 class="font-bold text-lg text-gray-700 border-b pb-2">Chọn đơn để xem chi tiết</h4>
                    <div id="list-prescriptions" class="space-y-3">
                        <!-- Thẻ đơn thuốc mẫu -->
                        <div class="p-4 rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:bg-yellow-50 transition duration-150" onclick="viewPrescriptionDetails('DN00123')">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-800">DN00123 - Nguyễn Văn An</span>
                                <span class="status-pill bg-red-100 text-red-700">Tương tác!</span>
                            </div>
                            <p class="text-sm text-gray-500">BS. Huy | 10:20 | 4 loại thuốc</p>
                        </div>
                        <div class="p-4 rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:bg-yellow-50 transition duration-150 bg-yellow-50 border-primary" onclick="viewPrescriptionDetails('DN00124')">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-800">DN00124 - Phạm Thu Hà</span>
                                <span class="status-pill bg-yellow-100 text-yellow-700">Chờ duyệt</span>
                            </div>
                            <p class="text-sm text-gray-500">BS. Vân | 10:25 | 2 loại thuốc</p>
                        </div>
                    </div>
                </div>

                <!-- Cột Chi tiết đơn & Tương tác -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xl h-[70vh] flex flex-col">
                    <h4 class="text-2xl font-bold primary-text mb-4 border-b pb-2">Chi tiết Đơn thuốc: <span id="current-prescription-id">DN00124</span></h4>
                    <div id="prescription-details" class="flex-1 overflow-y-auto space-y-4">
                        
                        <!-- Thông tin Bệnh nhân -->
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="font-semibold text-lg text-gray-800">Bệnh nhân: Phạm Thu Hà (BN00789)</p>
                            <p class="text-sm text-gray-600">BS Kê: Nguyễn Thị Vân | Chẩn đoán: Viêm họng cấp</p>
                        </div>

                        <!-- Danh sách thuốc -->
                        <div>
                            <p class="font-semibold text-gray-700 mb-2">Danh sách Thuốc:</p>
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-2 text-left">Thuốc</th>
                                        <th class="p-2 text-center">SL</th>
                                        <th class="p-2 text-left">HDSD</th>
                                        <th class="p-2 text-center">Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody id="drug-list-body">
                                    <tr class="border-b hover:bg-red-50/50 transition">
                                        <td class="p-2 font-medium text-red-600">Amoxicillin 500mg (Kháng sinh)</td>
                                        <td class="p-2 text-center">14</td>
                                        <td class="p-2">Uống 2 viên/ngày, sau ăn</td>
                                        <td class="p-2 text-center text-green-600">450</td>
                                    </tr>
                                    <tr class="border-b hover:bg-yellow-50 transition">
                                        <td class="p-2 font-medium">Paracetamol 500mg</td>
                                        <td class="p-2 text-center">10</td>
                                        <td class="p-2">Uống khi đau/sốt</td>
                                        <td class="p-2 text-center text-green-600">2000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Cảnh báo Tương tác -->
                        <div id="interaction-alert" class="p-4 rounded-xl bg-red-100 border border-red-400 text-red-800 hidden">
                            <p class="font-bold flex items-center mb-2"><i data-lucide="alert-octagon" class="w-5 h-5 mr-2"></i> CẢNH BÁO TƯƠNG TÁC THUỐC MỨC CAO!</p>
                            <p class="text-sm">Amoxicillin có thể làm giảm hiệu quả của thuốc ngừa thai đường uống. Cần tư vấn bệnh nhân dùng biện pháp tránh thai bổ sung.</p>
                        </div>

                        <!-- Ghi chú Dược sĩ -->
                        <div>
                            <label for="pharmacist-note" class="block text-gray-700 font-semibold mb-2">Ghi chú & Chỉ định Dược sĩ:</label>
                            <textarea id="pharmacist-note" rows="3" class="w-full p-3 border rounded-lg focus:ring-amber-500 focus:border-amber-500" placeholder="Đã kiểm tra tương tác. Amoxicillin và Paracetamol không tương tác trực tiếp, nhưng cần lưu ý cho bệnh nhân về Amoxicillin và thuốc ngừa thai."></textarea>
                        </div>
                    </div>

                    <!-- Khu vực Thao tác -->
                    <div class="mt-4 pt-4 border-t flex justify-end space-x-3">
                        <button class="bg-red-500 text-white p-3 rounded-lg font-bold hover:bg-red-600 transition" onclick="rejectPrescription()">
                            <i data-lucide="x" class="w-5 h-5 mr-2 inline-block"></i> Từ chối (Gửi lại BS)
                        </button>
                        <button class="primary-color text-white p-3 rounded-lg font-bold hover:bg-amber-600 transition" onclick="approvePrescription()">
                            <i data-lucide="check" class="w-5 h-5 mr-2 inline-block"></i> Duyệt & Xuất kho
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2.3. Kiểm tra Tồn kho -->
        <section id="kiem-kho" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="package-search" class="w-6 h-6 mr-2"></i> Kiểm tra Tồn kho & Đặt hàng
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex space-x-4 mb-4">
                    <input type="text" id="search-drug" class="flex-1 p-3 border rounded-lg focus:ring-amber-500 focus:border-amber-500" placeholder="Tìm kiếm theo Tên thuốc hoặc Hoạt chất...">
                    <button class="primary-color text-white p-3 rounded-lg font-bold hover:bg-amber-600 transition" onclick="searchDrug()">
                        <i data-lucide="search" class="w-5 h-5 inline-block"></i>
                    </button>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl mb-4">
                    <p class="font-semibold text-gray-700">Kết quả Tìm kiếm: <span id="search-result-count">3</span> loại thuốc</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Thuốc</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hoạt chất</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mức Min</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vị trí</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Đặt hàng</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-table-body" class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">Atorvastatin 20mg</td>
                                <td class="px-6 py-4 text-gray-500">Atorvastatin</td>
                                <td class="px-6 py-4 text-center text-green-600 font-bold">1200 hộp</td>
                                <td class="px-6 py-4 text-center">500</td>
                                <td class="px-6 py-4 text-gray-500">Kệ A-02</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-600">Đặt hàng</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-red-50 transition">
                                <td class="px-6 py-4 font-medium text-red-600">Omez (Omeprazole 20mg)</td>
                                <td class="px-6 py-4 text-gray-500">Omeprazole</td>
                                <td class="px-6 py-4 text-center text-red-600 font-bold">45 hộp</td>
                                <td class="px-6 py-4 text-center">150</td>
                                <td class="px-6 py-4 text-gray-500">Kệ C-10</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-600">Đặt hàng</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        
        <!-- 2.4. Cảnh báo Hết hạn -->
        <section id="canh-bao-hethan" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="alarm-check" class="w-6 h-6 mr-2"></i> Cảnh báo Thuốc Sắp hết hạn
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <!-- Card 1: Sắp hết hạn (Dưới 30 ngày) -->
                    <div class="bg-yellow-50 p-4 rounded-xl border-l-4 border-yellow-500 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-bold text-yellow-800">Cần xử lý gấp (Dưới 30 ngày)</p>
                            <p class="text-3xl font-extrabold text-yellow-600">5 mục hàng</p>
                        </div>
                        <i data-lucide="calendar-off" class="w-10 h-10 text-yellow-500 opacity-70"></i>
                    </div>
                    <!-- Card 2: Cần theo dõi (30 - 90 ngày) -->
                    <div class="bg-blue-50 p-4 rounded-xl border-l-4 border-blue-500 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-bold text-blue-800">Cần theo dõi (30 - 90 ngày)</p>
                            <p class="text-3xl font-extrabold text-blue-600">9 mục hàng</p>
                        </div>
                        <i data-lucide="calendar-days" class="w-10 h-10 text-blue-500 opacity-70"></i>
                    </div>
                </div>

                <h4 class="font-bold text-lg mb-3 border-b pb-2 text-red-700">Danh sách Thuốc sắp hết hạn (Dưới 30 ngày)</h4>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-red-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Thuốc</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lô/Mã</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SL Tồn</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider text-red-600">Ngày Hết hạn</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="expired-table-body" class="bg-white divide-y divide-gray-200">
                            <tr class="bg-red-50 hover:bg-red-100 transition">
                                <td class="px-6 py-4 font-medium text-red-600">Insulin NPH</td>
                                <td class="px-6 py-4 text-gray-500">Lot: INS-2023X</td>
                                <td class="px-6 py-4 text-center font-bold">120 lọ</td>
                                <td class="px-6 py-4 text-center text-red-700 font-bold">25/11/2024 (20 ngày)</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-gray-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-gray-600" onclick="processExpiry('INS-2023X')">Xử lý lô hàng</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-yellow-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">Viên uống Vitamin C 500mg</td>
                                <td class="px-6 py-4 text-gray-500">Lot: VITC-1124Y</td>
                                <td class="px-6 py-4 text-center font-bold">50 hộp</td>
                                <td class="px-6 py-4 text-center text-yellow-600 font-bold">15/12/2024 (50 ngày)</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-gray-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-gray-600" onclick="processExpiry('VITC-1124Y')">Xử lý lô hàng</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @endsection