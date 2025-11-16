<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Y Tá Dashboard - Quản lý Bệnh nhân & Chỉ số</title>
    <!-- Tải Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tải thư viện biểu tượng Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Thiết lập font chữ Inter */
        html { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        /* Tùy chỉnh màu sắc theo chủ đề: Xanh lá (Green) cho Y Tế */
        .primary-color { background-color: #10B981; } /* Emerald Green */
        .primary-text { color: #10B981; }
        .secondary-color { background-color: #ECFDF5; }
        .border-primary { border-color: #10B981; }
        
        /* Style cho nút active trong Sidebar */
        .nav-link.active {
            background-color: #059669; /* Darker Green */
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.5), 0 2px 4px -2px rgba(16, 185, 129, 0.5);
        }
        .sidebar { z-index: 50; }
        .status-pill {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    <!-- 1. Sidebar Menu (Thanh điều hướng) -->
    <aside id="sidebar" class="w-64 primary-color text-white fixed lg:sticky top-0 h-full p-4 flex flex-col space-y-4 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 sidebar rounded-r-2xl">
        <div class="text-2xl font-bold py-2 px-4 rounded-xl bg-white/20 text-center shadow-lg">Y TÁ Dashboard</div>
        <nav class="flex-1 space-y-2">
            <a href="#tong-quan" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150 active" onclick="showContent('tong-quan')">
                <i data-lucide="activity" class="w-5 h-5 mr-3"></i> Tình trạng Công việc
            </a>
            <a href="#danh-sach-kham" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('danh-sach-kham')">
                <i data-lucide="list-checks" class="w-5 h-5 mr-3"></i> Danh sách Chờ khám
            </a>
            <a href="#nhap-chi-so" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('nhap-chi-so')">
                <i data-lucide="thermometer" class="w-5 h-5 mr-3"></i> Nhập Chỉ số lâm sàng
            </a>
            <a href="#ho-so-benh-an" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('ho-so-benh-an')">
                <i data-lucide="notebook-pen" class="w-5 h-5 mr-3"></i> Cập nhật Hồ sơ
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

    <!-- Overlay cho mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-0 lg:hidden pointer-events-none transition-opacity duration-300 z-40" onclick="toggleSidebar()"></div>

    <!-- 2. Main Content (Nội dung chính) -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 ml-0 lg:ml-64 transition-all duration-300">
       @yield('body')
    </main>

    <script>
        // Khởi tạo icons Lucide
        lucide.createIcons();

        // Dữ liệu mẫu cho Hàng chờ đo Chỉ số Lâm sàng
        const vitalsQueueData = [
            { id: 'BN00456', name: "Trần Đình Chiến", room: "Tim mạch", status: "Chờ đo chỉ số" },
            { id: 'BN00123', name: "Nguyễn Văn An", room: "Tim mạch", status: "Đã đo chỉ số" },
            { id: 'BN00789', name: "Phạm Thị Thảo", room: "Nhi khoa", status: "Chờ đo chỉ số" },
            { id: 'BN00333', name: "Lê Văn Hùng", room: "Phụ khoa", status: "Đã đo chỉ số" }
        ];

        let currentPatientId = null;

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
            messageBox.className = `fixed top-4 right-4 z-[9999] p-4 rounded-xl text-white shadow-xl transition-opacity duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            document.body.appendChild(messageBox);
            
            setTimeout(() => {
                messageBox.classList.add('opacity-0');
                messageBox.addEventListener('transitionend', () => messageBox.remove());
            }, 3000);
        }

        // --- Hàm Xử lý Chức năng Y Tá ---

        /**
         * Hiển thị danh sách chờ đo Chỉ số Lâm sàng (Tong quan)
         */
        function renderVitalsQueueList() {
            const tbody = document.getElementById('vitals-queue-body');
            tbody.innerHTML = ''; 

            vitalsQueueData.forEach(patient => {
                let statusClass;
                let actionHtml;

                if (patient.status === "Chờ đo chỉ số") {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                    actionHtml = `<button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-green-600 transition" onclick="startVitalsInput('${patient.id}')">
                                    <i data-lucide="thermometer" class="w-4 h-4 mr-1 inline-block"></i> Bắt đầu
                                  </button>`;
                } else {
                    statusClass = 'bg-green-100 text-green-800';
                    actionHtml = `<span class="text-xs text-gray-500">Đã xong. Chờ BS</span>`;
                }

                const row = `
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${patient.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${patient.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${patient.room}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="status-pill ${statusClass}">${patient.status}</span>
                        </td>
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
         * Khởi động quá trình nhập chỉ số cho bệnh nhân
         * @param {string} patientId
         */
        function startVitalsInput(patientId) {
            const patient = vitalsQueueData.find(p => p.id === patientId);
            if (!patient) {
                showMessage("Không tìm thấy thông tin bệnh nhân.", 'error');
                return;
            }

            currentPatientId = patientId;
            document.getElementById('current-patient-name').textContent = patient.name + ' (' + patientId + ')';
            
            // Chuyển sang section nhập chỉ số
            showContent('nhap-chi-so');
            
            // Xóa form cũ
            document.getElementById('temperature').value = '';
            document.getElementById('heart-rate').value = '';
            document.getElementById('blood-pressure-sys').value = '';
            document.getElementById('blood-pressure-dia').value = '';
            document.getElementById('spo2').value = '';
            document.getElementById('weight').value = '';
            document.getElementById('height').value = '';
            document.getElementById('nurse-notes').value = '';
            document.getElementById('ready-for-doctor').checked = true;

            showMessage(`Đã chọn BN ${patient.name}. Bắt đầu nhập chỉ số lâm sàng.`, 'success');
        }
        
        /**
         * Lưu chỉ số lâm sàng và chuyển bệnh nhân sang trạng thái chờ bác sĩ
         */
        function saveVitalsAndProceed() {
            if (!currentPatientId) {
                showMessage("Vui lòng chọn bệnh nhân trước khi lưu chỉ số.", 'error');
                return;
            }

            const temp = document.getElementById('temperature').value;
            const heartRate = document.getElementById('heart-rate').value;
            const sysBP = document.getElementById('blood-pressure-sys').value;
            const diaBP = document.getElementById('blood-pressure-dia').value;
            const ready = document.getElementById('ready-for-doctor').checked;

            if (!temp || !heartRate || !sysBP || !diaBP) {
                showMessage("Vui lòng nhập đầy đủ Nhiệt độ, Nhịp tim và Huyết áp.", 'error');
                return;
            }

            // Logic lưu data vào hệ thống (giả lập)
            const patient = vitalsQueueData.find(p => p.id === currentPatientId);
            if (patient) {
                patient.status = 'Đã đo chỉ số';
            }

            // Cập nhật trạng thái trên card danh sách khám (nếu có)
            const statusPill = document.getElementById(`status-${currentPatientId.slice(2)}`);
            if(statusPill) {
                statusPill.textContent = 'Đã đo chỉ số';
                statusPill.className = 'status-pill bg-green-100 text-green-700';
            }
            
            // Thông báo
            showMessage(`Đã lưu chỉ số Vitals cho BN ${patient.name}. Chuyển hồ sơ cho Bác sĩ.`, 'success');

            // Cập nhật lại các danh sách
            renderVitalsQueueList();
            showContent('tong-quan'); // Quay lại màn hình tổng quan
        }

        /**
         * Lưu cập nhật Hồ sơ Bệnh án
         */
        function saveMedicalHistory() {
            const history = document.getElementById('medical-history').value.trim();
            const allergies = document.getElementById('allergies').value.trim();

            if (!history && !allergies) {
                 showMessage("Không có thông tin nào được cập nhật.", 'error');
                 return;
            }

            showMessage(`Đã cập nhật Tiền sử bệnh & Dị ứng vào hồ sơ BN00123 thành công.`, 'success');
        }


        // --- Khởi tạo ứng dụng ---

        window.onload = function() {
            renderVitalsQueueList();
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
