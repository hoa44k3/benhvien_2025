@extends('admin.master')

@section('body')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Sửa file hồ sơ bệnh án</h1>

    <form action="{{ route('medical_record_files.update', $file->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label class="font-semibold block mb-1">Hồ sơ</label>
        <select name="medical_record_id" class="border p-2 w-full mb-4">
            @foreach($records as $rec)
                <option value="{{ $rec->id }}" {{ $rec->id == $file->medical_record_id ? 'selected' : '' }}>
                    {{ $rec->id }}
                </option>
            @endforeach
        </select>

        <label class="font-semibold block mb-1">Tiêu đề</label>
        <input type="text" name="title" class="border p-2 w-full mb-4" value="{{ $file->title }}">

        <label class="font-semibold block mb-1">Mô tả</label>
        <textarea name="description" class="border p-2 w-full mb-4">{{ $file->description }}</textarea>

        <label class="font-semibold block mb-1">Trạng thái</label>
        <select name="status" class="border p-2 w-full mb-4">
            <option value="active" {{ $file->status == 'active' ? 'selected' : '' }}>active</option>
            <option value="archived" {{ $file->status == 'archived' ? 'selected' : '' }}>archived</option>
        </select>

        <label class="font-semibold block mb-1">File hiện tại</label>
        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 underline mb-2 block">
            {{ $file->original_name }}
        </a>

        <label class="font-semibold block mb-1">Upload file mới (tùy chọn)</label>
        <input type="file" name="file" class="border p-2 w-full mb-4">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Cập nhật</button>
    </form>

</div>
@endsection
