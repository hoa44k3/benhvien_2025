@extends('admin.master')

@section('title', 'Sửa danh mục thuốc')

@section('body')
<div class="container">
    <h4 class="mb-3">Sửa danh mục thuốc</h4>

    <form action="{{ route('medicine_categories.update', $medicineCategory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $medicineCategory->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $medicineCategory->description }}</textarea>
        </div>

        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('medicine_categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
