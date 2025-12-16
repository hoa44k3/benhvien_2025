@extends('site.master')

@section('title','Dịch vụ Y tế')

@section('body')
    {{-- 1. HERO BANNER --}}
    <section class="relative bg-gradient-to-r from-teal-600 to-teal-800 py-16 text-white shadow-lg">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/medical-icons.png')]"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-5xl font-extrabold mb-4">Dịch vụ Y tế Chất lượng cao</h1>
            <p class="text-lg opacity-90 mb-8 max-w-2xl mx-auto">
                Chăm sóc toàn diện - Quy trình chuẩn Y khoa - Chi phí minh bạch
            </p>
            
            {{-- Bộ lọc danh mục --}}
            <div class="flex flex-wrap justify-center gap-2">
                <a href="{{ route('services', ['category' => 'all']) }}" 
                   class="px-5 py-2 rounded-full border border-white/40 font-medium transition hover:bg-white hover:text-teal-700 {{ request('category') == 'all' ? 'bg-white text-teal-700 shadow-md' : 'text-white' }}">
                    Tất cả
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('services', ['category' => $category->id]) }}" 
                       class="px-5 py-2 rounded-full border border-white/40 font-medium transition hover:bg-white hover:text-teal-700 {{ request('category') == $category->id ? 'bg-white text-teal-700 shadow-md' : 'text-white' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. DANH SÁCH DỊCH VỤ (Giao diện thẻ bài tối ưu) --}}
    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 max-w-7xl">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full group">
                    
                    {{-- Ảnh đại diện --}}
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.jpg') }}" 
                             alt="{{ $service->name }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                        
                        {{-- Badge Trạng thái --}}
                        <div class="absolute top-3 right-3">
                             @if($service->status)
                                <span class="bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    <i class="fas fa-check-circle mr-1"></i> Hoạt động
                                </span>
                            @else
                                <span class="bg-red-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    <i class="fas fa-tools mr-1"></i> Bảo trì
                                </span>
                            @endif
                        </div>

                        {{-- Badge Giá tiền (Nổi bật) --}}
                        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-4 pt-10">
                            <span class="text-white font-bold text-lg text-shadow">
                                {{ $service->fee ? number_format($service->fee, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                            </span>
                        </div>
                    </div>

                    {{-- Nội dung chính --}}
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-teal-600 uppercase tracking-wide bg-teal-50 px-2 py-1 rounded">
                                {{ $service->department->name ?? 'Đa khoa' }}
                            </span>
                            <span class="text-xs text-gray-500 flex items-center">
                                <i class="far fa-clock mr-1"></i> {{ $service->duration ? $service->duration.' phút' : '--' }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-3 leading-tight group-hover:text-teal-700 transition">
                            <a href="{{ route('services.show', $service) }}">{{ $service->name }}</a>
                        </h3>

                        {{-- Mô tả dịch vụ (Show 3 dòng) --}}
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed flex-grow">
                            {{ $service->description }}
                        </p>

                        {{-- Các nút hành động --}}
                        <div class="mt-auto space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Nút Xem Quy Trình (Mở Modal) --}}
                                <button onclick="openProcessModal({{ $service->id }}, '{{ $service->name }}')" 
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition flex items-center justify-center gap-2">
                                    <i class="fas fa-list-ol text-teal-600"></i> Quy trình
                                </button>
                                
                                {{-- Nút Xem Chi Tiết --}}
                                <a href="{{ route('services.show', $service) }}" 
                                   class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-100 transition flex items-center justify-center gap-2">
                                    <i class="far fa-eye"></i> Chi tiết
                                </a>
                            </div>

                            {{-- Nút Đặt Lịch (To và Nổi bật nhất) --}}
                            <a href="{{ route('schedule', ['department_id' => $service->department_id]) }}" 
                               class="block w-full py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-bold text-center rounded-lg shadow-md hover:shadow-lg hover:from-teal-700 hover:to-teal-800 transition transform hover:-translate-y-0.5">
                                <i class="far fa-calendar-check mr-2"></i> ĐẶT LỊCH NGAY
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Dữ liệu ẩn chứa thông tin Quy trình (JS sẽ đọc cái này) --}}
                <div id="process-data-{{ $service->id }}" class="hidden">
                    @if($service->steps && $service->steps->count() > 0)
                        @foreach($service->steps as $step)
                            <div class="step-item" 
                                 data-title="{{ $step->title }}" 
                                 data-desc="{{ $step->description }}"
                                 data-image="{{ $step->image ? asset('storage/'.$step->image) : '' }}">
                            </div>
                        @endforeach
                    @else
                        {{-- Dữ liệu mẫu Demo --}}
                        <div class="step-item" data-title="Bước 1: Chuẩn bị" data-desc="Khám lâm sàng, xét nghiệm cần thiết và tư vấn trước thủ thuật."></div>
                        <div class="step-item" data-title="Bước 2: Thực hiện" data-desc="Tiến hành thủ thuật trong môi trường vô khuẩn bởi bác sĩ chuyên khoa."></div>
                        <div class="step-item" data-title="Bước 3: Hồi phục" data-desc="Theo dõi sau thủ thuật, hướng dẫn chăm sóc và hẹn lịch tái khám."></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MODAL POPUP QUY TRÌNH (Giữ nguyên logic nhưng làm đẹp CSS) --}}
    <div id="processModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="closeProcessModal()"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 animate-scale-up overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="bg-teal-600 p-5 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fas fa-clipboard-list mr-2"></i> Quy trình dịch vụ
                    </h3>
                    <button onclick="closeProcessModal()" class="hover:bg-white/20 rounded-full p-1 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <h4 id="modal-service-name" class="text-xl font-bold text-teal-800 mb-6 text-center border-b border-teal-100 pb-3">
                        </h4>
                    
                    <div id="modal-steps-container" class="space-y-6 relative border-l-2 border-teal-200 ml-3 pl-6">
                        </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 p-4 border-t flex justify-end">
                    <button onclick="closeProcessModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition">
                        Đóng lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- STYLE & SCRIPT --}}
    <style>
        .text-shadow { text-shadow: 1px 1px 3px rgba(0,0,0,0.6); }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes scaleUp { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .animate-scale-up { animation: scaleUp 0.2s ease-out; }
    </style>

    <script>
        function openProcessModal(serviceId, serviceName) {
            const modal = document.getElementById('processModal');
            const container = document.getElementById('modal-steps-container');
            const title = document.getElementById('modal-service-name');
            const dataDiv = document.getElementById(`process-data-${serviceId}`);

            title.textContent = serviceName;
            container.innerHTML = ''; 

            const steps = dataDiv.querySelectorAll('.step-item');
            
            steps.forEach((step, index) => {
                const stepTitle = step.getAttribute('data-title');
                const stepDesc = step.getAttribute('data-desc');
                const stepImage = step.getAttribute('data-image'); // Lấy link ảnh

                let imageHtml = '';
                if (stepImage) {
                    imageHtml = `<img src="${stepImage}" class="mt-3 w-full h-32 object-cover rounded-lg shadow-sm border border-gray-100">`;
                }

                const html = `
                    <div class="relative group">
                        <div class="absolute -left-[33px] top-1 w-4 h-4 rounded-full bg-white border-4 border-teal-500 shadow-sm group-hover:border-teal-600 transition z-10"></div>
                        
                        <h5 class="font-bold text-gray-800 text-md mb-1 group-hover:text-teal-600 transition">
                            <span class="text-teal-500 mr-1">Bước ${index + 1}:</span> ${stepTitle}
                        </h5>
                        <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                            ${stepDesc}
                            ${imageHtml}
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeProcessModal() {
            document.getElementById('processModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection