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
                    {{-- Input chứa user_id thực của bác sĩ (để gọi API check trùng) --}}
                    <input type="hidden" name="doctor_user_id" id="doctor_user_id" value="">
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
                                     data-doctor-id="{{ $doctor->id }}"
                                     data-user-id="{{ $doctor->user->id }}"> {{-- Thêm data-user-id vào đây để dùng cho JS --}}
                                    {{-- Selection Overlay --}}
                                    <div class="check-mark absolute inset-0 bg-primary/5 border-2 border-primary rounded-2xl opacity-0 scale-95 transition-all duration-200 z-0 pointer-events-none flex items-start justify-end p-3">
                                        <div class="bg-primary text-white rounded-full w-6 h-6 flex items-center justify-center shadow-sm"><i class="fas fa-check text-xs"></i></div>
                                    </div>
                                    <a href="{{ route('site.doctors.show', $doctor->id) }}" 
                                            target="_blank" {{-- Mở tab mới để ko mất dữ liệu form đang chọn --}}
                                            class="absolute top-2 right-2 z-20 w-8 h-8 rounded-full bg-slate-100 hover:bg-primary hover:text-white text-slate-400 flex items-center justify-center transition"
                                            title="Xem hồ sơ chi tiết bác sĩ"
                                            onclick="event.stopPropagation();"> {{-- Chặn sự kiện click vào card chọn --}}
                                            <i class="fas fa-info"></i>
                                        </a>
                                        <div class="absolute top-2 left-2 z-20">
        @php
            $max = $doctor->max_patients ?? 20;
            $current = $doctor->appointments_count ?? 0;
            $percent = ($current / $max) * 100;
            $colorClass = $percent >= 100 ? 'bg-red-100 text-red-600 border-red-200' : ($percent >= 80 ? 'bg-orange-100 text-orange-600 border-orange-200' : 'bg-blue-50 text-blue-600 border-blue-100');
        @endphp
        <span class="px-2 py-1 rounded-md text-[10px] font-bold border {{ $colorClass }} shadow-sm flex items-center gap-1">
            <i class="fas fa-users"></i> 
            <span class="quota-text">Hôm nay: {{ $current }}/{{ $max }}</span>
        </span>
    </div>
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=random&size=128' }}"
                                                 class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md group-hover:scale-105 transition-transform">
                                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                                        </div>
                                        <div>
                                           <h4 class="font-bold text-slate-800 text-base group-hover:text-primary transition">
                                                {{-- Thêm học vị vào tên --}}
                                                @if($doctor->degree) <span class="text-sm text-primary font-normal">{{ $doctor->degree }}</span> @endif
                                                {{ $doctor->user->name }}
                                            </h4>
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
                    <div class="mb-14">
                        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-3 shadow-md shadow-sky-200">3</span>
                            Thời gian khám
                        </h2>
                        
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            {{-- Cảnh báo --}}
                            <div class="mb-4 text-sm text-slate-500 flex items-center gap-2">
                                <span class="w-3 h-3 bg-white border border-slate-200 rounded"></span> Trống
                                <span class="w-3 h-3 bg-gray-200 rounded ml-2"></span> Đã có người đặt
                                <span class="w-3 h-3 bg-primary rounded ml-2"></span> Đang chọn
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ngày khám</label>
                                    <div class="relative">
                                        <input type="date" name="date" id="date_input"
                                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition bg-white"
                                               value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                        <i class="fas fa-calendar-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Giờ khám <span id="loading-slots" class="hidden text-primary text-xs ml-2"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</span>
                                    {{-- === THÊM MỚI: Hiển thị trạng thái chi tiết theo ngày === --}}
    <span id="quota-display" class="ml-2 text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md hidden">
        </span>
                                    </label>
                                    
                                    {{-- Grid giờ --}}
                                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 relative" id="time-grid">
                                        {{-- Overlay chặn click khi chưa chọn bác sĩ --}}
                                        <div id="time-overlay" class="absolute inset-0 bg-white/60 z-10 flex items-center justify-center text-sm text-slate-500 font-medium backdrop-blur-[1px] rounded-lg border border-dashed border-slate-300">
                                            Vui lòng chọn Bác sĩ trước
                                        </div>

                                        @foreach($timeSlots as $time)
                                            <div class="time-slot py-2.5 rounded-lg border border-slate-200 bg-white text-center cursor-pointer text-sm font-medium text-slate-600 hover:border-primary hover:text-primary transition-all select-none relative" 
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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const doctors = document.querySelectorAll('.doctor-card');
        const times = document.querySelectorAll('.time-slot');
        const inpDoc = document.getElementById('doctor_id');
        const inpDocUserId = document.getElementById('doctor_user_id'); // Hidden input mới
        const inpTime = document.getElementById('time_slot');
        const inpDate = document.getElementById('date_input');
        
        const timeOverlay = document.getElementById('time-overlay');
        const loadingSlots = document.getElementById('loading-slots');
