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
        
        {{-- Thông báo --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Thành công!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Thống kê --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="p-6 rounded-xl shadow-lg border border-gray-200 bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mb-3 bg-red-200 text-red-800 text-xl"><i class="fas fa-dollar-sign"></i></div>
                <h3 class="text-lg font-semibold text-gray-700">Chưa thanh toán</h3>
                <strong class="text-3xl font-extrabold block mt-1 text-red-600">{{ number_format($unpaidTotal) }} đ</strong>
                <small class="text-gray-500">{{ $invoices->where('status', 'unpaid')->count() }} hóa đơn</small>
            </div>
            
            <div class="p-6 rounded-xl shadow-lg border border-gray-200 bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mb-3 bg-green-200 text-green-800 text-xl"><i class="fas fa-check-circle"></i></div>
                <h3 class="text-lg font-semibold text-gray-700">Đã thanh toán</h3>
                <strong class="text-3xl font-extrabold block mt-1 text-green-600">{{ number_format($paidTotal) }} đ</strong>
                <small class="text-gray-500">{{ $invoices->where('status', 'paid')->count() }} hóa đơn</small>
            </div>
            
            <div class="p-6 rounded-xl shadow-lg border border-gray-200 bg-white hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                <div class="w-12 h-12 flex items-center justify-center rounded-full mb-3 bg-blue-200 text-blue-800 text-xl"><i class="fas fa-file-invoice"></i></div>
                <h3 class="text-lg font-semibold text-gray-700">Tổng hóa đơn</h3>
                <strong class="text-3xl font-extrabold block mt-1 text-blue-600">{{ number_format($totalAmount) }} đ</strong>
                <small class="text-gray-500">{{ $invoices->count() }} hóa đơn</small>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold mb-6 pl-3 border-l-4 border-teal-600 text-gray-700">Danh sách hóa đơn</h2>

        {{-- Danh sách hóa đơn --}}
        @forelse($invoices as $invoice)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6 border-l-8 {{ $invoice->status == 'paid' ? 'border-green-500' : 'border-red-500' }}" id="invoice-{{ $invoice->id }}">
            
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 flex-wrap">
                <div class="mr-4 mb-2 md:mb-0">
                    <div class="text-xl font-semibold text-gray-700">
                        Hóa đơn <span class="text-teal-600">#{{ $invoice->code }}</span>
                    </div>
                    <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                        <span><i class="far fa-calendar-alt mr-1"></i> {{ $invoice->created_at->format('d/m/Y') }}</span>
                        @if($invoice->medicalRecord && $invoice->medicalRecord->doctor)
                            <span><i class="fas fa-user-md mr-1"></i> BS. {{ $invoice->medicalRecord->doctor->name }}</span>
                        @endif
                        <span><i class="fas fa-clinic-medical mr-1"></i> {{ $invoice->medicalRecord->department->name ?? 'Thu phí dịch vụ' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    @if($invoice->status == 'paid')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">Đã thanh toán</span>
                        <div class="text-3xl font-extrabold mt-1 text-green-600">{{ number_format($invoice->total) }} đ</div>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">Chưa thanh toán</span>
                        <div class="text-3xl font-extrabold mt-1 text-red-600">{{ number_format($invoice->total) }} đ</div>
                    @endif
                </div>
            </div>

            {{-- Chi tiết items --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pb-6 mb-4 border-b border-dashed border-gray-300">
                <div>
                    <div class="text-base font-semibold text-teal-600 mb-2 pb-1 border-b border-gray-100">Chi tiết dịch vụ & Thuốc</div>
                    <ul class="space-y-1 text-sm text-gray-600">
                        @foreach($invoice->items as $item)
                        <li class="flex justify-between">
                            <span class="text-gray-700">{{ $item->item_name ?? $item->description }} (x{{ $item->quantity }})</span>
                            <span class="font-medium text-gray-900">{{ number_format($item->total ?? $item->total_price) }} đ</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-4 flex-wrap pt-2">
                @if($invoice->status == 'unpaid')
                    <span class="mr-auto text-sm font-medium text-orange-500"><i class="far fa-clock mr-1"></i> Vui lòng thanh toán</span>
                    
                    {{-- Nút Toggle form thanh toán --}}
                    <button class="toggle-payment-detail px-4 py-2 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 flex items-center" 
                            onclick="document.getElementById('detail-{{ $invoice->id }}').classList.toggle('hidden')">
                        <i class="fas fa-wallet mr-2"></i> Thanh toán
                    </button>
                @else
                    <span class="mr-auto text-sm font-medium text-green-600">
                        <i class="fas fa-check-circle mr-1"></i> Thanh toán lúc {{ \Carbon\Carbon::parse($invoice->paid_at)->format('H:i d/m/Y') }} qua {{ strtoupper($invoice->payment_method) }}
                    </span>
                    {{-- Có thể thêm link download PDF --}}
                    {{-- <button class="px-4 py-2 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition duration-300 flex items-center">
                        <i class="fas fa-download mr-2"></i> Tải hóa đơn
                    </button> --}}
                    <a href="{{ route('invoice.download', $invoice->id) }}" 
       class="px-4 py-2 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition duration-300 flex items-center text-decoration-none">
        <i class="fas fa-download mr-2"></i> Tải hóa đơn
    </a>
                @endif
            </div>
            
            {{-- FORM THANH TOÁN (Ẩn mặc định) --}}
            @if($invoice->status == 'unpaid')
            <div class="payment-detail-section hidden pt-6 mt-4 border-t border-gray-200" id="detail-{{ $invoice->id }}">
                <form action="{{ route('payment.process', $invoice->id) }}" method="POST">
                    @csrf
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Chọn phương thức thanh toán</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                            <input type="radio" name="payment_method" value="vnpay" class="h-5 w-5 accent-teal-600" checked>
                            <div class="flex-grow">
                                <strong class="block text-gray-900">VNPay</strong>
                                <span class="text-sm text-gray-600">Ví VNPay / QR Code</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                            <input type="radio" name="payment_method" value="momo" class="h-5 w-5 accent-teal-600">
                            <div class="flex-grow">
                                <strong class="block text-gray-900">MoMo</strong>
                                <span class="text-sm text-gray-600">Ví MoMo</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                            <input type="radio" name="payment_method" value="card" class="h-5 w-5 accent-teal-600">
                            <div class="flex-grow">
                                <strong class="block text-gray-900"><i class="fas fa-credit-card mr-1 text-gray-500"></i> Thẻ ngân hàng</strong>
                                <span class="text-sm text-gray-600">ATM / Visa / Mastercard</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer bg-gray-50 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 transition duration-300">
                            <input type="radio" name="payment_method" value="transfer" class="h-5 w-5 accent-teal-600">
                            <div class="flex-grow">
                                <strong class="block text-gray-900"><i class="fas fa-exchange-alt mr-1 text-gray-500"></i> Chuyển khoản</strong>
                                <span class="text-sm text-gray-600">Xác nhận thủ công</span>
                            </div>
                        </label>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-teal-50 border border-teal-200 rounded-lg flex-wrap gap-3">
                        <strong class="text-lg text-gray-800">Tổng thanh toán</strong>
                        <div class="flex items-center gap-5">
                            <strong class="text-2xl font-extrabold text-teal-600">{{ number_format($invoice->total) }} đ</strong>
                            <button type="submit" class="px-5 py-2.5 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition duration-300 flex items-center">
                                <i class="fas fa-arrow-right mr-2"></i> Thanh toán ngay
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
        @empty
            <div class="text-center py-10 text-gray-500">
                <i class="fas fa-file-invoice text-4xl mb-3 text-gray-400"></i>
                <p class="text-lg">Bạn chưa có hóa đơn nào.</p>
            </div>
        @endforelse
        
    </div>
@endsection