<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHospital - Hệ thống quản lý bệnh viện thông minh</title>
    <!-- Tải Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tải Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Thiết lập cấu hình Tailwind (Sử dụng font Inter và màu sắc chủ đạo) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#3b82f6', // blue-500
                        'secondary': '#6366f1', // indigo-500
                    },
                    fontFamily: {
                        sans: ['Inter', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


</head>
<body class="font-sans bg-gray-50 text-gray-800 leading-relaxed">

   {{-- <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center flex-wrap">
            <div class="text-2xl font-bold text-primary">
                <i class="fas fa-hospital-alt mr-2"></i> SmartHospital
            </div>
            
            <nav class="hidden lg:flex flex-grow justify-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-primary">Trang chủ</a>
                <a href="{{ route('services') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Dịch vụ</a>
                <a href="{{ route('schedule') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Đặt lịch khám</a>
                <a href="{{ route('medical_records') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Hồ sơ bệnh án</a>
                <a href="{{ route('payment') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Thanh toán</a>
              
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Liên hệ</a>
            </nav>
            
            <div class="flex items-center space-x-3 mt-4 lg:mt-0">
                <a href="{{ route('login') }}" class="py-2 px-4 font-semibold text-primary hover:bg-blue-50 rounded-lg transition-colors duration-200">Đăng nhập</a>
                <a href="{{ route('register') }}" class="py-2 px-4 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 shadow-md transition-colors duration-200">Đăng ký</a>
            </div>
        </div>
    </header> --}}
    <!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center flex-wrap">
        <div class="text-2xl font-bold text-primary">
            <i class="fas fa-hospital-alt mr-2"></i> SmartHospital
        </div>
        
        <nav class="hidden lg:flex flex-grow justify-center space-x-8">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-primary">Trang chủ</a>
            <a href="{{ route('services') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Dịch vụ</a>
            <a href="{{ route('schedule') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Đặt lịch khám</a>
            <a href="{{ route('medical_records') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Hồ sơ bệnh án</a>
            <a href="{{ route('payment') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Thanh toán</a>
            <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary font-medium pb-1 border-b-2 border-transparent hover:border-primary transition-colors duration-200">Liên hệ</a>
        </nav>
        
        <div class="flex items-center space-x-3 mt-4 lg:mt-0">
            @guest
                <a href="{{ route('login') }}" class="py-2 px-4 font-semibold text-primary hover:bg-blue-50 rounded-lg transition-colors duration-200">Đăng nhập</a>
                <a href="{{ route('register') }}" class="py-2 px-4 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 shadow-md transition-colors duration-200">Đăng ký</a>
            @endguest

            @auth
                <span class="text-gray-700 font-medium">Xin chào, {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200">
                        Đăng xuất
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>


   @yield('body')

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 pb-10 border-b border-gray-700">
                <!-- Cột 1: Thông tin -->
                <div class="lg:col-span-2">
                    <div class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-hospital-alt mr-2"></i> SmartHospital
                    </div>
                    <p class="text-gray-400 mb-6 text-sm">Hệ thống quản lý bệnh viện thông minh, mang đến trải nghiệm y tế hiện đại, tiện lợi và chất lượng cao cho bệnh nhân và đội ngũ y tế.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200 text-lg"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200 text-lg"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200 text-lg"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200 text-lg"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Cột 2: Liên kết nhanh -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Liên kết nhanh</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Đặt lịch khám</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Hồ sơ bệnh án</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Thanh toán viện phí</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>

                <!-- Cột 3: Dịch vụ -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Dịch vụ</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Khám tổng quát</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Chuyên khoa tim mạch</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Chuyên khoa nhi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200">Xét nghiệm</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="bg-gray-900 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p class="mb-2 md:mb-0">© 2024 SmartHospital. Tất cả quyền được bảo lưu.</p>
                <div class="flex space-x-3">
                    <a href="#" class="hover:text-primary transition-colors duration-200">Chính sách bảo mật</a>
                    <span>|</span>
                    <a href="#" class="hover:text-primary transition-colors duration-200">Điều khoản sử dụng</a>
                    <span>|</span>
                    <span>Powered by Readdy</span>
                </div>
            </div>
        </div>
    </footer>
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
