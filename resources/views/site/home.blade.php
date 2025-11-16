 @extends('site.master')

@section('title','Trang chủ')
@section('body')
 <!-- Hero Section -->
    <section class="hero-section h-[450px] relative flex items-center bg-cover bg-center">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-60 z-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 text-white">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold mb-4 leading-tight">Hệ thống quản lý bệnh viện thông minh</h1>
                <p class="text-lg sm:text-xl mb-8 opacity-90">Mang đến trải nghiệm y tế hiện đại, tiện lợi và chất lượng cao. Đặt lịch khám online, quản lý hồ sơ bệnh án điện tử, thanh toán trực tuyến và nhiều tính năng thông minh khác.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('schedule') }}" class="flex items-center justify-center py-3 px-6 bg-primary text-white font-semibold rounded-xl shadow-lg hover:bg-blue-600 transition-all duration-300 transform hover:scale-[1.02] text-lg">
                        <i class="far fa-calendar-check mr-2"></i> Đặt lịch khám ngay
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center justify-center py-3 px-6 bg-secondary text-white font-semibold rounded-xl shadow-lg hover:bg-indigo-600 transition-all duration-300 transform hover:scale-[1.02] text-lg">
                        <i class="fas fa-headset mr-2"></i> Liên hệ tư vấn
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Features Section -->
    <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold mb-3 text-center">Tính năng nổi bật</h2>
            <p class="text-lg text-gray-500 text-center mb-16">Hệ thống tích hợp đầy đủ các tính năng hiện đại, giúp tối ưu hóa quy trình khám chữa bệnh và nâng cao trải nghiệm người dùng</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Đặt lịch khám online</h3>
                    <p class="text-gray-600">Đặt lịch khám bệnh dễ dàng, nhanh chóng từ xa, tránh xếp hàng chờ đợi</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Hồ sơ bệnh án điện tử</h3>
                    <p class="text-gray-600">Quản lý và theo dõi hồ sơ sức khỏe cá nhân một cách thuận tiện</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Thanh toán trực tuyến</h3>
                    <p class="text-gray-600">Thanh toán viện phí an toàn qua VNPay, Momo, thẻ ngân hàng</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner">
                        <i class="far fa-comments"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Chatbot tư vấn</h3>
                    <p class="text-gray-600">Hỗ trợ chẩn đoán triệu chứng ban đầu và tư vấn chuyên khoa phù hợp</p>
                </div>
                <!-- Card 5 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner">
                        <i class="far fa-bell"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Thông báo thông minh</h3>
                    <p class="text-gray-600">Nhận nhắc lịch khám, uống thuốc, tái khám qua ứng dụng</p>
                </div>
                <!-- Card 6 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner">
                        <i class="far fa-file-pdf"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Đơn thuốc điện tử</h3>
                    <p class="text-gray-600">Xem đơn thuốc, hướng dẫn sử dụng và theo dõi quá trình điều trị</p>
                </div>
            </div>
        </div>
    </section>
    {{-- <section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-extrabold mb-3 text-center">Tính năng nổi bật</h2>
        <p class="text-lg text-gray-500 text-center mb-16">
            Hệ thống tích hợp đầy đủ các tính năng hiện đại, giúp tối ưu hóa quy trình khám chữa bệnh và nâng cao trải nghiệm người dùng
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($categories as $category)
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center text-primary text-3xl mb-4 shadow-inner overflow-hidden">
                        @if ($category->image_path)
                            <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <i class="far fa-folder-open text-blue-400"></i>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $category->name }}</h3>
                    <p class="text-gray-600">{{ $category->description ?? 'Chưa có mô tả' }}</p>
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-500">Chưa có danh mục tính năng nào được hiển thị.</p>
            @endforelse
        </div>
    </div>
