 @extends('receptionist.master')
@section('title','Lễ tân')
@section('body')
        <div class="bg-white p-6 rounded-2xl shadow-xl mb-8 border-l-8 border-primary">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Lễ tân: Nguyễn Thị Mai 👋</h2>
                    <p class="text-gray-500 mt-1">Quầy tiếp tân số 1 • Ca: Sáng (7:00 - 15:00)</p>
                </div>
                <div class="text-4xl primary-text font-extrabold hidden sm:block">
                    <span id="current-time">10:25</span>
                </div>
            </div>
        </div>

        <section id="tong-quan" class="content-section">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="layout-dashboard" class="w-6 h-6 mr-2"></i> Thống kê nhanh & Lưu lượng
            </h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Hàng chờ hiện tại</p>
                            <p class="text-3xl font-bold text-gray-800">8</p>
                        </div>
                        <i data-lucide="list-ordered" class="w-10 h-10 text-blue-500 opacity-50"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bệnh nhân đã Check-in</p>
                            <p class="text-3xl font-bold text-gray-800">24</p>
                        </div>
                        <i data-lucide="check-circle" class="w-10 h-10 text-green-500 opacity-50"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Đăng ký mới (Chờ XN)</p>
                            <p class="text-3xl font-bold text-gray-800">12</p>
                        </div>
                        <i data-lucide="mail-open" class="w-10 h-10 text-yellow-500 opacity-50"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Thanh toán Online (Chờ XN)</p>
                            <p class="text-3xl font-bold text-gray-800">18</p>
                        </div>
                        <i data-lucide="credit-card" class="w-10 h-10 text-purple-500 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <h4 class="font-bold text-lg mb-3 border-b pb-2 primary-text">Các ca hẹn đang chờ (Dashboard công khai)</h4>
            <div id="queue-display" class="grid lg:grid-cols-3 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-md border-l-4 border-yellow-500">
                    <div class="flex justify-between items-start">
                        <p class="text-2xl font-bold text-yellow-700">1</p>
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-semibold">Đang chờ</span>
                    </div>
                    <p class="font-semibold text-lg mt-1">Nguyễn Văn An</p>
                    <p class="text-sm text-gray-500">BS. Trần Thị Hoa | Giờ hẹn: 08:00</p>
                    <button class="mt-3 w-full primary-color text-white p-2 rounded-lg text-sm font-semibold hover:bg-blue-600" onclick="dispatchPatient(1)">
                        <i data-lucide="send" class="w-4 h-4 mr-1 inline-block"></i> Điều phối tới BS
                    </button>
                </div>
                 <div class="bg-white p-4 rounded-xl shadow-md border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <p class="text-2xl font-bold text-blue-700">2</p>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold">Đang khám</span>
                    </div>
                    <p class="font-semibold text-lg mt-1">Lê Thị Bình</p>
                    <p class="text-sm text-gray-500">BS. Phạm Văn Nam | Giờ hẹn: 08:30</p>
                    <button class="mt-3 w-full bg-gray-200 text-gray-700 p-2 rounded-lg text-sm font-semibold cursor-not-allowed">
                        <i data-lucide="user-check" class="w-4 h-4 mr-1 inline-block"></i> Đã điều phối
                    </button>
                </div>
            </div>
        </section>

        <section id="quan-ly-hang-cho" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="list-ordered" class="w-6 h-6 mr-2"></i> Quản lý Hàng chờ & Điều phối Bệnh nhân
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hàng chờ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bệnh nhân</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bác sĩ Hẹn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian chờ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="queue-list-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                </table>
                <div class="mt-4 flex justify-end">
                    <button class="bg-yellow-500 text-white p-2 rounded-lg font-semibold hover:bg-yellow-600 transition duration-150">
                        <i data-lucide="refresh-cw" class="w-4 h-4 mr-1 inline-block"></i> Cập nhật hàng chờ
                    </button>
                </div>
            </div>
        </section>
        
        <section id="dang-ky-online" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="mail-open" class="w-6 h-6 mr-2"></i> Tiếp nhận & Xác nhận Đăng ký Online
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã ĐK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thông tin BN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dịch vụ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày/Giờ hẹn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="online-reg-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                </table>
            </div>
        </section>

        <section id="check-in" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="qr-code" class="w-6 h-6 mr-2"></i> Check-in Bệnh nhân Online (QR/Nhập mã)
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-2xl shadow-xl text-center border-t-4 border-primary">
                    <i data-lucide="scan" class="w-16 h-16 primary-text mx-auto mb-4"></i>
                    <h4 class="font-bold text-xl mb-3">Quét mã QR</h4>
                    <p class="text-gray-600 mb-4">Sử dụng camera để quét mã QR từ ứng dụng của bệnh nhân.</p>
                    <button class="w-full primary-color text-white p-3 rounded-lg font-semibold hover:bg-blue-600" onclick="simulateQRScan()">
                        <i data-lucide="camera" class="w-5 h-5 mr-2 inline-block"></i> Mở camera quét
                    </button>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl text-center border-t-4 border-primary">
                    <i data-lucide="keyboard" class="w-16 h-16 primary-text mx-auto mb-4"></i>
                    <h4 class="font-bold text-xl mb-3">Nhập thủ công</h4>
                    <p class="text-gray-600 mb-4">Nhập mã bệnh nhân hoặc số điện thoại để check-in.</p>
                    <input type="text" id="manual-checkin-input" class="w-full p-3 border rounded-lg mb-4 text-center" placeholder="Mã BN / SĐT">
                    <button class="w-full bg-gray-300 text-gray-800 p-3 rounded-lg font-semibold hover:bg-gray-400" onclick="simulateManualCheckin()">
                        <i data-lucide="user-check" class="w-5 h-5 mr-2 inline-block"></i> Xác nhận Check-in
                    </button>
                </div>
            </div>
        </section>

        <section id="thanh-toan" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="credit-card" class="w-6 h-6 mr-2"></i> Hỗ trợ Thanh toán Trực tuyến (Xác nhận giao dịch)
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-yellow-500">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-bold text-lg text-yellow-700">Giao dịch Chờ xác nhận</p>
                        <span class="text-3xl font-bold">4</span>
                    </div>
                    <ul class="space-y-3" id="pending-payments">
                        <li class="p-3 bg-yellow-50 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-700">#PAY002 - Lê Thị Bình</p>
                                <p class="text-sm text-gray-500">320.000 VNĐ - Thẻ ngân hàng</p>
                            </div>
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-yellow-600" onclick="confirmPayment(2)">Xác nhận</button>
                        </li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-green-500">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-bold text-lg text-green-700">Giao dịch Đã hoàn thành (Hôm nay)</p>
                        <span class="text-3xl font-bold">18</span>
                    </div>
                    <ul class="space-y-3">
                        <li class="p-3 bg-green-50 rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-700">#PAY001 - Nguyễn Văn An</p>
                                <p class="text-sm text-gray-500">450.000 VNĐ - VNPay</p>
                            </div>
                            <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="ho-so-hanh-chinh" class="content-section hidden">
            <h3 class="text-xl font-bold mb-4 primary-text flex items-center">
                <i data-lucide="folder-open" class="w-6 h-6 mr-2"></i> Quản lý Hồ sơ Hành chính Điện tử (Giấy tờ Scan)
            </h3>
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex justify-end mb-4">
                    <button class="bg-green-500 text-white p-2 rounded-lg font-semibold hover:bg-green-600 transition duration-150">
                        <i data-lucide="upload" class="w-4 h-4 mr-1 inline-block"></i> Tải lên tài liệu mới
                    </button>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bệnh nhân</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại Tài liệu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên file</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="admin-files-body" class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Nguyễn Văn An</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CMND/CCCD</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 cursor-pointer">cmnd_nguyenvanan.pdf</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Đã xác minh</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button class="text-blue-600 hover:text-blue-900"><i data-lucide="download" class="w-5 h-5"></i></button>
                                <button class="text-red-600 hover:text-red-900"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Lê Thị Bình</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bảo hiểm y tế</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 cursor-pointer">bhyt_lethibinh.pdf</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Chờ xác nhận</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button class="text-blue-600 hover:text-blue-900"><i data-lucide="download" class="w-5 h-5"></i></button>
                                <button class="text-red-600 hover:text-red-900"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    @endsection