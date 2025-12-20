@extends('site.master')

@section('title', $service->name)

@section('body')
{{-- HERO BANNER --}}
<div class="relative bg-slate-900 py-24 text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 to-blue-900/90 z-10"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/medical-icons.png')] opacity-10 z-0"></div>
    
    <div class="container mx-auto max-w-6xl px-4 relative z-20">
        <div class="max-w-3xl">
            <a href="{{ route('services') }}" class="inline-flex items-center text-blue-200 hover:text-white text-sm font-medium mb-6 transition">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
            </a>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight tracking-tight">{{ $service->name }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-blue-100">
                <span class="bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm border border-white/10">
                    <i class="fas fa-tag mr-1 text-yellow-400"></i> {{ $service->category->name ?? 'Dịch vụ y tế' }}
                </span>
                <span><i class="far fa-clock mr-1"></i> {{ $service->duration > 0 ? $service->duration . ' phút' : 'Liên hệ' }}</span>
                <span><i class="fas fa-clinic-medical mr-1"></i> {{ $service->department->name ?? 'Đa khoa' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-slate-50 min-h-screen pb-12">
    <div class="container mx-auto max-w-6xl px-4 relative -mt-10 z-30">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- MAIN CONTENT (Left - 2 Cols) --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Info Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                    <div class="h-96 relative group">
                         <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.jpg') }}" 
                             alt="{{ $service->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-primary">
                                <i class="fas fa-info text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-800">Thông tin chi tiết</h2>
                        </div>
                        
                        {{-- Description / Content --}}
                        <div class="prose prose-slate max-w-none prose-img:rounded-xl prose-headings:text-slate-800 prose-a:text-primary hover:prose-a:underline">
                            <p class="text-lg text-slate-600 leading-relaxed font-medium mb-6">{{ $service->description }}</p>
                            
                            {{-- Raw content output --}}
                            {!! $service->content !!}
                        </div>
                    </div>
                </div>

                {{-- Process Steps (Visual Enhancement) --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                            <i class="fas fa-clipboard-check text-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">Quy trình thực hiện</h3>
                    </div>

                    <div class="space-y-8 relative before:absolute before:left-5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        <!-- Step 1 -->
                        <div class="relative pl-14">
                            <div class="absolute left-0 top-0 w-10 h-10 rounded-full bg-white border-2 border-primary text-primary font-bold flex items-center justify-center z-10 shadow-sm">1</div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Đăng ký & Tư vấn</h4>
                            <p class="text-slate-600 leading-relaxed">Đặt lịch hẹn trước qua website hoặc hotline. Nhân viên y tế sẽ xác nhận và hướng dẫn thủ tục ban đầu.</p>
                        </div>
                        <!-- Step 2 -->
                         <div class="relative pl-14">
                            <div class="absolute left-0 top-0 w-10 h-10 rounded-full bg-white border-2 border-primary text-primary font-bold flex items-center justify-center z-10 shadow-sm">2</div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Thăm khám chuyên sâu</h4>
                            <p class="text-slate-600 leading-relaxed">Bác sĩ chuyên khoa trực tiếp thăm khám, chỉ định các xét nghiệm cần thiết để đưa ra phác đồ chính xác.</p>
                        </div>
                        <!-- Step 3 -->
                         <div class="relative pl-14">
                            <div class="absolute left-0 top-0 w-10 h-10 rounded-full bg-white border-2 border-primary text-primary font-bold flex items-center justify-center z-10 shadow-sm">3</div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Thực hiện & Chăm sóc</h4>
                            <p class="text-slate-600 leading-relaxed">Tiến hành thực hiện dịch vụ trong môi trường vô khuẩn. Hướng dẫn chăm sóc và theo dõi sau thực hiện.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR (Right - Sticky) --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- BOOKING CARD --}}
                <div class="bg-white rounded-2xl shadow-xl border border-primary/20 overflow-hidden sticky top-24">
                    <div class="bg-gradient-to-r from-primary to-blue-600 p-6 text-center text-white relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
                        <p class="text-blue-100 text-sm font-medium mb-1 uppercase tracking-wider">Chi phí trọn gói</p>
                        <div class="text-4xl font-extrabold tracking-tight">
                            @if($service->fee == 0)
                                Liên hệ
                            @else
                                {{ number_format($service->fee, 0, ',', '.') }}<span class="text-xl font-normal opacity-80">đ</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                                <span class="text-slate-500">Mã dịch vụ</span>
                                <span class="font-mono font-bold text-slate-700">SV-{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </li>
                            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                                <span class="text-slate-500">Trạng thái</span>
                                @if($service->status)
                                    <span class="text-green-600 font-bold flex items-center"><i class="fas fa-check-circle mr-1"></i> Sẵn sàng</span>
                                @else
                                    <span class="text-red-500 font-bold flex items-center"><i class="fas fa-times-circle mr-1"></i> Tạm ngưng</span>
                                @endif
                            </li>
                            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                                <span class="text-slate-500">Khoa thực hiện</span>
                                <span class="font-bold text-primary">{{ $service->department->name ?? '---' }}</span>
                            </li>
                        </ul>

                        <a href="{{ route('schedule', ['department_id' => $service->department_id]) }}" 
                           class="block w-full bg-red-500 hover:bg-red-600 text-white text-center font-bold py-4 rounded-xl shadow-lg shadow-red-200 transition transform active:scale-95 mb-3 group">
                            <span class="group-hover:hidden">ĐẶT LỊCH NGAY</span>
                            <span class="hidden group-hover:inline-block"><i class="fas fa-calendar-check mr-2"></i> Chọn ngày khám</span>
                        </a>
                        
                        <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
                            <i class="fas fa-shield-alt"></i> Cam kết bảo mật thông tin 100%
                        </div>
                    </div>
                </div>

                {{-- SUPPORT CARD --}}
                <div class="bg-slate-900 rounded-2xl shadow-lg p-6 text-white text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-primary/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition duration-1000"></div>
                    
                    <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
                        <i class="fas fa-headset text-xl text-primary"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Cần tư vấn thêm?</h4>
                    <p class="text-sm text-slate-400 mb-4">Đội ngũ CSKH luôn sẵn sàng hỗ trợ giải đáp mọi thắc mắc của bạn.</p>
                    <a href="tel:19001234" class="inline-flex items-center text-2xl font-bold text-yellow-400 hover:text-yellow-300 transition">
                        <i class="fas fa-phone-alt text-lg mr-2"></i> 1900 1234
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection