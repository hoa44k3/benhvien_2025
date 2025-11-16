@extends('admin.master')

@section('body')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa bác sĩ</h1>

    <form action="{{ route('doctorsite.update', $doctor) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')

        {{-- User --}}
        <div>
            <label class="block font-semibold mb-1">Tài khoản bác sĩ</label>
            <select name="user_id" class="border w-full p-2 rounded">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $doctor->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Department --}}
        <div>
            <label class="block font-semibold mb-1">Chuyên khoa</label>
            <select name="department_id" class="border w-full p-2 rounded">
                <option value="">-- Chọn chuyên khoa --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $doctor->department_id == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Specialty --}}
        <div>
            <label class="block font-semibold mb-1">Chuyên ngành</label>
            <input type="text" name="specialty" value="{{ $doctor->specialty }}" class="border w-full p-2 rounded" required>
        </div>

        {{-- Bio --}}
        <div>
            <label class="block font-semibold mb-1">Giới thiệu</label>
            <textarea name="bio" rows="4" class="border w-full p-2 rounded">{{ $doctor->bio }}</textarea>
        </div>

        {{-- Rating --}}
        <div class="flex gap-4">
            <div class="w-1/2">
                <label class="block font-semibold mb-1">Điểm đánh giá</label>
                <input type="number" name="rating" value="{{ $doctor->rating }}" min="0" max="5" step="0.1" class="border w-full p-2 rounded">
            </div>
            <div class="w-1/2">
                <label class="block font-semibold mb-1">Số lượt đánh giá</label>
                <input type="number" name="reviews_count" value="{{ $doctor->reviews_count }}" min="0" class="border w-full p-2 rounded">
            </div>
        </div>

        {{-- Image --}}
        <div>
            <label class="block font-semibold mb-1">Ảnh bác sĩ</label>
            <input type="file" name="image" class="border w-full p-2 rounded">

            @if($doctor->image)
                <img src="{{ asset('storage/'.$doctor->image) }}" class="w-32 mt-2 rounded shadow">
            @endif
        </div>

        {{-- Status --}}
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="status" value="1" {{ $doctor->status ? 'checked' : '' }}>
                <span class="ml-2">Hoạt động</span>
            </label>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Cập nhật</button>
    </form>
</div>
@endsection
