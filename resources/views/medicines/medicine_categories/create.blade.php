@extends('admin.master')

@section('title', 'Thêm danh mục thuốc')

@section('body')
<div class="container">
    <h4 class="mb-3">Thêm danh mục thuốc</h4>

    <form action="{{ route('medicine_categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('medicine_categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
