@extends('site.master')

@section('body')
<section class="py-16 bg-white"> {{-- Đổi màu nền chính sang trắng hoặc nền nhẹ nhàng hơn, tăng padding trên dưới --}}
    <div class="container mx-auto w-[90%] max-w-5xl bg-white p-10 rounded-3xl shadow-2xl border border-gray-100"> {{-- Tăng max-width, padding, bo tròn và độ sâu shadow --}}
        
        {{-- Ảnh Dịch Vụ --}}
        <div class="mb-8 overflow-hidden rounded-2xl">
             <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.jpg') }}" 
                 alt="{{ $service->name }}" 
                 class="w-full h-80 object-cover transform hover:scale-105 transition duration-500 ease-in-out"> {{-- Tăng chiều cao ảnh, thêm hiệu ứng hover scale --}}
        </div>
        
        <div class="px-2"> {{-- Thêm padding ngang nhỏ cho nội dung bên dưới ảnh --}}

            {{-- Tiêu Đề và Mô Tả Ngắn --}}
            <h1 class="text-4xl font-extrabold mb-3 text-gray-900 leading-tight"> {{-- Tăng kích thước và độ đậm font --}}
                {{ $service->name }}
            </h1>
            <p class="text-xl text-gray-600 mb-6 border-b pb-4"> {{-- Tăng kích thước font mô tả --}}
                {{ $service->description }}
            </p>

            {{-- Thông tin Chi Tiết: Thời lượng và Chi phí --}}
            <div class="flex items-center justify-between text-lg font-bold py-4 mb-8 bg-blue-50 rounded-lg p-4"> {{-- Đưa thông tin vào block màu nhẹ nổi bật --}}
                
                {{-- Thời Lượng --}}
                <span class="text-gray-800 flex items-center">
                    <i class="far fa-clock mr-3 text-blue-500 text-2xl"></i> {{-- Tăng kích thước icon --}}
                    **Thời lượng:** <span class="ml-2 font-extrabold text-blue-600">
                        @if($service->duration == 0)
                            Liên tục
                        @else
                            {{ $service->duration }} phút
                        @endif
                    </span>
                </span>
                
                {{-- Chi Phí --}}
                <span class="text-red-600 flex items-center"> {{-- Màu đỏ cho chi phí để thu hút --}}
                    <i class="fas fa-hand-holding-usd mr-3 text-red-500 text-2xl"></i>
                    **Chi phí:**
                    <span class="ml-2 font-extrabold">
                        @if($service->fee == 0)
                            Liên hệ
                        @else
                            {{ number_format($service->fee, 0, ',', '.') }} VNĐ
                        @endif
                    </span>
                </span>
            </div>

            {{-- Nội Dung Chi Tiết Dịch Vụ --}}
            <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4"> {{-- Thêm line-height và spacing cho nội dung --}}
                {!! $service->content !!}
            </div>

            {{-- Nút Đặt Lịch --}}
            <div class="mt-12 text-center"> {{-- Tăng margin top --}}
                <a href="{{ route('schedule') }}" class="inline-block bg-blue-600 text-white px-10 py-4 rounded-full font-bold text-xl shadow-lg hover:bg-blue-700 transition duration-300 transform hover:-translate-y-1"> {{-- Nút lớn, bo tròn, font đậm và thêm hiệu ứng hover --}}
                    <i class="far fa-calendar-alt mr-2"></i> Đặt lịch ngay
                </a>
            </div>
        </div>
    </div>
</section>
@endsection