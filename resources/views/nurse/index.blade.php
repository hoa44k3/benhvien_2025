  @extends('nurse.master')
@section('title','Trang chủ')
@section('body')
 <!-- Header cho mobile -->
        <header class="flex justify-between items-center lg:hidden mb-6">
            <h1 class="text-3xl font-extrabold primary-text">Y TÁ Dashboard</h1>
            <button id="menu-button" class="p-2 primary-color text-white rounded-lg shadow-md" onclick="toggleSidebar()">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </header>
        
        <!-- Welcome Card -->
        <div class="bg-white p-6 rounded-2xl shadow-xl mb-8 border-l-8 border-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Y tá: Trần Thị Lan Phương 👩‍⚕️</h2>
                    <p class="text-gray-500 mt-1">Phòng khám: Tim mạch • Ca: Chiều (13:00 - 21:00)</p>
                </div>
                <div class="text-4xl primary-text font-extrabold hidden sm:block">
                    <span id="current-time">13:30</span>
                </div>
            </div>
        </div>

        <!-- Content Sections (Các phần chức năng) -->

        <!-- 2.1. Tình trạng Công việc (Tổng quan) -->
        <section id="tong-quan" class="content-section">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="activity" class="w-6 h-6 mr-2"></i> Tình trạng Công việc
            </h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Tổng BN chờ khám -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">BN Chờ khám (Lọc theo Phòng)</p>
                            <p class="text-3xl font-bold text-gray-800">5</p>
                        </div>
                        <i data-lucide="users" class="w-10 h-10 text-yellow-500 opacity-50"></i>
                    </div>
                </div>
                <!-- BN đã đo chỉ số -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Đã nhập Chỉ số Lâm sàng</p>
                            <p class="text-3xl font-bold text-gray-800">12</p>
                        </div>
                        <i data-lucide="heart-pulse" class="w-10 h-10 primary-text opacity-50"></i>
                    </div>
                </div>
                <!-- BN đang được khám -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bệnh nhân đang được khám</p>
                            <p class="text-3xl font-bold text-gray-800">3</p>
                        </div>
                        <i data-lucide="stethoscope" class="w-10 h-10 text-blue-500 opacity-50"></i>
                    </div>
                </div>
                <!-- BN cần hồ sơ bổ sung -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Hồ sơ cần bổ sung</p>
                            <p class="text-3xl font-bold text-gray-800">1</p>
                        </div>
                        <i data-lucide="alert-triangle" class="w-10 h-10 text-red-500 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <h4 class="font-bold text-xl mb-3 border-b pb-2 primary-text">Danh sách chờ đo Chỉ số Lâm sàng</h4>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bệnh nhân</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phòng khám</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="vitals-queue-body" class="bg-white divide-y divide-gray-200">
                        <!-- Dữ liệu mẫu sẽ được chèn bằng JS -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 2.2. Danh sách Chờ khám -->
        <section id="danh-sach-kham" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="list-checks" class="w-6 h-6 mr-2"></i> Danh sách Chờ khám (Phòng Tim mạch)
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <p class="mb-4 text-gray-600">Đây là danh sách bệnh nhân đã check-in và đang chờ khám tại phòng của bạn.</p>
                <div id="patient-cards" class="grid lg:grid-cols-3 md:grid-cols-2 gap-4">
                    <!-- Thẻ BN mẫu -->
                    <div class="bg-gray-50 p-4 rounded-xl shadow-md border-t-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Mã BN: BN00456</p>
                                <p class="font-bold text-lg text-gray-800">Trần Đình Chiến</p>
                            </div>
                            <span id="status-456" class="status-pill bg-blue-100 text-blue-700">Đang chờ đo chỉ số</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Giờ hẹn: 14:00 | BS. Nguyễn Văn A</p>
                        <button class="mt-3 w-full primary-color text-white p-2 rounded-lg text-sm font-semibold hover:bg-green-600" onclick="startVitalsInput('BN00456')">
                            <i data-lucide="thermometer" class="w-4 h-4 mr-1 inline-block"></i> Bắt đầu đo chỉ số
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2.3. Nhập Chỉ số lâm sàng (Vital Signs) -->
        <section id="nhap-chi-so" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="thermometer" class="w-6 h-6 mr-2"></i> Nhập Chỉ số Lâm sàng (Vital Signs)
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl border-t-4 border-primary">
                <h4 class="font-bold text-xl mb-4 text-gray-800">Bệnh nhân: <span id="current-patient-name" class="primary-text">Chưa chọn</span></h4>
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Form nhập chỉ số -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <label for="temperature" class="w-1/3 text-gray-600">Nhiệt độ ($$^{\circ}\text{C}$$):</label>
                            <input type="number" id="temperature" class="w-2/3 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="37.0">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label for="heart-rate" class="w-1/3 text-gray-600">Nhịp tim (lần/phút):</label>
                            <input type="number" id="heart-rate" class="w-2/3 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="75">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label for="blood-pressure-sys" class="w-1/3 text-gray-600">Huyết áp (Sys/Dia):</label>
                            <div class="flex w-2/3 space-x-2">
                                <input type="number" id="blood-pressure-sys" class="w-1/2 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="120 (Tâm thu)">
                                <input type="number" id="blood-pressure-dia" class="w-1/2 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="80 (Tâm trương)">
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label for="spo2" class="w-1/3 text-gray-600">SpO2 ($$\%$$):</label>
                            <input type="number" id="spo2" class="w-2/3 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="98">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label for="weight" class="w-1/3 text-gray-600">Cân nặng (kg):</label>
                            <input type="number" id="weight" class="w-2/3 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="65">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label for="height" class="w-1/3 text-gray-600">Chiều cao (cm):</label>
                            <input type="number" id="height" class="w-2/3 p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="170">
                        </div>
                    </div>
                    <!-- Các ghi chú và Thao tác -->
                    <div class="space-y-4">
                        <div>
                            <label for="nurse-notes" class="block text-gray-600 mb-2">Ghi chú Y tá (Lý do khám, Tình trạng ban đầu):</label>
                            <textarea id="nurse-notes" rows="5" class="w-full p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Bệnh nhân than đau ngực nhẹ, không sốt. Đã dùng thuốc giảm đau trước đó."></textarea>
                        </div>
                        <div class="bg-green-50 p-4 rounded-xl border border-green-300">
                             <h5 class="font-semibold text-green-700 mb-2 flex items-center"><i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> Trạng thái Sẵn sàng</h5>
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="ready-for-doctor" class="form-checkbox text-green-600 w-5 h-5 rounded" checked>
                                <span class="ml-2 text-gray-700">Sẵn sàng chuyển hồ sơ cho Bác sĩ khám</span>
                            </label>
                        </div>
                        <button class="w-full primary-color text-white p-3 rounded-lg font-bold text-lg hover:bg-green-600 transition" onclick="saveVitalsAndProceed()">
                            <i data-lucide="upload-cloud" class="w-5 h-5 mr-2 inline-block"></i> Lưu Chỉ số & Chuyển BN
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2.4. Cập nhật Hồ sơ Bệnh án -->
        <section id="ho-so-benh-an" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="notebook-pen" class="w-6 h-6 mr-2"></i> Cập nhật Hồ sơ Bệnh án & Tiền sử
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <p class="mb-6 text-gray-600">Sử dụng chức năng này để bổ sung thông tin tiền sử bệnh, dị ứng, hoặc thuốc đang dùng cho bệnh nhân **Nguyễn Văn An (BN00123)** trước khi chuyển vào khám.</p>
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Tiền sử Bệnh -->
                    <div>
                        <label for="medical-history" class="block text-gray-700 font-semibold mb-2">Tiền sử Bệnh (Bệnh mạn tính, phẫu thuật...)</label>
                        <textarea id="medical-history" rows="4" class="w-full p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Tiểu đường tuýp 2 (5 năm). Phẫu thuật ruột thừa năm 2010."></textarea>
                    </div>
                    <!-- Dị ứng và Thuốc -->
                    <div>
                        <label for="allergies" class="block text-gray-700 font-semibold mb-2">Dị ứng & Thuốc đang dùng</label>
                        <textarea id="allergies" rows="4" class="w-full p-3 border rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Dị ứng: Penicillin. Thuốc đang dùng: Metformin 500mg x 2 lần/ngày."></textarea>
                    </div>
                </div>
                
                <button class="mt-6 primary-color text-white p-3 rounded-lg font-bold hover:bg-green-600 transition" onclick="saveMedicalHistory()">
                    <i data-lucide="save" class="w-5 h-5 mr-2 inline-block"></i> Lưu cập nhật Hồ sơ
                </button>
            </div>
        </section>
@endsection