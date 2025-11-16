 @extends('doctor.master')

@section('title','Trang chủ')
@section('body')
 <div class="bg-white p-6 rounded-2xl shadow-xl mb-8 border-l-8 border-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Xin chào, Bác sĩ Nguyễn Văn A! 👋</h2>
                    <p class="text-gray-500 mt-1">Chúc bạn một ngày làm việc hiệu quả. Hãy kiểm tra lịch khám hôm nay.</p>
                </div>
                <div class="text-4xl primary-text font-extrabold hidden sm:block">
                    <span id="current-time">10:00</span>
                </div>
            </div>
        </div>
        <!-- 2.1. Quản lý Lịch khám -->
        <section id="lich-kham" class="content-section">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="calendar-check" class="w-6 h-6 mr-2"></i> Quản lý Lịch khám & Điều chỉnh ca
            </h3>
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-lg font-semibold text-gray-700 mb-3">Lịch làm việc hôm nay (20/10/2025)</p>
                    <ul class="space-y-2">
                        <li class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="font-medium text-blue-600">Sáng (8:00 - 12:00)</span>
                            <span class="text-sm bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full">Phòng A01</span>
                        </li>
                        <li class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <span class="font-medium text-yellow-600">Chiều (13:30 - 17:30)</span>
                            <span class="text-sm bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full">Khám Telemedicine</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <p class="text-lg font-semibold text-gray-700 mb-3">Điều chỉnh ca khám</p>
                    <div class="space-y-3">
                        <label class="block">
                            <span class="text-gray-700">Chọn ngày:</span>
                            <input type="date" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-2">
                        </label>
                        <label class="block">
                            <span class="text-gray-700">Ca làm việc:</span>
                            <select class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm p-2">
                                <option>Sáng (8:00 - 12:00) - Phòng A01</option>
                                <option>Chiều (13:30 - 17:30) - Telemedicine</option>
                                <option>Nghỉ</option>
                            </select>
                        </label>
                        <button class="w-full primary-color text-white p-2 rounded-xl font-semibold hover:bg-green-600 transition duration-150">
                            Cập nhật lịch khám
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2.2. Danh sách Bệnh nhân Online (Hàng chờ) -->
        <section id="danh-sach-benh-nhan" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="users" class="w-6 h-6 mr-2"></i> Danh sách Bệnh nhân Hẹn Online
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl scrollable-table">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Bệnh nhân</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian Hẹn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="patient-list-body" class="bg-white divide-y divide-gray-200">
                        <!-- Dữ liệu mẫu sẽ được chèn bằng JS -->
                    </tbody>
                </table>
            </div>
            <!-- Modal (Popup) để xem Hồ sơ Bệnh án Điện tử (EMR) -->
            <div id="emr-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center p-4 z-50">
                <div class="bg-white p-8 rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h4 class="text-2xl font-bold primary-text">Hồ sơ Bệnh án Điện tử (EMR)</h4>
                        <button class="text-gray-400 hover:text-gray-700" onclick="closeModal('emr-modal')">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    <div id="emr-content">
                        <!-- Nội dung hồ sơ EMR sẽ được chèn tại đây -->
                        <p class="text-gray-600"><strong>Họ tên:</strong> <span id="emr-name"></span></p>
                        <p class="text-gray-600"><strong>Mã BN:</strong> <span id="emr-id"></span></p>
                        <p class="text-gray-600"><strong>Lịch sử khám:</strong></p>
                        <ul id="emr-history" class="mt-2 space-y-3 p-3 bg-gray-50 rounded-lg">
                            <li class="border-l-4 border-primary pl-3">
                                <p class="font-semibold text-sm text-gray-700">20/08/2025: Viêm họng cấp</p>
                                <p class="text-xs text-gray-500">BS: Trần Thị B, Thuốc: Amoxicillin (7 ngày)</p>
                            </li>
                            <li class="border-l-4 border-primary pl-3">
                                <p class="font-semibold text-sm text-gray-700">05/03/2025: Tái khám huyết áp</p>
                                <p class="text-xs text-gray-500">BS: Nguyễn Văn A, Kết quả XN: Cholesterol cao nhẹ</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2.3. Khám bệnh Online & Kê đơn thuốc điện tử -->
        <section id="kham-benh" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="stethoscope" class="w-6 h-6 mr-2"></i> Khám bệnh (Telemedicine) & Kê đơn thuốc điện tử
            </h3>
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Thông tin Bệnh nhân -->
                <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-xl">
                    <h4 class="font-bold text-lg mb-3 border-b pb-2 text-blue-600">Bệnh nhân Đang Khám</h4>
                    <p class="text-xl font-semibold mb-1">Trần Thị C</p>
                    <p class="text-sm text-gray-500 mb-3">35 tuổi - Nữ | Mã BN: 001234</p>
                    <button class="w-full bg-blue-500 text-white p-2 rounded-xl font-semibold hover:bg-blue-600 transition duration-150 flex items-center justify-center">
                        <i data-lucide="video" class="w-5 h-5 mr-2"></i> Bắt đầu Video Call
                    </button>
                    <div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
                        Lý do khám: Đau đầu, chóng mặt 2 ngày.
                    </div>
                </div>

                <!-- Chẩn đoán & Kê đơn -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xl space-y-4">
                    <h4 class="font-bold text-lg mb-3 border-b pb-2 primary-text">Nhập Chẩn đoán & Y lệnh</h4>
                    
                    <div>
                        <label for="chuan-doan" class="block font-medium text-gray-700 mb-1">Chẩn đoán (ICD-10):</label>
                        <textarea id="chuan-doan" rows="3" class="w-full p-3 border rounded-lg focus:ring-primary focus:border-primary transition duration-150" placeholder="Viết chẩn đoán, ví dụ: R51 - Đau đầu"></textarea>
                    </div>

                    <!-- Kê đơn Thuốc Điện tử -->
                    <div class="border p-4 rounded-xl space-y-3">
                        <h5 class="font-semibold text-orange-600 flex items-center"><i data-lucide="pill" class="w-5 h-5 mr-2"></i> Kê đơn Thuốc Điện tử</h5>
                        <div id="prescription-items" class="space-y-2">
                            <!-- Mẫu đơn thuốc -->
                            <div class="flex space-x-2">
                                <input type="text" placeholder="Tên Thuốc (vd: Paracetamol 500mg)" class="flex-1 p-2 border rounded-lg">
                                <input type="number" placeholder="SL" class="w-16 p-2 border rounded-lg">
                                <input type="text" placeholder="Liều dùng" class="flex-1 p-2 border rounded-lg">
                                <button class="text-red-500 hover:text-red-700"><i data-lucide="x" class="w-5 h-5"></i></button>
                            </div>
                        </div>
                        <button class="text-sm text-blue-500 hover:text-blue-700 flex items-center" onclick="addPrescriptionItem()">
                            <i data-lucide="plus-circle" class="w-4 h-4 mr-1"></i> Thêm thuốc
                        </button>
                    </div>

                    <button class="w-full primary-color text-white p-3 rounded-xl font-bold text-lg hover:bg-green-600 transition duration-150 flex items-center justify-center" onclick="signAndSendPrescription()">
                        <i data-lucide="signature" class="w-5 h-5 mr-2"></i> Ký số & Gửi Đơn thuốc (Dược sĩ)
                    </button>
                </div>
            </div>
        </section>

        <!-- 2.4. Yêu cầu & Kết quả Xét nghiệm Điện tử -->
        <section id="xet-nghiem" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="flask-round" class="w-6 h-6 mr-2"></i> Yêu cầu & Kết quả Xét nghiệm Điện tử
            </h3>
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Yêu cầu xét nghiệm -->
                <div class="bg-white p-6 rounded-2xl shadow-xl space-y-4">
                    <h4 class="font-bold text-lg mb-3 border-b pb-2 text-purple-600">Yêu cầu Xét nghiệm (Gửi Lab)</h4>
                    <label for="bn-xetnghiem" class="block font-medium text-gray-700 mb-1">Chọn Bệnh nhân:</label>
                    <select id="bn-xetnghiem" class="w-full p-2 border rounded-lg">
                        <option>001234 - Trần Thị C</option>
                        <option>005678 - Lê Văn D</option>
                    </select>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Chỉ định Xét nghiệm:</label>
                        <div class="space-y-2">
                            <label class="flex items-center"><input type="checkbox" class="rounded mr-2 primary-text"> Huyết học (CTM)</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded mr-2 primary-text"> Sinh hóa (Đường huyết, Mỡ máu)</label>
                            <label class="flex items-center"><input type="checkbox" class="rounded mr-2 primary-text"> Nước tiểu</label>
                        </div>
                        <textarea rows="2" class="w-full p-2 mt-2 border rounded-lg" placeholder="Chỉ định khác..."></textarea>
                    </div>

                    <button class="w-full bg-purple-600 text-white p-2 rounded-xl font-semibold hover:bg-purple-700 transition duration-150" onclick="sendLabRequest()">
                        Gửi chỉ định (→ Lab Technician)
                    </button>
                </div>

                <!-- Kết quả xét nghiệm -->
                <div class="bg-white p-6 rounded-2xl shadow-xl space-y-4">
                    <h4 class="font-bold text-lg mb-3 border-b pb-2 text-green-600">Xem Kết quả Xét nghiệm Điện tử</h4>
                    <ul class="space-y-3">
                        <li class="p-3 bg-green-50 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-700">001234 - Trần Thị C</p>
                                <p class="text-sm text-gray-500">Sinh hóa (Đã hoàn thành) - 20/10/2025</p>
                            </div>
                            <button class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center">
                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Xem chi tiết
                            </button>
                        </li>
                        <li class="p-3 bg-yellow-50 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-700">005678 - Lê Văn D</p>
                                <p class="text-sm text-gray-500">Huyết học (Đang tiến hành) - 19/10/2025</p>
                            </div>
                            <span class="text-xs text-yellow-700 bg-yellow-200 px-2 py-0.5 rounded-full">Chờ kết quả</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- 2.5. Theo dõi Điều trị Nội trú -->
        <section id="noi-tru" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="bed" class="w-6 h-6 mr-2"></i> Theo dõi Điều trị Nội trú (Cập nhật từ Nurse)
            </h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Thẻ Bệnh nhân Nội trú Mẫu -->
                <div class="bg-white p-5 rounded-2xl shadow-lg border-l-4 border-red-500">
                    <p class="font-bold text-xl mb-1 text-red-700">Nguyễn Văn K</p>
                    <p class="text-sm text-gray-500 mb-3">Phòng: 102 - Giường: B | 55 tuổi</p>
                    <p class="text-xs font-semibold text-gray-600">Chẩn đoán: Viêm phổi nặng</p>
                    <div class="mt-3 text-sm">
                        <p class="flex justify-between"><span>Tình trạng mới nhất:</span> <span class="font-medium text-red-500">Sốt nhẹ</span></p>
                        <p class="flex justify-between"><span>Cập nhật lúc:</span> <span class="font-medium text-gray-500">10:00 (Nurse B)</span></p>
                    </div>
                    <button class="mt-3 w-full bg-red-100 text-red-600 p-2 rounded-xl text-sm font-semibold hover:bg-red-200">
                        Xem chi tiết & Y lệnh
                    </button>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-lg border-l-4 border-blue-500">
                    <p class="font-bold text-xl mb-1 text-blue-700">Phạm Thị L</p>
                    <p class="text-sm text-gray-500 mb-3">Phòng: 105 - Giường: A | 28 tuổi</p>
                    <p class="text-xs font-semibold text-gray-600">Chẩn đoán: Hậu phẫu ruột thừa</p>
                    <div class="mt-3 text-sm">
                        <p class="flex justify-between"><span>Tình trạng mới nhất:</span> <span class="font-medium text-blue-500">Ổn định</span></p>
                        <p class="flex justify-between"><span>Cập nhật lúc:</span> <span class="font-medium text-gray-500">08:30 (Nurse C)</span></p>
                    </div>
                    <button class="mt-3 w-full bg-blue-100 text-blue-600 p-2 rounded-xl text-sm font-semibold hover:bg-blue-200">
                        Xem chi tiết & Y lệnh
                    </button>
                </div>
            </div>
        </section>

        <!-- 2.6. Thống kê Cá nhân -->
        <section id="thong-ke" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="bar-chart-3" class="w-6 h-6 mr-2"></i> Thống kê Cá nhân (Tháng này)
            </h3>
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Tổng số ca khám -->
                <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center justify-between border-b-4 border-primary">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tổng số ca khám</p>
                        <p class="text-3xl font-bold text-gray-800">125</p>
                        <span class="text-xs text-green-500 font-semibold">+8% so với tháng trước</span>
                    </div>
                    <i data-lucide="activity" class="w-10 h-10 primary-text opacity-50"></i>
                </div>

                <!-- Bệnh thường gặp -->
                <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-xl">
                    <p class="text-sm font-medium text-gray-500 mb-3">Top 5 Bệnh thường gặp</p>
                    <ul class="space-y-2">
                        <li class="flex justify-between items-center text-gray-700">
                            <span class="font-medium">1. Viêm họng (J02.9)</span>
                            <span class="font-bold primary-text">25 Ca</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700">
                            <span class="font-medium">2. Rối loạn tiêu hóa (K30)</span>
                            <span class="font-bold primary-text">18 Ca</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700">
                            <span class="font-medium">3. Tăng huyết áp (I10)</span>
                            <span class="font-bold primary-text">12 Ca</span>
                        </li>
                        <li class="flex justify-between items-center text-gray-700">
                            <span class="font-medium">4. Đau nửa đầu (G43)</span>
                            <span class="font-bold primary-text">10 Ca</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
@endsection