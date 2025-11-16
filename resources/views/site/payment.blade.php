@extends('site.master')

@section('title','Thanh toán viện phí')
@section('body')
    <section class="py-16 mb-8 shadow-lg" style="background-image: linear-gradient(to right, var(--primary-color), #14b8a6);">
        <div class="container mx-auto max-w-7xl px-4 text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">Thanh toán viện phí</h1>
            <p class="text-lg opacity-90">Thanh toán viện phí an toàn, nhanh chóng qua nhiều phương thức khác nhau</p>
        </div>
    </section>

    <div class="container mx-auto max-w-7xl px-4 pb-12">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            
            <div class="p-6 rounded-xl shadow-lg border border-gray-200 bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mb-3 bg-red-200 text-red-800 text-xl"><i class="fas fa-dollar-sign"></i></div>
                <h3 class="text-lg font-semibold text-gray-700">Chưa thanh toán</h3>
                <strong class="text-3xl font-extrabold block mt-1 text-red-600">950.000 đ</strong>
                <small class="text-gray-500">1 hóa đơn</small>
            </div>
            
            <div class="p-6 rounded-xl shadow-lg border border-gray-200 bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mb-3 bg-green-200 text-green-800 text-xl"><i class="fas fa-check-circle"></i></div>
                <h3 class="text-lg font-semibold text-gray-700">Đã thanh toán</h3>
                <strong class="text-3xl font-extrabold block mt-1 text-green-600">1.080.000 đ</strong>
                <small class="text-gray-500">2 hóa đơn</small>
            </div>
            
            <div class="p-6 rounded-xl shadow-lg border border-gray-200 bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mb-3 bg-blue-200 text-blue-800 text-xl"><i class="fas fa-file-invoice"></i></div>
                <h3 class="text-lg font-semibold text-gray-700">Tổng hóa đơn</h3>
                <strong class="text-3xl font-extrabold block mt-1 text-blue-600">2.030.000 đ</strong>
                <small class="text-gray-500">3 hóa đơn</small>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold mb-6 pl-3 border-l-4 border-teal-600 text-gray-700">Danh sách hóa đơn</h2>

        <div class="bg-white rounded-xl shadow-md p-6 mb-6 border-l-8 border-red-500" id="invoice-001">
            
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 flex-wrap">
                <div class="mr-4 mb-2 md:mb-0">
                    <div class="text-xl font-semibold text-gray-700">
                        Hóa đơn <span class="text-teal-600">#INV001</span>
                    </div>
                    <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                        <span><i class="far fa-calendar-alt mr-1"></i> 15/1/2024</span>
                        <span><i class="fas fa-user-md mr-1"></i> BS. Nguyễn Văn An</span>
                        <span><i class="fas fa-clinic-medical mr-1"></i> Khám bệnh</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">Chưa thanh toán</span>
                    <div class="text-3xl font-extrabold mt-1 text-red-600">950.000 đ</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pb-6 mb-4 border-b border-dashed border-gray-300">
                
                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Dịch vụ khám</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span class="text-gray-700">Khám tim mạch</span>
                            <span class="font-medium text-gray-900">200.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Điện tâm đồ</span>
                            <span class="font-medium text-gray-900">150.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Siêu âm tim</span>
                            <span class="font-medium text-gray-900">300.000 đ</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Thuốc</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span class="text-gray-700">Amlodipine 5mg (30 viên)</span>
                            <span class="font-medium text-gray-900">120.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Losartan 50mg (30 viên)</span>
                            <span class="font-medium text-gray-900">180.000 đ</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-4 flex-wrap pt-2">
                <span class="mr-auto text-sm font-medium text-orange-500"><i class="far fa-clock mr-1"></i> Hạn thanh toán: 22/1/2024</span>
                <a href="#" class="px-4 py-2 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition duration-300 flex items-center"><i class="fas fa-download mr-2"></i> Tải hóa đơn</a>
                <button class="toggle-payment-detail px-4 py-2 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 flex items-center" data-target="detail-001"><i class="fas fa-wallet mr-2"></i> Thanh toán</button>
            </div>
            
            <div class="payment-detail-section hidden pt-6 mt-4 border-t border-gray-200" id="detail-001">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Chọn phương thức thanh toán</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    
                    <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                        <input type="radio" name="payment_method_001" value="vnpay" class="h-5 w-5 accent-teal-600" checked>
                        <div class="flex-grow">
                            <strong class="block text-gray-900">VNPay</strong>
                            <span class="text-sm text-gray-600">Thanh toán qua ví điện tử VNPay</span>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                        <input type="radio" name="payment_method_001" value="momo" class="h-5 w-5 accent-teal-600">
                        <div class="flex-grow">
                            <strong class="block text-gray-900">MoMo</strong>
                            <span class="text-sm text-gray-600">Thanh toán qua ví điện tử MoMo</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                        <input type="radio" name="payment_method_001" value="card" class="h-5 w-5 accent-teal-600">
                        <div class="flex-grow">
                            <strong class="block text-gray-900"><i class="fas fa-credit-card mr-1 text-gray-500"></i> Thẻ ngân hàng</strong>
                            <span class="text-sm text-gray-600">Thanh toán bằng thẻ ATM/Visa/Mastercard</span>
                            <span class="block text-xs text-red-500 mt-0.5">Phí giao dịch: 5.000 đ</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                        <input type="radio" name="payment_method_001" value="transfer" class="h-5 w-5 accent-teal-600">
                        <div class="flex-grow">
                            <strong class="block text-gray-900"><i class="fas fa-exchange-alt mr-1 text-gray-500"></i> Chuyển khoản</strong>
                            <span class="text-sm text-gray-600">Chuyển khoản ngân hàng trực tiếp</span>
                        </div>
                    </label>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-teal-50 border border-teal-200 rounded-lg flex-wrap gap-3">
                    <strong class="text-lg text-gray-800">Tổng thanh toán</strong>
                    <div class="flex items-center gap-5">
                        <strong class="text-2xl font-extrabold text-teal-600">950.000 đ</strong>
                        <button class="px-5 py-2.5 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 flex items-center">
                            <i class="fas fa-arrow-right mr-2"></i> Thanh toán ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 mb-6 border-l-8 border-green-500">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 flex-wrap">
                <div class="mr-4 mb-2 md:mb-0">
                    <div class="text-xl font-semibold text-gray-700">
                        Hóa đơn <span class="text-teal-600">#INV002</span>
                    </div>
                    <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                        <span><i class="far fa-calendar-alt mr-1"></i> 8/1/2024</span>
                        <span><i class="fas fa-user-md mr-1"></i> BS. Trần Thị Bình</span>
                        <span><i class="fas fa-clinic-medical mr-1"></i> Khám bệnh</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">Đã thanh toán</span>
                    <div class="text-3xl font-extrabold mt-1 text-gray-800">460.000 đ</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pb-6 mb-4 border-b border-dashed border-gray-300">
                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Dịch vụ khám</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span class="text-gray-700">Khám tổng quát</span>
                            <span class="font-medium text-gray-900">150.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Xét nghiệm máu</span>
                            <span class="font-medium text-gray-900">200.000 đ</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Thuốc</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span class="text-gray-700">Amoxicillin 500mg (21 viên)</span>
                            <span class="font-medium text-gray-900">80.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Paracetamol 500mg (20 viên)</span>
                            <span class="font-medium text-gray-900">30.000 đ</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-4 flex-wrap pt-2">
                <span class="mr-auto text-sm font-medium text-green-600"><i class="fas fa-check-circle mr-1"></i> Đã thanh toán ngày 10/1/2024</span>
                <a href="#" class="px-4 py-2 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition duration-300 flex items-center"><i class="fas fa-download mr-2"></i> Tải hóa đơn</a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 mb-6 border-l-8 border-green-500">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 flex-wrap">
                <div class="mr-4 mb-2 md:mb-0">
                    <div class="text-xl font-semibold text-gray-700">
                        Hóa đơn <span class="text-teal-600">#INV003</span>
                    </div>
                    <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                        <span><i class="far fa-calendar-alt mr-1"></i> 20/12/2023</span>
                        <span><i class="fas fa-user-md mr-1"></i> BS. Lê Minh Cường</span>
                        <span><i class="fas fa-clinic-medical mr-1"></i> Khám bệnh</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">Đã thanh toán</span>
                    <div class="text-3xl font-extrabold mt-1 text-gray-800">620.000 đ</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pb-6 mb-4 border-b border-dashed border-gray-300">
                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Dịch vụ khám</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span class="text-gray-700">Khám chấn thương</span>
                            <span class="font-medium text-gray-900">180.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Chụp X-quang</span>
                            <span class="font-medium text-gray-900">250.000 đ</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-700">Nẹp cố định</span>
                            <span class="font-medium text-gray-900">100.000 đ</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Thuốc</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span class="text-gray-700">Thuốc giảm đau (14 viên)</span>
                            <span class="font-medium text-gray-900">90.000 đ</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-4 flex-wrap pt-2">
                <span class="mr-auto text-sm font-medium text-green-600"><i class="fas fa-check-circle mr-1"></i> Đã thanh toán ngày 22/12/2023</span>
                <a href="#" class="px-4 py-2 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition duration-300 flex items-center"><i class="fas fa-download mr-2"></i> Tải hóa đơn</a>
            </div>
        </div>
        
    </div>
@endsection