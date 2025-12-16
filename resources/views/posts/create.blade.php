@extends('admin.master')

@section('title','Thêm bài viết')

@section('body')
<div class="container mt-4">
<h4>Thêm bài viết</h4>

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Tiêu đề</label>
    <input name="title" class="form-control" required>
</div>

<div class="mb-3">
    <label>Mô tả ngắn</label>
    <textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-3">
    <label>Nội dung</label>
    <textarea name="content" rows="8" class="form-control" required></textarea>
</div>

<div class="mb-3">
    <label>Ảnh đại diện</label>
    <input type="file" name="image" class="form-control">
</div>

<div class="mb-3">
    <label>Trạng thái</label>
    <select name="status" class="form-control">
        <option value="published">Published</option>
        <option value="draft">Draft</option>
    </select>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="is_featured" class="form-check-input">
    <label class="form-check-label">Bài viết nổi bật</label>
</div>

<button class="btn btn-success">Lưu</button>
<a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>

</form>
</div>
@endsection
