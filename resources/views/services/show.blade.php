@extends('admin.master')

@section('body')
<div class="container">
    <h1>Chi tiết dịch vụ: {{ $service->name }}</h1>
    
    <div class="mb-3">
        <strong>Mô tả:</strong>
        <p>{{ $service->description ?? '-' }}</p>
    </div>

    <div class="mb-3">
        <strong>Chi tiết:</strong>
        <p>{{ $service->content ?? '-' }}</p>
    </div>

    <div class="mb-3">
        <strong>Phí:</strong> {{ number_format($service->fee, 0, ',', '.') }} VND
    </div>

    <div class="mb-3">
        <strong>Thời gian:</strong> {{ $service->duration }} phút
    </div>

    <div class="mb-3">
        <strong>Trạng thái:</strong> {{ $service->status ? 'Active' : 'Inactive' }}
    </div>

    <div class="mb-3">
        <strong>Danh mục:</strong> {{ $service->category->name ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Chuyên khoa:</strong> {{ $service->department->name ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Ảnh:</strong><br>
        @if($service->image)
            <img src="{{ asset('storage/'.$service->image) }}" alt="Ảnh dịch vụ" width="200">
        @else
            -
        @endif
    </div>

    <a href="{{ route('services.index') }}" class="btn btn-secondary">Quay lại</a>
    <a href="{{ route('services.edit', $service) }}" class="btn btn-warning">Sửa</a>
</div>
@endsection
