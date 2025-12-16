@extends('admin.master')

@section('title','Sửa bước dịch vụ')

@section('body')
<div class="container mt-4">
    <h4>Sửa bước dịch vụ</h4>

    <form action="{{ route('service_steps.update', $serviceStep->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Dịch vụ</label>
            <select name="service_id" class="form-control">
                @foreach($services as $service)
                    <option value="{{ $service->id }}"
                        {{ $serviceStep->service_id == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tiêu đề</label>
            <input name="title" class="form-control"
                   value="{{ $serviceStep->title }}" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ $serviceStep->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Thứ tự</label>
            <input type="number" name="step_order"
                   class="form-control" value="{{ $serviceStep->step_order }}">
        </div>

        <div class="mb-3">
            <label>Ảnh</label><br>
            @if($serviceStep->image)
                <img src="{{ asset('storage/'.$serviceStep->image) }}" width="120" class="mb-2">
            @endif
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('service_steps.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
