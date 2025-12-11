@extends('admin.master')

@section('body')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Chi tiết file hồ sơ</h1>

    <p><strong>ID:</strong> {{ $file->id }}</p>
    <p><strong>Hồ sơ:</strong> {{ $file->medical_record_id }}</p>

    <p><strong>Tiêu đề:</strong> {{ $file->title ?? 'N/A' }}</p>

    <p><strong>Mô tả:</strong><br>
        {!! nl2br(e($file->description)) !!}
    </p>

    <p class="mt-4"><strong>File:</strong></p>
    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 underline">
        {{ $file->original_name }}
    </a>

    <p class="mt-4"><strong>Loại file:</strong> {{ $file->file_type }}</p>
    <p><strong>MIME:</strong> {{ $file->mime_type }}</p>
    <p><strong>Kích thước:</strong> {{ number_format($file->file_size / 1024, 2) }} KB</p>

    <p class="mt-4"><strong>Người upload:</strong> {{ $file->uploader->name ?? 'N/A' }}</p>

    <p><strong>Trạng thái:</strong> {{ $file->status }}</p>

    <p><strong>Ngày tạo:</strong> {{ $file->created_at }}</p>

    <a href="{{ route('medical_record_files.index') }}" class="mt-6 inline-block bg-gray-600 text-white px-4 py-2 rounded">
        Quay lại
    </a>
</div>
@endsection
