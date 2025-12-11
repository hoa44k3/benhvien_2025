@extends('admin.master')

@section('body')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Thêm file hồ sơ bệnh án</h1>

    <form action="{{ route('medical_record_files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="font-semibold block mb-1">Chọn hồ sơ</label>
        <select name="medical_record_id" class="border p-2 w-full mb-4">
            @foreach($records as $rec)
            <option value="{{ $rec->id }}">{{ $rec->id }} - {{ $rec->title ?? 'Hồ sơ' }}</option>
            @endforeach
        </select>

        <label class="font-semibold block mb-1">Tiêu đề (tùy chọn)</label>
        <input type="text" name="title" class="border p-2 w-full mb-4">

        <label class="font-semibold block mb-1">Mô tả</label>
        <textarea name="description" class="border p-2 w-full mb-4"></textarea>

        <label class="font-semibold block mb-1">File</label>
        <input type="file" name="file" class="border p-2 w-full mb-4">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Lưu</button>

    </form>

</div>
@endsection
