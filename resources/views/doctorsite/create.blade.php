@extends('admin.master')

@section('body')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Thêm bác sĩ mới</h1>

    <form action="{{ route('doctorsite.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Chọn user --}}
        <div>
            <label class="block font-semibold mb-1">Tài khoản bác sĩ</label>
            <select name="user_id" class="border w-full p-2 rounded" required>
                <option value="">-- Chọn tài khoản --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Chuyên khoa --}}
        <div>
            <label class="block font-semibold mb-1">Chuyên khoa</label>
            <select name="department_id" class="border w-full p-2 rounded">
                <option value="">-- Chọn chuyên khoa --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>


        {{-- Giới thiệu --}}
        <div>
            <label class="block font-semibold mb-1">Giới thiệu</label>
            <textarea name="bio" rows="4" class="border w-full p-2 rounded"></textarea>
        </div>

        {{-- Rating --}}
        <div class="flex gap-4">
            <div class="w-1/2">
                <label class="block font-semibold mb-1">Điểm đánh giá</label>
                <input type="number" name="rating" min="0" max="5" step="0.1" class="border w-full p-2 rounded">
            </div>
            <div class="w-1/2">
                <label class="block font-semibold mb-1">Số lượt đánh giá</label>
                <input type="number" name="reviews_count" min="0" class="border w-full p-2 rounded">
            </div>
        </div>

        {{-- Image --}}
        <div>
            <label class="block font-semibold mb-1">Ảnh bác sĩ</label>
            <input type="file" name="image" class="border w-full p-2 rounded">
        </div>

        {{-- Status --}}
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="status" value="1" checked>
                <span class="ml-2">Hoạt động</span>
            </label>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Lưu</button>
    </form>
</div>
@endsection