</section> --}}


    <!-- Professional Services Section -->
    {{-- <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold mb-3 text-center">Dịch vụ y tế chuyên nghiệp</h2>
            <p class="text-lg text-gray-500 text-center mb-16">Đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại, cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Service Card 1 -->
                <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                    <img src="https://placehold.co/400x250/3b82f6/ffffff?text=Kh%C3%A1m+t%E1%BB%95ng+qu%C3%A1t" alt="Khám tổng quát" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-1">Khám tổng quát</h3>
                        <p class="text-gray-600">Khám sức khỏe định kỳ và tầm soát bệnh</p>
                    </div>
                </div>
                <!-- Service Card 2 -->
                <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                    <img src="https://placehold.co/400x250/6366f1/ffffff?text=Chuy%C3%AAn+khoa+tim+m%E1%BA%A1ch" alt="Chuyên khoa tim mạch" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-1">Chuyên khoa tim mạch</h3>
                        <p class="text-gray-600">Chẩn đoán và điều trị các bệnh về tim mạch</p>
                    </div>
                </div>
                <!-- Service Card 3 -->
                <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                    <img src="https://placehold.co/400x250/3b82f6/ffffff?text=Chuy%C3%AAn+khoa+nhi" alt="Chuyên khoa nhi" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-1">Chuyên khoa nhi</h3>
                        <p class="text-gray-600">Chăm sóc sức khỏe trẻ em từ sơ sinh đến dưới 18 tuổi</p>
                    </div>
                </div>
                <!-- Service Card 4 -->
                <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                    <img src="https://placehold.co/400x250/6366f1/ffffff?text=X%C3%A9t+nghi%E1%BB%87m" alt="Xét nghiệm" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-1">Xét nghiệm</h3>
                        <p class="text-gray-600">Xét nghiệm máu, nước tiểu và các chỉ số sinh hóa</p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-extrabold mb-3 text-center">Dịch vụ y tế chuyên nghiệp</h2>
        <p class="text-lg text-gray-500 text-center mb-16">
            Đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại, cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($departments as $department)
                <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('storage/' . $department->image) }}" 
                         alt="{{ $department->name }}" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-1">{{ $department->name }}</h3>
                        <p class="text-gray-600">
                            {{ $department->comment ?? 'Chuyên khoa hàng đầu với đội ngũ bác sĩ tận tâm.' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
    <!-- Statistics Section -->
    <section class="bg-primary text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <!-- Stat Item 1 -->
                <div class="p-4 border-r border-blue-400 md:border-r-0 lg:border-r">
                    <span class="block text-4xl font-extrabold mb-1">50,000+</span>
                    <span class="block text-sm opacity-90">Bệnh nhân tin tưởng</span>
                </div>
                <!-- Stat Item 2 -->
                <div class="p-4 border-r border-blue-400">
                    <span class="block text-4xl font-extrabold mb-1">200+</span>
                    <span class="block text-sm opacity-90">Bác sĩ chuyên khoa</span>
                </div>
                <!-- Stat Item 3 -->
                <div class="p-4 border-r border-blue-400 md:border-r lg:border-r">
                    <span class="block text-4xl font-extrabold mb-1">15+</span>
                    <span class="block text-sm opacity-90">Chuyên khoa</span>
                </div>
                <!-- Stat Item 4 -->
                <div class="p-4">
                    <span class="block text-4xl font-extrabold mb-1">24/7</span>
                    <span class="block text-sm opacity-90">Hỗ trợ khẩn cấp</span>
                </div>
            </div>
        </div>
    </section>
   



    <!-- CTA Section -->
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-3">Sẵn sàng trải nghiệm dịch vụ y tế thông minh?</h2>
            <p class="text-xl text-gray-500 mb-8">Đăng ký ngay hôm nay để được hưởng những tiện ích tuyệt vời từ hệ thống quản lý bệnh viện thông minh của chúng tôi</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="flex items-center justify-center py-3 px-8 bg-primary text-white font-semibold rounded-xl shadow-lg hover:bg-blue-600 transition-all duration-300 text-lg">
                    <i class="fas fa-user-plus mr-2"></i> Đăng ký tài khoản
                </a>
                <a href="#" class="flex items-center justify-center py-3 px-8 bg-transparent text-primary border-2 border-primary font-semibold rounded-xl hover:bg-primary hover:text-white transition-all duration-300 text-lg">
                    <i class="fas fa-headset mr-2"></i> Tư vấn miễn phí
                </a>
            </div>
        </div>
    </section>
@endsection	