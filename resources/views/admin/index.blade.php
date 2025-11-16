 @extends('admin.master')

@section('title','Trang chủ')
@section('body')
<div class="main-content">
            <div class="page-header">
                <h1 id="pageTitle">Tổng quan hệ thống</h1>
                <p id="pageSubtitle">Tổng quan và quản lý toàn bộ hoạt động của bệnh viện</p>
            </div>

            <div id="dashboard" class="tab-content active">
                
                <div class="stat-grid">
                    <div class="stat-card stat-card-total">
                        <div class="stat-label">Tổng bệnh nhân</div>
                        <div class="stat-card-content">
                            <div class="stat-value-line">
                                <div class="stat-value">12,847</div>
                                <div class="stat-change up">+12%</div>
                            </div>
                            <div class="stat-icon-wrapper"><i class="fas fa-user-injured"></i></div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-schedule">
                        <div class="stat-label">Lịch hẹn hôm nay</div>
                        <div class="stat-card-content">
                            <div class="stat-value-line">
                                <div class="stat-value">156</div>
                                <div class="stat-change up">+8%</div>
                            </div>
                            <div class="stat-icon-wrapper"><i class="far fa-calendar-alt"></i></div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-revenue">
                        <div class="stat-label">Doanh thu tháng</div>
                        <div class="stat-card-content">
                            <div class="stat-value-line">
                                <div class="stat-value">2.4 tỷ VNĐ</div>
                                <div class="stat-change up">+15%</div>
                            </div>
                            <div class="stat-icon-wrapper"><i class="fas fa-wallet"></i></div>
                        </div>
                    </div>
                    <div class="stat-card stat-card-doctor">
                        <div class="stat-label">Bác sĩ trực</div>
                        <div class="stat-card-content">
                            <div class="stat-value-line">
                                <div class="stat-value">48</div>
                                <div class="stat-change neutral">0%</div>
                            </div>
                            <div class="stat-icon-wrapper"><i class="fas fa-user-md"></i></div>
                        </div>
                    </div>
                </div>

                <div class="content-grid-dashboard">
                    <div class="content-card">
                        <div class="section-header">
                            <h3>Lịch hẹn gần đây</h3>
                            <a href="#" class="status-action-btn" data-status="completed" onclick="switchTab('lichhen')">Xem tất cả</a>
                        </div>
                        
                        <ul class="app-list">
                            <li class="app-item">
                                <div class="app-info">
                                    <h4>Nguyễn Văn An</h4>
                                    <p>BS. Trần Thị Hoa - 09:00 - Tim mạch</p>
                                </div>
                                <span class="status-action-btn" data-status="confirmed">Đã xác nhận</span>
                            </li>
                            <li class="app-item">
                                <div class="app-info">
                                    <h4>Lê Thị Bình</h4>
                                    <p>BS. Phạm Văn Nam - 10:30 - Nhi khoa</p>
                                </div>
                                <span class="status-action-btn" data-status="pending">Đang chờ</span>
                            </li>
                            <li class="app-item">
                                <div class="app-info">
                                    <h4>Hoàng Minh Cường</h4>
                                    <p>BS. Nguyễn Thị Lan - 14:00 - Tổng quát</p>
                                </div>
                                <span class="status-action-btn" data-status="completed">Hoàn thành</span>
                            </li>
                        </ul>
                    </div>

                    <div class="content-card">
                        <div class="section-header">
                            <h3>Thống kê nhanh</h3>
                        </div>
                        <ul class="quick-stats-list">
                            <li class="stat-row">
                                <span class="stat-label-item">Phòng trống</span>
                                <span class="stat-value-item success">12/20</span>
                            </li>
                            <li class="stat-row">
                                <span class="stat-label-item">Bệnh nhân nội trú</span>
                                <span class="stat-value-item">45</span>
                            </li>
                            <li class="stat-row">
                                <span class="stat-label-item">Ca cấp cứu</span>
                                <span class="stat-value-item danger">3</span>
                            </li>
                            <li class="stat-row">
                                <span class="stat-label-item">Tỷ lệ hài lòng</span>
                                <span class="stat-value-item success">94.5%</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="lichhen" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý lịch hẹn</h2>
                        <a href="#" class="btn-primary-icon">
                            <i class="fas fa-plus"></i> Thêm lịch hẹn
                        </a>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Mã LH</th>
                                <th style="width: 25%;">Bệnh nhân</th>
                                <th style="width: 25%;">Bác sĩ</th>
                                <th style="width: 20%;">Thời gian</th>
                                <th style="width: 10%;">Trạng thái</th>
                                <th style="width: 10%;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>AP001</td>
                                <td>Nguyễn Văn An</td>
                                <td>BS. Trần Thị Hoa</td>
                                <td>09:00 <span class="time-detail">- Tim mạch</span></td>
                                <td><span class="status-badge status-confirmed">Đã xác nhận</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>AP002</td>
                                <td>Lê Thị Bình</td>
                                <td>BS. Phạm Văn Nam</td>
                                <td>10:30 <span class="time-detail">- Nhi khoa</span></td>
                                <td><span class="status-badge status-pending-table">Đang chờ</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>AP003</td>
                                <td>Hoàng Minh Cường</td>
                                <td>BS. Nguyễn Thị Lan</td>
                                <td>14:00 <span class="time-detail">- Tổng quát</span></td>
                                <td><span class="status-badge status-completed-table">Hoàn thành</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="nguoidung" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý người dùng</h2>
                        <a href="{{ route('users.create') }}" class="btn-primary-icon">
                            <i class="fas fa-plus"></i> Thêm người dùng
                        </a>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th style="width: 10%;">Ảnh</th>
                                <th style="width: 15%;">Họ tên</th>
                                <th style="width: 15%;">Email</th>
                                <th style="width: 10%;">Số điện thoại</th>
                                <th style="width: 15%;">Địa chỉ</th>
                                <th style="width: 8%;">Giới tính</th>
                                <th style="width: 8%;">Tuổi</th>
                                <th style="width: 10%;">Nghề nghiệp</th>
                                <th style="width: 8%;">Vai trò</th>
                                <th style="width: 10%;">Trạng thái</th>
                                <th style="width: 10%;">Ngày tạo</th>
                                <th style="width: 10%;">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                             @if(isset($users) && $users->count() > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <img src="{{ $user->image ? asset('storage/'.$user->image) : asset('assets/img/default-user.png') }}"
                                                alt="Ảnh người dùng"
                                                style="width: 45px; height: 45px; border-radius: 50%;">
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->gender == 'male' ? 'Nam' : ($user->gender == 'female' ? 'Nữ' : 'Khác') }}</td>
                                        <td>{{ $user->age ?? '-' }}</td>
                                        <td>{{ $user->job ?? '-' }}</td>
                                        <td>
                                            @if ($user->role == 'admin')
                                                <span class="status-badge status-admin">Admin</span>
                                            @elseif ($user->role == 'doctor')
                                                <span class="status-badge status-doctor">Bác sĩ</span>
                                            @elseif ($user->role == 'patient')
                                                <span class="status-badge status-patient">Bệnh nhân</span>
                                            @else
                                                <span class="status-badge status-other">Khác</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->status == 'active')
                                                <span class="status-badge status-active-patient">Hoạt động</span>
                                            @else
                                                <span class="status-badge status-inactive-patient">Khóa</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="action-icons">
                                            <a href="{{ route('users.edit', $user->id) }}" class="edit-icon"><i class="far fa-edit"></i></a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-icon" onclick="return confirm('Xóa người dùng này?')">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                             @else
                                <tr>
                                    <td colspan="12" style="text-align:center;">Không có người dùng nào</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        



            <div id="baocao" class="tab-content">
                <div class="content-card">
                    <h2>Báo cáo Tài chính & Hoạt động</h2>
                    
                    <div class="report-filter">
                        <select>
                            <option>Báo cáo Doanh thu</option>
                            <option>Báo cáo Hiệu suất Bác sĩ</option>
                            <option>Báo cáo Tình trạng Kho thuốc</option>
                        </select>
                        <select>
                            <option>Theo Tháng (Tháng 12/2024)</option>
                            <option>Theo Quý</option>
                            <option>Theo Năm</option>
                        </select>
                        <button class="btn-primary-icon"><i class="fas fa-search"></i> Xem báo cáo</button>
                        <button class="btn-secondary-icon"><i class="fas fa-file-download"></i> Tải PDF</button>
                    </div>

                    <div class="chart-placeholder">
                        Khu vực hiển thị Biểu đồ báo cáo chi tiết (sử dụng thư viện Chart.js/D3.js)
                    </div>
                </div>
            </div>

            <div id="chuyenkhoa" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý Chuyên khoa & Dịch vụ (UC35)</h2>
                        <a href="#" class="btn-primary-icon">
                            <i class="fas fa-plus"></i> Thêm mới
                        </a>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Mã CK</th>
                                <th style="width: 30%;">Tên Chuyên khoa/Dịch vụ</th>
                                <th style="width: 20%;">Số lượng Bác sĩ</th>
                                <th style="width: 20%;">Phí khám (VNĐ)</th>
                                <th style="width: 10%;">Trạng thái</th>
                                <th style="width: 10%;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SK001</td>
                                <td>Tim mạch</td>
                                <td>5</td>
                                <td>200,000</td>
                                <td><span class="status-badge status-confirmed">Đang hoạt động</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>SK002</td>
                                <td>Nhi khoa</td>
                                <td>4</td>
                                <td>180,000</td>
                                <td><span class="status-badge status-confirmed">Đang hoạt động</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>SRV01</td>
                                <td>Khám tổng quát trọn gói</td>
                                <td>N/A</td>
                                <td>5,000,000</td>
                                <td><span class="status-badge status-pending-table">Tạm dừng</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="thuockho" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý Thuốc & Kho thuốc (UC36)</h2>
                        <a href="#" class="btn-primary-icon">
                            <i class="fas fa-plus"></i> Nhập kho
                        </a>
                    </div>

                    <div class="drug-stat-grid">
                        <div class="drug-card">
                            <h4>Tổng số loại thuốc</h4>
                            <div class="value">450</div>
                        </div>
                        <div class="drug-card">
                            <h4>Tổng giá trị tồn kho</h4>
                            <div class="value">5.2 tỷ VNĐ</div>
                        </div>
                        <div class="drug-card">
                            <h4>Thuốc sắp hết hạn/hết kho</h4>
                            <div class="value inventory-low">15 loại</div>
                        </div>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Mã Thuốc</th>
                                <th style="width: 30%;">Tên Thuốc</th>
                                <th style="width: 15%;">Số lượng tồn</th>
                                <th style="width: 15%;">Đơn vị</th>
                                <th style="width: 20%;">Ngày hết hạn</th>
                                <th style="width: 10%;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>DRG001</td>
                                <td>Paracetamol 500mg</td>
                                <td>15,000</td>
                                <td>Viên</td>
                                <td>30/06/2025</td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>DRG005</td>
                                <td>Amoxicillin 250mg</td>
                                <td><span class="inventory-low">450</span></td>
                                <td>Hộp</td>
                                <td>31/01/2025</td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="phonggiuong" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý Phòng & Giường bệnh (UC38)</h2>
                        <a href="#" class="btn-primary-icon">
                            <i class="fas fa-plus"></i> Thêm phòng
                        </a>
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Mã Phòng</th>
                                <th style="width: 30%;">Loại phòng</th>
                                <th style="width: 20%;">Số giường</th>
                                <th style="width: 20%;">Giường trống</th>
                                <th style="width: 15%;">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>R101</td>
                                <td>Phòng thường (Khoa Tổng quát)</td>
                                <td>4</td>
                                <td>3</td>
                                <td><span class="status-badge status-confirmed">Sẵn sàng</span></td>
                            </tr>
                            <tr>
                                <td>R205</td>
                                <td>Phòng VIP (Tim mạch)</td>
                                <td>1</td>
                                <td>0</td>
                                <td><span class="status-badge status-inactive-patient">Đã sử dụng</span></td>
                            </tr>
                            <tr>
                                <td>R302</td>
                                <td>Phòng Hồi sức cấp cứu</td>
                                <td>2</td>
                                <td>1</td>
                                <td><span class="status-badge status-pending-table">Cần vệ sinh</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="taikhoan" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý Tài khoản & Phân quyền (UC34)</h2>
                        <a href="#" class="btn-primary-icon">
                            <i class="fas fa-user-plus"></i> Tạo tài khoản
                        </a>
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Tên đăng nhập</th>
                                <th style="width: 20%;">Họ và tên</th>
                                <th style="width: 20%;">Vai trò</th>
                                <th style="width: 20%;">Trạng thái</th>
                                <th style="width: 20%;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>admin_hosp</td>
                                <td>Nguyễn Văn Quản Trị</td>
                                <td>**Administrator**</td>
                                <td><span class="status-badge status-active">Hoạt động</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="fas fa-key" title="Đổi mật khẩu"></i></a>
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>dr_hoa_tm</td>
                                <td>BS. Trần Thị Hoa</td>
                                <td>Bác sĩ Tim mạch</td>
                                <td><span class="status-badge status-active">Hoạt động</span></td>
                                <td class="action-icons">
                                    <a href="#" class="edit-icon"><i class="fas fa-key" title="Đổi mật khẩu"></i></a>
                                    <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                                    <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="nhanvien" class="tab-content">
                <div class="section-header">
                    <h2>Quản lý Nhân viên Y tế (UC39)</h2>
                    <a href="#" class="btn-primary-icon">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </a>
                </div>

                <div class="doctor-grid">
                    
                    <div class="doctor-card">
                        <div class="doctor-avatar"><i class="fas fa-user-md"></i></div>
                        <div class="doctor-info">
                            <div class="doctor-name">BS. Trần Thị Hoa</div>
                            <div class="doctor-specialty">Chuyên khoa: Tim mạch</div>
                            <div class="doctor-experience">Kinh nghiệm: 15 năm</div>
                            <div class="doctor-stats">
                                <span>Vai trò: **Bác sĩ**</span>
                                <span class="doctor-status status-active">Hoạt động</span>
                            </div>
                        </div>
                        <div class="doctor-actions">
                            <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                            <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>

                    <div class="doctor-card">
                        <div class="doctor-avatar" style="background-color: #fce7f3; color: #db2777;"><i class="fas fa-user-nurse"></i></div>
                        <div class="doctor-info">
                            <div class="doctor-name">Y tá. Nguyễn Văn Cảnh</div>
                            <div class="doctor-specialty">Khoa: Cấp cứu</div>
                            <div class="doctor-experience">Kinh nghiệm: 5 năm</div>
                            <div class="doctor-stats">
                                <span>Vai trò: **Y tá**</span>
                                <span class="doctor-status status-active">Hoạt động</span>
                            </div>
                        </div>
                        <div class="doctor-actions">
                            <a href="#" class="edit-icon"><i class="far fa-edit"></i></a>
                            <a href="#" class="delete-icon"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="chatbot" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Quản lý Chatbot AI (UC37)</h2>
                        <a href="#" class="btn-secondary-icon">
                            <i class="fas fa-sync"></i> Huấn luyện lại mô hình
                        </a>
                    </div>
                    
                    <ul class="quick-stats-list">
                        <li class="stat-row">
                            <span class="stat-label-item">Trạng thái Bot</span>
                            <span class="stat-value-item success">Đang hoạt động</span>
                        </li>
                        <li class="stat-row">
                            <span class="stat-label-item">Phiên tư vấn hôm nay</span>
                            <span class="stat-value-item">580</span>
                        </li>
                        <li class="stat-row">
                            <span class="stat-label-item">Tỷ lệ trả lời chính xác</span>
                            <span class="stat-value-item success">92.1%</span>
                        </li>
                        <li class="stat-row">
                            <span class="stat-label-item">Phiên cần đánh giá</span>
                            <span class="stat-value-item danger">12</span>
                        </li>
                    </ul>
                    <hr style="margin: 20px 0; border-color: var(--border-color);">
                    <h3>Quản lý Kiến thức (Knowledge Base)</h3>
                    <p style="color: #6b7280; margin-top: 10px;">Thêm, sửa đổi các câu hỏi/trả lời mẫu để cải thiện chất lượng tư vấn.</p>
                    <button class="btn-primary-icon" style="margin-top: 15px;"><i class="fas fa-book"></i> Quản lý cơ sở tri thức</button>
                </div>
            </div>

            <div id="auditlog" class="tab-content">
                <div class="content-card">
                    <div class="section-header">
                        <h2>Nhật ký hệ thống (Audit Log) - UC41</h2>
                        <button class="btn-secondary-icon">
                            <i class="fas fa-file-csv"></i> Xuất CSV
                        </button>
                    </div>

                    <table class="data-table log-table">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Thời gian</th>
                                <th style="width: 15%;">Người dùng</th>
                                <th style="width: 15%;">Hành động</th>
                                <th style="width: 55%;">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11:55 PM</td>
                                <td><span class="log-user">Admin</span></td>
                                <td><span class="log-action">CẬP NHẬT</span></td>
                                <td class="log-description">Sửa thông tin **BS. Trần Thị Hoa** (UC39)</td>
                            </tr>
                            <tr>
                                <td>11:50 PM</td>
                                <td><span class="log-user">BS. Hoa</span></td>
                                <td><span class="log-action">XEM</span></td>
                                <td class="log-description">Truy cập hồ sơ bệnh nhân **PT001 - Nguyễn Văn An**</td>
                            </tr>
                            <tr>
                                <td>11:45 PM</td>
                                <td><span class="log-user">System</span></td>
                                <td><span class="log-action" style="color: var(--danger-color);">LỖI</span></td>
                                <td class="log-description">Kết nối CSDL bị gián đoạn trong 5 giây</td>
                            </tr>
                            <tr>
                                <td>11:40 PM</td>
                                <td><span class="log-user">Lễ tân</span></td>
                                <td><span class="log-action" style="color: var(--success-color);">TẠO MỚI</span></td>
                                <td class="log-description">Thêm lịch hẹn **AP004** cho bệnh nhân **Phạm Thị D**</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
@endsection