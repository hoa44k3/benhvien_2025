@extends('site.master')

@section('title', 'Đặt lịch khám bệnh')

@section('body')
<section class="booking-banner bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16 text-center mb-10 shadow-lg">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4">Đặt Lịch Khám Trực Tuyến</h1>
        <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">Chọn bác sĩ, chuyên khoa và thời gian phù hợp với bạn chỉ trong vài bước đơn giản.</p>
    </div>
</section>

<section class="booking-content pb-20 px-4">
    <div class="container mx-auto max-w-5xl">
        
        <div class="bg-white p-6 md:p-10 rounded-2xl shadow-xl border border-gray-100">
            @if(Auth::check())
                {{-- Form trỏ đúng đến route storeFromSite --}}
                <form action="{{ route('site.schedule.store') }}" method="POST" id="booking-form">
                    @csrf
                    
                    {{-- CÁC INPUT ẨN ĐỂ CHỨA DỮ LIỆU GỬI ĐI --}}
                    <input type="hidden" name="department_id" id="department_id">
                    <input type="hidden" name="doctor_id" id="doctor_id">
                    <input type="hidden" name="time" id="time_slot">

                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">1</span>
                            Chọn chuyên khoa
                        </h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div class="specialty-item group flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200" data-dept-id="all">
                                <i class="fas fa-th-large text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                                <span class="font-semibold text-gray-600 group-hover:text-blue-600">Tất cả</span>
                            </div>

                            @foreach($departments as $department)
                                <div class="specialty-item group flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200" data-dept-id="{{ $department->id }}">
                                    {{-- Icon mặc định nếu không có ảnh --}}
                                    <i class="fas fa-heartbeat text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                                    <span class="font-semibold text-gray-600 group-hover:text-blue-600 text-center">{{ $department->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">2</span>
                            Chọn bác sĩ
                        </h2>

                        <div id="doctor-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($doctors as $doctor)
                                <div class="doctor-card border-2 border-gray-100 rounded-xl p-5 cursor-pointer hover:shadow-lg hover:border-blue-500 transition-all duration-200 bg-white"
                                     data-dept-id="{{ $doctor->department_id }}"
                                     data-doctor-id="{{ $doctor->id }}">
                                     
                                    <div class="flex items-center mb-3">
                                        {{-- Ảnh đại diện bác sĩ --}}
                                        <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user->name).'&background=random' }}"
                                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 mr-4">
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $doctor->user->name }}</h4>
                                            <p class="text-xs text-blue-600 font-semibold uppercase">{{ $doctor->specialization ?? 'Chuyên khoa' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm text-gray-500 border-t pt-3 mt-2">
                                        <span><i class="fas fa-star text-yellow-400 mr-1"></i> {{ $doctor->rating ?? 5.0 }}</span>
                                        <span>{{ $doctor->experience_years ?? 1 }} năm KN</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p id="no-doctor-msg" class="hidden text-center text-gray-500 py-4 italic">Không tìm thấy bác sĩ phù hợp.</p>
                    </div>

                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">3</span>
                            Chọn ngày & giờ khám
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Ngày khám</label>
                                <div class="relative">
                                    <input type="date" name="date" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                           value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Giờ khám</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($timeSlots as $time)
                                        <div class="time-slot py-2 px-1 border border-gray-300 rounded text-center cursor-pointer text-sm font-medium hover:bg-blue-50 hover:text-blue-600 hover:border-blue-400 transition" 
                                             data-time="{{ $time }}">
                                            {{ $time }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">4</span>
                            Thông tin bệnh nhân
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Họ và tên</label>
                                <input type="text" name="patient_name" value="{{ Auth::user()->name }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500" readonly>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Số điện thoại</label>
                                <input type="tel" name="patient_phone" value="{{ Auth::user()->phone ?? '' }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Mô tả triệu chứng (Tùy chọn)</label>
                            <textarea name="reason" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!session('success'))
                    <div class="pt-6 border-t border-gray-100 text-center submit-area">
                        <button type="submit" class="bg-blue-600 text-white font-bold text-lg px-12 py-4 rounded-full shadow-lg hover:bg-blue-700 hover:shadow-xl transform hover:-translate-y-1 transition duration-300 w-full md:w-auto">
                            <i class="fas fa-paper-plane mr-2"></i> Xác Nhận Đặt Lịch
                        </button>
                    </div>
                    @endif

                    @if(session('success'))
                        <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-inner text-center animate-pulse">
                            <i class="fas fa-check-circle mr-2"></i> 
                            <strong>Đặt lịch thành công!</strong> Chúng tôi sẽ xác nhận qua SĐT sớm nhất.
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('site.schedule.store') }}" onclick="window.location.reload();" class="text-blue-600 underline">Đặt thêm lịch khác</a>
                        </div>
                    @endif

                </form>
            @else
                {{-- Màn hình yêu cầu đăng nhập --}}
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-user-lock"></i></div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Vui lòng đăng nhập</h3>
                    <p class="text-gray-500 mb-8">Bạn cần đăng nhập tài khoản để thực hiện đặt lịch khám.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Đăng nhập ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

{{-- JAVASCRIPT XỬ LÝ GIAO DIỆN VÀ ĐIỀN DỮ LIỆU --}}
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Khai báo
    const specialties = document.querySelectorAll('.specialty-item');
    const doctors = document.querySelectorAll('.doctor-card');
    const times = document.querySelectorAll('.time-slot');
    
    const inpDept = document.getElementById('department_id');
    const inpDoc = document.getElementById('doctor_id');
    const inpTime = document.getElementById('time_slot');

    // 2. Chọn Chuyên khoa
    specialties.forEach(item => {
        item.addEventListener('click', () => {
            // UI
            specialties.forEach(i => i.classList.remove('border-blue-500', 'bg-blue-50'));
            item.classList.add('border-blue-500', 'bg-blue-50');
            
            // Logic Lọc
            const deptId = item.getAttribute('data-dept-id');
            inpDept.value = (deptId === 'all') ? '' : deptId;

            // Reset Bác sĩ
            doctors.forEach(d => {
                d.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                if(deptId === 'all' || d.getAttribute('data-dept-id') === deptId) {
                    d.style.display = 'block';
                } else {
                    d.style.display = 'none';
                }
            });
            inpDoc.value = "";
        });
    });

    // 3. Chọn Bác sĩ
    doctors.forEach(doc => {
        doc.addEventListener('click', () => {
            // UI
            doctors.forEach(d => d.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200'));
            doc.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');

            // Logic: Điền ID Bác sĩ và ID Khoa
            inpDoc.value = doc.getAttribute('data-doctor-id');
            inpDept.value = doc.getAttribute('data-dept-id'); // Tự động điền khoa
        });
    });

    // 4. Chọn Giờ
    times.forEach(slot => {
        slot.addEventListener('click', () => {
            // UI
            times.forEach(t => t.classList.remove('bg-blue-600', 'text-white', 'border-blue-600'));
            slot.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            
            // Logic
            inpTime.value = slot.getAttribute('data-time');
        });
    });

    // 5. Validate trước khi gửi (để không load lại trang nếu quên chọn)
    const form = document.getElementById('booking-form');
    if(form) {
        form.addEventListener('submit', function(e) {
            if(!inpDoc.value) {
                alert("Vui lòng chọn Bác sĩ!");
                e.preventDefault();
                return;
            }
            if(!inpTime.value) {
                alert("Vui lòng chọn Giờ khám!");
                e.preventDefault();
                return;
            }
        });
    }
});
</script>
@endsection