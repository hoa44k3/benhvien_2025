<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Hệ Thống Toàn Diện - SmartHospital</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styleadmin.css') }}">

    <style>
        /* CSS Bổ sung để fix nhanh layout nếu file styleadmin.css chưa đủ */
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #343a40; /* Màu tối chuẩn admin */
            color: #fff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .sidebar .logo {
            padding: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid #4b545c;
            text-align: center;
            background-color: #343a40;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .nav-section-title {
            padding: 15px 20px 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #adb5bd;
            font-weight: bold;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #c2c7d0;
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
        
        /* Dropdown menu styling */
        .nav-treeview {
            list-style: none;
            padding-left: 0;
            background-color: #2c3136;
            display: none; /* Ẩn mặc định, JS sẽ xử lý hiển thị */
        }
        
        .menu-open .nav-treeview {
            display: block;
        }

        .nav-treeview .nav-link {
            padding-left: 45px;
            font-size: 0.9rem;
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f4f6f9;
            width: 100%;
            overflow-x: hidden;
        }

        /* Logout button styling fixed from Tailwind to CSS */
        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid #4b545c;
            background-color: #343a40;
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            background-color: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Scrollbar đẹp hơn cho sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #6c757d;
            border-radius: 3px;
        }
    </style>
</head>

<body>

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-hospital-alt text-primary"></i> <span>SmartHospital</span>
            </div>
            
            <ul class="nav-menu">
                <!-- Dashboard -->
                <li class="nav-section-title">Hoạt động & Báo cáo</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> 
                        <span>Tổng quan (UC40)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->is('appointments*') ? 'active' : '' }}">
                        <i class="far fa-calendar-alt"></i> 
                        <span>Lịch hẹn</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="fas fa-user-injured"></i> 
                        <span>Người dùng</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> 
                        <span>Báo cáo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('video_calls.index') }}" class="nav-link {{ request()->is('video_calls*') ? 'active' : '' }}">
                        <i class="fas fa-video"></i> 
                        <span>Lịch sử cuộc gọi</span>
                    </a>
                </li>

                <!-- Quản lý Dữ liệu -->
                <li class="nav-section-title">Quản lý Dữ liệu</li>
                
                <li class="nav-item">
                    <a href="{{ route('departments.index') }}" class="nav-link {{ request()->is('departments*') ? 'active' : '' }}">
                        <i class="fas fa-stethoscope"></i> 
                        <span>Chuyên khoa & Dịch vụ</span>
                    </a>
                </li>

                <!-- Menu Đa cấp Thuốc -->
                <li class="nav-item has-treeview {{ request()->is('medicines*') ? 'menu-open' : '' }}">
                    <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->is('medicines') ? 'active' : '' }}">
                        <i class="fas fa-pills"></i>
                        <span>Thuốc & Kho thuốc</span>
                        <i class="fas fa-angle-left ms-auto" style="float: right;"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('medicine_units.index') }}" class="nav-link {{ request()->is('medicines/medicine_units*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <span>Đơn vị thuốc</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('medicine_categories.index') }}" class="nav-link {{ request()->is('medicines/medicine_categories*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <span>Phân loại thuốc</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('doctorsite.index') }}" class="nav-link {{ request()->is('doctorsite*') ? 'active' : '' }}">
                        <i class="fas fa-user-md"></i> 
                        <span>Quản lý bác sĩ</span>
                    </a>
                </li>

                <!-- Hệ thống & Cấu hình -->
                <li class="nav-section-title">Hệ thống & Cấu hình</li>
                
                <li class="nav-item">
                    <a href="{{ route('services.index') }}" class="nav-link {{ request()->is('services*') ? 'active' : '' }}">
                        <i class="fas fa-concierge-bell"></i> 
                        <span>Quản lý dịch vụ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('doctor_attendances.index') }}" class="nav-link {{ request()->is('doctor_attendances*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> 
                        <span>Quản lý ca trực Bác sĩ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->is('leaves*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-times"></i> 
                        <span>Duyệt xin nghỉ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->is('invoices*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i> 
                        <span>Hóa đơn</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i> 
                        <span>Danh mục chung</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('service_steps.index') }}" class="nav-link {{ request()->is('service_steps*') ? 'active' : '' }}">
                        <i class="fas fa-shoe-prints"></i> 
                        <span>Quy trình dịch vụ</span>
                    </a>
                </li>
                
                <!-- Truyền thông & Tương tác -->
                <li class="nav-section-title">Truyền thông & Tương tác</li>
                
                <li class="nav-item">
                    <a href="{{ route('contacts.index') }}" class="nav-link {{ request()->is('contacts*') ? 'active' : '' }}">
                        <i class="fas fa-address-book"></i> 
                        <span>Liên hệ (Contact)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('posts.index') }}" class="nav-link {{ request()->is('posts*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i> 
                        <span>Bài viết (News)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('comments.index') }}" class="nav-link {{ request()->is('comments*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i> 
                        <span>Bình luận</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('faqs.index') }}" class="nav-link {{ request()->is('faqs*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i> 
                        <span>Câu hỏi thường gặp</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('medical_records.index') }}" class="nav-link {{ request()->is('medical_records*') ? 'active' : '' }}">
                        <i class="fas fa-notes-medical"></i> 
                        <span>Hồ sơ bệnh án</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('prescriptions.index') }}" class="nav-link {{ request()->is('prescriptions*') ? 'active' : '' }}">
                        <i class="fas fa-prescription-bottle-alt"></i> 
                        <span>Đơn thuốc</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('audit_log.index') }}" class="nav-link {{ request()->is('audit_log*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> 
                        <span>Nhật ký hệ thống</span>
                    </a>
                </li>
            </ul>

            <!-- Footer: Logout -->
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- Có thể thêm Header/Navbar ở đây nếu cần -->
            
            <!-- Nội dung thay đổi -->
            @yield('body')
        </main>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    
    @stack('scripts')
</body>

</html>