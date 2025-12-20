@extends('site.master')

@section('title','Liên hệ')
@section('body')
    {{-- HEADER --}}
    <div class="relative bg-white pt-16 pb-24 overflow-hidden border-b border-slate-100">
        <div class="container mx-auto max-w-7xl px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 mb-4">Liên hệ với chúng tôi</h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">Đội ngũ hỗ trợ khách hàng luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn 24/7.</p>
        </div>
        {{-- Decor --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 opacity-40 pointer-events-none">
            <div class="absolute top-10 left-10 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-secondary/10 rounded-full blur-3xl"></div>
        </div>
    </div>

    <div class="container mx-auto max-w-7xl px-4 -mt-16 relative z-20 pb-20">
        
        {{-- INFO CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            @foreach([
                ['icon' => 'fa-map-marker-alt', 'title' => 'Địa chỉ', 'text' => '123 Đường ABC, Quận 1, TP.HCM', 'link' => '#', 'label' => 'Xem bản đồ'],
                ['icon' => 'fa-phone-alt', 'title' => 'Hotline', 'text' => '1900 1234', 'link' => 'tel:19001234', 'label' => 'Gọi ngay'],
                ['icon' => 'fa-envelope', 'title' => 'Email', 'text' => 'info@smarthospital.vn', 'link' => 'mailto:info@smarthospital.vn', 'label' => 'Gửi email'],
                ['icon' => 'fa-clock', 'title' => 'Giờ làm việc', 'text' => 'Thứ 2 - CN: 24/7', 'link' => '#', 'label' => 'Xem chi tiết'],
            ] as $item)
            <div class="bg-white p-8 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-14 h-14 bg-blue-50 text-primary rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-lg mb-1">{{ $item['title'] }}</h3>
                <p class="text-slate-500 text-sm mb-4">{{ $item['text'] }}</p>
                <a href="{{ $item['link'] }}" class="text-sm font-semibold text-primary hover:text-sky-600 hover:underline">{{ $item['label'] }}</a>
            </div>
            @endforeach
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
            
            {{-- FORM SECTION --}}
            <div class="lg:col-span-3">
                <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-primary"></i> Gửi tin nhắn hỗ trợ
                    </h2>
                    
                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                        @csrf
                        @auth
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" placeholder="Nguyễn Văn A" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-slate-50 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" placeholder="example@email.com" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-slate-50 focus:bg-white">
                                </div>
                            </div>
                        @endauth
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Số điện thoại</label>
                                <input type="tel" name="phone" placeholder="0912..." value="{{ Auth::user()->phone ?? '' }}" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-slate-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Chủ đề <span class="text-red-500">*</span></label>
                                <select name="subject" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-slate-50 focus:bg-white">
                                    <option value="">Chọn vấn đề cần hỗ trợ</option>
                                    <option value="Đặt lịch khám">Đặt lịch khám</option>
                                    <option value="Yêu cầu hỗ trợ">Yêu cầu hỗ trợ kỹ thuật</option>
                                    <option value="Góp ý">Góp ý dịch vụ</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nội dung tin nhắn <span class="text-red-500">*</span></label>
                            <textarea name="message" placeholder="Mô tả chi tiết vấn đề của bạn..." rows="5" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-slate-50 focus:bg-white"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-primary hover:bg-sky-600 text-white font-bold rounded-xl shadow-lg shadow-sky-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            Gửi yêu cầu <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>

                    {{-- SUPPORT HISTORY (Refined) --}}
                    @auth
                        @if(isset($myContacts) && $myContacts->count() > 0)
                            <div class="mt-12 pt-8 border-t border-slate-100">
                                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                    <i class="fas fa-history text-slate-400"></i> Lịch sử hỗ trợ gần đây
                                </h3>
                                
                                <div class="space-y-4">
                                    @foreach($myContacts as $contact)
                                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-slate-700">{{ $contact->subject }}</h4>
                                                @if($contact->status == 'replied')
                                                    <span class="bg-green-100 text-green-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Đã trả lời</span>
                                                @else
                                                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Đang chờ</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-slate-500 mb-3 line-clamp-2">"{{ $contact->message }}"</p>
                                            <div class="text-xs text-slate-400 flex items-center gap-2">
                                                <i class="far fa-clock"></i> {{ $contact->created_at->format('H:i d/m/Y') }}
                                            </div>

                                            @if($contact->reply_message)
                                                <div class="mt-4 bg-white p-4 rounded-lg border border-green-100 shadow-sm relative">
                                                    <div class="absolute -top-1.5 left-4 w-3 h-3 bg-white border-t border-l border-green-100 rotate-45"></div>
                                                    <p class="text-xs font-bold text-green-600 mb-1 flex items-center gap-1"><i class="fas fa-user-shield"></i> Admin phản hồi:</p>
                                                    <p class="text-sm text-slate-700">{!! nl2br(e($contact->reply_message)) !!}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($myContacts->count() >= 5)
                                    <div class="text-center mt-6">
                                        <a href="{{ route('my.contacts') }}" class="text-sm font-semibold text-primary hover:underline">Xem tất cả lịch sử</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        
            {{-- MAP & DEPARTMENTS --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-2 rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1685360986685!2d106.69670111471853!3d10.793739792312695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f36070a2529%3A0x6b44910e5b72e5a!2zVmlldGluYmV0IC0gU21hcnRIb3NwaXRhbCBzdGFuZGFyZCBzb2x1dGlvbiBhbmQgaG9zcGl0YWw!5e0!3m2!1svi!2s" 
                        class="w-full h-80 rounded-2xl" 
                        allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
                
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-slate-100">
                    <h3 class="font-bold text-lg text-slate-800 mb-5">Danh bạ khoa phòng</h3>
                    <div class="space-y-3">
                        @foreach([
                            ['name' => 'Khoa Khám bệnh', 'phone' => '(028) 1234 5679', 'time' => '6:00 - 20:00', 'color' => 'teal'],
                            ['name' => 'Khoa Cấp cứu', 'phone' => '(028) 1234 5680', 'time' => '24/7', 'color' => 'red'],
                            ['name' => 'Khoa Nội Tổng Hợp', 'phone' => '(028) 1234 5681', 'time' => '7:00 - 17:00', 'color' => 'blue'],
                            ['name' => 'Khoa Ngoại', 'phone' => '(028) 1234 5682', 'time' => '7:00 - 18:00', 'color' => 'purple'],
                        ] as $dept)
                            <div class="group p-4 rounded-xl border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-md transition cursor-default">
                                <h4 class="font-bold text-{{ $dept['color'] }}-600 mb-2">{{ $dept['name'] }}</h4>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span><i class="fas fa-phone-alt mr-1"></i> {{ $dept['phone'] }}</span>
                                    <span><i class="far fa-clock mr-1"></i> {{ $dept['time'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- FAQ SECTION --}}
    <section class="py-16 bg-slate-50 border-t border-slate-200">
        <div class="container mx-auto max-w-4xl px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Câu hỏi thường gặp</h2>
                <p class="text-slate-500">Giải đáp nhanh các thắc mắc phổ biến của bệnh nhân</p>
            </div>
            
            <div class="space-y-4">
               @foreach($faqs as $faq)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <button class="faq-btn w-full flex justify-between items-center p-5 text-left hover:bg-slate-50 transition focus:outline-none"
                                data-target="#faq-answer-{{ $faq->id }}">
                            <span class="font-semibold text-slate-700">{{ $faq->question }}</span>
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fas fa-plus text-xs icon-toggle transition-transform duration-300"></i>
                            </div>
                        </button>
                        <div id="faq-answer-{{ $faq->id }}" class="faq-content hidden border-t border-slate-100">
                            <div class="p-5 text-slate-600 bg-slate-50/50 leading-relaxed text-sm">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.faq-btn').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const content = document.querySelector(targetId);
                const icon = this.querySelector('.icon-toggle');

                content.classList.toggle('hidden');
                
                if (content.classList.contains('hidden')) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                    icon.style.transform = 'rotate(180deg)';
                }
            });
        });
    </script>
@endsection