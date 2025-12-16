@extends('doctor.master')

@section('title', 'Upload Kết quả Xét nghiệm')

@section('body')
<div class="container mx-auto max-w-3xl px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white mb-1">Upload Kết quả Xét nghiệm</h2>
            <p class="text-indigo-100 text-sm">Tải lên kết quả xét nghiệm cho bệnh nhân (Hình ảnh, PDF)</p>
        </div>

        <div class="p-8">
            {{-- Form Upload --}}
            {{-- Lưu ý: enctype="multipart/form-data" là BẮT BUỘC để upload file --}}
            <form action="{{ route('doctor.test_results.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- 1. Chọn Bệnh nhân (Từ danh sách đang khám) --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">
                        <i class="fas fa-user-injured mr-2 text-indigo-500"></i> Chọn Bệnh nhân
                    </label>
                    <select name="appointment_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">-- Chọn bệnh nhân --</option>
                        @foreach($patients as $app)
                            <option value="{{ $app->id }}">
                                {{ $app->user->name }} (Mã BN: {{ $app->user_id }}) - {{ $app->time }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Tên loại xét nghiệm --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">
                        <i class="fas fa-vial mr-2 text-indigo-500"></i> Loại xét nghiệm
                    </label>
                    <input type="text" name="test_name" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="VD: Chụp X-Quang phổi, Xét nghiệm máu tổng quát..." required>
                </div>

                {{-- 3. Kết luận / Chẩn đoán --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">
                        <i class="fas fa-file-medical-alt mr-2 text-indigo-500"></i> Kết luận / Chẩn đoán
                    </label>
                    <textarea name="diagnosis" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Nhập kết luận của bác sĩ về kết quả này..."></textarea>
                </div>

                {{-- 4. File Upload --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-bold mb-2">
                        <i class="fas fa-file-upload mr-2 text-indigo-500"></i> File kết quả
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition relative">
                        <input type="file" name="file" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".jpg,.jpeg,.png,.pdf" required onchange="previewFile()">
                        <div id="preview-area">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Kéo thả hoặc click để chọn file</p>
                            <p class="text-xs text-gray-400 mt-1">(Hỗ trợ: JPG, PNG, PDF - Max: 2MB)</p>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <a href="{{ route('doctor.test_results.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold mr-6 transition">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:bg-indigo-700 transition transform active:scale-95">
                        <i class="fas fa-save mr-2"></i> Lưu kết quả
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script hiển thị tên file khi chọn --}}
<script>
    function previewFile() {
        const input = document.getElementById('file-upload');
        const preview = document.getElementById('preview-area');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            preview.innerHTML = `
                <div class="text-indigo-600 font-bold">
                    <i class="fas fa-check-circle mr-1"></i> Đã chọn: ${file.name}
                </div>
                <p class="text-xs text-gray-500 mt-1">Kích thước: ${(file.size / 1024).toFixed(2)} KB</p>
            `;
        }
    }
</script>
@endsection