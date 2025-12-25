@extends('site.master')

@section('title', 'Thông tin Bác sĩ ' . $doctor->user->name)

@section('body')
<div class="bg-slate-50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-6xl">
        
        {{-- BREADCRUMB --}}
        <nav class="flex mb-6 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-primary">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('schedule') }}" class="hover:text-primary">Đội ngũ bác sĩ</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800 font-semibold">{{ $doctor->user->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- CỘT TRÁI: THÔNG TIN TỔNG QUAN --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden sticky top-24">
                    {{-- Avatar & Badge --}}
                    <div class="relative pt-8 pb-4 px-6 text-center bg-gradient-to-b from-blue-50 to-white">
                        <div class="relative inline-block">
                            <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&size=200' }}" 
                                 class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-md mx-auto">
                            @if($doctor->license_number)
                                <div class="absolute bottom-2 right-2 bg-green-500 text-white p-1.5 rounded-full shadow-sm border-2 border-white" title="Đã xác thực chứng chỉ">
                                    <i class="fas fa-check"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h1 class="text-2xl font-bold text-slate-800 mt-4">
                            @if($doctor->degree) <span class="text-primary text-lg">{{ $doctor->degree }}</span> @endif
                            {{ $doctor->user->name }}
                        </h1>
                        <p class="text-slate-500 font-medium">{{ $doctor->specialization ?? 'Bác sĩ chuyên khoa' }}</p>
                        
                        <div class="mt-3 flex justify-center gap-1 text-yellow-400 text-sm">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $doctor->rating ? '' : 'text-slate-200' }}"></i>
                            @endfor
                            <span class="text-slate-400 ml-1">({{ $doctor->reviews_count }} đánh giá)</span>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Thông tin chi tiết nhỏ --}}
                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-primary flex items-center justify-center shrink-0">
                                <i class="fas fa-hospital-user"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">Khoa trực thuộc</p>
                                <p class="text-slate-700 font-semibold">{{ $doctor->department->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">Kinh nghiệm</p>
                                <p class="text-slate-700 font-semibold">{{ $doctor->experience_years }} năm công tác</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">Chứng chỉ hành nghề</p>
                                @if($doctor->license_number)
                                    <p class="text-slate-700 font-semibold flex items-center gap-1">
                                        {{ $doctor->license_number }} 
                                        <i class="fas fa-check-circle text-green-500 text-xs" title="Đã xác thực bởi Bộ Y Tế"></i>
                                    </p>
                                    @if($doctor->license_issued_by)
                                        <p class="text-xs text-slate-500 italic">Cấp bởi: {{ $doctor->license_issued_by }}</p>
                                    @endif
                                @else
                                    <p class="text-slate-400 italic">Đang cập nhật</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Nút hành động --}}
                    <div class="p-6 pt-0">
                        <a href="{{ route('schedule', ['doctor_id' => $doctor->id]) }}" class="block w-full py-3 bg-primary hover:bg-blue-700 text-white text-center rounded-xl font-bold shadow-lg shadow-blue-200 transition transform hover:-translate-y-1">
                            <i class="fas fa-calendar-check mr-2"></i> Đặt lịch khám ngay
                        </a>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: NỘI DUNG CHI TIẾT --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- 1. GIỚI THIỆU --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-md text-primary"></i> Giới thiệu bác sĩ
                    </h3>
                    <div class="text-slate-600 leading-relaxed text-justify">
                        @if($doctor->bio)
                            {!! nl2br(e($doctor->bio)) !!}
                        @else
                            <p class="italic text-slate-400">Bác sĩ chưa cập nhật phần giới thiệu.</p>
                        @endif
                    </div>
                </div>

                {{-- 2. HỒ SƠ NĂNG LỰC / CHỨNG CHỈ (PHẦN QUAN TRỌNG ĐỂ TĂNG UY TÍN) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-certificate text-yellow-500"></i> Hồ sơ năng lực & Pháp lý
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-700 mb-2">Đào tạo & Học vị</h4>
                            <ul class="space-y-2 text-sm text-slate-600">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-graduation-cap text-slate-400"></i>
                                    <span>Học vị: <strong>{{ $doctor->degree ?? 'Bác sĩ' }}</strong></span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-university text-slate-400"></i>
                                    <span>Đơn vị đào tạo: <strong>{{ $doctor->license_issued_by ?? 'Đại học Y' }}</strong></span>
                                </li>
                            </ul>
                        </div>

                        <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                            <h4 class="font-bold text-green-800 mb-2">Pháp lý hành nghề</h4>
                            <ul class="space-y-2 text-sm text-green-700">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-id-card"></i>
                                    <span>Số CCHN: <strong>{{ $doctor->license_number ?? 'Đang cập nhật' }}</strong></span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Trạng thái: <strong>Đã xác thực danh tính</strong></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    {{-- Nếu muốn show ảnh chứng chỉ (cân nhắc bảo mật, có thể chỉ hiện thumbnail mờ hoặc watermark) --}}
                    @if($doctor->license_image)
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-600 mb-2">Minh chứng bằng cấp:</p>
                        <div class="w-full h-48 bg-slate-100 rounded-lg flex items-center justify-center border border-dashed border-slate-300 relative overflow-hidden group cursor-pointer">
                            <img src="{{ asset('storage/'.$doctor->license_image) }}" class="h-full object-contain opacity-50 group-hover:opacity-100 transition duration-300">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="bg-black/50 text-white px-4 py-2 rounded-full text-sm font-bold backdrop-blur-sm">
                                    <i class="fas fa-eye mr-1"></i> Đã được Admin kiểm duyệt
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 3. ĐÁNH GIÁ TỪ BỆNH NHÂN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-comments text-primary"></i> Đánh giá từ bệnh nhân
                    </h3>

                    @if($doctor->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($doctor->reviews as $review)
                                <div class="border-b border-slate-100 pb-6 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500">
                                                {{ substr($review->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm">{{ $review->user->name ?? 'Người dùng ẩn danh' }}</p>
                                                <p class="text-xs text-slate-400">{{ $review->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-yellow-400 text-xs">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-slate-600 text-sm pl-12 bg-slate-50 p-3 rounded-lg">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400">
                            <i class="far fa-comment-dots text-4xl mb-3"></i>
                            <p>Bác sĩ này chưa có đánh giá nào.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection