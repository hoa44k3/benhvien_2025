@extends('site.master')

@section('title','Liên hệ')
@section('body')
    <section class="py-16 mb-8 shadow-lg" style="background-image: linear-gradient(to right, var(--primary-color), #14b8a6);">
        <div class="container mx-auto max-w-7xl px-4 text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">Liên hệ với chúng tôi</h1>
            <p class="text-lg opacity-90">Chúng tôi luôn sẵn sàng hỗ trợ và giải đáp mọi thắc mắc của bạn</p>
        </div>
    </section>

    <div class="container mx-auto max-w-7xl px-4 pb-12">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="fas fa-map-marker-alt"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Địa chỉ</h3>
                <p class="text-gray-600 mb-2">123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>
                <a href="#" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Xem bản đồ</a>
            </div>
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="fas fa-phone-alt"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Điện thoại</h3>
                <p class="text-gray-600 mb-2 font-mono">(028) 1234 5678</p>
                <a href="tel:02812345678" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Gọi ngay</a>
            </div>
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="fas fa-envelope"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Email</h3>
                <p class="text-gray-600 mb-2 break-words">info@smarthospital.vn</p>
                <a href="mailto:info@smarthospital.vn" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Gửi email</a>
            </div>
            
            <div class="p-6 rounded-xl shadow-md border border-gray-200 bg-white text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-3 bg-teal-100 text-teal-600 text-xl"><i class="far fa-clock"></i></div>
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Giờ làm việc</h3>
                <p class="text-gray-600 mb-2">Thứ 2 - Chủ nhật: <br class="md:hidden"> 6:00 - 22:00</p>
                <a href="#" class="text-teal-600 font-semibold hover:text-teal-700 transition duration-300">Xem chi tiết</a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
            
            <div class="form-section lg:col-span-3 bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                <h2 class="text-2xl font-bold mb-6 text-gray-700 border-l-4 border-teal-600 pl-3">Gửi tin nhắn</h2>
                <form action="#" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="form-group">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Chủ đề <span class="text-red-500">*</span></label>
                            <select id="subject" name="subject" required class="appearance-none">
                                <option value="">Chọn chủ đề</option>
                                <option value="dat_lich">Đặt lịch khám</option>
                                <option value="ho_tro">Yêu cầu hỗ trợ</option>
                                <option value="gop_y">Góp ý</option>
                                <option value="khac">Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="block text-sm font-medium text-gray-700">Nội dung tin nhắn <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" placeholder="Nhập nội dung tin nhắn..." maxlength="500" rows="5" required></textarea>
                        <span class="char-count text-xs text-gray-500 mt-1 block">Tối đa 500 ký tự</span>
                    </div>
                    
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                    </button>
                </form>
            </div>
            
            <div class="map-section lg:col-span-2 space-y-8">
                
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Vị trí bệnh viện</h2>
                    <div class="map-container relative pb-[56.25%] h-0 overflow-hidden rounded-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1685360986685!2d106.69670111471853!3d10.793739792312695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f36070a2529%3A0x6b44910e5b72e5a!2zVmlldGluYmV0IC0gU21hcnRIb3NwaXRhbCBzdGFuZGFyZCBzb2x1dGlvbiBhbmQgaG9zcGl0YWw!5e0!3m2!1svi!2s" 
                            class="absolute top-0 left-0 w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <a href="#" class="block text-teal-600 font-semibold mt-3 text-sm hover:text-teal-700 transition duration-300">View larger map</a>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Liên hệ các khoa</h2>
                    <div class="space-y-3">
                        
                        <div class="department-card p-3 border-l-4 border-teal-500 bg-teal-50 rounded-md">
                            <h4 class="text-lg font-semibold mb-1 text-teal-800">Khoa Khám bệnh</h4>
                            <p class="text-sm text-gray-600"><i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5679</p>
                            <p class="text-sm text-gray-600"><i class="fas fa-envelope w-4 mr-1"></i> khambenh@smarthospital.vn</p>
                            <p class="text-sm text-gray-600"><i class="far fa-clock w-4 mr-1"></i> 6:00 - 20:00</p>
                        </div>
                        
                        <div class="department-card p-3 border-l-4 border-red-500 bg-red-50 rounded-md">
                            <h4 class="text-lg font-semibold mb-1 text-red-800">Khoa Cấp cứu</h4>
                            <p class="text-sm text-gray-600"><i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5680</p>
                            <p class="text-sm text-gray-600"><i class="fas fa-envelope w-4 mr-1"></i> capcuu@smarthospital.vn</p>
                            <p class="text-sm text-gray-600"><i class="far fa-clock w-4 mr-1"></i> 24/7</p>
                        </div>

                        <div class="department-card p-3 border-l-4 border-blue-500 bg-blue-50 rounded-md">
                            <h4 class="text-lg font-semibold mb-1 text-blue-800">Khoa Xét nghiệm</h4>
                            <p class="text-sm text-gray-600"><i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5681</p>
                            <p class="text-sm text-gray-600"><i class="fas fa-envelope w-4 mr-1"></i> xetnghiem@smarthospital.vn</p>
                            <p class="text-sm text-gray-600"><i class="far fa-clock w-4 mr-1"></i> 6:00 - 18:00</p>
                        </div>

                        <div class="department-card p-3 border-l-4 border-purple-500 bg-purple-50 rounded-md">
                            <h4 class="text-lg font-semibold mb-1 text-purple-800">Khoa Dược</h4>
                            <p class="text-sm text-gray-600"><i class="fas fa-phone-alt w-4 mr-1"></i> (028) 1234 5682</p>
                            <p class="text-sm text-gray-600"><i class="fas fa-envelope w-4 mr-1"></i> duoc@smarthospital.vn</p>
                            <p class="text-sm text-gray-600"><i class="far fa-clock w-4 mr-1"></i> 6:00 - 20:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <section class="py-12 bg-white mt-12 shadow-inner">
        <div class="container mx-auto max-w-7xl px-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-gray-700">Câu hỏi thường gặp</h2>
            <p class="text-center text-gray-600 mb-8">Tìm câu trả lời cho những thắc mắc phổ biến</p>
            
            <div class="faq-list max-w-3xl mx-auto space-y-4">
                
                <div class="faq-item border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="faq-question flex justify-between items-center p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300">
                        <span class="font-semibold text-gray-700">Làm thế nào để đặt lịch khám online?</span>
                        <i class="fas fa-plus text-teal-600 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer p-4 pt-0 text-gray-600 hidden">
                        <p>Bạn có thể đặt lịch khám trực tuyến bằng cách truy cập mục **"Đặt lịch khám"** trên thanh menu. Sau đó, chọn chuyên khoa hoặc bác sĩ, chọn ngày giờ khám phù hợp và điền thông tin cá nhân. Hệ thống sẽ gửi xác nhận lịch qua email hoặc tin nhắn.</p>
                    </div>
                </div>

                <div class="faq-item border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="faq-question flex justify-between items-center p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300">
                        <span class="font-semibold text-gray-700">Tôi có thể xem kết quả xét nghiệm online không?</span>
                        <i class="fas fa-plus text-teal-600 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer p-4 pt-0 text-gray-600 hidden">
                        <p>Có. Bạn có thể xem kết quả xét nghiệm đã có sẵn (màu xanh lá "Có kết quả") trong mục **"Hồ sơ bệnh án"**, tab "Kết quả xét nghiệm". Kết quả sẽ được cập nhật ngay khi hoàn thành.</p>
                    </div>
                </div>

                <div class="faq-item border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="faq-question flex justify-between items-center p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300">
                        <span class="font-semibold text-gray-700">Các phương thức thanh toán nào được hỗ trợ?</span>
                        <i class="fas fa-plus text-teal-600 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer p-4 pt-0 text-gray-600 hidden">
                        <p>Chúng tôi hỗ trợ nhiều phương thức thanh toán trực tuyến an toàn bao gồm: **VNPay, MoMo, Thẻ ngân hàng** (ATM/Visa/Mastercard) và **Chuyển khoản** ngân hàng trực tiếp. Vui lòng kiểm tra mục "Thanh toán" để xem chi tiết.</p>
                    </div>
                </div>

                <div class="faq-item border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="faq-question flex justify-between items-center p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300">
                        <span class="font-semibold text-gray-700">Làm thế nào để hủy lịch khám?</span>
                        <i class="fas fa-plus text-teal-600 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer p-4 pt-0 text-gray-600 hidden">
                        <p>Để hủy lịch khám, vui lòng truy cập mục quản lý lịch hẹn (thường nằm trong "Hồ sơ bệnh án" hoặc "Đặt lịch khám"), tìm lịch hẹn muốn hủy và chọn tùy chọn **"Hủy lịch"**. Hoặc bạn có thể gọi đến tổng đài hỗ trợ để được trợ giúp.</p>
                    </div>
                </div>
                
                <div class="faq-item border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="faq-question flex justify-between items-center p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300">
                        <span class="font-semibold text-gray-700">Tôi quên mật khẩu, làm sao để lấy lại?</span>
                        <i class="fas fa-plus text-teal-600 transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer p-4 pt-0 text-gray-600 hidden">
                        <p>Trên trang Đăng nhập, nhấp vào liên kết **"Quên mật khẩu"**. Bạn sẽ được yêu cầu nhập địa chỉ email hoặc số điện thoại đã đăng ký để nhận liên kết hoặc mã khôi phục mật khẩu mới.</p>
                    </div>
                </div>
            </div>
            
            <div class="faq-support-text text-center mt-10">
                <p class="text-lg font-medium text-gray-700">Không tìm thấy câu trả lời bạn cần?</p>
                <a href="#" class="inline-flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 mt-4">
                    <i class="fas fa-headphones-alt"></i> Liên hệ hỗ trợ
                </a>
            </div>
        </div>
    </section>
@endsection