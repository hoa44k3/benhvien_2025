@extends('admin.master')

@section('title','Cập nhật FAQ')

@section('body')
<div class="container mt-4">
    <h4 class="mb-3">Cập nhật FAQ</h4>

    <form action="{{ route('faqs.update', $faq->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Câu hỏi</label>
            <input type="text" name="question"
                   class="form-control"
                   value="{{ old('question', $faq->question) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Câu trả lời</label>
            <textarea name="answer" rows="5"
                      class="form-control" required>{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Thứ tự hiển thị</label>
            <input type="number" name="order"
                   class="form-control"
                   value="{{ old('order', $faq->order) }}">
        </div>

        <div class="mb-3 form-check">
            {{-- Kiểm tra giá trị cũ để checked --}}
            <input type="checkbox" name="is_active" value="1"
                   class="form-check-input" 
                   {{ $faq->is_active ? 'checked' : '' }}>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <button class="btn btn-warning">Cập nhật</button>
        <a href="{{ route('faqs.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection