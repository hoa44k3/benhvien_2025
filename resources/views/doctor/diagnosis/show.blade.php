@extends('doctor.master')

@section('body')
<div class="p-6 grid grid-cols-2 gap-6">

    {{-- Thông tin bệnh nhân --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-3 text-blue-800">🧍‍♂️ Thông tin Bệnh nhân</h3>
        <div class="space-y-2 text-gray-700">
            <p><strong>Tên:</strong> {{ $appointment->patient_name }}</p>
            <p><strong>Mã BN:</strong> {{ $appointment->id }}</p>
            <p><strong>Thời gian hẹn:</strong> {{ $appointment->time ?? 'Chưa có' }}</p>
            <p><strong>Lý do khám:</strong> {{ $appointment->description ?? 'Không có' }}</p>
            <p><strong>Trạng thái:</strong> 
                <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-700">
                    {{ $appointment->status }}
                </span>
            </p>
        </div>

        <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2 hover:bg-blue-700">
            <i data-lucide="video"></i> Bắt đầu Video Call
        </button>
    </div>

    {{-- Form chẩn đoán và kê đơn --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-green-700 mb-3">💊 Chẩn đoán & Kê đơn thuốc</h3>

        <form action="{{ route('doctor.diagnosis.store', $appointment->id) }}" method="POST">
            @csrf

            {{-- Thông tin cơ bản --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Chẩn đoán (ICD-10)</label>
                <textarea name="diagnosis" rows="3" class="w-full border rounded p-2 mt-1" 
                    placeholder="Ví dụ: R51 - Đau đầu, M54.5 - Đau thắt lưng"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Ghi chú thêm</label>
                <textarea name="note" rows="2" class="w-full border rounded p-2 mt-1" 
                    placeholder="Ví dụ: Theo dõi thêm, tái khám sau 5 ngày..."></textarea>
            </div>

            {{-- Kê đơn thuốc --}}
            <div>
                <label class="font-semibold text-orange-600 flex items-center gap-2">
                    <i data-lucide="pill"></i> Danh sách thuốc kê
                </label>
                <div id="thuoc-container" class="space-y-2 mt-2">
                    <div class="flex flex-wrap gap-2 items-center">
                        <input type="text" name="medicines[0][ten_thuoc]" placeholder="Tên thuốc" class="flex-1 border rounded p-2">
                        <input type="number" name="medicines[0][so_luong]" placeholder="SL" class="w-20 border rounded p-2">
                        <input type="text" name="medicines[0][don_vi]" placeholder="Đơn vị (viên/gói)" class="w-32 border rounded p-2">
                        <input type="text" name="medicines[0][lieu_dung]" placeholder="Liều dùng" class="flex-1 border rounded p-2">
                        <input type="text" name="medicines[0][thoi_gian]" placeholder="Thời gian (5 ngày...)" class="w-32 border rounded p-2">
                    </div>
                </div>

                <button type="button" id="add-medicine" class="text-blue-600 mt-2 text-sm hover:underline">+ Thêm thuốc</button>
            </div>

            {{-- Nút submit --}}
            <button type="submit" 
                class="w-full bg-green-600 text-white py-2 rounded mt-5 hover:bg-green-700 transition">
                💾 Lưu chẩn đoán & Gửi đơn thuốc
            </button>
        </form>
    </div>
</div>

{{-- Thêm thuốc bằng JS --}}
<script>
document.getElementById('add-medicine').addEventListener('click', function() {
    const container = document.getElementById('thuoc-container');
    const index = container.children.length;
    const div = document.createElement('div');
    div.classList.add('flex', 'flex-wrap', 'gap-2', 'items-center', 'mt-2');
    div.innerHTML = `
        <input type="text" name="medicines[${index}][ten_thuoc]" placeholder="Tên thuốc" class="flex-1 border rounded p-2">
        <input type="number" name="medicines[${index}][so_luong]" placeholder="SL" class="w-20 border rounded p-2">
        <input type="text" name="medicines[${index}][don_vi]" placeholder="Đơn vị (viên/gói)" class="w-32 border rounded p-2">
        <input type="text" name="medicines[${index}][lieu_dung]" placeholder="Liều dùng" class="flex-1 border rounded p-2">
        <input type="text" name="medicines[${index}][thoi_gian]" placeholder="Thời gian (5 ngày...)" class="w-32 border rounded p-2">
    `;
    container.appendChild(div);
});
</script>
@endsection
