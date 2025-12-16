@extends('doctor.master')

@section('title', 'Khám bệnh')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Cột trái: Thông tin bệnh nhân --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-injured mr-2 text-blue-500"></i> Thông tin bệnh nhân
                </h3>
                
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center text-2xl text-gray-600 mr-4">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl text-gray-900">{{ $appointment->patient_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $appointment->patient_phone }}</p>
                    </div>
                </div>
                
                <div class="bg-red-50 p-3 rounded-lg border border-red-100 mb-4">
                    <span class="text-xs font-bold text-red-500 uppercase">Lý do khám</span>
                    <p class="text-gray-800 mt-1 font-medium">{{ $appointment->reason ?? 'Không có mô tả' }}</p>
                </div>

                <div class="border-t pt-4">
                     {{-- Giả lập nút Video Call --}}
                 <a href="{{ route('doctor.videoCall', $appointment->id) }}" target="_blank"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center gap-2 font-semibold shadow-md transform hover:-translate-y-1">
                        <i class="fas fa-video animate-pulse"></i> Bắt đầu Video Call
                    </a>
                </div>
            </div>
        </div>

        {{-- Cột phải: Form Chẩn đoán & Kê đơn --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-notes-medical mr-2 text-green-500"></i> Chẩn đoán & Kê đơn
                </h3>

                <form action="{{ route('doctor.diagnosis.store', $appointment->id) }}" method="POST">
                    @csrf
                    
                    {{-- 1. Chẩn đoán --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Kết luận Chẩn đoán / Bệnh lý:</label>
                        <textarea name="diagnosis" rows="3" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="VD: Viêm họng cấp (J02)..." required></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Lời dặn / Ghi chú:</label>
                        <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="VD: Kiêng ăn đồ lạnh, tái khám sau 3 ngày..."></textarea>
                    </div>

                    {{-- 2. Kê đơn thuốc (JS thêm dòng) --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-gray-700 font-bold">Đơn thuốc:</label>
                            <button type="button" id="add-medicine" class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200 font-semibold transition">
                                <i class="fas fa-plus mr-1"></i> Thêm thuốc
                            </button>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" id="medicine-container">
                            {{-- Dòng thuốc mặc định --}}
                            <div class="medicine-row grid grid-cols-12 gap-2 mb-3 items-end">
                                <div class="col-span-5">
                                    <label class="text-xs text-gray-500 mb-1 block">Tên thuốc</label>
                                    <input type="text" name="medicine_name[]" class="w-full border border-gray-300 rounded p-2 text-sm" placeholder="VD: Panadol 500mg" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs text-gray-500 mb-1 block">Số lượng</label>
                                    <input type="number" name="medicine_quantity[]" class="w-full border border-gray-300 rounded p-2 text-sm" placeholder="SL" required>
                                </div>
                                <div class="col-span-4">
                                    <label class="text-xs text-gray-500 mb-1 block">Cách dùng</label>
                                    <input type="text" name="medicine_usage[]" class="w-full border border-gray-300 rounded p-2 text-sm" placeholder="VD: Sáng 1, Tối 1 sau ăn" required>
                                </div>
                                <div class="col-span-1 flex justify-center pb-2">
                                    {{-- Nút xóa dòng này (ẩn cho dòng đầu tiên nếu muốn bắt buộc) --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('doctor.diagnosis.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg mr-3 hover:bg-gray-200 transition">Hủy</a>
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg shadow-lg hover:bg-green-700 transition transform active:scale-95">
                            <i class="fas fa-check-circle mr-2"></i> Hoàn tất Khám
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script thêm dòng thuốc --}}
<script>
    document.getElementById('add-medicine').addEventListener('click', function() {
        const container = document.getElementById('medicine-container');
        const newRow = document.createElement('div');
        newRow.className = 'medicine-row grid grid-cols-12 gap-2 mb-3 items-end animation-fade-in';
        newRow.innerHTML = `
            <div class="col-span-5">
                <input type="text" name="medicine_name[]" class="w-full border border-gray-300 rounded p-2 text-sm" placeholder="Tên thuốc" required>
            </div>
            <div class="col-span-2">
                <input type="number" name="medicine_quantity[]" class="w-full border border-gray-300 rounded p-2 text-sm" placeholder="SL" required>
            </div>
            <div class="col-span-4">
                <input type="text" name="medicine_usage[]" class="w-full border border-gray-300 rounded p-2 text-sm" placeholder="Cách dùng" required>
            </div>
            <div class="col-span-1 flex justify-center pb-1">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    });
</script>
@endsection