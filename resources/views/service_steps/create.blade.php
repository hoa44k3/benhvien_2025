@extends('admin.master')

@section('title','Thêm bước dịch vụ')

@section('body')
<div class="container mt-4">
    <h4>Thêm bước dịch vụ</h4>

    <form action="{{ route('service_steps.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
@if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label>Dịch vụ</label>
            <select name="service_id" class="form-control" required>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tiêu đề bước</label>
            <input name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Thứ tự</label>
            <input type="number" name="step_order" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>Ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('service_steps.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
