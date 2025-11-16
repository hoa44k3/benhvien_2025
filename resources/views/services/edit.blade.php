@extends('admin.master')

@section('body')
<div class="container">
    <h1>Sửa dịch vụ</h1>
    <form action="{{ route('services.update', $service) }}" method="POST"enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Tên dịch vụ</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $service->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Phí</label>
            <input type="number" name="fee" class="form-control" value="{{ old('fee', $service->fee) }}" required>
        </div>
        <div class="mb-3">
            <label>Thời gian (phút)</label>
            <input type="number" name="duration" class="form-control" value="{{ old('duration', $service->duration ?? '') }}">
            <small class="text-muted">Nhập 0 nếu dịch vụ là "Liên tục" (ví dụ: cấp cứu)</small>
        </div>
        {{-- <div class="mb-3">
            <label>Thời gian (phút)</label>
            <input type="number" name="duration" class="form-control" value="{{ old('duration', $service->duration) }}" required>
        </div> --}}

        <div class="mb-3">
            <label>Chi tiết nội dung</label>
            <textarea name="content" class="form-control">{{ old('content', $service->content ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Ảnh minh họa</label>
            <input type="file" name="image" class="form-control">
            @if(isset($service) && $service->image)
                <img src="{{ asset('storage/'.$service->image) }}" alt="Ảnh dịch vụ" width="150" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="1" {{ old('status', $service->status)==1?'selected':'' }}>Active</option>
                <option value="0" {{ old('status', $service->status)==0?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $service->category_id)==$category->id?'selected':'' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Chuyên khoa</label>
            <select name="department_id" class="form-control">
                <option value="">-- Chọn chuyên khoa --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $service->department_id)==$department->id?'selected':'' }}>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
