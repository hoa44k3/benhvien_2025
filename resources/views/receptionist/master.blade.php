<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard - Hệ thống Quản lý Lễ tân</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Thiết lập font chữ Inter */
        html { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        /* Tùy chỉnh màu sắc theo chủ đề */
        .primary-color { background-color: #3B82F6; } /* Blue */
        .primary-text { color: #3B82F6; }
        .secondary-color { background-color: #EFF6FF; }
        .border-primary { border-color: #3B82F6; }
        /* Style cho nút active trong Sidebar */
        .nav-link.active {
            background-color: #2563EB; /* Darker blue */
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5), 0 2px 4px -2px rgba(59, 130, 246, 0.5);
        }
        .sidebar {
            /* Tăng z-index để nó nằm trên nội dung chính khi ở mobile */
            z-index: 50;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    <aside id="sidebar" class="w-64 primary-color text-white fixed lg:sticky top-0 h-full p-4 flex flex-col space-y-4 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 sidebar rounded-r-2xl">
        <div class="text-2xl font-bold py-2 px-4 rounded-xl bg-white/20 text-center shadow-lg">Lễ Tân Dashboard</div>
        <nav class="flex-1 space-y-2">
            <a href="#tong-quan" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150 active" onclick="showContent('tong-quan')">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Tổng quan
            </a>
            <a href="#quan-ly-hang-cho" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('quan-ly-hang-cho')">
                <i data-lucide="list-ordered" class="w-5 h-5 mr-3"></i> Quản lý Hàng chờ
            </a>
            <a href="#dang-ky-online" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('dang-ky-online')">
                <i data-lucide="mail-open" class="w-5 h-5 mr-3"></i> Tiếp nhận Đăng ký Online
            </a>
            <a href="#check-in" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('check-in')">
                <i data-lucide="qr-code" class="w-5 h-5 mr-3"></i> Check-in Bệnh nhân
            </a>
            <a href="#thanh-toan" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('thanh-toan')">
                <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i> Hỗ trợ Thanh toán
            </a>
            <a href="#ho-so-hanh-chinh" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('ho-so-hanh-chinh')">
                <i data-lucide="folder-open" class="w-5 h-5 mr-3"></i> QL Hồ sơ Hành chính
            </a>
        </nav>
        <div class="mt-auto pt-4 border-t border-white/20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center p-3 w-full rounded-xl hover:bg-red-600 transition duration-150 text-white bg-red-500/80">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i> Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-0 lg:hidden pointer-events-none transition-opacity duration-300 z-40" onclick="toggleSidebar()"></div>

    <main class="flex-1 p-4 sm:p-6 lg:p-8 ml-0 lg:ml-64 transition-all duration-300">
        <header class="flex justify-between items-center lg:hidden mb-6">
            <h1 class="text-3xl font-extrabold primary-text">Lễ Tân Dashboard</h1>
            <button id="menu-button" class="p-2 primary-color text-white rounded-lg shadow-md" onclick="toggleSidebar()">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </header>
        @yield('body')

    </main>

    <script>
        // Khởi tạo icons Lucide
        lucide.createIcons();

        // Dữ liệu mẫu cho Hàng chờ và Đăng ký Online
        const queueData = [
            { id: 1, name: "Nguyễn Văn An", phone: "0901234567", doctor: "BS. Trần Thị Hoa (Tim mạch)", time: "08:00", status: "Đang chờ", timeWaited: "15 phút" },
            { id: 2, name: "Lê Thị Bình", phone: "0987654321", doctor: "BS. Phạm Văn Nam (Nhi khoa)", time: "08:30", status: "Đang khám", timeWaited: "0 phút" },
            { id: 3, name: "Hoàng Minh Cường", phone: "0912345678", doctor: "BS. Nguyễn Thị Lan (Tổng quát)", time: "09:00", status: "Đã hẹn", timeWaited: "Đã qua giờ" },
            { id: 4, name: "Phạm Thu Dung", phone: "0934567890", doctor: "BS. Trần Thị Hoa (Tim mạch)", time: "10:30", status: "Đã check-in", timeWaited: "2 phút" }
        ];

        const onlineRegData = [
            { id: 'DK005', name: "Trần Văn Khang", service: "Khám tổng quát", datetime: "16/11/2025 - 14:00", status: "Chờ xác nhận" },
            { id: 'DK006', name: "Hồ Thị Mai", service: "Tư vấn dinh dưỡng", datetime: "17/11/2025 - 10:00", status: "Đã xác nhận" }
        ];

        // --- Hàm Quản lý Giao diện ---

        /**
         * Ẩn/Hiện nội dung của các section
         * @param {string} sectionId - ID của section muốn hiện
         */
        function showContent(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');

            // Cập nhật trạng thái active của nav-link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`.nav-link[href="#${sectionId}"]`).classList.add('active');

            // Ẩn sidebar trên mobile sau khi chọn
            if (window.innerWidth < 1024) {
                toggleSidebar();
            }
            // Tạo lại icons Lucide (đề phòng)
            lucide.createIcons();
        }

        /**
         * Chuyển đổi trạng thái sidebar trên mobile
         */
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-50');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-50');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        /**
         * Cập nhật thời gian hiện tại
         */
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('current-time').textContent = timeString;
        }

        /**
         * Hiển thị thông báo tạm thời (thay cho alert)
         */
        function showMessage(message, type = 'success') {
            const messageBox = document.createElement('div');
            messageBox.textContent = message;
            messageBox.className = `fixed top-4 right-4 z-[9999] p-4 rounded-lg text-white shadow-xl transition-opacity duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            document.body.appendChild(messageBox);
            
            setTimeout(() => {
                messageBox.classList.add('opacity-0');
                messageBox.addEventListener('transitionend', () => messageBox.remove());
            }, 3000);
        }

        // --- Hàm Xử lý Chức năng Lễ tân ---

        /**
         * Hiển thị danh sách hàng chờ vào bảng (QL Hàng chờ)
         */
        function renderQueueList() {
            const tbody = document.getElementById('queue-list-body');
            tbody.innerHTML = ''; 

            queueData.forEach(patient => {
                let statusClass;
                let actionHtml;

                switch (patient.status) {
                    case "Đang chờ":
                        statusClass = 'bg-yellow-100 text-yellow-800';
                        actionHtml = `<button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-blue-600 transition" onclick="dispatchPatient(${patient.id})">
                                        <i data-lucide="send" class="w-4 h-4 mr-1 inline-block"></i> Điều phối
                                      </button>`;
                        break;
                    case "Đã check-in":
                        statusClass = 'bg-blue-100 text-blue-800';
                        actionHtml = `<button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-blue-600 transition" onclick="dispatchPatient(${patient.id})">
                                        <i data-lucide="send" class="w-4 h-4 mr-1 inline-block"></i> Điều phối
                                      </button>`;
                        break;
                    case "Đang khám":
                        statusClass = 'bg-green-100 text-green-800';
                        actionHtml = `<span class="text-xs text-gray-500">Đã gửi BS</span>`;
                        break;
                    default:
                        statusClass = 'bg-gray-100 text-gray-600';
                        actionHtml = `<span class="text-xs text-gray-500">Hoàn thành</span>`;
                }

                const row = `
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${patient.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${patient.name} (${patient.phone})</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${patient.doctor}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-500">${patient.timeWaited}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            ${actionHtml}
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
            lucide.createIcons();
        }

        /**
         * Giả lập chức năng điều phối bệnh nhân đến phòng khám/bác sĩ
         * @param {number} patientId
         */
        function dispatchPatient(patientId) {
            const patient = queueData.find(p => p.id === patientId);
            if (patient && patient.status !== 'Đang khám') {
                patient.status = 'Đang khám';
                showMessage(`Đã điều phối BN ${patient.name} tới phòng khám ${patient.doctor}.`, 'success');
                renderQueueList(); // Cập nhật lại bảng
            } else {
                 showMessage(`Bệnh nhân ${patient.name} đang được khám hoặc chưa sẵn sàng.`, 'error');
            }
        }

        /**
         * Hiển thị danh sách đăng ký online (Tiếp nhận ĐK Online)
         */
        function renderOnlineRegistrations() {
            const tbody = document.getElementById('online-reg-body');
            tbody.innerHTML = '';

            onlineRegData.forEach(reg => {
                let statusClass;
                let actionHtml;

                switch (reg.status) {
                    case "Chờ xác nhận":
                        statusClass = 'bg-yellow-100 text-yellow-800';
                        actionHtml = `<button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-blue-600 transition mr-2" onclick="confirmRegistration('${reg.id}')">Xác nhận</button>
                                      <button class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-red-600 transition">Hủy</button>`;
                        break;
                    case "Đã xác nhận":
                    default:
                        statusClass = 'bg-green-100 text-green-800';
                        actionHtml = `<span class="text-xs text-gray-500">Đã XN. Chờ check-in</span>`;
                }

                const row = `
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${reg.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${reg.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${reg.service}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${reg.datetime}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass} mr-2">${reg.status}</span>
                            ${actionHtml}
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
            lucide.createIcons();
        }

        /**
         * Xác nhận đăng ký online
         * @param {string} regId
         */
        function confirmRegistration(regId) {
            const registration = onlineRegData.find(r => r.id === regId);
            if (registration) {
                registration.status = 'Đã xác nhận';
                showMessage(`Đã xác nhận lịch hẹn ${regId} cho BN ${registration.name}.`, 'success');
                renderOnlineRegistrations();
            }
        }

        /**
         * Giả lập chức năng quét QR code
         */
        function simulateQRScan() {
            showMessage("Đang mở camera... (Quét thành công mã BN001234)", 'success');
            setTimeout(() => {
                const patientName = "Nguyễn Văn An";
                showMessage(`Check-in thành công cho BN ${patientName}. Đã thêm vào hàng chờ.`, 'success');
                // Logic thực tế: thêm BN vào hàng chờ (queueData)
            }, 1500);
        }

        /**
         * Giả lập chức năng nhập thủ công để check-in
         */
        function simulateManualCheckin() {
            const input = document.getElementById('manual-checkin-input').value.trim();
            if (input.length < 5) {
                showMessage("Vui lòng nhập mã BN hoặc SĐT hợp lệ.", 'error');
                return;
            }
            showMessage(`Đã tìm thấy thông tin BN tương ứng với mã/SĐT: ${input}. Check-in thành công!`, 'success');
        }

        /**
         * Giả lập chức năng xác nhận thanh toán
         * @param {number} paymentId
         */
        function confirmPayment(paymentId) {
            // Giả lập chuyển giao dịch sang trạng thái hoàn thành
            const patientName = "Lê Thị Bình"; 
            showMessage(`Đã xác nhận giao dịch #PAY00${paymentId} của BN ${patientName}. Đã hoàn tất.`, 'success');
            // Logic thực tế: Cập nhật UI/database, có thể xóa khỏi danh sách chờ xác nhận
            document.getElementById('pending-payments').innerHTML = `<p class="text-gray-500 text-sm italic">Không có giao dịch chờ xác nhận nào.</p>`;
        }


        // --- Khởi tạo ứng dụng ---

        window.onload = function() {
            renderQueueList();
            renderOnlineRegistrations();
            updateTime();
            setInterval(updateTime, 60000); // Cập nhật thời gian mỗi phút
            
            showContent('tong-quan'); 
            
            window.addEventListener('resize', () => {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth >= 1024 && sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                }
            });
        };
    </script>
</body>
</html>
