@extends('admin.master')

@section('title','Thêm FAQ')

@section('body')
<div class="container mt-4">
    <h4 class="mb-3">Thêm FAQ</h4>

    <form action="{{ route('faqs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Câu hỏi</label>
            <input type="text" name="question"
                   class="form-control"
                   value="{{ old('question') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Câu trả lời</label>
            <textarea name="answer" rows="5"
                      class="form-control" required>{{ old('answer') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Thứ tự hiển thị</label>
            <input type="number" name="order"
                   class="form-control"
                   value="{{ old('order', 0) }}">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active"
                   class="form-check-input" checked>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('faqs.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
