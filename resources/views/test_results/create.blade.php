@extends('admin.master')

@section('body')
<div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Thêm kết quả xét nghiệm</h2>

    <form action="{{ route('test_results.store') }}" method="POST" enctype="multipart/form-data"
          class="space-y-4 bg-white p-6 shadow rounded-xl border">
        @csrf

        <div>
            <label class="font-semibold">Bệnh nhân</label>
            <select name="user_id" class="w-full border p-2 rounded">
                @foreach($patients as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Ngày xét nghiệm</label>
            <input type="date" name="date" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Loại xét nghiệm</label>
            <input type="text" name="test_type" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Kết quả</label>
            <textarea name="result" class="w-full border p-2 rounded"></textarea>
        </div>

        <div>
            <label class="font-semibold">Bác sĩ phụ trách</label>
            <select name="doctor_id" class="w-full border p-2 rounded">
                <option value="">— Không chọn —</option>
                @foreach($doctors as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Khoa</label>
            <select name="department_id" class="w-full border p-2 rounded">
                <option value="">— Không chọn —</option>
                @foreach($departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Trạng thái</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="pending">Đang xử lý</option>
                <option value="completed">Hoàn tất</option>
                <option value="abnormal">Bất thường</option>
            </select>
        </div>

        <div>
            <label class="font-semibold">Ghi chú</label>
            <textarea name="notes" class="w-full border p-2 rounded"></textarea>
        </div>

        <div>
            <label class="font-semibold">Tải file xét nghiệm (PDF/JPG/PNG)</label>
            <input type="file" name="file_path" class="w-full border p-2 rounded">
        </div>

        <button class="bg-blue-600 text-white px-5 py-2 rounded">Lưu</button>
    </form>
</div>
@endsection
