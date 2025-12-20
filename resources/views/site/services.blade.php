@extends('site.master')

@section('title','Dịch vụ Y tế')

@section('body')
    {{-- 1. HERO HEADER --}}
    <div class="bg-slate-900 relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Dịch vụ Y tế Chất lượng cao</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-10">Chúng tôi cung cấp các gói dịch vụ y tế toàn diện với trang thiết bị hiện đại và đội ngũ chuyên gia hàng đầu.</p>
            
            {{-- Filter Pills --}}
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('services', ['category' => 'all']) }}" 
                   class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') == 'all' || !request('category') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    Tất cả
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('services', ['category' => $category->id]) }}" 
                       class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') == $category->id ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 2. SERVICES GRID --}}
    <section class="py-16 bg-slate-50 min-h-screen">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 overflow-hidden group flex flex-col h-full hover:-translate-y-1">
                    
                    {{-- Image Top --}}
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.jpg') }}" 
                             alt="{{ $service->name }}" 
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                        
                        {{-- Status Badge --}}
                        <div class="absolute top-4 left-4">
                             @if($service->status)
                                <span class="bg-emerald-500/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <i class="fas fa-circle text-[6px] animate-pulse"></i> Hoạt động
                                </span>
                            @else
                                <span class="bg-slate-500/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                    Bảo trì
                                </span>
                            @endif
                        </div>

                        {{-- Department Badge --}}
                         <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-slate-800 shadow-sm">
                            {{ $service->department->name ?? 'Đa khoa' }}
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-end mb-3">
                             <div class="text-2xl font-bold text-slate-800 group-hover:text-primary transition">
                                {{ $service->fee ? number_format($service->fee, 0, ',', '.') : '---' }} <span class="text-sm font-normal text-slate-400">VNĐ</span>
                             </div>
                             <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded"><i class="far fa-clock mr-1"></i> {{ $service->duration }}p</span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-900 mb-3 leading-snug">
                            <a href="{{ route('services.show', $service) }}" class="hover:underline decoration-primary decoration-2 underline-offset-2">{{ $service->name }}</a>
                        </h3>

                        <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed flex-grow">
                            {{ $service->description }}
                        </p>

                        <div class="grid grid-cols-2 gap-3 mt-auto pt-4 border-t border-slate-50">
                            <button onclick="openProcessModal({{ $service->id }}, '{{ $service->name }}')" 
                                    class="py-2.5 px-4 rounded-lg bg-slate-50 text-slate-600 text-sm font-semibold hover:bg-slate-100 transition flex items-center justify-center gap-2">
                                <i class="fas fa-info-circle"></i> Quy trình
                            </button>
                            <a href="{{ route('schedule', ['department_id' => $service->department_id]) }}" 
                               class="py-2.5 px-4 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-sky-600 shadow-md shadow-sky-100 transition flex items-center justify-center gap-2">
                                <i class="fas fa-calendar-check"></i> Đặt lịch
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Hidden Data for Modal --}}
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
                        {{-- Dummy Data --}}
                        <div class="step-item" data-title="Bước 1: Tiếp nhận" data-desc="Đăng ký tại quầy hoặc online, nhân viên y tế sẽ hướng dẫn thủ tục."></div>
                        <div class="step-item" data-title="Bước 2: Khám lâm sàng" data-desc="Bác sĩ chuyên khoa thăm khám và chỉ định cận lâm sàng cần thiết."></div>
                        <div class="step-item" data-title="Bước 3: Thực hiện & Kết luận" data-desc="Tiến hành dịch vụ và trả kết quả, tư vấn điều trị."></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MODAL PROCESS (Redesigned) --}}
    <div id="processModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeProcessModal()"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 animate-fade-in-up overflow-hidden max-h-[85vh] flex flex-col">
                
                <div class="bg-white p-5 border-b border-slate-100 flex justify-between items-center sticky top-0 z-20">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center"><i class="fas fa-list-ol text-sm"></i></span>
                        Quy trình thực hiện
                    </h3>
                    <button onclick="closeProcessModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition text-slate-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <h4 id="modal-service-name" class="text-xl font-extrabold text-primary mb-6"></h4>
                    
                    <div id="modal-steps-container" class="space-y-0 relative border-l-2 border-slate-200 ml-3.5 pb-2">
                        {{-- JS will inject content here --}}
                    </div>
                </div>

                <div class="bg-slate-50 p-4 border-t border-slate-100 text-center">
                    <button onclick="closeProcessModal()" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition">Đóng cửa sổ</button>
                </div>
            </div>
        </div>
    </div>

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
                const stepImage = step.getAttribute('data-image');

                let imageHtml = '';
                if (stepImage) {
                    imageHtml = `<img src="${stepImage}" class="mt-3 w-full h-32 object-cover rounded-lg shadow-sm border border-slate-100">`;
                }

                const html = `
                    <div class="relative pl-8 pb-8 last:pb-0 group">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-4 border-slate-300 group-hover:border-primary transition z-10"></div>
                        
                        <h5 class="font-bold text-slate-800 text-base mb-1 group-hover:text-primary transition">
                            Bước ${index + 1}: ${stepTitle}
                        </h5>
                        <div class="text-sm text-slate-500 leading-relaxed bg-slate-50/50 p-3 rounded-xl border border-slate-100 group-hover:bg-blue-50/30 group-hover:border-blue-100 transition">
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