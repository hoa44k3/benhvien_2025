@extends('site.master')

@section('title', $service->name)

@section('body')
{{-- HERO BANNER --}}
<div class="relative bg-gradient-to-r from-blue-700 to-blue-500 py-20 text-white">
    <div class="container mx-auto max-w-6xl px-4 relative z-10">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="md:w-2/3">
                <span class="inline-block bg-blue-800 text-blue-100 text-xs font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
                    {{ $service->category->name ?? 'Dịch vụ' }}
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $service->name }}</h1>
                <p class="text-lg opacity-90 mb-6 font-light">{{ $service->description }}</p>
                
                <div class="flex flex-wrap gap-4 text-sm font-medium">
                    <span class="flex items-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                        <i class="far fa-clock mr-2"></i> 
                        {{ $service->duration > 0 ? $service->duration . ' phút' : 'Liên hệ' }}
                    </span>
                    <span class="flex items-center bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-clinic-medical mr-2"></i> 
                        {{ $service->department->name ?? 'Đa khoa' }}
                    </span>
                </div>
            </div>
            <div class="md:w-1/3 hidden md:block">
                {{-- Có thể để ảnh vector hoặc icon lớn ở đây nếu muốn --}}
                <i class="fas fa-user-md text-9xl opacity-20"></i>
            </div>
        </div>
    </div>
    {{-- Decorative circles --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
</div>

<div class="container mx-auto max-w-6xl px-4 py-12 -mt-10 relative z-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- MAIN CONTENT --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="h-80 overflow-hidden relative group">
                    <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.jpg') }}" 
                         alt="{{ $service->name }}" 
                         class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <p class="text-sm font-medium"><i class="fas fa-camera mr-1"></i> Hình ảnh minh họa</p>
                    </div>
                </div>
                
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-blue-500 pl-4">Thông tin chi tiết</h2>
                    <div class="prose max-w-none text-gray-600 leading-relaxed">
                        {!! $service->content !!}
                    </div>
                </div>
            </div>

            {{-- QUY TRÌNH (Giả lập để trang trông đầy đặn hơn) --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Quy trình thực hiện</h3>
                <div class="space-y-6">
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">1</div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Đăng ký & Tư vấn</h4>
                            <p class="text-gray-600">Đặt lịch hẹn trước qua website hoặc hotline để được ưu tiên.</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">2</div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Thăm khám với Bác sĩ</h4>
                            <p class="text-gray-600">Bác sĩ chuyên khoa sẽ trực tiếp thăm khám và đưa ra chỉ định.</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold">3</div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Thực hiện dịch vụ</h4>
                            <p class="text-gray-600">Tiến hành thực hiện dịch vụ với trang thiết bị hiện đại.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- BOX ĐẶT LỊCH --}}
            <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden sticky top-24">
                <div class="bg-blue-600 p-6 text-center text-white">
                    <p class="text-sm font-medium opacity-90 mb-1">Chi phí dịch vụ</p>
                    <div class="text-3xl font-extrabold">
                        @if($service->fee == 0)
                            Liên hệ
                        @else
                            {{ number_format($service->fee, 0, ',', '.') }} <span class="text-lg font-normal">VNĐ</span>
                        @endif
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                            <span class="text-gray-500">Mã dịch vụ:</span>
                            <span class="font-medium text-gray-800">SV-{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                            <span class="text-gray-500">Trạng thái:</span>
                            @if($service->status)
                                <span class="text-green-600 font-bold"><i class="fas fa-check-circle mr-1"></i> Đang hoạt động</span>
                            @else
                                <span class="text-red-500 font-bold"><i class="fas fa-times-circle mr-1"></i> Tạm ngưng</span>
                            @endif
                        </div>
                        <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                            <span class="text-gray-500">Khoa thực hiện:</span>
                            <span class="font-medium text-blue-600">{{ $service->department->name ?? '---' }}</span>
                        </div>
                    </div>

                    {{-- 🔥 NÚT ĐẶT LỊCH: Truyền ID KHOA sang --}}
                    <a href="{{ route('schedule', ['department_id' => $service->department_id]) }}" 
                       class="block w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white text-center font-bold py-4 rounded-xl shadow-lg transform hover:-translate-y-1 transition duration-300">
                        <i class="far fa-calendar-check mr-2"></i> ĐẶT LỊCH NGAY
                    </a>
                    
                    <p class="text-xs text-center text-gray-400">
                        <i class="fas fa-shield-alt mr-1"></i> Cam kết bảo mật thông tin
                    </p>
                </div>
            </div>

            {{-- BOX LIÊN HỆ --}}
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white text-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <h4 class="font-bold text-lg mb-2">Cần hỗ trợ tư vấn?</h4>
                <p class="text-sm opacity-80 mb-4">Liên hệ ngay hotline để được giải đáp thắc mắc về dịch vụ.</p>
                <a href="tel:19001234" class="text-2xl font-bold text-yellow-400 hover:text-yellow-300 transition">1900 1234</a>
            </div>

        </div>
    </div>
</div>
@endsection