<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>SmartHospital - Hệ thống quản lý bệnh viện thông minh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#3b82f6',
                        'secondary': '#6366f1',
                    },
                    fontFamily: {
                        sans: ['Inter', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Hiệu ứng Chatbox */
        .chat-container { display: none; transition: all 0.3s ease-in-out; }
        .chat-container.open { display: block; animation: slideIn 0.3s forwards; }
        @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800 leading-relaxed relative">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-primary flex-shrink-0">
                    <i class="fas fa-hospital-alt mr-2"></i> SmartHospital
                </a>

                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full relative">
                        <input type="text" name="keyword" placeholder="Tìm bác sĩ, khoa, dịch vụ..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50 text-sm transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </form>
                </div>
                
                <div class="flex items-center space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-primary">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="text-sm py-2 px-4 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 shadow-md">Đăng ký</a>
                    @endguest

                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 font-medium focus:outline-none">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-caret-down"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block border border-gray-100">
                                <a href="{{ route('medical_records') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Hồ sơ của tôi</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <nav class="hidden lg:flex justify-center space-x-8 mt-3 pt-3 border-t border-gray-100">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary font-medium text-sm">Trang chủ</a>
                <a href="{{ route('services') }}" class="text-gray-700 hover:text-primary font-medium text-sm">Dịch vụ</a>
                <a href="{{ route('schedule') }}" class="text-gray-700 hover:text-primary font-medium text-sm">Đặt lịch khám</a>
                <a href="{{ route('medical_records') }}" class="text-gray-700 hover:text-primary font-medium text-sm">Hồ sơ bệnh án</a>
                <a href="{{ route('payment') }}" class="text-gray-700 hover:text-primary font-medium text-sm">Thanh toán</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary font-medium text-sm">Liên hệ</a>
            </nav>
        </div>
    </header>

    @yield('body')

    <footer class="bg-gray-800 text-gray-300 pt-16">
         <div class="max-w-7xl mx-auto px-4 py-6 text-center">© 2024 SmartHospital</div>
    </footer>

    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        
        <div id="chatWindow" class="chat-container w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden mb-4 hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 flex justify-between items-center text-white">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-blue-600 mr-2">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Trợ lý ảo AI</h4>
                        <span class="text-xs text-blue-100 flex items-center"><span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span> Đang online</span>
                    </div>
                </div>
                <button onclick="toggleChat()" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
            </div>

            <div id="chatMessages" class="h-80 p-4 overflow-y-auto bg-gray-50 space-y-3 text-sm">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary flex-shrink-0 mr-2">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="bg-white p-3 rounded-tr-xl rounded-br-xl rounded-bl-xl shadow-sm border border-gray-100 max-w-[85%]">
                        <p class="text-gray-700">Xin chào! Tôi có thể giúp gì cho bạn? <br>
                        <span class="text-xs text-gray-500 mt-1 block">- Hỏi chi phí dịch vụ<br>- Hướng dẫn đặt lịch<br>- Tra cứu bác sĩ</span></p>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-white border-t border-gray-100">
                <form id="chatForm" onsubmit="sendMessage(event)" class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                    <input type="text" id="userMessage" class="bg-transparent border-none focus:ring-0 flex-1 text-sm px-2" placeholder="Nhập câu hỏi..." autocomplete="off">
                    <button type="submit" class="text-primary hover:text-blue-700 transition"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>

        <button onclick="toggleChat()" class="w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full shadow-lg hover:scale-110 transition-transform duration-300 flex items-center justify-center text-2xl animate-bounce">
            <i class="fas fa-comment-dots"></i>
        </button>
    </div>

    <script>
        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.classList.toggle('hidden');
            chatWindow.classList.toggle('open');
            if(!chatWindow.classList.contains('hidden')) {
                document.getElementById('userMessage').focus();
            }
        }

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('userMessage');
            const message = input.value.trim();
            const chatBox = document.getElementById('chatMessages');

            if (!message) return;

            // 1. Hiện tin nhắn User
            chatBox.innerHTML += `
                <div class="flex items-end justify-end">
                    <div class="bg-blue-600 text-white p-3 rounded-tl-xl rounded-tr-xl rounded-bl-xl shadow-md max-w-[85%]">
                        ${message}
                    </div>
                </div>`;
            input.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            // 2. Hiện Typing...
            const loadingId = 'loading-' + Date.now();
            chatBox.innerHTML += `
                <div id="${loadingId}" class="flex items-start">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary flex-shrink-0 mr-2">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="bg-white p-3 rounded-tr-xl rounded-br-xl rounded-bl-xl shadow-sm border border-gray-100">
                        <i class="fas fa-ellipsis-h animate-pulse text-gray-400"></i>
                    </div>
                </div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            // 3. Gửi AJAX lên Server
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
                
                // Xóa loading
                document.getElementById(loadingId).remove();

                // Hiện trả lời của Bot
                chatBox.innerHTML += `
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary flex-shrink-0 mr-2">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="bg-white p-3 rounded-tr-xl rounded-br-xl rounded-bl-xl shadow-sm border border-gray-100 max-w-[85%]">
                            <p class="text-gray-800">${data.reply}</p>
                        </div>
                    </div>`;
                
                chatBox.scrollTop = chatBox.scrollHeight;

            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>