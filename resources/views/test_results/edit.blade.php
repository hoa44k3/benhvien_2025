@extends('admin.master')

@section('body')
<div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Sửa kết quả xét nghiệm</h2>

    <form action="{{ route('test_results.update', $testResult) }}" method="POST"
          enctype="multipart/form-data" class="space-y-4 bg-white p-6 shadow rounded-xl border">
        @csrf
        @method('PUT')

        <div>
            <label class="font-semibold">Bệnh nhân</label>
            <select name="user_id" class="w-full border p-2 rounded">
                @foreach($patients as $p)
                <option value="{{ $p->id }}" @selected($p->id==$testResult->user_id)>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Ngày xét nghiệm</label>
            <input type="date" name="date" class="w-full border p-2 rounded"
                   value="{{ $testResult->date }}">
        </div>

        <div>
            <label class="font-semibold">Loại xét nghiệm</label>
            <input type="text" name="test_type" class="w-full border p-2 rounded"
                   value="{{ $testResult->test_type }}">
        </div>

        <div>
            <label class="font-semibold">Kết quả</label>
            <textarea name="result" class="w-full border p-2 rounded">{{ $testResult->result }}</textarea>
        </div>

        <div>
            <label class="font-semibold">Bác sĩ</label>
            <select name="doctor_id" class="w-full border p-2 rounded">
                <option value="">— Không chọn —</option>
                @foreach($doctors as $d)
                <option value="{{ $d->id }}" @selected($d->id==$testResult->doctor_id)>
                    {{ $d->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Khoa</label>
            <select name="department_id" class="w-full border p-2 rounded">
                <option value="">— Không chọn —</option>
                @foreach($departments as $d)
                <option value="{{ $d->id }}" @selected($d->id==$testResult->department_id)>
                    {{ $d->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Trạng thái</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="pending" @selected($testResult->status=='pending')>Đang xử lý</option>
                <option value="completed" @selected($testResult->status=='completed')>Hoàn tất</option>
                <option value="abnormal" @selected($testResult->status=='abnormal')>Bất thường</option>
            </select>
        </div>

        <div>
            <label class="font-semibold">Ghi chú</label>
            <textarea name="notes" class="w-full border p-2 rounded">{{ $testResult->notes }}</textarea>
        </div>

        <div>
            <label class="font-semibold">File xét nghiệm</label>
            <input type="file" name="file_path" class="w-full border p-2 rounded">

            @if($testResult->file_path)
            <p class="mt-2 text-sm">
                File hiện tại:
                <a href="{{ asset('storage/'.$testResult->file_path) }}" class="text-blue-600" target="_blank">
                    Xem file
                </a>
            </p>
            @endif
        </div>

        <button class="bg-yellow-600 text-white px-5 py-2 rounded">Cập nhật</button>
    </form>
</div>
@endsection
