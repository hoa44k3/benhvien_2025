@extends('site.master')

@section('title','Liên hệ')
@section('body')
    <section class="py-16 mb-8 shadow-lg" style="background-image: linear-gradient(to right, var(--primary-color), #14b8a6);">
        <div class="container mx-auto max-w-7xl px-4 text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">Liên hệ với chúng tôi</h1>
            <p class="text-lg opacity-90">Chúng tôi luôn sẵn sàng hỗ trợ và giải đáp mọi thắc mắc của bạn</p>
        </div>
    </section>

    <div class="container mx-auto max-w-7xl px-4 pb-12">
        
        {{-- Phần thông tin liên hệ (Giữ nguyên) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            {{-- ... (Các ô địa chỉ, điện thoại, email giữ nguyên) ... --}}
             <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="fas fa-map-marker-alt"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Địa chỉ</h3>
                <p class="text-gray-600 mb-2">123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>
                <a href="#" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Xem bản đồ</a>
            </div>
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="fas fa-phone-alt"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Điện thoại</h3>
                <p class="text-gray-600 mb-2 font-mono">(028) 1234 5678</p>
                <a href="tel:02812345678" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Gọi ngay</a>
            </div>
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="fas fa-envelope"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Email</h3>
                <p class="text-gray-600 mb-2 break-words">info@smarthospital.vn</p>
                <a href="mailto:info@smarthospital.vn" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Gửi email</a>
            </div>
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="far fa-clock"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Giờ làm việc</h3>
                <p class="text-gray-600 mb-2">Thứ 2 - Chủ nhật: <br class="md:hidden"> 6:00 - 22:00</p>
                <a href="#" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Xem chi tiết</a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
            
            {{-- FORM GỬI TIN NHẮN & LỊCH SỬ --}}
            <div class="form-section lg:col-span-3 bg-white p-8 rounded-xl shadow-lg border border-gray-100 h-fit">
                <h2 class="text-2xl font-bold mb-6 text-gray-700 border-l-4 border-teal-600 pl-3">Gửi tin nhắn</h2>
                
                {{-- Form nhập --}}
                <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                    @csrf
                    @auth
                        {{-- Nếu đã đăng nhập thì tự điền thông tin --}}
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required class="w-full p-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required class="w-full p-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    @endauth
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" value="{{ Auth::user()->phone ?? '' }}" class="w-full p-2 border border-gray-300 rounded-lg">
                        </div>
                        <div class="form-group">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Chủ đề <span class="text-red-500">*</span></label>
                            <select id="subject" name="subject" required class="w-full p-2 border border-gray-300 rounded-lg">
                                <option value="">Chọn chủ đề</option>
                                <option value="Đặt lịch khám">Đặt lịch khám</option>
                                <option value="Yêu cầu hỗ trợ">Yêu cầu hỗ trợ</option>
                                <option value="Góp ý">Góp ý</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="block text-sm font-medium text-gray-700">Nội dung tin nhắn <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" placeholder="Nhập nội dung tin nhắn..." maxlength="500" rows="4" required class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                    </button>
                </form>

                {{-- 🔥 PHẦN MỚI THÊM: LỊCH SỬ HỖ TRỢ --}}
                @auth
                    @if(isset($myContacts) && $myContacts->count() > 0)
                        <div class="mt-10 pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-bold mb-4 text-gray-700 flex items-center gap-2">
                                <i class="fas fa-history text-teal-600"></i> Lịch sử hỗ trợ gần đây
                            </h3>
                            
                            <div class="space-y-4">
                                @foreach($myContacts as $contact)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <span class="font-bold text-gray-800 block">{{ $contact->subject }}</span>
                                                <span class="text-xs text-gray-500">{{ $contact->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            @if($contact->status == 'replied')
                                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-bold">Đã trả lời</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-bold">Đang xử lý</span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-2 italic">"{{ Str::limit($contact->message, 80) }}"</p>
                                        
                                        {{-- Hiển thị câu trả lời nếu có --}}
                                        @if($contact->reply_message)
                                            <div class="mt-3 bg-white p-3 rounded border border-green-200 border-l-4 border-l-green-500">
                                                <p class="text-xs font-bold text-green-700 mb-1"><i class="fas fa-reply"></i> Admin phản hồi:</p>
                                                <p class="text-sm text-gray-800">{!! nl2br(e($contact->reply_message)) !!}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($myContacts->count() >= 5)
                                <div class="text-center mt-4">
                                    <a href="{{ route('my.contacts') }}" class="text-teal-600 hover:underline text-sm font-medium">Xem tất cả lịch sử</a>
                                </div>
                            @endif
                        </div>
                    @endif
                @endauth
                {{-- KẾT THÚC PHẦN LỊCH SỬ --}}

            </div>
        
            <div class="map-section lg:col-span-2 space-y-8">
                {{-- Phần bản đồ và thông tin khoa (Giữ nguyên) --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Vị trí bệnh viện</h2>
                    <div class="map-container relative pb-[56.25%] h-0 overflow-hidden rounded-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1685360986685!2d106.69670111471853!3d10.793739792312695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f36070a2529%3A0x6b44910e5b72e5a!2zVmlldGluYmV0IC0gU21hcnRIb3NwaXRhbCBzdGFuZGFyZCBzb2x1dGlvbiBhbmQgaG9zcGl0YWw!5e0!3m2!1svi!2s" 
                            class="absolute top-0 left-0 w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Liên hệ các khoa</h2>
                   <div class="space-y-3">

    <!-- Khoa Khám bệnh -->
    <div class="department-card p-3 border-l-4 border-teal-500 bg-teal-50 rounded-md">
        <h4 class="text-lg font-semibold mb-1 text-teal-800">Khoa Khám bệnh</h4>
        <p class="text-sm text-gray-600">
            <i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5679
        </p>
        <p class="text-sm text-gray-600">
            <i class="far fa-clock w-4 mr-1"></i> 6:00 - 20:00
        </p>
    </div>

    <!-- Khoa Cấp cứu -->
    <div class="department-card p-3 border-l-4 border-red-500 bg-red-50 rounded-md">
        <h4 class="text-lg font-semibold mb-1 text-red-800">Khoa Cấp cứu</h4>
        <p class="text-sm text-gray-600">
            <i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5680
        </p>
        <p class="text-sm text-gray-600">
            <i class="far fa-clock w-4 mr-1"></i> 24/7
        </p>
    </div>

    <!-- Khoa Nội Tổng Hợp -->
    <div class="department-card p-3 border-l-4 border-blue-500 bg-blue-50 rounded-md">
        <h4 class="text-lg font-semibold mb-1 text-blue-800">Khoa Nội Tổng Hợp</h4>
        <p class="text-sm text-gray-600">
            <i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5681
        </p>
        <p class="text-sm text-gray-600">
            <i class="far fa-clock w-4 mr-1"></i> 7:00 - 17:00
        </p>
    </div>

    <!-- Khoa Ngoại -->
    <div class="department-card p-3 border-l-4 border-purple-500 bg-purple-50 rounded-md">
        <h4 class="text-lg font-semibold mb-1 text-purple-800">Khoa Ngoại</h4>
        <p class="text-sm text-gray-600">
            <i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5682
        </p>
        <p class="text-sm text-gray-600">
            <i class="far fa-clock w-4 mr-1"></i> 7:00 - 18:00
        </p>
    </div>

    <!-- Khoa Tim Mạch -->
    <div class="department-card p-3 border-l-4 border-pink-500 bg-pink-50 rounded-md">
        <h4 class="text-lg font-semibold mb-1 text-pink-800">Khoa Tim Mạch</h4>
        <p class="text-sm text-gray-600">
            <i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5683
        </p>
        <p class="text-sm text-gray-600">
            <i class="far fa-clock w-4 mr-1"></i> 7:00 - 17:30
        </p>
    </div>

</div>

                </div>
            </div>
        </div>
    </div>
    
    {{-- Phần FAQ (Giữ nguyên) --}}
    <section class="py-12 bg-white mt-12 shadow-inner">
        <div class="container mx-auto max-w-7xl px-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-gray-700">Câu hỏi thường gặp</h2>
            <p class="text-center text-gray-600 mb-8">Những câu hỏi được Admin tổng hợp để giải đáp thắc mắc chung</p>
            
            <div class="faq-list max-w-3xl mx-auto space-y-4 mt-8">
               @foreach($faqs as $index => $faq)
                    <div class="faq-item border border-gray-200 rounded-lg overflow-hidden shadow-sm bg-white">
                        {{-- Tiêu đề câu hỏi --}}
                        <button class="faq-btn w-full flex justify-between items-center p-4 text-left bg-gray-50 hover:bg-gray-100 transition duration-300 focus:outline-none"
                                data-target="#faq-answer-{{ $faq->id }}">
                            <span class="font-semibold text-gray-700">{{ $faq->question }}</span>
                            <i class="fas fa-plus text-teal-600 transition-transform duration-300 icon-toggle"></i>
                        </button>
                        
                        {{-- Nội dung câu trả lời (Mặc định ẩn) --}}
                        <div id="faq-answer-{{ $faq->id }}" class="faq-content hidden border-t border-gray-100">
                            <div class="p-4 text-gray-600 bg-white leading-relaxed">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="faq-support-text text-center mt-10">
                <p class="text-lg font-medium text-gray-700">Không tìm thấy câu trả lời bạn cần?</p>
                <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="inline-flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 mt-4">
                    <i class="fas fa-paper-plane"></i> Gửi câu hỏi mới
                </a>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.faq-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    // 1. Lấy ID của phần trả lời
                    const targetId = this.getAttribute('data-target');
                    const content = document.querySelector(targetId);
                    const icon = this.querySelector('.icon-toggle');

                    // 2. Toggle hiển thị (Thêm/Bỏ class hidden)
                    content.classList.toggle('hidden');

                    // 3. Đổi icon (+ thành -)
                    if (content.classList.contains('hidden')) {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                        this.classList.remove('bg-teal-50'); // Bỏ màu nền active
                    } else {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                        this.classList.add('bg-teal-50'); // Thêm màu nền active cho đẹp
                    }
                });
            });
        });
    </script>
@endsection