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
            <form id="booking-form">
                
                <!-- 1. Chọn chuyên khoa -->
                <div class="specialty-selection mb-8">
                    <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">1. Chọn chuyên khoa</h2>
                    {{-- <div id="specialty-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Items -->
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="general">
                            <i class="fas fa-stethoscope mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i> Khám tổng quát
                        </div>
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="cardiology">
                            <i class="fas fa-heartbeat mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i> Tim mạch
                        </div>
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="pediatrics">
                            <i class="fas fa-baby mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i> Nhi khoa
                        </div>
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="orthopedics">
                            <i class="fas fa-bone mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i> Chấn thương chỉnh hình
                        </div>
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="dermatology">
                            <i class="fas fa-allergies mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i> Da liễu
                        </div>
                        <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="neurology">
                            <i class="fas fa-brain mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i> Thần kinh
                        </div>
                    </div> --}}
                    <div id="specialty-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($departments as $department)
                            <div class="specialty-item group flex items-center justify-center p-4 border border-gray-300 rounded-xl cursor-pointer font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition duration-200" data-specialty="{{ Str::slug($department->name) }}">
                                <i class="{{ $department->icon ?? 'fas fa-stethoscope' }} mr-2 text-xl text-gray-500 group-hover:text-blue-600 transition duration-200"></i>
                                {{ $department->name }}
                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- 2. Chọn bác sĩ -->
                <div class="doctor-selection mb-8">
                    <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">2. Chọn bác sĩ</h2>
                    <div id="doctor-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Doctor 1 -->
                        <div class="doctor-card border border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:shadow-lg transition duration-200" data-doctor-id="an">
                            <img src="https://placehold.co/80x80/2563eb/ffffff?text=BS+An" alt="BS. Nguyễn Văn An" class="w-20 h-20 rounded-full object-cover mb-3 mx-auto border-4 border-white shadow-md">
                            <h4 class="text-lg font-bold mb-1">BS. Nguyễn Văn An</h4>
                            <p class="text-sm text-gray-500 leading-tight">Tim mạch, Khám tổng quát</p>
                            <p class="text-sm text-gray-500 leading-tight mb-2">15 năm kinh nghiệm</p>
                            <div class="rating text-yellow-500 text-sm">
                                <i class="fas fa-star"></i> 4.9 (120 đánh giá)
                            </div>
                        </div>

                        <!-- Doctor 2 -->
                        <div class="doctor-card border border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:shadow-lg transition duration-200" data-doctor-id="binh">
                            <img src="https://placehold.co/80x80/059669/ffffff?text=BS+Binh" alt="BS. Trần Thị Bình" class="w-20 h-20 rounded-full object-cover mb-3 mx-auto border-4 border-white shadow-md">
                            <h4 class="text-lg font-bold mb-1">BS. Trần Thị Bình</h4>
                            <p class="text-sm text-gray-500 leading-tight">Nhi khoa</p>
                            <p class="text-sm text-gray-500 leading-tight mb-2">12 năm kinh nghiệm</p>
                            <div class="rating text-yellow-500 text-sm">
                                <i class="fas fa-star"></i> 4.8 (88 đánh giá)
                            </div>
                        </div>

                        <!-- Doctor 3 -->
                        <div class="doctor-card border border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:shadow-lg transition duration-200" data-doctor-id="cuong">
                            <img src="https://placehold.co/80x80/9333ea/ffffff?text=BS+Cuong" alt="BS. Lê Minh Cường" class="w-20 h-20 rounded-full object-cover mb-3 mx-auto border-4 border-white shadow-md">
                            <h4 class="text-lg font-bold mb-1">BS. Lê Minh Cường</h4>
                            <p class="text-sm text-gray-500 leading-tight">Chấn thương chỉnh hình</p>
                            <p class="text-sm text-gray-500 leading-tight mb-2">18 năm kinh nghiệm</p>
                            <div class="rating text-yellow-500 text-sm">
                                <i class="fas fa-star"></i> 4.9 (210 đánh giá)
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 3. Chọn ngày & giờ khám -->
                <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">3. Chọn ngày & giờ khám</h2>
                <div class="time-selection grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <!-- Chọn Ngày -->
                    <div>
                        <label for="date-input" class="block font-semibold mb-1 text-gray-700">Ngày khám</label>
                        <div class="date-input-group relative">
                            <input type="date" id="date-input" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500" value="2025-10-06">
                            <i class="far fa-calendar-alt absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Chọn Giờ -->
                    <div>
                        <label for="time-slot" class="block font-semibold mb-1 text-gray-700">Giờ khám</label>
                        <div id="time-slot-grid" class="time-slot-grid grid grid-cols-3 gap-3 max-h-52 overflow-y-auto pr-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="08:30">08:30</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="09:00">09:00</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="09:30">09:30</div>
                            <div class="time-slot active bg-blue-600 text-white border-blue-600 p-2 rounded-md text-center cursor-pointer font-medium hover:bg-blue-700 transition duration-150" data-time="10:00">10:00</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="10:30">10:30</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="14:00">14:00</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="14:30">14:30</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="15:00">15:00</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="15:30">15:30</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="16:00">16:00</div>
                            <div class="time-slot p-2 border border-gray-300 rounded-md text-center cursor-pointer font-medium hover:bg-gray-200 transition duration-150" data-time="16:30">16:30</div>
                        </div>
                    </div>
                </div>

                <!-- 4. Thông Tin Bệnh Nhân -->
                <h2 class="form-section-title text-2xl font-bold mb-4 border-b pb-2 text-gray-700">4. Thông tin bệnh nhân</h2>
                <div class="patient-info mb-8">
                    <div class="patient-info-grid grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="input-group">
                            <label for="ho-ten" class="block font-semibold mb-1 text-gray-700">Họ và tên</label>
                            <input type="text" id="ho-ten" placeholder="Nhập họ và tên" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="input-group">
                            <label for="sdt" class="block font-semibold mb-1 text-gray-700">Số điện thoại</label>
                            <input type="tel" id="sdt" placeholder="Nhập số điện thoại" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="email" class="block font-semibold mb-1 text-gray-700">Email (Tùy chọn)</label>
                        <input type="email" id="email" placeholder="Nhập địa chỉ email" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="textarea-group">
                        <label for="trieu-chung" class="block font-semibold mb-1 text-gray-700">Mô tả triệu chứng (Tùy chọn)</label>
                        <textarea id="trieu-chung" placeholder="Mô tả ngắn gọn về triệu chứng hoặc lý do khám bệnh..." class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base min-h-[120px] resize-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        <p class="text-sm text-gray-500 mt-1 text-right">Tối đa 500 ký tự</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="submit-button text-center pt-4">
                    <button type="submit" class="bg-blue-600 text-white font-bold px-10 py-3 rounded-xl text-lg hover:bg-blue-700 transition duration-300 shadow-xl shadow-blue-400/50 w-full sm:w-auto">
                        <i class="far fa-calendar-alt mr-2"></i> Xác nhận Đặt lịch khám
                    </button>
                </div>

            </form>
        </div>
    </section>
@endsection