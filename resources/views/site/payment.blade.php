@extends('site.master')

@section('title','Thanh toán viện phí')
@section('body')
    {{-- HEADER --}}
    <div class="bg-white border-b border-slate-200 py-12">
        <div class="container mx-auto max-w-7xl px-4 text-center">
            <h1 class="text-3xl font-extrabold text-slate-800 mb-2">Cổng Thanh Toán Trực Tuyến</h1>
            <p class="text-slate-500">Thanh toán phí khám & tư vấn an toàn, nhanh chóng</p>
        </div>
    </div>

    <div class="bg-slate-50 min-h-screen py-10 px-4">
        <div class="container mx-auto max-w-7xl">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Card 1: Unpaid -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Chưa thanh toán</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($unpaidTotal) }} đ</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $invoices->where('status', 'unpaid')->count() }} hóa đơn</p>
                    </div>
                </div>
                
                <!-- Card 2: Paid -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Đã thanh toán</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($paidTotal) }} đ</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $invoices->where('status', 'paid')->count() }} hóa đơn</p>
                    </div>
                </div>
                
                <!-- Card 3: Total -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Tổng giao dịch</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalAmount) }} đ</p>
                        <p class="text-xs text-slate-400 mt-1">Lịch sử toàn bộ</p>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-history text-slate-400"></i> Danh sách hóa đơn
            </h2>

            {{-- INVOICES LIST --}}
            <div class="space-y-6">
                @forelse($invoices as $invoice)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition duration-300 relative group" id="invoice-{{ $invoice->id }}">
                    
                    {{-- Status Bar (Left Border) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $invoice->status == 'paid' ? 'bg-green-500' : 'bg-red-500' }}"></div>

                    <div class="p-6 pl-8">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-bold text-slate-800">Hóa đơn #{{ $invoice->code }}</h3>
                                    @if($invoice->status == 'paid')
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide">ĐÃ THANH TOÁN</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide animate-pulse">CHỜ THANH TOÁN</span>
                                    @endif
                                </div>
                                <div class="text-sm text-slate-500 flex flex-wrap gap-4">
                                    <span><i class="far fa-calendar mr-1"></i> {{ $invoice->created_at->format('d/m/Y') }}</span>
                                    @if($invoice->medicalRecord)
                                        <span><i class="fas fa-clinic-medical mr-1"></i> {{ $invoice->medicalRecord->department->name ?? 'Dịch vụ y tế' }}</span>
                                        <span><i class="fas fa-user-md mr-1"></i> BS. {{ $invoice->medicalRecord->doctor->user->name ?? '---' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-extrabold {{ $invoice->status == 'paid' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ number_format($invoice->total) }} <span class="text-lg font-bold text-slate-400">đ</span>
                                </div>
                            </div>
                        </div>

                        {{-- Items Detail --}}
                        <div class="bg-slate-50 rounded-xl p-5 mb-5 border border-slate-100">
                            <ul class="space-y-3 text-sm">
                                @foreach($invoice->items as $item)
                                <li class="flex justify-between items-center text-slate-600 border-b border-dashed border-slate-200 pb-2 last:border-0 last:pb-0">
                                    <span>{{ $item->item_name ?? $item->description }} <span class="text-slate-400 text-xs ml-1 bg-white px-1.5 rounded border border-slate-200">x{{ $item->quantity }}</span></span>
                                    <span class="font-bold text-slate-700">{{ number_format($item->total ?? $item->total_price) }} đ</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex justify-end items-center gap-3">
                            @if($invoice->status == 'unpaid')
                                <button class="toggle-payment-detail px-6 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-sky-600 shadow-lg shadow-sky-100 transition flex items-center gap-2 transform active:scale-95" 
                                        onclick="document.getElementById('detail-{{ $invoice->id }}').classList.toggle('hidden')">
                                    <i class="fas fa-credit-card"></i> Chọn phương thức thanh toán
                                </button>
                            @else
                                <span class="text-xs font-medium text-slate-400 mr-2 flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i> 
                                    Thanh toán lúc {{ \Carbon\Carbon::parse($invoice->paid_at)->format('H:i d/m/Y') }} qua {{ strtoupper($invoice->payment_method) }}
                                </span>
                                <a href="{{ route('invoice.download', $invoice->id) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold hover:bg-slate-50 transition flex items-center gap-2">
                                    <i class="fas fa-file-download"></i> Tải hóa đơn
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    {{-- PAYMENT FORM (4 METHODS) --}}
                    @if($invoice->status == 'unpaid')
                    <div class="hidden bg-slate-50 border-t border-slate-200 p-6 md:p-8 animate-fade-in-up" id="detail-{{ $invoice->id }}">
                        <form action="{{ route('payment.process', $invoice->id) }}" method="POST">
                            @csrf
                            <h4 class="font-bold text-slate-800 mb-6 text-lg">Chọn phương thức thanh toán:</h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                                
                                {{-- 1. VNPay --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="vnpay" class="peer sr-only" checked>
                                    <div class="h-full p-4 bg-white border-2 border-slate-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50/50 transition hover:border-blue-300 hover:shadow-md flex flex-col items-center justify-center text-center gap-3">
                                        <div class="w-12 h-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center shadow-sm">
                                            <span class="font-extrabold text-blue-600 text-sm">VNPAY</span>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-700 text-sm">VNPay QR</span>
                                            <span class="text-[10px] text-slate-400">Quét mã nhanh</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-blue-500 transition-opacity">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                                
                                {{-- 2. MoMo --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="momo" class="peer sr-only">
                                    <div class="h-full p-4 bg-white border-2 border-slate-200 rounded-2xl peer-checked:border-pink-500 peer-checked:bg-pink-50/50 transition hover:border-pink-300 hover:shadow-md flex flex-col items-center justify-center text-center gap-3">
                                        <div class="w-12 h-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-wallet text-2xl text-pink-600"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-700 text-sm">Ví MoMo</span>
                                            <span class="text-[10px] text-slate-400">Siêu ứng dụng</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-pink-500 transition-opacity">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>

                                {{-- 3. ATM / Visa / Master --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="card" class="peer sr-only">
                                    <div class="h-full p-4 bg-white border-2 border-slate-200 rounded-2xl peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 transition hover:border-indigo-300 hover:shadow-md flex flex-col items-center justify-center text-center gap-3">
                                        <div class="w-12 h-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-credit-card text-2xl text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-700 text-sm">Thẻ Quốc tế / ATM</span>
                                            <span class="text-[10px] text-slate-400">Visa, Master, JCB</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-indigo-500 transition-opacity">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>

                                {{-- 4. Chuyển khoản ngân hàng --}}
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="transfer" class="peer sr-only">
                                    <div class="h-full p-4 bg-white border-2 border-slate-200 rounded-2xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 transition hover:border-emerald-300 hover:shadow-md flex flex-col items-center justify-center text-center gap-3">
                                        <div class="w-12 h-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-university text-2xl text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-700 text-sm">Chuyển khoản</span>
                                            <span class="text-[10px] text-slate-400">Internet Banking</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-emerald-500 transition-opacity">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            {{-- Footer Actions --}}
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <button type="button" onclick="document.getElementById('detail-{{ $invoice->id }}').classList.add('hidden')" class="text-slate-500 hover:text-slate-700 font-medium text-sm flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-slate-100 transition">
                                    <i class="fas fa-times"></i> Hủy bỏ
                                </button>
                                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-blue-600 text-white rounded-xl font-bold hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                    <span>Thanh toán ngay</span> <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="far fa-smile text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Không có hóa đơn nào</h3>
                        <p class="text-slate-500">Tuyệt vời! Bạn đã thanh toán đầy đủ các khoản phí.</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
@endsection