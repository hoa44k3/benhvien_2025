@extends('site.master')

@section('title', 'Đặt lịch khám bệnh')

@section('body')
<section class="booking-banner bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16 text-center mb-10 shadow-lg relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/medical-icons.png')]"></div>
    <div class="container mx-auto px-4 relative z-10">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 tracking-tight">Đặt Lịch Khám Trực Tuyến</h1>
        <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto font-light">
            Chủ động thời gian - Không lo chờ đợi - Chăm sóc tận tâm
        </p>
    </div>
</section>

<section class="booking-content pb-20 px-4">
    <div class="container mx-auto max-w-5xl">
        
        <div class="bg-white p-6 md:p-10 rounded-2xl shadow-xl border border-gray-100 relative">
            
            @if(Auth::check())
                {{-- Form gửi dữ liệu --}}
                <form action="{{ route('schedule.store') }}" method="POST" id="booking-form">
                    @csrf
                    
                    {{-- 🔥 QUAN TRỌNG: CÁC INPUT ẨN ĐỂ LƯU DỮ LIỆU --}}
                    <input type="hidden" name="department_id" id="department_id" value="{{ $selectedDeptId ?? '' }}">
                    <input type="hidden" name="doctor_id" id="doctor_id" value="">
                    <input type="hidden" name="time" id="time_slot" value="">

                    {{-- BƯỚC 1: CHỌN CHUYÊN KHOA --}}
                    <div class="mb-12">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center border-b pb-2">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold shadow-md">1</span>
                            Chọn chuyên khoa
                        </h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            {{-- Nút Tất cả --}}
                            <div class="specialty-item group flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 {{ !isset($selectedDeptId) ? 'active border-blue-500 bg-blue-50' : '' }}" 
                                 data-dept-id="all">
                                <i class="fas fa-th-large text-3xl text-gray-400 group-hover:text-blue-500 mb-2 transition-colors"></i>
                                <span class="font-semibold text-gray-600 group-hover:text-blue-600 text-sm">Tất cả</span>
                            </div>

                            @foreach($departments as $dept)
                                <div class="specialty-item group flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 {{ (isset($selectedDeptId) && $selectedDeptId == $dept->id) ? 'active border-blue-500 bg-blue-50' : '' }}" 
                                     data-dept-id="{{ $dept->id }}">
                                    
                                    <div class="mb-2 transition-transform transform group-hover:scale-110">
                                        @if($dept->id == 1)<i class="fas fa-heartbeat text-3xl text-red-400"></i>
                                        @elseif($dept->id == 2)<i class="fas fa-brain text-3xl text-purple-400"></i>
                                        @elseif($dept->id == 3)<i class="fas fa-bone text-3xl text-yellow-500"></i>
                                        @else <i class="fas fa-stethoscope text-3xl text-blue-400"></i>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-gray-600 group-hover:text-blue-600 text-sm text-center line-clamp-2">{{ $dept->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- BƯỚC 2: CHỌN BÁC SĨ --}}
                    <div class="mb-12">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center border-b pb-2">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold shadow-md">2</span>
                            Chọn bác sĩ
                        </h2>

                        <div id="doctor-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($doctors as $doctor)
                                <div class="doctor-card border-2 border-gray-100 rounded-2xl p-5 cursor-pointer hover:shadow-xl hover:border-blue-500 transition-all duration-300 bg-white relative overflow-hidden group"
                                     data-dept-id="{{ $doctor->department_id }}"
                                     data-doctor-id="{{ $doctor->id }}">
                                     
                                    <div class="absolute top-0 left-0 w-2 h-full bg-gray-200 group-hover:bg-blue-500 transition-colors"></div>

                                    <div class="flex items-center pl-4">
                                        <div class="relative">
                                            <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=random&size=128' }}"
                                                 class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md">
                                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full" title="Đang làm việc"></div>
                                        </div>
                                        
                                        <div class="ml-4">
                                            <h4 class="font-bold text-gray-800 text-lg">{{ $doctor->user->name }}</h4>
                                            <p class="text-xs text-blue-600 font-bold uppercase tracking-wide bg-blue-50 px-2 py-0.5 rounded-md inline-block mt-1">
                                                {{ $doctor->department->name ?? 'Chuyên khoa' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm text-gray-500 border-t pt-4 mt-4 pl-4">
                                        <span class="flex items-center"><i class="fas fa-star text-yellow-400 mr-1"></i> 5.0</span>
                                        <span class="flex items-center"><i class="fas fa-briefcase mr-1 text-gray-400"></i> {{ rand(5, 20) }} năm KN</span>
                                    </div>

                                    {{-- Checkmark khi chọn --}}
                                    <div class="check-mark absolute top-3 right-3 text-blue-600 opacity-0 transform scale-0 group-hover:scale-100 transition-all">
                                        <i class="fas fa-check-circle text-2xl"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="no-doctor-msg" class="hidden text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <i class="fas fa-user-md text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500 italic">Chưa có bác sĩ nào thuộc chuyên khoa này.</p>
                        </div>
                    </div>

                    {{-- BƯỚC 3: CHỌN NGÀY GIỜ --}}
                    <div class="mb-12">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center border-b pb-2">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold shadow-md">3</span>
                            Thời gian khám
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2"><i class="far fa-calendar-alt mr-1 text-blue-500"></i> Ngày dự kiến</label>
                                <div class="relative">
                                    <input type="date" name="date" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none shadow-sm cursor-pointer hover:bg-gray-50 transition"
                                           value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-2"><i class="far fa-clock mr-1 text-blue-500"></i> Khung giờ</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($timeSlots as $time)
                                        <div class="time-slot py-2 px-1 border border-gray-300 rounded-lg text-center cursor-pointer text-sm font-medium hover:bg-blue-50 hover:text-blue-600 hover:border-blue-400 transition select-none" 
                                             data-time="{{ $time }}">
                                            {{ $time }}
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-400 mt-2 italic">* Vui lòng đến trước 15 phút để làm thủ tục.</p>
                            </div>
                        </div>
                    </div>

                    {{-- BƯỚC 4: THÔNG TIN CÁ NHÂN --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center border-b pb-2">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm font-bold shadow-md">4</span>
                            Thông tin bệnh nhân
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Họ và tên</label>
                                <input type="text" name="patient_name" value="{{ Auth::user()->name }}" 
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed shadow-inner" readonly>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="tel" name="patient_phone" value="{{ Auth::user()->phone ?? '' }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none shadow-sm transition" 
                                       placeholder="Nhập số điện thoại liên hệ" required>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Triệu chứng / Lý do khám</label>
                            <textarea name="reason" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none shadow-sm transition"
                                      placeholder="Ví dụ: Đau đầu, sốt nhẹ, ho..."></textarea>
                        </div>
                    </div>

                    {{-- THÔNG BÁO LỖI/SUCCESS --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200 shadow-sm flex items-start">
                            <i class="fas fa-exclamation-triangle mt-1 mr-3"></i>
                            <ul class="list-disc pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mt-6 p-6 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow-md text-center">
                            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">
                                <i class="fas fa-check"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-1">Đặt lịch thành công!</h3>
                            <p>Mã lịch hẹn của bạn đã được gửi. Chúng tôi sẽ liên hệ sớm.</p>
                        </div>
                    @else
                        <div class="pt-6 border-t border-gray-100 text-center">
                            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-lg px-12 py-4 rounded-full shadow-lg hover:shadow-2xl hover:from-blue-700 hover:to-blue-800 transform hover:-translate-y-1 transition duration-300">
                                <i class="fas fa-paper-plane mr-2"></i> Xác Nhận Đặt Lịch
                            </button>
                        </div>
                    @endif

                </form>
            @else
                {{-- Màn hình yêu cầu đăng nhập --}}
                <div class="text-center py-20 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Bạn chưa đăng nhập</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Vui lòng đăng nhập hoặc đăng ký tài khoản để sử dụng tính năng đặt lịch khám trực tuyến.</p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-blue-600 border border-blue-600 rounded-lg font-bold hover:bg-blue-50 shadow transition transform hover:-translate-y-1">
                            Đăng ký mới
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

{{-- 🔥 ĐÃ CHUYỂN SCRIPT RA KHỎI SECTION('scripts') VÀ ĐẶT TRỰC TIẾP Ở ĐÂY ĐỂ ĐẢM BẢO CHẠY --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- KHAI BÁO BIẾN ---
    const specialties = document.querySelectorAll('.specialty-item');
    const doctors = document.querySelectorAll('.doctor-card');
    const times = document.querySelectorAll('.time-slot');
    const noDocMsg = document.getElementById('no-doctor-msg');
    
    const inpDept = document.getElementById('department_id');
    const inpDoc = document.getElementById('doctor_id');
    const inpTime = document.getElementById('time_slot');

    // --- HÀM LỌC BÁC SĨ ---
    function filterDoctors(deptId) {
        let visibleCount = 0;
        doctors.forEach(doc => {
            // Reset style khi lọc lại
            doc.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
            doc.querySelector('.check-mark').style.opacity = '0';
            doc.querySelector('.check-mark').style.transform = 'scale(0)';

            if(deptId === 'all' || !deptId || doc.getAttribute('data-dept-id') == deptId) {
                doc.style.display = 'block';
                visibleCount++;
            } else {
                doc.style.display = 'none';
            }
        });

        if(visibleCount === 0) {
            noDocMsg.classList.remove('hidden');
        } else {
            noDocMsg.classList.add('hidden');
        }
    }

    // --- TỰ ĐỘNG CHẠY KHI VÀO TRANG ---
    const currentDeptId = inpDept.value;
    if(currentDeptId) {
        filterDoctors(currentDeptId);
    } else {
        filterDoctors('all'); 
    }

    // --- SỰ KIỆN CHỌN CHUYÊN KHOA ---
    specialties.forEach(item => {
        item.addEventListener('click', () => {
            specialties.forEach(i => i.classList.remove('active', 'border-blue-500', 'bg-blue-50'));
            item.classList.add('active', 'border-blue-500', 'bg-blue-50');
            
            const deptId = item.getAttribute('data-dept-id');
            inpDept.value = (deptId === 'all') ? '' : deptId;
            
            filterDoctors(deptId);
            
            // Reset input bác sĩ khi đổi khoa để tránh lỗi
            inpDoc.value = "";
        });
    });

    // --- SỰ KIỆN CHỌN BÁC SĨ ---
    doctors.forEach(doc => {
        doc.addEventListener('click', () => {
            doctors.forEach(d => {
                d.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                d.querySelector('.check-mark').style.opacity = '0';
                d.querySelector('.check-mark').style.transform = 'scale(0)';
            });

            doc.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
            doc.querySelector('.check-mark').style.opacity = '1';
            doc.querySelector('.check-mark').style.transform = 'scale(1)';

            // 🔥 ĐIỀN GIÁ TRỊ VÀO INPUT ẨN
            inpDoc.value = doc.getAttribute('data-doctor-id');
            
            // Tự động chọn lại Khoa tương ứng nếu chưa chọn
            const docDeptId = doc.getAttribute('data-dept-id');
            if(inpDept.value === '' || inpDept.value !== docDeptId) {
                 inpDept.value = docDeptId;
                 specialties.forEach(s => {
                     s.classList.remove('active', 'border-blue-500', 'bg-blue-50');
                     if(s.getAttribute('data-dept-id') == docDeptId) {
                         s.classList.add('active', 'border-blue-500', 'bg-blue-50');
                     }
                 });
            }
        });
    });

    // --- SỰ KIỆN CHỌN GIỜ ---
    times.forEach(slot => {
        slot.addEventListener('click', () => {
            times.forEach(t => t.classList.remove('bg-blue-600', 'text-white', 'border-blue-600'));
            slot.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            
            // 🔥 ĐIỀN GIÁ TRỊ VÀO INPUT ẨN
            inpTime.value = slot.getAttribute('data-time');
        });
    });

    // --- VALIDATE FORM TRƯỚC KHI GỬI ---
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

</section>
@endsection