
        // Khởi tạo icons Lucide
        lucide.createIcons();

        // Dữ liệu mẫu cho Danh sách Bệnh nhân
        const patientData = [
            { id: 1, name: "Trần Thị C", time: "10:30", status: "Chờ khám", action: "start", notes: "Ho dai dẳng 1 tuần" },
            { id: 2, name: "Lê Văn D", time: "11:00", status: "Đã check-in", action: "start", notes: "Đau lưng dưới" },
            { id: 3, name: "Phạm Hùng", time: "09:45", status: "Đã khám", action: "view", notes: "Kiểm tra định kỳ" },
            { id: 4, name: "Nguyễn Thu E", time: "11:30", status: "Chờ check-in", action: "wait", notes: "Tư vấn dinh dưỡng" }
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
         * Hiện Modal (Popup)
         * @param {string} modalId - ID của modal muốn hiện
         */
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            lucide.createIcons();
        }

        /**
         * Ẩn Modal (Popup)
         * @param {string} modalId - ID của modal muốn ẩn
         */
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('flex');
            modal.classList.add('hidden');
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

        // --- Hàm Xử lý Dữ liệu/Chức năng ---

        /**
         * Hiển thị danh sách bệnh nhân vào bảng
         */
        function renderPatientList() {
            const tbody = document.getElementById('patient-list-body');
            tbody.innerHTML = ''; // Xóa dữ liệu cũ

            patientData.forEach(patient => {
                let statusClass, actionHtml;

                // Xử lý trạng thái và màu sắc
                switch (patient.status) {
                    case "Chờ khám":
                        statusClass = 'bg-yellow-100 text-yellow-800';
                        actionHtml = `<button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-green-600 transition" onclick="startConsultation(${patient.id})">
                                        <i data-lucide="video" class="w-4 h-4 mr-1 inline-block"></i> Bắt đầu khám
                                      </button>`;
                        break;
                    case "Đã check-in":
                        statusClass = 'bg-blue-100 text-blue-800';
                        actionHtml = `<button class="primary-color text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-green-600 transition" onclick="startConsultation(${patient.id})">
                                        <i data-lucide="stethoscope" class="w-4 h-4 mr-1 inline-block"></i> Mời vào phòng
                                      </button>`;
                        break;
                    case "Đã khám":
                        statusClass = 'bg-green-100 text-green-800';
                        actionHtml = `<button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold hover:bg-gray-300 transition" onclick="viewEMR(${patient.id}, '${patient.name}')">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1 inline-block"></i> Xem hồ sơ
                                      </button>`;
                        break;
                    case "Chờ check-in":
                    default:
                        statusClass = 'bg-gray-100 text-gray-600';
                        actionHtml = `<span class="text-xs text-gray-500">Chờ Lễ tân</span>`;
                }

                const row = `
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${patient.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold">${patient.name}</span>
                            <p class="text-xs text-gray-500">${patient.notes}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${patient.time}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${patient.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            ${actionHtml}
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
            lucide.createIcons(); // Đảm bảo icons hiển thị
        }

        /**
         * Mở form khám bệnh
         * @param {number} patientId
         */
        function startConsultation(patientId) {
            const patient = patientData.find(p => p.id === patientId);
            if (!patient) return;

            // Chuyển sang section khám bệnh
            showContent('kham-benh');
            
            // Cập nhật thông tin bệnh nhân đang khám
            document.querySelector('#kham-benh .text-xl.font-semibold.mb-1').textContent = patient.name;
            document.querySelector('#kham-benh .text-sm.text-gray-500.mb-3').textContent = `Mã BN: 000${patientId} | Tuổi/Giới tính`;
            document.querySelector('#kham-benh .bg-red-50.text-red-700').textContent = `Lý do khám: ${patient.notes}`;

            // Hiển thị thông báo
            showMessage(`Đã bắt đầu phiên khám với BN ${patient.name}.`);
        }

        /**
         * Xem Hồ sơ Bệnh án Điện tử (EMR)
         * @param {number} patientId
         * @param {string} patientName
         */
        function viewEMR(patientId, patientName) {
            // Cập nhật nội dung EMR giả lập
            document.getElementById('emr-name').textContent = patientName;
            document.getElementById('emr-id').textContent = `000${patientId}`;
            // Mở modal
            openModal('emr-modal');
        }

        /**
         * Thêm dòng kê đơn thuốc mới
         */
        function addPrescriptionItem() {
            const container = document.getElementById('prescription-items');
            const newItem = document.createElement('div');
            newItem.className = 'flex space-x-2';
            newItem.innerHTML = `
                <input type="text" placeholder="Tên Thuốc" class="flex-1 p-2 border rounded-lg">
                <input type="number" placeholder="SL" class="w-16 p-2 border rounded-lg">
                <input type="text" placeholder="Liều dùng" class="flex-1 p-2 border rounded-lg">
                <button class="text-red-500 hover:text-red-700" onclick="this.parentNode.remove()"><i data-lucide="x" class="w-5 h-5"></i></button>
            `;
            container.appendChild(newItem);
            lucide.createIcons();
        }

        /**
         * Giả lập chức năng Ký số & Gửi đơn thuốc
         */
        function signAndSendPrescription() {
            const diagnosis = document.getElementById('chuan-doan').value.trim();
            if (diagnosis === "") {
                showMessage("Vui lòng nhập chẩn đoán trước khi gửi đơn thuốc.", 'error');
                return;
            }
            // Giả lập logic ký số và gửi API
            showMessage("Đơn thuốc đã được Ký số và Gửi thành công đến hệ thống Dược sĩ!", 'success');
        }

        /**
         * Giả lập chức năng Gửi yêu cầu xét nghiệm
         */
        function sendLabRequest() {
            const patient = document.getElementById('bn-xetnghiem').value;
            showMessage(`Đã gửi chỉ định xét nghiệm cho BN ${patient.split(' - ')[1]} đến Lab Technician.`, 'success');
            // Sau khi gửi, có thể reset form yêu cầu xét nghiệm
        }


        // --- Hàm tiện ích chung ---

        /**
         * Hiển thị thông báo tạm thời (thay cho alert)
         * @param {string} message - Nội dung thông báo
         * @param {string} type - 'success' hoặc 'error'
         */
        function showMessage(message, type = 'success') {
            const messageBox = document.createElement('div');
            messageBox.textContent = message;
            messageBox.className = `fixed top-4 right-4 z-[9999] p-4 rounded-lg text-white shadow-xl transition-opacity duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            document.body.appendChild(messageBox);
            
            // Tự động ẩn sau 3 giây
            setTimeout(() => {
                messageBox.classList.add('opacity-0');
                messageBox.addEventListener('transitionend', () => messageBox.remove());
            }, 3000);
        }

        /**
         * Cập nhật thời gian hiện tại
         */
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('current-time').textContent = timeString;
        }


        // --- Khởi tạo ứng dụng ---

        window.onload = function() {
            renderPatientList();
            updateTime();
            setInterval(updateTime, 60000); // Cập nhật thời gian mỗi phút
            // Thiết lập trạng thái mặc định (trang lịch khám)
            showContent('lich-kham'); 
            
            // Xử lý thay đổi kích thước màn hình để đảm bảo sidebar hoạt động đúng
            window.addEventListener('resize', () => {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth >= 1024 && sidebar.classList.contains('-translate-x-full')) {
                    // Đảm bảo sidebar hiện nếu chuyển từ mobile sang desktop
                    sidebar.classList.remove('-translate-x-full');
                }
            });
        };
