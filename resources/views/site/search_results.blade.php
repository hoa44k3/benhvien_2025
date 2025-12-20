@extends('site.master')

@section('title', 'Kết quả tìm kiếm: ' . $keyword)

@section('body')
<div class="bg-slate-50 min-h-screen pb-20">
    
    {{-- SEARCH HEADER --}}
    <div class="bg-white border-b border-slate-200 sticky top-20 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Kết quả cho "<span class="text-primary">{{ $keyword }}</span>"
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Tìm thấy tổng cộng <strong class="text-slate-800">{{ $doctors->count() + $services->count() + $departments->count() }}</strong> kết quả phù hợp.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-10 space-y-16">

        @if($doctors->isEmpty() && $departments->isEmpty() && $services->isEmpty())
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search-minus text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Không tìm thấy kết quả</h3>
                <p class="text-slate-500 mb-6">Thử lại với từ khóa khác chung chung hơn.</p>
                <a href="{{ route('home') }}" class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition">
                    Về trang chủ
                </a>
            </div>
        @else

            {{-- 1. DEPARTMENTS --}}
            @if(!$departments->isEmpty())
                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-clinic-medical text-primary"></i> Chuyên khoa ({{ $departments->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($departments as $dept)
                            <a href="{{ route('services', ['department' => $dept->id]) }}" class="group block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-primary/50 transition duration-300">
                                <div class="h-32 bg-slate-100 overflow-hidden relative">
                                    <img src="{{ $dept->image ? asset('storage/'.$dept->image) : asset('images/default-department.jpg') }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-slate-800 text-lg group-hover:text-primary transition mb-1">{{ $dept->name }}</h3>
                                    <p class="text-sm text-slate-500 line-clamp-2">{{ $dept->description ?? 'Chuyên khoa hàng đầu...' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- 2. DOCTORS --}}
            @if(!$doctors->isEmpty())
                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-md text-primary"></i> Bác sĩ ({{ $doctors->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($doctors as $doc)
                            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-primary/50 transition flex items-start gap-4 group">
                                <img src="{{ $doc->image ? asset('storage/'.$doc->image) : 'https://ui-avatars.com/api/?name='.urlencode($doc->user->name).'&background=random' }}" 
                                     class="w-20 h-20 rounded-full object-cover border-2 border-slate-100 group-hover:border-primary transition">
                                <div class="flex-grow">
                                    <h3 class="font-bold text-slate-800 text-lg group-hover:text-primary transition">{{ $doc->user->name }}</h3>
                                    <p class="text-xs font-bold text-primary uppercase tracking-wide mb-2">{{ $doc->department->name ?? 'Chuyên khoa' }}</p>
                                    <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $doc->bio ?? 'Bác sĩ chuyên khoa giàu kinh nghiệm.' }}</p>
                                    
                                    <div class="flex gap-3">
                                        <a href="{{ route('schedule', ['doctor_id' => $doc->id]) }}" class="text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-lg hover:bg-sky-600 transition">Đặt lịch</a>
                                        <a href="#" class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg hover:bg-slate-200 transition">Xem hồ sơ</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- 3. SERVICES --}}
            @if(!$services->isEmpty())
                <section>
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-briefcase-medical text-primary"></i> Dịch vụ ({{ $services->count() }})
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($services as $srv)
                            <a href="{{ route('services.show', $srv->id) }}" class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-primary hover:shadow-md transition group">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-primary flex items-center justify-center mr-4 group-hover:bg-primary group-hover:text-white transition">
                                    <i class="fas fa-notes-medical"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-700 group-hover:text-primary transition text-sm">{{ $srv->name }}</h4>
                                    <p class="text-xs text-red-500 font-bold mt-0.5">{{ number_format($srv->price ?? 0) }} đ</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

        @endif
    </div>
</div>
@endsection