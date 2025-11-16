 @extends('site.master')

@section('title','Dịch vụ')
@section('body')
<!-- 1. Service Hero Section (Banner và Category Filter) -->
    
    <section class="service-hero-section bg-blue-600 text-white py-16 md:py-24">
        <div class="container mx-auto w-[90%] max-w-7xl text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Dịch vụ y tế chuyên nghiệp</h1>
            <p class="text-lg md:text-xl opacity-90 mb-10 max-w-3xl mx-auto">
                Đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại, cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất
            </p>
            
            <!-- Category Filter Bar -->
            <div id="service-categories" class="service-categories flex flex-nowrap overflow-x-auto gap-3 p-3 bg-white rounded-xl shadow-2xl max-w-full md:max-w-5xl mx-auto">
                <a href="{{ route('services', ['category' => 'all']) }}" 
                class="category-item {{ request('category') == 'all' ? 'active bg-blue-600 text-white' : 'text-gray-700' }} flex-shrink-0 font-semibold px-4 py-2 rounded-xl transition duration-200 cursor-pointer shadow-lg shadow-blue-500/50 hover:bg-blue-700">
                    <i class="fas fa-th-list mr-2"></i> Tất cả dịch vụ
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('services', ['category' => $category->id]) }}" 
                    class="category-item {{ request('category') == $category->id ? 'active bg-blue-600 text-white' : 'text-gray-700' }} flex-shrink-0 font-medium px-4 py-2 rounded-xl hover:bg-gray-100 transition duration-200 cursor-pointer">
                        <i class="{{ $category->icon ?? 'fas fa-stethoscope' }} mr-2"></i> {{ $category->name }}
                    </a>
                @endforeach
            </div>

        </div>
    </section>


    <section class="service-list-section py-12 md:py-20 bg-gray-50">
    <div class="container mx-auto w-[90%] max-w-7xl">
        <h2 class="section-title text-3xl font-bold text-center mb-2 text-gray-800">Danh mục dịch vụ</h2>
        <p class="section-subtitle text-lg text-center text-gray-500 mb-12">
            Chọn dịch vụ phù hợp với nhu cầu của bạn
        </p>

        <div class="service-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="service-card bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                <div class="service-card-top p-6">
                    <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.jpg') }}" 
                         alt="{{ $service->name }}" 
                         class="w-full h-40 object-cover rounded-xl mb-4 shadow-md">
                    <div class="service-card-info">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $service->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ Str::limit($service->description, 80) }}</p>

                        <div class="service-meta flex flex-wrap items-center justify-between text-sm font-semibold border-t border-gray-100 pt-3">
                            <span class="text-blue-600">
                                @if($service->fee == 0 || $service->fee === null)
                                    Liên hệ
                                @else
                                    {{ number_format($service->fee, 0, ',', '.') }} VNĐ
                                @endif
                            </span>

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $service->status ? 'Có sẵn' : 'Tạm ngưng' }}
                            </span>

                            <span class="text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                @if($service->duration == 0)
                                    Liên tục
                                @else
                                    {{ $service->duration }} phút
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="service-card-details bg-blue-50 p-6">
                    <h4 class="text-base font-semibold text-gray-700 mb-3">Thông tin:</h4>
                    <p class="text-sm text-gray-600">{{ Str::limit(strip_tags($service->content), 100) }}</p>
                </div>

                <div class="service-card-actions p-6 flex gap-3">
                    <a href="{{ route('schedule') }}" class="flex-1 text-center bg-blue-600 text-white font-semibold px-4 py-3 rounded-xl hover:bg-blue-700 transition duration-300 shadow-lg shadow-blue-400/50">
                        <i class="far fa-calendar-alt mr-2"></i> Đặt lịch
                    </a>
                    <a href="{{ route('services.show', $service) }}" class="flex-1 text-center border border-gray-300 text-gray-700 font-semibold px-4 py-3 rounded-xl hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-info-circle mr-2"></i> Chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
    <!-- 3. Specialty Section (Chuyên Khoa) -->
    {{-- <section class="specialty-section py-12 md:py-20 bg-white">
        <div class="container mx-auto w-[90%] max-w-7xl">
            <h2 class="section-title text-3xl font-bold text-center mb-2 text-gray-800">Chuyên khoa nổi bật</h2>
            <p class="section-subtitle text-lg text-center text-gray-500 mb-12">Đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm</p>

            <div class="specialty-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                
                <!-- Specialty Card 1: Tim mạch -->
                <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                    <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl"><i class="fas fa-heartbeat"></i></div>
                    <h3 class="text-lg font-bold mb-1 text-gray-800">Tim mạch</h3>
                    <p class="text-sm text-gray-500 mb-3">Chẩn đoán và điều trị các bệnh lý tim mạch</p>
                    <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                        <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> 8 bác sĩ</span>
                        <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> 12 dịch vụ</span>
                    </div>
                    <a href="#" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a>
                </div>

                <!-- Specialty Card 2: Nhi khoa -->
                <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                    <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl"><i class="fas fa-baby"></i></div>
                    <h3 class="text-lg font-bold mb-1 text-gray-800">Nhi khoa</h3>
                    <p class="text-sm text-gray-500 mb-3">Chăm sóc sức khỏe trẻ em toàn diện</p>
                    <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                        <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> 6 bác sĩ</span>
                        <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> 10 dịch vụ</span>
                    </div>
                    <a href="#" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a>
                </div>

                <!-- Specialty Card 3: Chấn thương chỉnh hình -->
                <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                    <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl"><i class="fas fa-bone"></i></div>
                    <h3 class="text-lg font-bold mb-1 text-gray-800">Chấn thương chỉnh hình</h3>
                    <p class="text-sm text-gray-500 mb-3">Điều trị chấn thương và bệnh lý xương khớp</p>
                    <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                        <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> 5 bác sĩ</span>
                        <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> 8 dịch vụ</span>
                    </div>
                    <a href="#" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a>
                </div>

                <!-- Specialty Card 4: Da liễu -->
                <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                    <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl"><i class="fas fa-allergies"></i></div>
                    <h3 class="text-lg font-bold mb-1 text-gray-800">Da liễu</h3>
                    <p class="text-sm text-gray-500 mb-3">Chẩn đoán và điều trị các bệnh về da</p>
                    <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                        <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> 4 bác sĩ</span>
                        <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> 6 dịch vụ</span>
                    </div>
                    <a href="#" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a>
                </div>
                
                <!-- Specialty Card 5: Thần kinh -->
                <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                    <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl"><i class="fas fa-brain"></i></div>
                    <h3 class="text-lg font-bold mb-1 text-gray-800">Thần kinh</h3>
                    <p class="text-sm text-gray-500 mb-3">Điều trị các bệnh lý thần kinh</p>
                    <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                        <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> 3 bác sĩ</span>
                        <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> 7 dịch vụ</span>
                    </div>
                    <a href="#" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a>
                </div>
                
                <!-- Specialty Card 6: Mắt -->
                <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                    <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl"><i class="fas fa-eye"></i></div>
                    <h3 class="text-lg font-bold mb-1 text-gray-800">Mắt</h3>
                    <p class="text-sm text-gray-500 mb-3">Chăm sóc và điều trị các bệnh về mắt</p>
                    <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                        <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> 4 bác sĩ</span>
                        <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> 5 dịch vụ</span>
                    </div>
                    <a href="#" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a>
                </div>

            </div>
        </div>
    </section> --}}
    <section class="specialty-section py-12 md:py-20 bg-white">
    <div class="container mx-auto w-[90%] max-w-7xl">
        <h2 class="section-title text-3xl font-bold text-center mb-2 text-gray-800">Chuyên khoa nổi bật</h2>
        <p class="section-subtitle text-lg text-center text-gray-500 mb-12">Đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm</p>

        <div class="specialty-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($departments as $department)
            <div class="specialty-card p-6 bg-gray-50 rounded-xl border border-gray-200 text-center hover:bg-blue-50 hover:border-blue-300 transition duration-300 shadow-lg">
                <div class="specialty-icon w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-2xl">
                    <i class="{{ $department->icon ?? 'fas fa-stethoscope' }}"></i>
                </div>
                <h3 class="text-lg font-bold mb-1 text-gray-800">{{ $department->name }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ Str::limit($department->description ?? 'Không có mô tả', 50) }}</p>
                <div class="specialty-stats text-xs text-gray-600 space-y-1 mb-4">
                    <span class="block"><i class="fas fa-user-md mr-1 text-blue-500"></i> {{ $department->num_doctors ?? 0 }} bác sĩ</span>
                    <span class="block"><i class="fas fa-book-medical mr-1 text-blue-500"></i> {{ $department->num_nurses ?? 0 }} y tá</span>
                </div>
                {{-- <a href="{{ route('departments.show', $department->id) }}" class="inline-block border border-blue-600 text-blue-600 font-medium px-4 py-2 rounded-full text-sm hover:bg-blue-600 hover:text-white transition duration-300">Xem bác sĩ</a> --}}
            </div>
            @endforeach
        </div>
    </div>
</section>


    <!-- 4. CTA Section (Kêu gọi hành động) -->
    <section class="cta-section bg-blue-700 text-white py-16">
        <div class="container mx-auto w-[90%] max-w-5xl text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-3">Cần tư vấn về dịch vụ?</h2>
            <p class="text-lg opacity-90 mb-8">Đội ngũ chuyên viên của chúng tôi sẵn sàng tư vấn và hỗ trợ bạn chọn dịch vụ phù hợp nhất</p>
            <div class="cta-input-group flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                <input type="email" placeholder="Nhập email của bạn để nhận tư vấn..." class="flex-1 px-5 py-3 rounded-xl border-2 border-white focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-800" onfocus="this.placeholder='Tư vấn miễn phí qua email...'" onblur="this.placeholder='Nhập email của bạn để nhận tư vấn...'">
                <a href="{{ route('schedule') }}" class="btn bg-white text-blue-700 font-bold px-8 py-3 rounded-xl hover:bg-gray-100 transition duration-300 shadow-xl shadow-blue-800/50 flex-shrink-0">
                    <i class="far fa-calendar-alt mr-2"></i> Đặt lịch ngay
                </a>
            </div>
        </div>
    </section>
@endsection
