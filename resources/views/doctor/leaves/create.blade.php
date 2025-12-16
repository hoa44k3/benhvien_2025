@extends('doctor.master')

@section('title', 'Tạo đơn xin nghỉ')

@section('body')
<div class="container mx-auto max-w-2xl px-4 py-12">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        {{-- Header Form --}}
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white mb-1">Đơn Xin Nghỉ Phép</h2>
            <p class="text-purple-100 text-sm">Vui lòng điền đầy đủ thông tin để Admin duyệt</p>
        </div>
        
        <div class="p-8">
            <form action="{{ route('doctor.leaves.store') }}" method="POST">
                @csrf
                
                {{-- Chọn ngày --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="far fa-calendar-alt mr-1 text-purple-600"></i> Từ ngày
                        </label>
                        <input type="date" name="start_date" 
                               class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 outline-none transition" 
                               required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="far fa-calendar-check mr-1 text-purple-600"></i> Đến ngày
                        </label>
                        <input type="date" name="end_date" 
                               class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 outline-none transition" 
                               required>
                    </div>
                </div>

                {{-- Lý do --}}
                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="far fa-edit mr-1 text-purple-600"></i> Lý do nghỉ
                    </label>
                    <textarea name="reason" rows="4" 
                              class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-200 outline-none transition resize-none" 
                              placeholder="VD: Tôi bị sốt cao cần nghỉ ngơi, đi khám sức khỏe định kỳ..." required></textarea>
                </div>

                {{-- Nút bấm --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <a href="{{ route('doctor.leaves.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Quay lại
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl hover:from-purple-700 hover:to-indigo-700 transition transform active:scale-95">
                        Gửi đơn <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection