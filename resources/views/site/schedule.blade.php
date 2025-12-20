@extends('site.master')

@section('title', 'Đặt lịch khám bệnh')

@section('body')
{{-- HERO HEADER --}}
<div class="relative bg-slate-900 py-16 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 to-blue-900/90 z-10"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/medical-icons.png')] opacity-10 z-0"></div>
    <div class="container mx-auto px-4 relative z-20 text-center text-white">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 tracking-tight">Đặt Lịch Khám Trực Tuyến</h1>
        <p class="text-lg text-blue-100 max-w-2xl mx-auto font-light">
            Tiết kiệm thời gian - Chọn bác sĩ yêu thích - Chăm sóc tận tâm
        </p>
    </div>
</div>

<div class="bg-slate-50 min-h-screen py-12 px-4 relative -mt-8 z-30">
    <div class="container mx-auto max-w-5xl">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            
            @if(Auth::check())
                <form action="{{ route('schedule.store') }}" method="POST" id="booking-form" class="p-8 md:p-12">
                    @csrf
                    
                    {{-- INPUTS ẨN --}}
                    <input type="hidden" name="department_id" id="department_id" value="{{ $selectedDeptId ?? '' }}">
                    <input type="hidden" name="doctor_id" id="doctor_id" value="">
                    <input type="hidden" name="time" id="time_slot" value="">

                    {{-- BƯỚC 1: CHỌN CHUYÊN KHOA --}}
                    <div class="mb-14 relative">
                        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-slate-100 -ml-4 hidden md:block"></div>
                        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-3 shadow-md shadow-sky-200">1</span>
                            Chọn chuyên khoa
                        </h2>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            {{-- Nút Tất cả --}}
                            <div class="specialty-item cursor-pointer p-4 rounded-xl border border-slate-200 bg-white hover:border-primary hover:shadow-md transition-all duration-200 flex flex-col items-center gap-3 group {{ !isset($selectedDeptId) ? 'active ring-2 ring-primary border-primary bg-sky-50' : '' }}" data-dept-id="all">
                                <div class="w-12 h-12 rounded-full bg-slate-50 group-hover:bg-white flex items-center justify-center text-slate-400 group-hover:text-primary transition-colors">
                                    <i class="fas fa-th-large text-xl"></i>
                                </div>
                                <span class="font-semibold text-sm text-slate-600 group-hover:text-primary text-center">Tất cả</span>
                            </div>

                            @foreach($departments as $dept)
                                <div class="specialty-item cursor-pointer p-4 rounded-xl border border-slate-200 bg-white hover:border-primary hover:shadow-md transition-all duration-200 flex flex-col items-center gap-3 group {{ (isset($selectedDeptId) && $selectedDeptId == $dept->id) ? 'active ring-2 ring-primary border-primary bg-sky-50' : '' }}" data-dept-id="{{ $dept->id }}">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 group-hover:bg-white flex items-center justify-center text-slate-500 group-hover:text-primary transition-colors">
                                        @if($dept->id == 1)<i class="fas fa-heartbeat text-xl text-red-400"></i>
                                        @elseif($dept->id == 2)<i class="fas fa-brain text-xl text-purple-400"></i>
                                        @elseif($dept->id == 3)<i class="fas fa-bone text-xl text-yellow-500"></i>
                                        @else <i class="fas fa-stethoscope text-xl"></i>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-sm text-slate-600 group-hover:text-primary text-center line-clamp-2 leading-tight">{{ $dept->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- BƯỚC 2: CHỌN BÁC SĨ --}}
                    <div class="mb-14">
                        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-3 shadow-md shadow-sky-200">2</span>
                            Chọn bác sĩ phụ trách
                        </h2>

                        <div id="doctor-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($doctors as $doctor)
                                <div class="doctor-card cursor-pointer group bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-lg hover:border-primary transition-all duration-300 relative overflow-hidden"
                                     data-dept-id="{{ $doctor->department_id }}"
                                     data-doctor-id="{{ $doctor->id }}">
                                     
                                    {{-- Selection Overlay --}}
                                    <div class="check-mark absolute inset-0 bg-primary/5 border-2 border-primary rounded-2xl opacity-0 scale-95 transition-all duration-200 z-0 pointer-events-none flex items-start justify-end p-3">
                                        <div class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center shadow-sm"><i class="fas fa-check text-xs"></i></div>
                                    </div>

                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=random&size=128' }}"
                                                 class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md group-hover:scale-105 transition-transform">
                                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-base group-hover:text-primary transition">{{ $doctor->user->name }}</h4>
                                            <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $doctor->department->name ?? 'Chuyên khoa' }}</span>
                                            <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-400">
                                                <span><i class="fas fa-star text-yellow-400"></i> 5.0</span>
                                                <span>• {{ rand(5, 20) }} năm KN</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="no-doctor-msg" class="hidden flex flex-col items-center justify-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-slate-400">
                            <i class="fas fa-user-md-slash text-4xl mb-2"></i>
                            <p>Không tìm thấy bác sĩ phù hợp trong chuyên khoa này.</p>
                        </div>
                    </div>

                    {{-- BƯỚC 3: CHỌN THỜI GIAN --}}
                    <div class="mb-14">
                        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-3 shadow-md shadow-sky-200">3</span>
                            Thời gian khám
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Ngày dự kiến</label>
                                <div class="relative">
                                    <input type="date" name="date" 
                                           class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-white"
                                           value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                    <i class="fas fa-calendar-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Khung giờ còn trống</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($timeSlots as $time)
                                        <div class="time-slot py-2.5 rounded-lg border border-slate-200 bg-white text-center cursor-pointer text-sm font-medium text-slate-600 hover:border-primary hover:text-primary transition-all select-none" 
                                             data-time="{{ $time }}">
                                            {{ $time }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BƯỚC 4: THÔNG TIN CÁ NHÂN --}}
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-3 shadow-md shadow-sky-200">4</span>
                            Xác nhận thông tin
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Họ tên bệnh nhân</label>
                                <input type="text" name="patient_name" value="{{ Auth::user()->name }}" 
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-100 text-slate-500 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="tel" name="patient_phone" value="{{ Auth::user()->phone ?? '' }}" 
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" 
                                       placeholder="0912..." required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Triệu chứng / Ghi chú</label>
                            <textarea name="reason" rows="3" 
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                                      placeholder="Mô tả ngắn gọn tình trạng sức khỏe của bạn..."></textarea>
                        </div>
                    </div>

                    {{-- ALERT MESSAGES --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                            <ul class="list-disc pl-4 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mt-6 p-8 bg-green-50 border border-green-100 rounded-2xl text-center">
                            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                                <i class="fas fa-check"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-green-800 mb-2">Đặt lịch thành công!</h3>
                            <p class="text-green-700">Mã phiếu khám đã được gửi. Chúng tôi sẽ liên hệ lại sớm nhất.</p>
                        </div>
                    @else
                        <div class="pt-8 border-t border-slate-100 text-center">
                            <button type="submit" class="w-full md:w-auto bg-primary hover:bg-sky-600 text-white font-bold text-lg px-12 py-4 rounded-full shadow-lg shadow-sky-200 transform hover:-translate-y-1 transition duration-300 flex items-center justify-center gap-2 mx-auto">
                                <i class="fas fa-calendar-check"></i> Xác Nhận Đặt Lịch
                            </button>
                        </div>
                    @endif
                </form>
            @else
                {{-- GUEST VIEW --}}
                <div class="text-center py-24 px-6">
                    <div class="w-24 h-24 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-lock text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-3">Yêu cầu đăng nhập</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">Vui lòng đăng nhập để sử dụng tính năng đặt lịch khám và quản lý hồ sơ sức khỏe cá nhân.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-primary text-white rounded-full font-bold hover:bg-sky-600 shadow-lg shadow-sky-100 transition">
                            Đăng nhập ngay
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-primary border border-primary rounded-full font-bold hover:bg-sky-50 transition">
                            Đăng ký tài khoản
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SCRIPTS (Preserved Logic) --}}
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const specialties = document.querySelectorAll('.specialty-item');
        const doctors = document.querySelectorAll('.doctor-card');
        const times = document.querySelectorAll('.time-slot');
        const noDocMsg = document.getElementById('no-doctor-msg');
        
        const inpDept = document.getElementById('department_id');
        const inpDoc = document.getElementById('doctor_id');
        const inpTime = document.getElementById('time_slot');

        function filterDoctors(deptId) {
            let visibleCount = 0;
            doctors.forEach(doc => {
                // Reset styles
                doc.querySelector('.check-mark').style.opacity = '0';
                doc.querySelector('.check-mark').style.transform = 'scale(0.95)';
                doc.classList.remove('ring-2', 'ring-primary', 'border-primary');

                if(deptId === 'all' || !deptId || doc.getAttribute('data-dept-id') == deptId) {
                    doc.style.display = 'block';
                    visibleCount++;
                } else {
                    doc.style.display = 'none';
                }
            });

            if(visibleCount === 0) noDocMsg.classList.remove('hidden');
            else noDocMsg.classList.add('hidden');
        }

        const currentDeptId = inpDept.value;
        filterDoctors(currentDeptId ? currentDeptId : 'all');

        specialties.forEach(item => {
            item.addEventListener('click', () => {
                specialties.forEach(i => i.classList.remove('active', 'ring-2', 'ring-primary', 'border-primary', 'bg-sky-50'));
                item.classList.add('active', 'ring-2', 'ring-primary', 'border-primary', 'bg-sky-50');
                
                const deptId = item.getAttribute('data-dept-id');
                inpDept.value = (deptId === 'all') ? '' : deptId;
                filterDoctors(deptId);
                inpDoc.value = "";
            });
        });

        doctors.forEach(doc => {
            doc.addEventListener('click', () => {
                doctors.forEach(d => {
                    d.querySelector('.check-mark').style.opacity = '0';
                    d.querySelector('.check-mark').style.transform = 'scale(0.95)';
                    d.classList.remove('ring-2', 'ring-primary', 'border-primary');
                });

                doc.classList.add('ring-2', 'ring-primary', 'border-primary');
                doc.querySelector('.check-mark').style.opacity = '1';
                doc.querySelector('.check-mark').style.transform = 'scale(1)';

                inpDoc.value = doc.getAttribute('data-doctor-id');
                
                const docDeptId = doc.getAttribute('data-dept-id');
                if(inpDept.value === '' || inpDept.value !== docDeptId) {
                     inpDept.value = docDeptId;
                     specialties.forEach(s => {
                         s.classList.remove('active', 'ring-2', 'ring-primary', 'border-primary', 'bg-sky-50');
                         if(s.getAttribute('data-dept-id') == docDeptId) {
                             s.classList.add('active', 'ring-2', 'ring-primary', 'border-primary', 'bg-sky-50');
                         }
                     });
                }
            });
        });

        times.forEach(slot => {
            slot.addEventListener('click', () => {
                times.forEach(t => t.classList.remove('bg-primary', 'text-white', 'border-primary'));
                slot.classList.add('bg-primary', 'text-white', 'border-primary');
                inpTime.value = slot.getAttribute('data-time');
            });
        });

        const form = document.getElementById('booking-form');
        if(form) {
            form.addEventListener('submit', function(e) {
                if(!inpDoc.value) {
                    alert("Vui lòng chọn Bác sĩ phụ trách!");
                    e.preventDefault();
                    document.getElementById('doctor-grid').scrollIntoView({behavior: 'smooth', block: 'center'});
                    return;
                }
                if(!inpTime.value) {
                    alert("Vui lòng chọn Khung giờ khám!");
                    e.preventDefault();
                    return;
                }
            });
        }
    });
    </script>
</div>
@endsection