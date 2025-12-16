@extends('site.master')

@section('title', 'Kết quả tìm kiếm cho: ' . $keyword)

@section('body')
<div class="bg-gray-50 min-h-screen pb-12">
    
    {{-- 1. HEADER TÌM KIẾM & THỐNG KÊ --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Kết quả tìm kiếm</h1>
                    <p class="text-gray-500 mt-1">
                        Tìm thấy <strong class="text-primary">{{ $doctors->count() + $services->count() + $departments->count() }}</strong> kết quả cho từ khóa: "<span class="text-primary font-bold">{{ $keyword }}</span>"
                    </p>
                </div>
                
                {{-- Form tìm kiếm lại ngay tại đây --}}
                {{-- <div class="w-full md:w-1/3">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="keyword" value="{{ $keyword }}" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition" 
                               placeholder="Tìm kiếm khác...">
                        <button type="submit" class="absolute left-3 top-3 text-gray-400 hover:text-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-12">

        {{-- TRƯỜNG HỢP KHÔNG TÌM THẤY GÌ --}}
        @if($doctors->isEmpty() && $departments->isEmpty() && $services->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Không tìm thấy kết quả nào phù hợp</h3>
                <p class="text-gray-500 mb-6">Vui lòng thử lại với từ khóa khác hoặc kiểm tra lỗi chính tả.</p>
                <a href="{{ route('home') }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-home mr-2"></i> Quay về trang chủ
                </a>
            </div>
        @else

            {{-- 2. KẾT QUẢ: KHOA / CHUYÊN KHOA --}}
            @if(!$departments->isEmpty())
                <section>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">
                            <i class="fas fa-clinic-medical"></i>
                        </span>
                        Chuyên khoa ({{ $departments->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($departments as $dept)
                            <a href="{{ route('services', ['department' => $dept->id]) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition-all duration-300">
                                <div class="h-40 overflow-hidden relative">
                                    <img src="{{ $dept->image ? asset('storage/'.$dept->image) : asset('images/default-department.jpg') }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <h3 class="absolute bottom-3 left-4 text-white font-bold text-lg">{{ $dept->name }}</h3>
                                </div>
                                <div class="p-4">
                                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                        {{ $dept->description ?? 'Chuyên khoa hàng đầu với trang thiết bị hiện đại...' }}
                                    </p>
                                    <span class="text-primary text-sm font-semibold group-hover:underline">Xem chi tiết <i class="fas fa-arrow-right ml-1"></i></span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
                <hr class="border-gray-200">
            @endif

            {{-- 3. KẾT QUẢ: BÁC SĨ (Hiển thị chi tiết dạng Card ngang) --}}
            @if(!$doctors->isEmpty())
                <section>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="bg-teal-100 text-teal-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">
                            <i class="fas fa-user-md"></i>
                        </span>
                        Đội ngũ Bác sĩ ({{ $doctors->count() }})
                    </h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($doctors as $doc)
                            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 flex flex-col sm:flex-row gap-5">
                                {{-- Ảnh bác sĩ --}}
                                <div class="flex-shrink-0 mx-auto sm:mx-0">
                                    <img src="{{ $doc->image ? asset('storage/'.$doc->image) : asset('assets/img/default-doctor.png') }}" 
                                         class="w-28 h-28 rounded-full object-cover border-4 border-gray-50 shadow-sm">
                                </div>
                                
                                {{-- Thông tin chi tiết --}}
                                <div class="flex-grow text-center sm:text-left">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">BS. {{ $doc->user->name }}</h3>
                                            <p class="text-teal-600 font-medium text-sm">{{ $doc->specialization ?? 'Bác sĩ chuyên khoa' }}</p>
                                        </div>
                                        <div class="mt-2 sm:mt-0 flex items-center justify-center sm:justify-end text-yellow-400 text-sm">
                                            <span class="font-bold text-gray-700 mr-1">{{ number_format($doc->rating, 1) }}</span>
                                            <i class="fas fa-star"></i>
                                            <span class="text-gray-400 text-xs ml-1">({{ $doc->reviews_count }} đánh giá)</span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-sm text-gray-500 space-y-1 mb-4">
                                        <p><i class="fas fa-clinic-medical w-5 text-center mr-1"></i> Khoa: <span class="text-gray-700">{{ $doc->department->name ?? 'Chưa cập nhật' }}</span></p>
                                        <p><i class="fas fa-briefcase w-5 text-center mr-1"></i> Kinh nghiệm: <span class="text-gray-700">{{ $doc->experience_years }} năm</span></p>
                                        <p class="line-clamp-1 italic">"{{ Str::limit($doc->bio ?? 'Tận tâm, chuyên nghiệp vì sức khỏe bệnh nhân.', 50) }}"</p>
                                    </div>

                                    <div class="flex gap-3 justify-center sm:justify-start">
                                        <a href="{{ route('doctorsite.show', $doc->id) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                            Hồ sơ
                                        </a>
                                        <a href="{{ route('schedule', ['doctor_id' => $doc->id]) }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-blue-600 shadow-md hover:shadow-lg transition">
                                            Đặt lịch khám
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                <hr class="border-gray-200">
            @endif

            {{-- 4. KẾT QUẢ: DỊCH VỤ (Hiển thị dạng bảng giá/Card) --}}
            @if(!$services->isEmpty())
                <section>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">
                            <i class="fas fa-notes-medical"></i>
                        </span>
                        Dịch vụ Y tế ({{ $services->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($services as $srv)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:border-indigo-500 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                                {{-- Trang trí --}}
                                <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition group-hover:bg-indigo-100"></div>

                                <div class="mb-4">
                                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-500 bg-indigo-50 px-2 py-1 rounded">
                                        {{ $srv->category->name ?? 'Dịch vụ' }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition">{{ $srv->name }}</h3>
                                <p class="text-gray-500 text-sm mb-4 h-10 line-clamp-2">
                                    {{ $srv->description ?? 'Liên hệ để biết thêm chi tiết về quy trình thực hiện dịch vụ này.' }}
                                </p>

                                <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-400">Chi phí ước tính</span>
                                        <span class="text-lg font-bold text-red-500">{{ number_format($srv->price, 0, ',', '.') }}đ</span>
                                    </div>
                                    <a href="{{ route('service.show', $srv->id) }}" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-indigo-600 hover:text-white transition">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

        @endif
    </div>
</div>
@endsection