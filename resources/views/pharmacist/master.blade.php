<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dược Sĩ Dashboard - Quản lý Thuốc & Đơn</title>
    <!-- Tải Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tải thư viện biểu tượng Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Thiết lập font chữ Inter */
        html { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        /* Tùy chỉnh màu sắc theo chủ đề: Vàng Cam (Amber) cho Dược phẩm */
        .primary-color { background-color: #F59E0B; } /* Amber 500 */
        .primary-text { color: #F59E0B; }
        .secondary-color { background-color: #FFFBEB; } /* Amber 50 */
        .border-primary { border-color: #F59E0B; }
        
        /* Style cho nút active trong Sidebar */
        .nav-link.active {
            background-color: #D97706; /* Darker Amber */
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.5), 0 2px 4px -2px rgba(245, 158, 11, 0.5);
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
        <div class="text-2xl font-bold py-2 px-4 rounded-xl bg-white/20 text-center shadow-lg">DƯỢC SĨ Dashboard</div>
        <nav class="flex-1 space-y-2">
            <a href="#tong-quan" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150 active" onclick="showContent('tong-quan')">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Tổng quan Công việc
            </a>
            <a href="#duyet-don-thuoc" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('duyet-don-thuoc')">
                <i data-lucide="clipboard-list" class="w-5 h-5 mr-3"></i> Đơn thuốc Chờ duyệt
            </a>
            <a href="#kiem-kho" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('kiem-kho')">
                <i data-lucide="package-search" class="w-5 h-5 mr-3"></i> Kiểm tra Tồn kho
            </a>
            <a href="#canh-bao-hethan" class="nav-link flex items-center p-3 rounded-xl hover:bg-white/30 transition duration-150" onclick="showContent('canh-bao-hethan')">
                <i data-lucide="alarm-check" class="w-5 h-5 mr-3"></i> Cảnh báo Hết hạn
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
        <!-- Header cho mobile -->
        <header class="flex justify-between items-center lg:hidden mb-6">
            <h1 class="text-3xl font-extrabold primary-text">DƯỢC SĨ Dashboard</h1>
            <button id="menu-button" class="p-2 primary-color text-white rounded-lg shadow-md" onclick="toggleSidebar()">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </header>
        
      @yield('body')

    </main>

    <script>
        // Khởi tạo icons Lucide
        lucide.createIcons();

        // Dữ liệu mẫu cho Hàng chờ duyệt đơn thuốc
        const prescriptionQueueData = [
            { id: 'DN00123', name: "Nguyễn Văn An", doctor: "BS. Huy", time: "10:20", status: "Tương tác!", statusClass: 'bg-red-100 text-red-700', details: { hasInteraction: true } },
            { id: 'DN00124', name: "Phạm Thu Hà", doctor: "BS. Vân", time: "10:25", status: "Chờ duyệt", statusClass: 'bg-yellow-100 text-yellow-700', details: { hasInteraction: false } },
            { id: 'DN00125', name: "Lê Văn Tùng", doctor: "BS. An", time: "10:28", status: "Chờ duyệt", statusClass: 'bg-yellow-100 text-yellow-700', details: { hasInteraction: false } },
            { id: 'DN00126', name: "Trần Thị Bé", doctor: "BS. Huy", time: "10:35", status: "Tương tác!", statusClass: 'bg-red-100 text-red-700', details: { hasInteraction: true } }
        ];

        let currentPrescriptionId = 'DN00124'; // Đơn mặc định đang hiển thị

        // --- Hàm Quản lý Giao diện ---

        /**
         * Ẩn/Hiện nội dung của các section
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

        // --- Hàm Xử lý Chức năng Dược sĩ ---

        /**
         * Hiển thị danh sách đơn thuốc chờ duyệt (Tong quan)
         */
        function renderPrescriptionQueueList() {
            const tbody = document.getElementById('prescription-queue-body');
            tbody.innerHTML = ''; 

            prescriptionQueueData.forEach(prescription => {
                const row = `
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${prescription.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${prescription.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${prescription.doctor}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${prescription.time}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <span class="status-pill ${prescription.statusClass} mr-2">${prescription.status}</span>
                            <button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-amber-600 transition" onclick="viewPrescriptionDetails('${prescription.id}')">
                                <i data-lucide="eye" class="w-4 h-4 mr-1 inline-block"></i> Xem
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
            lucide.createIcons();
            renderPrescriptionList(); // Cập nhật danh sách ở tab duyệt đơn
        }

        /**
         * Hiển thị danh sách đơn thuốc (Duyet don thuoc)
         */
        function renderPrescriptionList() {
            const listContainer = document.getElementById('list-prescriptions');
            listContainer.innerHTML = '';
            
            prescriptionQueueData.forEach(p => {
                const isActive = p.id === currentPrescriptionId ? 'bg-yellow-50 border-primary' : '';
                const interactionText = p.details.hasInteraction ? 'Tương tác!' : 'Chờ duyệt';
                const interactionClass = p.details.hasInteraction ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700';

                const card = `
                    <div class="p-4 rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:bg-yellow-50 transition duration-150 ${isActive}" onclick="viewPrescriptionDetails('${p.id}')">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-800">${p.id} - ${p.name}</span>
                            <span class="status-pill ${interactionClass}">${interactionText}</span>
                        </div>
                        <p class="text-sm text-gray-500">BS. ${p.doctor.split('. ')[1]} | ${p.time} | ${Math.floor(Math.random() * 5) + 2} loại thuốc</p>
                    </div>
                `;
                listContainer.innerHTML += card;
            });
            updatePrescriptionView(currentPrescriptionId);
        }

        /**
         * Xem chi tiết đơn thuốc
         * @param {string} prescriptionId
         */
        function viewPrescriptionDetails(prescriptionId) {
            currentPrescriptionId = prescriptionId;
            const prescription = prescriptionQueueData.find(p => p.id === prescriptionId);

            if (!prescription) {
                showMessage("Không tìm thấy đơn thuốc.", 'error');
                return;
            }

            // Cập nhật tiêu đề
            document.getElementById('current-prescription-id').textContent = prescriptionId;
            
            // Xử lý cảnh báo tương tác
            const alertBox = document.getElementById('interaction-alert');
            if (prescription.details.hasInteraction) {
                alertBox.classList.remove('hidden');
            } else {
                alertBox.classList.add('hidden');
            }

            // Giả lập cập nhật thông tin chi tiết
            const drugListBody = document.getElementById('drug-list-body');
            drugListBody.innerHTML = '';
            if (prescriptionId === 'DN00123') {
                drugListBody.innerHTML = `
                    <tr class="border-b hover:bg-red-50/50 transition">
                        <td class="p-2 font-medium text-red-600">Thuốc A (Kháng sinh)</td>
                        <td class="p-2 text-center">7</td>
                        <td class="p-2">Uống 1 viên/ngày</td>
                        <td class="p-2 text-center text-green-600">120</td>
                    </tr>
                    <tr class="border-b hover:bg-red-50/50 transition">
                        <td class="p-2 font-medium text-red-600">Thuốc B (Hạ huyết áp)</td>
                        <td class="p-2 text-center">30</td>
                        <td class="p-2">Uống 1 viên vào sáng</td>
                        <td class="p-2 text-center text-green-600">900</td>
                    </tr>
                `;
            } else {
                 drugListBody.innerHTML = `
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
                `;
            }

            // Cập nhật lại danh sách bên trái để đánh dấu đơn đang chọn
            renderPrescriptionList(); 

            // Nếu đang ở tab Tổng quan, chuyển sang tab Duyệt đơn
            if (document.getElementById('tong-quan').classList.contains('hidden') === false) {
                 showContent('duyet-don-thuoc');
            }
        }

        /**
         * Duyệt đơn thuốc
         */
        function approvePrescription() {
            if (!currentPrescriptionId) {
                showMessage("Vui lòng chọn đơn thuốc để duyệt.", 'error');
                return;
            }
            // Giả lập xóa đơn khỏi hàng chờ
            const index = prescriptionQueueData.findIndex(p => p.id === currentPrescriptionId);
            if (index !== -1) {
                prescriptionQueueData.splice(index, 1);
            }
            
            showMessage(`Đơn thuốc ${currentPrescriptionId} đã được Duyệt và chuyển sang khâu thanh toán/xuất kho.`, 'success');
            
            // Cập nhật lại danh sách và chuyển về đơn đầu tiên (nếu còn)
            currentPrescriptionId = prescriptionQueueData.length > 0 ? prescriptionQueueData[0].id : null;
            renderPrescriptionQueueList();
            if (currentPrescriptionId) {
                viewPrescriptionDetails(currentPrescriptionId);
            } else {
                 document.getElementById('current-prescription-id').textContent = 'Không có đơn nào';
                 document.getElementById('prescription-details').innerHTML = '<div class="text-center p-8 text-gray-500">🎉 Hoàn thành công việc! Hiện không có đơn thuốc nào chờ duyệt.</div>';
            }
        }

        /**
         * Từ chối đơn thuốc
         */
        function rejectPrescription() {
             if (!currentPrescriptionId) {
                showMessage("Vui lòng chọn đơn thuốc để từ chối.", 'error');
                return;
            }
             
            showMessage(`Đơn thuốc ${currentPrescriptionId} đã bị Từ chối và gửi lại cho Bác sĩ để chỉnh sửa.`, 'error');

            // Giữ nguyên đơn trong danh sách chờ nhưng đánh dấu trạng thái (giả lập)
            const prescription = prescriptionQueueData.find(p => p.id === currentPrescriptionId);
            if (prescription) {
                prescription.status = 'Đã Từ chối';
                prescription.statusClass = 'bg-red-500 text-white';
            }
            renderPrescriptionQueueList();
        }

        /**
         * Giả lập tìm kiếm thuốc
         */
        function searchDrug() {
            const query = document.getElementById('search-drug').value.trim();
            if (query.length < 2) {
                showMessage("Vui lòng nhập ít nhất 2 ký tự để tìm kiếm.", 'error');
                return;
            }
            
            // Giả lập kết quả tìm kiếm
            const results = [
                { name: 'Atorvastatin 20mg', active: 'Atorvastatin', stock: 1200, min: 500, location: 'Kệ A-02' },
                { name: 'Omez (Omeprazole 20mg)', active: 'Omeprazole', stock: 45, min: 150, location: 'Kệ C-10' },
                { name: 'Vitamin C 500mg', active: 'Acid Ascorbic', stock: 5000, min: 200, location: 'Kệ D-05' },
            ];

            const tbody = document.getElementById('inventory-table-body');
            tbody.innerHTML = '';
            let count = 0;

            results.forEach(drug => {
                if (drug.name.toLowerCase().includes(query.toLowerCase()) || drug.active.toLowerCase().includes(query.toLowerCase())) {
                    count++;
                    const stockClass = drug.stock < drug.min ? 'text-red-600' : 'text-green-600';
                    const row = `
                        <tr class="${drug.stock < drug.min ? 'bg-red-50' : 'hover:bg-gray-50'} transition">
                            <td class="px-6 py-4 font-medium text-gray-900">${drug.name}</td>
                            <td class="px-6 py-4 text-gray-500">${drug.active}</td>
                            <td class="px-6 py-4 text-center ${stockClass} font-bold">${drug.stock} hộp</td>
                            <td class="px-6 py-4 text-center">${drug.min}</td>
                            <td class="px-6 py-4 text-gray-500">${drug.location}</td>
                            <td class="px-6 py-4 text-center">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-600">Đặt hàng</button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                }
            });
            document.getElementById('search-result-count').textContent = count;
            showMessage(`Tìm thấy ${count} kết quả cho "${query}".`, 'success');
        }

        /**
         * Xử lý lô hàng sắp hết hạn
         */
        function processExpiry(lotId) {
             showMessage(`Đã tạo yêu cầu xử lý lô hàng ${lotId}. Chuyển sang kiểm kê kho dược.`, 'success');
        }

        // --- Khởi tạo ứng dụng ---

        window.onload = function() {
            renderPrescriptionQueueList();
            updateTime();
            setInterval(updateTime, 60000); // Cập nhật thời gian mỗi phút
            
            showContent('tong-quan'); 
            
            window.addEventListener('resize', () => {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth >= 1024 && sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                }
            });

            // Hiển thị chi tiết đơn mặc định khi load
            viewPrescriptionDetails(currentPrescriptionId);
        };
    </script>
</body>
</html>
