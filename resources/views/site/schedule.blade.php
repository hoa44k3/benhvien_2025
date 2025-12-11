@extends('site.master')

@section('title','Đặt lịch khám bệnh')
@section('body')
<!-- Banner Đặt Lịch -->
<section class="booking-banner bg-blue-600 text-white py-20 text-center mb-12">
    <div class="container mx-auto w-[90%] max-w-7xl">
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-3">Đặt lịch khám bệnh</h1>
        <p class="text-lg sm:text-xl opacity-90">Đặt lịch khám dễ dàng, nhanh chóng với đội ngũ bác sĩ chuyên nghiệp</p>
    </div>
</section>

<!-- Booking Form Container -->
<section class="booking-content flex justify-center pb-20 px-4">
    <div class="booking-card bg-white p-6 sm:p-10 rounded-2xl shadow-2xl w-full max-w-5xl">
        @if(Auth::check())
       <form action="{{ route('site.schedule.store') }}" method="POST" id="booking-form">
            @csrf
        
            <!-- 1. Chọn chuyên khoa -->
            <div class="specialty-selection mb-8">
                <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">1. Chọn chuyên khoa</h2>
                <input type="hidden" name="department_id" id="selected-department" value="">
                <div id="specialty-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-dept-id="all">
                        <i class="fas fa-layer-group mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i>
                        Tất cả
                    </div>
                    @foreach($departments as $department)
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-dept-id="{{ $department->id }}">
                            <i class="{{ $department->icon ?? 'fas fa-stethoscope' }} mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i>
                            {{ $department->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Chọn bác sĩ -->
            <div class="doctor-selection mb-8">
                <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">2. Chọn bác sĩ</h2>
                <input type="hidden" name="doctor_id" id="selected-doctor">

                <div id="doctor-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($doctors as $doctor)
                        <div class="doctor-card border border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:shadow-lg transition duration-200"
                             data-dept-id="{{ $doctor->department_id }}"
                             data-doctor-id="{{ $doctor->id }}">
                             
                            <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : 'https://placehold.co/80x80/cccccc/ffffff?text=BS' }}"
                                 class="w-20 h-20 rounded-full object-cover mb-3 mx-auto border-4 border-white shadow-md">

                            <h4 class="text-lg font-bold mb-1">{{ $doctor->user->name }}</h4>
                            <p class="text-sm text-gray-500 leading-tight">{{ $doctor->specialization }}</p>
                            <p class="text-sm text-gray-500 leading-tight mb-1">{{ $doctor->experience_years ?? 0 }} năm kinh nghiệm</p>

                            <div class="rating text-yellow-500 text-sm">
                                ⭐ {{ $doctor->rating ?? 0 }} ({{ $doctor->review_count ?? 0 }} đánh giá)
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Chọn ngày & giờ khám -->
            <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">3. Chọn ngày & giờ khám</h2>
            <div class="time-selection grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Ngày -->
                <div>
                    <label for="date-input" class="block font-semibold mb-1 text-gray-700">Ngày khám</label>
                    <input type="date" name="date" id="date-input" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500" value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Giờ -->
                <div>
                    <label class="block font-semibold mb-1 text-gray-700">Giờ khám</label>
                    <input type="hidden" name="time" id="selected-time" required>
                    <div id="time-slot-grid" class="grid grid-cols-3 gap-3 max-h-52 overflow-y-auto pr-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        @foreach($timeSlots as $time)
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="{{ $time }}">
                                {{ $time }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 4. Thông tin bệnh nhân -->
            <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">4. Thông tin bệnh nhân</h2>
            <div class="patient-info-grid grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="input-group">
                    <label class="block font-semibold mb-1 text-gray-700">Họ và tên</label>
                    <input type="text" name="patient_name" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500" readonly>
                </div>
                <div class="input-group">
                    <label for="patient_phone" class="block font-semibold mb-1 text-gray-700">Số điện thoại</label>
                    <input type="tel" name="patient_phone" id="patient_phone" placeholder="Nhập số điện thoại" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            <div class="textarea-group mb-4">
                <label for="reason" class="block font-semibold mb-1 text-gray-700">Mô tả triệu chứng (Tùy chọn)</label>
                <textarea name="reason" id="reason" placeholder="Mô tả ngắn gọn về triệu chứng..." class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base min-h-[120px] resize-none focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="submit-button text-center pt-4">
                <button type="submit" class="bg-blue-600 text-white font-bold px-10 py-3 rounded-xl text-lg hover:bg-blue-700 transition duration-300 shadow-xl shadow-blue-400/50 w-full sm:w-auto">
                    <i class="far fa-calendar-alt mr-2"></i> Xác nhận Đặt lịch khám
                </button>
            </div>

        </form>
        @else
            <p class="text-center text-red-500 font-semibold">Bạn cần <a href="{{ route('login') }}" class="underline">đăng nhập</a> để đặt lịch.</p>
        @endif
    </div>
</section>
@endsection

@section('scripts')
@section('scripts')
<script>
    const specialtyItems = document.querySelectorAll('.specialty-item');
    const doctorCards = document.querySelectorAll('.doctor-card');
    const timeSlots = document.querySelectorAll(".time-slot");
    
    const selectedDepartmentInput = document.getElementById('selected-department');
    const selectedDoctorInput = document.getElementById('selected-doctor');
    const selectedTimeInput = document.getElementById("selected-time");

    // --- 1. Chọn Chuyên Khoa và Lọc Bác Sĩ ---
    specialtyItems.forEach(item => {
        item.addEventListener('click', () => {
            // Loại bỏ active khỏi tất cả và thêm vào item được chọn
            specialtyItems.forEach(i => i.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600'));
            item.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600'); // Thêm màu nền và chữ
            
            // Xóa lựa chọn bác sĩ cũ
            doctorCards.forEach(c => c.classList.remove('border-blue-600', 'bg-blue-50'));
            selectedDoctorInput.value = ''; // Reset doctor_id

            const deptId = item.getAttribute('data-dept-id');
            selectedDepartmentInput.value = (deptId === 'all') ? '' : deptId; // Lưu department_id, nếu 'all' thì lưu rỗng
            
            // Lọc bác sĩ theo chuyên khoa
            doctorCards.forEach(card => card.style.display = 'none');
            doctorCards.forEach(card => {
                if(deptId === 'all' || card.getAttribute('data-dept-id') === deptId) {
                    card.style.display = 'block';
                }
            });
        });
    });

    // --- 2. Chọn Bác Sĩ ---
    doctorCards.forEach(card => {
        card.addEventListener('click', () => {
            // Loại bỏ active khỏi tất cả và thêm vào card được chọn
            doctorCards.forEach(c => c.classList.remove('border-blue-600', 'bg-blue-50'));
            card.classList.add('border-blue-600', 'bg-blue-50'); // Thêm màu nền nhẹ
            
            selectedDoctorInput.value = card.getAttribute('data-doctor-id');
        });
    });

    // --- 3. Chọn Giờ Khám ---
    timeSlots.forEach(slot => {
        slot.addEventListener("click", () => {
            // Loại bỏ active khỏi tất cả và thêm vào slot được chọn
            timeSlots.forEach(s => s.classList.remove("bg-blue-600", "text-white", "border-blue-600"));
            slot.classList.add("bg-blue-600", "text-white", "border-blue-600");
            selectedTimeInput.value = slot.getAttribute("data-time");
        });
    });

    // --- Đảm bảo lựa chọn trước khi Submit ---
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        if (!selectedDepartmentInput.value) {
            alert('Vui lòng chọn Chuyên khoa.');
            e.preventDefault();
            return;
        }
        if (!selectedDoctorInput.value) {
            alert('Vui lòng chọn Bác sĩ.');
            e.preventDefault();
            return;
        }
        if (!selectedTimeInput.value) {
            alert('Vui lòng chọn Giờ khám.');
            e.preventDefault();
            return;
        }
    });

</script>
@endsection
@endsection
