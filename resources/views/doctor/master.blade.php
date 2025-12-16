<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Hệ thống Quản lý Phòng khám Trực tuyến</title>
    <!-- Tải Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tải thư viện biểu tượng Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    
     <link rel="stylesheet" href="{{ asset('css/styledoctor.css') }}">
</head>
<body class="bg-gray-50 min-h-screen flex">

    <!-- 1. Sidebar Menu (Thanh điều hướng) -->
    <aside id="sidebar" class="w-64 primary-color text-white fixed lg:sticky top-0 h-full p-4 flex flex-col space-y-4 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 rounded-r-2xl">
        
        <div class="text-2xl font-bold py-3 px-4 rounded-xl bg-white/20 text-center shadow-lg mb-2">
            Bác Sĩ Dashboard
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto">
            
            <a href="{{ route('doctor.schedule.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
               {{ request()->routeIs('doctor.schedule.*') ? 'bg-white text-blue-700 shadow-md font-bold' : 'text-white hover:bg-white/20' }}">
               <i data-lucide="calendar" class="w-5 h-5"></i>
               <span>Lịch khám</span>
            </a>

            <a href="{{ route('doctor.patients.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
               {{ request()->routeIs('doctor.patients.*') ? 'bg-white text-blue-700 shadow-md font-bold' : 'text-white hover:bg-white/20' }}">
               <i data-lucide="users" class="w-5 h-5"></i>
               <span>Danh sách bệnh nhân</span>
            </a>

            <a href="{{ route('doctor.diagnosis.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
               {{ request()->routeIs('doctor.diagnosis.*') ? 'bg-white text-blue-700 shadow-md font-bold' : 'text-white hover:bg-white/20' }}">
               <i data-lucide="stethoscope" class="w-5 h-5"></i>
               <span>Khám & Chẩn đoán</span>
            </a>
            
            <a href="{{ route('doctor.leaves.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-white hover:bg-white/20" onclick="showContent('xet-nghiem')">
                <i data-lucide="flask-round" class="w-5 h-5"></i>
                <span>Xin nghỉ phép</span>
            </a>

            <a href="{{ route('doctor.test_results.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
               {{ request()->routeIs('doctor.test_results.*') ? 'bg-white text-blue-700 shadow-md font-bold' : 'text-white hover:bg-white/20' }}">
                <i data-lucide="flask-round" class="w-5 h-5"></i>
                <span>Kết quả Xét nghiệm</span>
            </a>

            <a href="{{ route('doctor.statistics.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 
               {{ request()->routeIs('doctor.statistics.*') ? 'bg-white text-blue-700 shadow-md font-bold' : 'text-white hover:bg-white/20' }}">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                <span>Thống kê</span>
            </a>

        </nav>

        <div class="mt-auto pt-4 border-t border-white/20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center w-full p-3 rounded-xl bg-red-500/80 hover:bg-red-600 text-white transition duration-150 shadow-sm font-medium">
                    <i data-lucide="log-out" class="w-5 h-5 mr-2"></i> Đăng xuất
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
            <h1 class="text-3xl font-extrabold primary-text">Bác Sĩ Dashboard</h1>
            <button id="menu-button" class="p-2 primary-color text-white rounded-lg shadow-md" onclick="toggleSidebar()">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </header>
        
        <!-- Welcome Card -->
       

        <!-- Content Sections (Các phần chức năng) -->
 @yield('body')
    </main>

   <script src="{{ asset('js/doctor.js') }}"></script>
</body>
</html>
