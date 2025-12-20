@extends('site.master')

@section('title','Trang chủ')
@section('body')
    <!-- Hero Section (Clean & Modern) -->
    <section class="relative h-[600px] flex items-center bg-cover bg-center overflow-hidden group">
        <!-- Background Image with Parallax Effect -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80')] bg-cover bg-center transition-transform duration-[10s] group-hover:scale-105"></div>
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-transparent z-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full">
            <div class="max-w-2xl animate-fade-in-up">
                <div class="inline-block px-4 py-1.5 rounded-full bg-primary/20 border border-primary/30 text-sky-300 text-sm font-semibold mb-6 backdrop-blur-sm">
                    <i class="fas fa-shield-alt mr-2"></i> Hệ thống y tế đạt chuẩn quốc tế
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight tracking-tight">
                    Chăm sóc sức khỏe <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-500">Toàn diện & Tận tâm</span>
                </h1>
                <p class="text-lg text-slate-300 mb-8 leading-relaxed max-w-lg">
                    Trải nghiệm đặt lịch khám online tiện lợi, hồ sơ bệnh án điện tử bảo mật và đội ngũ bác sĩ chuyên khoa hàng đầu luôn sẵn sàng hỗ trợ bạn.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('schedule') }}" class="flex items-center justify-center py-4 px-8 bg-primary text-white font-bold rounded-full shadow-lg shadow-sky-500/30 hover:bg-sky-600 transition-all duration-300 transform hover:-translate-y-1">
                        Đặt lịch khám ngay <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center justify-center py-4 px-8 bg-white/10 backdrop-blur-md text-white border border-white/20 font-bold rounded-full hover:bg-white hover:text-slate-900 transition-all duration-300">
                        <i class="fas fa-phone-alt mr-2"></i> Tư vấn miễn phí
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Decoration Bubbles -->
        <div class="absolute bottom-10 right-10 z-20 hidden lg:block animate-bounce duration-[3000ms]">
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-white flex items-center gap-3 shadow-lg max-w-xs">
                <div class="bg-green-500 rounded-full p-2"><i class="fas fa-user-md"></i></div>
                <div>
                    <p class="font-bold text-sm">200+ Bác sĩ</p>
                    <p class="text-xs opacity-70">Luôn sẵn sàng hỗ trợ</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services / Departments Grid -->
    <section class="py-20 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-4">Chuyên khoa & Dịch vụ</h2>
                <div class="h-1 w-20 bg-primary mx-auto rounded-full mb-4"></div>
                <p class="text-slate-500 text-lg">Đa dạng các chuyên khoa với trang thiết bị hiện đại, đáp ứng mọi nhu cầu thăm khám và điều trị của bệnh nhân.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($departments as $department)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col h-full hover:-translate-y-1">
                        {{-- Image Area --}}
                        <div class="relative h-48 overflow-hidden">
                            <a href="{{ route('services', ['department' => $department->id]) }}">
                                <img src="{{ $department->image ? asset('storage/' . $department->image) : asset('images/default-department.jpg') }}" 
                                     alt="{{ $department->name }}" 
                                     class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            </a>
                            <div class="absolute top-3 right-3">
                                @if($department->status === 'active')
                                    <span class="bg-white/90 backdrop-blur text-green-600 text-[10px] font-bold px-2 py-1 rounded-full shadow-sm flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-full">Tạm ngưng</span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-primary transition line-clamp-1">
                                <a href="{{ route('services', ['department' => $department->id]) }}">{{ $department->name }}</a>
                            </h3>
                            
                            <div class="flex items-center text-xs text-slate-500 mb-3 bg-slate-50 p-2 rounded-lg">
                                <i class="fas fa-user-tie text-primary mr-2"></i>
                                <span class="truncate">Trưởng khoa: <strong class="text-slate-700">{{ $department->user->name ?? 'Đang cập nhật' }}</strong></span>
                            </div>

                            <p class="text-slate-500 text-sm mb-4 line-clamp-2 h-10">
                                {{ Str::limit($department->description ?? 'Chuyên khoa hàng đầu...', 80) }}
                            </p>

                            <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-4">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase">Phí khám</p>
                                    <p class="text-base font-bold text-red-500 font-mono">{{ $department->fee > 0 ? number_format($department->fee, 0, ',', '.') . 'đ' : 'Liên hệ' }}</p>
                                </div>
                                <a href="{{ route('schedule', ['department_id' => $department->id]) }}" 
                                   class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-primary hover:text-white transition shadow-sm group-hover:shadow-md" title="Đặt lịch ngay">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                 <a href="{{ route('services') }}" class="inline-flex items-center text-primary font-semibold hover:underline">
                    Xem tất cả dịch vụ <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- News & Blog Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Cẩm nang sức khỏe</h2>
                    <p class="text-slate-500">Cập nhật kiến thức y khoa và tin tức mới nhất từ chuyên gia.</p>
                </div>
                <a href="#" class="px-6 py-2 border border-slate-200 rounded-full text-slate-600 hover:border-primary hover:text-primary transition text-sm font-medium">
                    Xem tất cả bài viết
                </a>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main News Column (Left) --}}
                <div class="lg:col-span-2 space-y-8">
                    @foreach($latestPosts as $post)
                        <div class="flex flex-col md:flex-row gap-6 group">
                            <div class="w-full md:w-56 h-40 flex-shrink-0 overflow-hidden rounded-2xl relative">
                                <a href="{{ route('site.postshow', $post->id) }}">
                                    <img src="{{ $post->image ? asset('storage/'.$post->image) : 'https://placehold.co/600x400' }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                </a>
                            </div>
                            <div class="flex-1 py-2">
                                <div class="flex items-center gap-2 text-xs font-semibold text-primary mb-2">
                                    <span class="bg-blue-50 px-2 py-0.5 rounded text-blue-600">Tin tức</span>
                                    <span class="text-slate-400">&bull;</span>
                                    <span class="text-slate-400">{{ $post->created_at->format('d/m/Y') }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2 leading-tight group-hover:text-primary transition">
                                    <a href="{{ route('site.postshow', $post->id) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="text-slate-500 text-sm line-clamp-2 mb-3">{{ $post->description }}</p>
                                <a href="{{ route('site.postshow', $post->id) }}" class="text-sm font-semibold text-slate-600 hover:text-primary inline-flex items-center">
                                    Đọc tiếp <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Featured Sidebar (Right) --}}
                <div class="lg:col-span-1">
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 sticky top-24">
                        <div class="flex items-center gap-2 mb-6">
                            <i class="fas fa-star text-yellow-400"></i>
                            <h3 class="text-lg font-bold text-slate-800">Nổi bật tuần này</h3>
                        </div>
                        
                        <div class="space-y-5">
                            @foreach($featuredPosts as $index => $post)
                                <div class="group flex gap-4 items-start">
                                    <span class="text-2xl font-black text-slate-200 group-hover:text-primary/20 transition-colors leading-none">0{{ $index + 1 }}</span>
                                    <div>
                                        <h4 class="font-bold text-slate-700 text-sm leading-snug group-hover:text-primary transition mb-1">
                                            <a href="{{ route('site.postshow', $post->id) }}">{{ $post->title }}</a>
                                        </h4>
                                        <p class="text-xs text-slate-400 flex items-center gap-2">
                                            <span><i class="far fa-eye mr-1"></i> {{ $post->views }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-primary py-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-10 opacity-10">
            <i class="fas fa-hospital-alt text-9xl text-white"></i>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/20">
                <div class="px-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">50k+</div>
                    <div class="text-blue-100 text-sm font-medium">Bệnh nhân tin tưởng</div>
                </div>
                <div class="px-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">200+</div>
                    <div class="text-blue-100 text-sm font-medium">Bác sĩ chuyên khoa</div>
                </div>
                <div class="px-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">15+</div>
                    <div class="text-blue-100 text-sm font-medium">Chuyên khoa</div>
                </div>
                <div class="px-4">
                    <div class="text-4xl lg:text-5xl font-extrabold text-white mb-2">24/7</div>
                    <div class="text-blue-100 text-sm font-medium">Hỗ trợ khẩn cấp</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-6">Sức khỏe của bạn là ưu tiên hàng đầu</h2>
            <p class="text-xl text-slate-500 mb-10 max-w-2xl mx-auto">Đăng ký tài khoản ngay hôm nay để quản lý hồ sơ sức khỏe cho cả gia đình một cách thông minh và tiện lợi nhất.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="py-4 px-10 bg-slate-900 text-white font-bold rounded-full shadow-xl hover:bg-slate-800 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-user-plus mr-2"></i> Tạo tài khoản
                </a>
            </div>
        </div>
    </section>
@endsection