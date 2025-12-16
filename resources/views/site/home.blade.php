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
  <section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold mb-3 text-gray-800">Cẩm nang sức khỏe</h2>
            <p class="text-lg text-gray-500">
                Kiến thức y khoa, mẹo vặt sức khỏe và tin tức mới nhất từ chuyên gia
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            {{-- CỘT TRÁI: BÀI VIẾT MỚI NHẤT (Chiếm 2 phần) --}}
            <div class="lg:col-span-2 space-y-8">
                @foreach($latestPosts as $post)
                <div class="flex flex-col md:flex-row gap-6 group">
                    {{-- Ảnh thumb --}}
                    <div class="w-full md:w-1/3 h-52 overflow-hidden rounded-xl">
                        <a href="{{ route('site.postshow', $post->id) }}">
                            <img src="{{ $post->image ? asset('storage/'.$post->image) : 'https://placehold.co/600x400' }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        </a>
                    </div>
                    {{-- Nội dung tóm tắt --}}
                    <div class="flex-1 flex flex-col justify-center">
                        <div class="text-sm text-blue-600 font-bold mb-2 uppercase">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $post->created_at->format('d/m/Y') }}
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-gray-800 group-hover:text-blue-600 transition">
                            <a href="{{ route('site.postshow', $post->id) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 line-clamp-2 mb-4">{{ $post->description }}</p>
                        <a href="{{ route('site.postshow', $post->id) }}" class="text-blue-600 font-semibold hover:underline">
                            Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <hr class="border-gray-100">
                @endforeach
            </div>

            {{-- CỘT PHẢI: BÀI VIẾT NỔI BẬT (Chiếm 1 phần) --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 sticky top-24">
                    <h3 class="text-xl font-bold mb-6 flex items-center border-l-4 border-blue-600 pl-3">
                        <i class="fas fa-star text-yellow-400 mr-2"></i> Bài viết nổi bật
                    </h3>
                    
                    <div class="space-y-6">
                        @foreach($featuredPosts as $post)
                        <div class="group">
                            <div class="h-40 rounded-lg overflow-hidden mb-3 relative">
                                <a href="{{ route('site.postshow', $post->id) }}">
                                    <img src="{{ $post->image ? asset('storage/'.$post->image) : 'https://placehold.co/600x400' }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                </a>
                                <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">HOT</span>
                            </div>
                            <h4 class="font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition mb-1">
                                <a href="{{ route('site.postshow', $post->id) }}">{{ $post->title }}</a>
                            </h4>
                            <span class="text-xs text-gray-500"><i class="far fa-eye mr-1"></i> {{ $post->views }} lượt xem</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-extrabold mb-3 text-center">Dịch vụ y tế chuyên nghiệp</h2>
        <p class="text-lg text-gray-500 text-center mb-16">
            Đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại, cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($departments as $department)
        <div class="service-card group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 flex flex-col h-full border border-gray-100 overflow-hidden relative">
            
            {{-- 1. ẢNH ĐẠI DIỆN & BADGE --}}
            <a href="{{ route('services', ['department' => $department->id]) }}" class="relative h-48 block overflow-hidden">
                <img src="{{ $department->image ? asset('storage/' . $department->image) : asset('images/default-department.jpg') }}" 
                     alt="{{ $department->name }}" 
                     class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                
                {{-- Lớp phủ gradient để làm nổi bật text bên trên --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                {{-- Trạng thái --}}
                <div class="absolute top-3 right-3">
                    @if($department->status === 'active')
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm flex items-center gap-1">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span> Hoạt động
                        </span>
                    @else
                        <span class="bg-gray-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                            Tạm ngưng
                        </span>
                    @endif
                </div>
            </a>

            {{-- 2. NỘI DUNG CHI TIẾT --}}
            <div class="p-5 pt-8 flex flex-col flex-grow relative">
                
                {{-- Tên Khoa --}}
                <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition line-clamp-1" title="{{ $department->name }}">
                    <a href="{{ route('services', ['department' => $department->id]) }}">
                        {{ $department->name }}
                    </a>
                </h3>

                {{-- Trưởng khoa (Nếu có relation user) --}}
                <p class="text-xs text-gray-500 mb-4 flex items-center">
                    <i class="fas fa-user-tie mr-1 text-blue-400"></i>
                    Trưởng khoa: <span class="font-semibold text-gray-700 ml-1">{{ $department->user->name ?? 'Đang cập nhật' }}</span>
                </p>

                {{-- Mô tả --}}
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 h-10 flex-grow">
                    {{ Str::limit($department->description ?? 'Chuyên khoa hàng đầu với trang thiết bị hiện đại và đội ngũ tận tâm.', 80) }}
                </p>

                {{-- Phí khám --}}
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-dashed border-gray-200">
                    <span class="text-sm text-gray-500">Phí khám cơ bản:</span>
                    <span class="text-lg font-bold text-red-600">
                        {{ $department->fee > 0 ? number_format($department->fee, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                    </span>
                </div>

                {{-- 3. CÁC NÚT HÀNH ĐỘNG --}}
                <div class="mt-auto grid grid-cols-2 gap-3">
                    <a href="{{ route('services', ['department' => $department->id]) }}" 
                       class="py-2.5 px-3 bg-gray-50 text-gray-700 text-sm font-bold rounded-lg text-center hover:bg-gray-200 transition flex items-center justify-center gap-2 group/btn">
                        Chi tiết <i class="fas fa-arrow-right text-xs transition-transform group-hover/btn:translate-x-1"></i>
                    </a>
                    
                    <a href="{{ route('schedule', ['department_id' => $department->id]) }}" 
                       class="py-2.5 px-3 bg-blue-600 text-white text-sm font-bold rounded-lg text-center hover:bg-blue-700 transition shadow-md shadow-blue-200 flex items-center justify-center gap-2 transform active:scale-95">
                        <i class="far fa-calendar-check"></i> Đặt lịch
                    </a>
                </div>
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