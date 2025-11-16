<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Hệ Thống Toàn Diện - SmartHospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

     <link rel="stylesheet" href="{{ asset('css/styleadmin.css') }}">
</head>
<body>

    <div class="admin-layout">
        
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-hospital-alt"></i> **Admin Panel**
            </div>
            <ul class="nav-menu">
                <li class="nav-section-title">Hoạt động & Báo cáo</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link active" data-tab="dashboard" data-title="Tổng quan hệ thống" data-subtitle="Tổng quan và quản lý toàn bộ hoạt động của bệnh viện">
                        <i class="fas fa-tachometer-alt"></i> Tổng quan (UC40)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('appointments.index') }}" class="nav-link" data-tab="lichhen" data-title="Quản lý Lịch hẹn" data-subtitle="Xử lý các lịch hẹn khám bệnh trực tuyến và tại chỗ.">
                        <i class="far fa-calendar-alt"></i> Lịch hẹn
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link"
                    data-tab="benhnhan"
                    data-title="Quản lý người dùng"
                    data-subtitle="Hồ sơ và thông tin chi tiết của tất cả bệnh nhân.">
                        <i class="fas fa-user-injured"></i> Người dùng
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link" data-tab="baocao" data-title="Hệ thống Báo cáo" data-subtitle="Các báo cáo tài chính, hoạt động và hiệu suất chi tiết (UC40).">
                        <i class="fas fa-chart-line"></i> Báo cáo (UC40)
                    </a>
                </li>

                <li class="nav-section-title">Quản lý Dữ liệu</li>
                <li class="nav-item">
                    <a href="{{ route('departments.index') }}" class="nav-link" data-tab="chuyenkhoa" data-title="Quản lý Chuyên khoa & Dịch vụ" data-subtitle="Thiết lập các chuyên khoa và gói dịch vụ khám chữa bệnh (UC35).">
                        <i class="fas fa-stethoscope"></i> Chuyên khoa & Dịch vụ (UC35)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('medicines.index') }}" class="nav-link" data-tab="thuockho" data-title="Quản lý Thuốc & Kho thuốc" data-subtitle="Quản lý số lượng, nhập/xuất và danh mục thuốc (UC36).">
                        <i class="fas fa-pills"></i> Thuốc & Kho thuốc (UC36)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('hospital_rooms.index') }}" class="nav-link" data-tab="phonggiuong" data-title="Quản lý Phòng & Giường bệnh" data-subtitle="Theo dõi tình trạng phòng và giường bệnh (UC38).">
                        <i class="fas fa-bed"></i> Phòng & Giường bệnh (UC38)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('doctorsite.index') }}" class="nav-link" data-tab="phonggiuong" data-title="Quản lý bác sĩ" data-subtitle="quản lý bác sĩ (UC38).">
                        <i class="fas fa-bed"></i> Quản lý bác sĩ (UC38)
                    </a>
                </li>
                <li class="nav-section-title">Hệ thống & Cấu hình</li>
                <li class="nav-item">
                    <a href="{{ route('services.index') }}" class="nav-link" data-tab="dichvu" data-title="Quản lý dịch vụ" data-subtitle="Quản lý dịch vụ (UC34).">
                        <i class="fas fa-user-shield"></i> Quản lý dịch vụ (UC34)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link" data-tab="danhmuc" data-title="Quản lý danh mục" data-subtitle="Danh mục(UC39).">
                        <i class="fas fa-users"></i> Quản lý danh mục(UC39)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="chatbot" data-title="Quản lý Chatbot AI" data-subtitle="Cấu hình, đào tạo và theo dõi hiệu suất Chatbot tư vấn (UC37).">
                        <i class="fas fa-robot"></i> Quản lý Chatbot AI (UC37)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('audit_log.index') }}" class="nav-link" data-tab="auditlog" data-title="Nhật ký hệ thống (Audit Log)" data-subtitle="Ghi lại mọi hoạt động của người dùng trong hệ thống (UC41).">
                        <i class="fas fa-clipboard-list"></i> Nhật ký hệ thống (UC41)
                    </a>
                </li>
                <div class="mt-auto pt-4 border-t border-white/20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center p-3 w-full rounded-xl hover:bg-red-600 transition duration-150 text-white bg-red-500/80">
                    <i data-lucide="log-out" class="w-5 h-5 mr-3"></i> Đăng xuất
                </button>
            </form>
        </div>
            </ul>
        </div>

           @yield('body')
   <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>