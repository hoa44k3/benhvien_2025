<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>SmartHospital - Hệ thống y tế thông minh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9', // Sky 500
                        secondary: '#6366f1', // Indigo 500
                        dark: '#0f172a', // Slate 900
                        accent: '#f43f5e', // Rose 500
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(14, 165, 233, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animations */
        .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-700 antialiased flex flex-col min-h-screen selection:bg-primary selection:text-white">

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 w-full backdrop-blur-xl bg-white/90 border-b border-slate-200/60 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                {{-- LOGO --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-primary to-blue-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-slate-800 leading-none">SmartHospital</span>
                        <span class="text-[10px] font-medium text-slate-500 uppercase tracking-widest">Medical System</span>
                    </div>
                </a>

                {{-- DESKTOP NAVIGATION --}}
                <nav class="hidden lg:flex items-center gap-1 bg-slate-100/50 p-1.5 rounded-full border border-slate-200/60">
                    @foreach([
                        ['route' => 'home', 'label' => 'Trang chủ'],
                        ['route' => 'services', 'label' => 'Dịch vụ'],
                        ['route' => 'schedule', 'label' => 'Đặt lịch'],
                        ['route' => 'medical_records', 'label' => 'Hồ sơ'],
                        ['route' => 'contact', 'label' => 'Liên hệ']
                    ] as $item)
                        <a href="{{ route($item['route']) }}" 
                           class="px-5 py-2 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs($item['route']) ? 'bg-white text-primary shadow-sm ring-1 ring-slate-200' : 'text-slate-600 hover:text-primary hover:bg-slate-200/50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                {{-- RIGHT ACTIONS --}}
                <div class="hidden md:flex items-center gap-4">
                    {{-- Search Icon Trigger --}}
                    <div class="relative group">
                        <button class="w-10 h-10 rounded-full bg-slate-50 text-slate-500 hover:text-primary hover:bg-blue-50 transition flex items-center justify-center">
                            <i class="fas fa-search"></i>
                        </button>
                        {{-- Dropdown Search --}}
                        <div class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-100 p-3 hidden group-hover:block animate-fade-in-up origin-top-right">
                            <form action="{{ route('search') }}" method="GET">
                                <input type="text" name="keyword" placeholder="Tìm bác sĩ, chuyên khoa..." 
                                       class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm transition">
                            </form>
                        </div>
                    </div>

                    @guest
                        <div class="h-6 w-px bg-slate-200 mx-2"></div>
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-primary transition">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-primary hover:bg-sky-600 text-white text-sm font-semibold rounded-full shadow-lg shadow-sky-200 hover:shadow-sky-300 transform hover:-translate-y-0.5 transition-all duration-300">
                            Đăng ký ngay
                        </a>
                    @endguest

                    @auth
                        <div class="relative group z-50">
                            <button class="flex items-center gap-3 focus:outline-none pl-2 border-l border-slate-200">
                                <div class="text-right hidden xl:block">
                                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 uppercase">Thành viên</p>
                                </div>
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0ea5e9&color=fff" class="w-10 h-10 rounded-full border-2 border-white shadow-md">
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                                </div>
                            </button>
                            
                            {{-- User Dropdown --}}
                            <div class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden hidden group-hover:block animate-fade-in-up origin-top-right">
                                <div class="bg-slate-50 px-4 py-3 border-b border-slate-100">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tài khoản của bạn</p>
                                </div>
                                <div class="p-1">
                                    <a href="{{ route('medical_records') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary rounded-xl transition">
                                        <i class="fas fa-file-medical w-6 opacity-70"></i> Hồ sơ sức khỏe
                                    </a>
                                    <a href="{{ route('payment') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary rounded-xl transition">
                                        <i class="fas fa-credit-card w-6 opacity-70"></i> Lịch sử thanh toán
                                    </a>
                                </div>
                                <div class="border-t border-slate-100 p-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition font-medium">
                                            <i class="fas fa-sign-out-alt w-6 opacity-70"></i> Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                {{-- MOBILE MENU BUTTON --}}
                <button class="lg:hidden text-slate-600 hover:text-primary transition text-2xl" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        {{-- MOBILE MENU DRAWER --}}
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 absolute w-full left-0 top-full shadow-xl">
            <div class="p-4 space-y-2">
                @foreach([
                    ['route' => 'home', 'label' => 'Trang chủ', 'icon' => 'fa-home'],
                    ['route' => 'services', 'label' => 'Dịch vụ', 'icon' => 'fa-stethoscope'],
                    ['route' => 'schedule', 'label' => 'Đặt lịch khám', 'icon' => 'fa-calendar-check'],
                    ['route' => 'medical_records', 'label' => 'Hồ sơ sức khỏe', 'icon' => 'fa-file-medical'],
                    ['route' => 'contact', 'label' => 'Liên hệ', 'icon' => 'fa-envelope']
                ] as $item)
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-primary transition font-medium">
                        <i class="fas {{ $item['icon'] }} w-5 opacity-70"></i> {{ $item['label'] }}
                    </a>
                @endforeach
                
                <div class="border-t border-slate-100 my-2 pt-2">
                     @guest
                        <a href="{{ route('login') }}" class="block w-full text-center py-2.5 text-slate-600 font-semibold mb-2">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="block w-full text-center py-2.5 bg-primary text-white rounded-lg font-bold">Đăng ký ngay</a>
                    @endguest
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center py-2.5 text-red-500 font-semibold bg-red-50 rounded-lg">Đăng xuất</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('body')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-8 border-t-4 border-primary">
         <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
             <div class="space-y-4">
                 <div class="flex items-center gap-2 text-white mb-4">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white text-sm">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <span class="text-xl font-bold">SmartHospital</span>
                 </div>
                 <p class="text-sm leading-relaxed text-slate-400">Hệ thống quản lý bệnh viện thông minh, mang lại trải nghiệm y tế tốt nhất cho mọi nhà. Kết nối bệnh nhân và bác sĩ một cách dễ dàng.</p>
                 <div class="flex gap-3 pt-2">
                     <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition"><i class="fab fa-youtube"></i></a>
                 </div>
             </div>
             
             <div>
                 <h4 class="text-white font-bold text-lg mb-6 relative inline-block">Liên kết nhanh <span class="absolute -bottom-2 left-0 w-10 h-1 bg-primary rounded-full"></span></h4>
                 <ul class="space-y-3 text-sm">
                     <li><a href="{{ route('schedule') }}" class="hover:text-primary transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-primary"></i> Đặt lịch khám</a></li>
                     <li><a href="{{ route('services') }}" class="hover:text-primary transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-primary"></i> Dịch vụ y tế</a></li>
                     <li><a href="{{ route('contact') }}" class="hover:text-primary transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-primary"></i> Đội ngũ bác sĩ</a></li>
                     <li><a href="{{ route('contact') }}" class="hover:text-primary transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 text-primary"></i> Câu hỏi thường gặp</a></li>
                 </ul>
             </div>

             <div>
                 <h4 class="text-white font-bold text-lg mb-6 relative inline-block">Thông tin liên hệ <span class="absolute -bottom-2 left-0 w-10 h-1 bg-primary rounded-full"></span></h4>
                 <ul class="space-y-4 text-sm">
                     <li class="flex items-start gap-3">
                         <div class="w-8 h-8 rounded bg-slate-800 flex-shrink-0 flex items-center justify-center text-primary"><i class="fas fa-map-marker-alt"></i></div>
                         <span class="mt-1">123 Đường Nguyễn Văn Cừ, TP. Vinh, Nghệ An</span>
                     </li>
                     <li class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded bg-slate-800 flex-shrink-0 flex items-center justify-center text-primary"><i class="fas fa-phone"></i></div>
                         <span>Hotline: <strong class="text-white">1900 1234</strong></span>
                     </li>
                     <li class="flex items-center gap-3">
                         <div class="w-8 h-8 rounded bg-slate-800 flex-shrink-0 flex items-center justify-center text-primary"><i class="fas fa-envelope"></i></div>
                         <span>support@smarthospital.vn</span>
                     </li>
                 </ul>
             </div>

             <div>
                 <h4 class="text-white font-bold text-lg mb-6 relative inline-block">Tải ứng dụng <span class="absolute -bottom-2 left-0 w-10 h-1 bg-primary rounded-full"></span></h4>
                 <p class="text-xs text-slate-400 mb-4">Trải nghiệm đặt lịch nhanh hơn trên di động</p>
                 <div class="grid grid-cols-2 gap-2">
                     <button class="bg-slate-800 hover:bg-slate-700 p-2 rounded-lg transition flex items-center justify-center gap-2 border border-slate-700">
                         <i class="fab fa-apple text-xl text-white"></i> 
                         <div class="text-left"><span class="block text-[8px] uppercase">Download on</span><span class="block text-xs font-bold text-white">App Store</span></div>
                     </button>
                     <button class="bg-slate-800 hover:bg-slate-700 p-2 rounded-lg transition flex items-center justify-center gap-2 border border-slate-700">
                         <i class="fab fa-google-play text-xl text-white"></i>
                         <div class="text-left"><span class="block text-[8px] uppercase">Get it on</span><span class="block text-xs font-bold text-white">Google Play</span></div>
                     </button>
                 </div>
             </div>
         </div>
         <div class="border-t border-slate-800 pt-8 text-center">
             <p class="text-sm text-slate-500">&copy; 2025 SmartHospital System. All rights reserved.</p>
         </div>
    </footer>

    {{-- CHATBOT AI (Refined UI) --}}
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans">
        
        {{-- Cửa sổ Chat --}}
        <div id="chatWindow" class="w-[360px] bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden mb-4 hidden transform transition-all duration-300 origin-bottom-right scale-95 opacity-0 flex flex-col">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-primary p-4 flex justify-between items-center text-white shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/40">
                        <i class="fas fa-robot text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Trợ lý SmartHospital</h4>
                        <span class="text-[11px] text-blue-100 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Online 24/7
                        </span>
                    </div>
                </div>
                <button onclick="toggleChat()" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-full transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Nội dung Chat --}}
            <div id="chatMessages" class="h-80 p-4 overflow-y-auto bg-slate-50 space-y-4 text-sm scroll-smooth">
                <div class="flex items-start gap-2.5">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary flex-shrink-0 mt-1">
                        <i class="fas fa-robot text-xs"></i>
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 text-slate-700 max-w-[85%]">
                        <p>Xin chào! Em là trợ lý AI. Em có thể giúp gì cho anh/chị ạ? 👋</p>
                    </div>
                </div>
                
                <div class="pl-11 flex flex-wrap gap-2">
                    <button onclick="fillAndSend('Làm sao để đặt lịch khám?')" class="text-xs bg-white border border-slate-200 px-3 py-1.5 rounded-full hover:bg-primary hover:text-white hover:border-primary transition text-slate-600">📅 Đặt lịch khám</button>
                    <button onclick="fillAndSend('Chi phí khám bệnh thế nào?')" class="text-xs bg-white border border-slate-200 px-3 py-1.5 rounded-full hover:bg-primary hover:text-white hover:border-primary transition text-slate-600">💰 Chi phí</button>
                    <button onclick="fillAndSend('Bệnh viện ở đâu?')" class="text-xs bg-white border border-slate-200 px-3 py-1.5 rounded-full hover:bg-primary hover:text-white hover:border-primary transition text-slate-600">📍 Địa chỉ</button>
                </div>
            </div>

            {{-- Input --}}
            <div class="p-3 bg-white border-t border-slate-100">
                <form id="chatForm" onsubmit="sendMessage(event)" class="relative">
                    <input type="text" id="userMessage" 
                           class="w-full bg-slate-100 border-none rounded-full pl-4 pr-12 py-3 text-sm focus:ring-2 focus:ring-primary/50 focus:bg-white transition" 
                           placeholder="Nhập câu hỏi..." autocomplete="off">
                    <button type="submit" id="sendBtn" class="absolute right-1.5 top-1.5 w-9 h-9 bg-primary text-white rounded-full flex items-center justify-center hover:bg-blue-600 transition shadow-sm">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Nút Mở Chat --}}
        <button onclick="toggleChat()" class="group flex items-center gap-2 focus:outline-none">
            <span class="bg-white text-slate-800 px-3 py-1.5 rounded-lg shadow-lg text-xs font-bold opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-x-2 pointer-events-none border border-slate-100">Chat ngay!</span>
            <div class="w-14 h-14 bg-gradient-to-tr from-primary to-blue-600 text-white rounded-full shadow-lg hover:shadow-primary/50 hover:scale-110 transition-all duration-300 flex items-center justify-center text-2xl relative z-10">
                <i class="fas fa-comment-dots"></i>
                <span class="absolute top-0 right-0 flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            </div>
        </button>
    </div>

    {{-- INCOMING CALL MODAL (Styled Better) --}}
    <div id="incoming-call-modal" class="fixed bottom-6 left-6 z-50 hidden animate-fade-in-up">
        <div class="bg-white rounded-2xl shadow-2xl p-0 w-80 overflow-hidden border border-slate-100">
            {{-- Header --}}
            <div class="bg-green-500 p-4 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 animate-pulse"></div>
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-lg relative z-10">
                    <i class="fas fa-user-md text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-white font-bold text-lg relative z-10">Cuộc gọi đến!</h3>
                <p id="caller-name" class="text-green-100 text-xs relative z-10">Bác sĩ đang liên hệ...</p>
            </div>
            
            {{-- Body --}}
            <div class="p-5">
                <div class="flex gap-3">
                    <button id="ignore-btn" onclick="closeCallModal()" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                        <i class="fas fa-times mr-1"></i> Bỏ qua
                    </button>
                    <a id="join-btn" href="#" class="flex-1 py-3 bg-green-500 text-white text-center rounded-xl font-bold hover:bg-green-600 shadow-lg shadow-green-200 transition animate-pulse">
                        <i class="fas fa-video mr-1"></i> Trả lời
                    </a>
                </div>
            </div>
            <audio id="ringtone" src="https://assets.mixkit.co/sfx/preview/mixkit-waiting-ringtone-1354.mp3" loop></audio>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // Hàm bật tắt Chat
        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                setTimeout(() => {
                    chatWindow.classList.remove('opacity-0', 'scale-95');
                    chatWindow.classList.add('opacity-100', 'scale-100');
                    document.getElementById('userMessage').focus();
                }, 10);
            } else {
                chatWindow.classList.remove('opacity-100', 'scale-100');
                chatWindow.classList.add('opacity-0', 'scale-95');
                setTimeout(() => { chatWindow.classList.add('hidden'); }, 300);
            }
        }

        // Gợi ý nhanh
        function fillAndSend(text) {
            document.getElementById('userMessage').value = text;
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }

        // Gửi tin nhắn
        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('userMessage');
            const btn = document.getElementById('sendBtn');
            const message = input.value.trim();
            const chatBox = document.getElementById('chatMessages');

            if (!message) return;

            // 1. Hiện tin nhắn người dùng
            chatBox.innerHTML += `
                <div class="flex justify-end mb-4 animate-fade-in-up">
                    <div class="bg-primary text-white px-4 py-2 rounded-2xl rounded-tr-none shadow-md max-w-[85%] text-sm">
                        ${message}
                    </div>
                </div>`;
            
            input.value = '';
            input.disabled = true; // Khóa ô nhập
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>'; // Icon loading
            chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });

            // 2. Hiện trạng thái "Đang gõ..."
            const loadingId = 'loading-' + Date.now();
            chatBox.innerHTML += `
                <div id="${loadingId}" class="flex items-start gap-2 mb-4 animate-fade-in-up">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary flex-shrink-0">
                        <i class="fas fa-robot text-xs"></i>
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100">
                        <div class="flex gap-1">
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce delay-100"></span>
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce delay-200"></span>
                        </div>
                    </div>
                </div>`;
            chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
                        try {
                const response = await fetch("{{ route('chatbot.ask') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: message })
                });
                const data = await response.json();

                document.getElementById(loadingId).remove();
                // 3. HIỆN CÂU TRẢ LỜI TỪ AI (CÓ HỖ TRỢ ẢNH)
                chatBox.innerHTML += `

                    <div class="flex items-start gap-2 mb-4 animate-fade-in-up">

                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary flex-shrink-0 mt-1">

                            <i class="fas fa-robot text-xs"></i>

                        </div>

                        <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[90%] text-slate-700 text-sm leading-relaxed overflow-hidden">

                            ${data.reply}

                        </div>

                    </div>`;
            } catch (error) {
                document.getElementById(loadingId).remove();
                chatBox.innerHTML += `<div class="text-center text-xs text-red-400 mb-4">Lỗi kết nối máy chủ!</div>`;
            } finally {
                input.disabled = false;
                input.focus();
                btn.innerHTML = '<i class="fas fa-paper-plane text-xs"></i>';
                chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
            }
        }

       document.addEventListener("DOMContentLoaded", function() {
            @if(Auth::check())
                const modal = document.getElementById('incoming-call-modal');
                const callerName = document.getElementById('caller-name');
                const joinBtn = document.getElementById('join-btn');
                const ringtone = document.getElementById('ringtone');
                const ignoreBtn = document.getElementById('ignore-btn');
                
                let isModalOpen = false;
                let ignoredCallId = null; 

                const currentUrl = window.location.href;
                if (currentUrl.includes('join-call') || currentUrl.includes('video-call')) return;

                setInterval(() => {
                    fetch("{{ route('patient.checkCall') }}")
                        .then(response => response.json())
                        .then(data => {
                            if (data.incoming) {
                                if (data.appointment_id == ignoredCallId) return;

                                if (!isModalOpen) {
                                    modal.classList.remove('hidden');
                                    callerName.textContent = "BS. " + data.doctor_name + " đang gọi...";
                                    joinBtn.href = data.join_url;
                                    ignoreBtn.setAttribute('data-id', data.appointment_id);

                                    try { ringtone.play().catch(e => console.log(e)); } catch(e) {}
                                    isModalOpen = true;
                                }
                            } else {
                                if (isModalOpen) closeCallModal();
                                ignoredCallId = null;
                            }
                        })
                        .catch(err => console.error("Polling error:", err));
                }, 5000);

                window.closeCallModal = function() {
                    modal.classList.add('hidden');
                    ringtone.pause();
                    ringtone.currentTime = 0;
                    isModalOpen = false;
                    const currentId = ignoreBtn.getAttribute('data-id');
                    if (currentId) ignoredCallId = currentId;
                }
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>