const quotaDisplay = document.getElementById('quota-display'); // Lấy element mới thêm
       // Hàm kiểm tra giờ trống
        async function checkAvailability() {
            const doctorUserId = inpDocUserId.value; 
            const date = inpDate.value;

            if (!doctorUserId || !date) return;

            // Reset UI
            loadingSlots.classList.remove('hidden');
            quotaDisplay.classList.add('hidden'); // Ẩn tạm thời
            // Reset thông báo lỗi nếu có trước đó (Bạn có thể thêm 1 thẻ div id="full-day-alert" vào HTML nếu muốn đẹp hơn)
            const timeGrid = document.getElementById('time-grid');
            
            // Mở lại các slot để tính toán
            times.forEach(t => {
                t.classList.remove('bg-red-50', 'text-red-400', 'bg-gray-100', 'text-gray-400', 'pointer-events-none', 'line-through', 'cursor-not-allowed');
                t.classList.remove('bg-primary', 'text-white', 'border-primary'); 
                t.classList.add('bg-white', 'text-slate-600', 'border-slate-200'); 
                t.innerHTML = t.getAttribute('data-time'); // Reset text gốc
            });
            inpTime.value = ''; 

            try {
                const response = await fetch(`{{ route('get.booked.slots') }}?doctor_id=${doctorUserId}&date=${date}`);
                const data = await response.json();
                if(data.quota) {
                    quotaDisplay.classList.remove('hidden');
                    // Format lại ngày cho đẹp (DD/MM)
                    const dateObj = new Date(date);
                    const dayStr = dateObj.getDate() + '/' + (dateObj.getMonth() + 1);
                    
                    quotaDisplay.innerHTML = `Ngày ${dayStr}: <span class="${data.quota.current >= data.quota.max ? 'text-red-600' : 'text-primary'}">${data.quota.current}/${data.quota.max} ca</span>`;
                    
                    if(data.is_full_day) {
                        quotaDisplay.classList.add('bg-red-50', 'border', 'border-red-100');
                        quotaDisplay.innerHTML += ' (Đã đầy)';
                    } else {
                        quotaDisplay.classList.remove('bg-red-50', 'border', 'border-red-100');
                    }
                }
                // --- XỬ LÝ KHI FULL NGÀY (QUOTA) ---
                if (data.is_full_day) {
                     times.forEach(slot => {
                        slot.classList.add('bg-red-50', 'text-red-300', 'pointer-events-none', 'cursor-not-allowed');
                        slot.innerHTML = '<span class="text-xs">Full</span>';
                    });
                } else {
                    times.forEach(slot => {
                        const timeValue = slot.getAttribute('data-time');
                        if (data.booked_slots.includes(timeValue)) {
                             slot.classList.add('bg-gray-100', 'text-gray-400', 'pointer-events-none', 'cursor-not-allowed');
                             slot.innerHTML = timeValue + ' <span class="text-[10px] block">(Kín)</span>';
                        } else {
                             slot.innerHTML = timeValue; // Reset lại
                        }
                    });
                }

            } catch (error) {
                console.error('Lỗi check lịch:', error);
            } finally {
                loadingSlots.classList.add('hidden');
            }
        }
        
        // Sự kiện chọn Bác sĩ
        doctors.forEach(doc => {
            doc.addEventListener('click', () => {
                // ... (Code style chọn bác sĩ cũ giữ nguyên) ...
                doctors.forEach(d => {
                    d.querySelector('.check-mark').style.opacity = '0';
                    d.classList.remove('ring-2', 'ring-primary', 'border-primary');
                });
                doc.classList.add('ring-2', 'ring-primary', 'border-primary');
                doc.querySelector('.check-mark').style.opacity = '1';

                // Cập nhật Input
                inpDoc.value = doc.getAttribute('data-doctor-id'); // ID bảng doctor_sites
                inpDocUserId.value = doc.getAttribute('data-user-id'); // ID bảng users (quan trọng để check lịch)

                // Mở khóa phần chọn giờ
                timeOverlay.classList.add('hidden');
                
                // Gọi hàm check lịch ngay
                checkAvailability();
            });
        });

        // Sự kiện đổi ngày -> Check lại lịch
        inpDate.addEventListener('change', checkAvailability);

        // Sự kiện chọn giờ (Chỉ chọn được giờ chưa bị disable)
        times.forEach(slot => {
            slot.addEventListener('click', () => {
                if(slot.classList.contains('pointer-events-none')) return; // Chặn click nếu disable

                times.forEach(t => {
                    if(!t.classList.contains('pointer-events-none')) {
                        t.classList.remove('bg-primary', 'text-white', 'border-primary');
                        t.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
                    }
                });

                slot.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
                slot.classList.add('bg-primary', 'text-white', 'border-primary');
                inpTime.value = slot.getAttribute('data-time');
            });
        });

        // Form Submit check
        const form = document.getElementById('booking-form');
        if(form) {
            form.addEventListener('submit', function(e) {
                if(!inpDoc.value) {
                    alert("Vui lòng chọn Bác sĩ!"); e.preventDefault(); return;
                }
                if(!inpTime.value) {
                    alert("Vui lòng chọn giờ khám!"); e.preventDefault(); return;
                }
            });
        }
        
        // Logic lọc chuyên khoa (giữ nguyên của bạn)
        const specialties = document.querySelectorAll('.specialty-item');
        const noDocMsg = document.getElementById('no-doctor-msg');
        // ... (copy lại đoạn filterDoctors cũ của bạn vào đây) ...
         function filterDoctors(deptId) {
            let visibleCount = 0;
            doctors.forEach(doc => {
                 // Reset khi đổi khoa
                doc.querySelector('.check-mark').style.opacity = '0';
                doc.classList.remove('ring-2', 'ring-primary', 'border-primary');
                
                if(deptId === 'all' || !deptId || doc.getAttribute('data-dept-id') == deptId) {
                    doc.style.display = 'block'; visibleCount++;
                } else {
                    doc.style.display = 'none';
                }
            });
            if(visibleCount === 0) noDocMsg.classList.remove('hidden');
            else noDocMsg.classList.add('hidden');
            
            // Khi đổi khoa -> Reset luôn phần chọn bác sĩ và giờ
            inpDoc.value = "";
            inpDocUserId.value = "";
            inpTime.value = "";
            timeOverlay.classList.remove('hidden'); // Khóa lại giờ
        }
         specialties.forEach(item => {
            item.addEventListener('click', () => {
                specialties.forEach(i => i.classList.remove('active', 'ring-2', 'ring-primary', 'border-primary', 'bg-sky-50'));
                item.classList.add('active', 'ring-2', 'ring-primary', 'border-primary', 'bg-sky-50');
                const deptId = item.getAttribute('data-dept-id');
                filterDoctors(deptId);
            });
        });
    });
    </script>
</div>
@endsection