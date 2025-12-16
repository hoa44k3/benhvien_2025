@extends('admin.master')

@section('title','Chi tiết bước dịch vụ')

@section('body')
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Chi tiết bước dịch vụ</h4>
        <div>
            <a href="{{ route('service_steps.edit', $serviceStep->id) }}"
               class="btn btn-warning">Sửa</a>

            <a href="{{ route('service_steps.index') }}"
               class="btn btn-secondary">Quay lại</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <p><strong>Dịch vụ:</strong>
                {{ $serviceStep->service->name ?? '-' }}
            </p>

            <p><strong>Tiêu đề bước:</strong>
                {{ $serviceStep->title }}
            </p>

            <p><strong>Thứ tự:</strong>
                {{ $serviceStep->step_order }}
            </p>

            <p><strong>Mô tả:</strong></p>
            <div class="border p-3 rounded bg-light">
                {!! nl2br(e($serviceStep->description)) !!}
            </div>

            @if($serviceStep->image)
                <p class="mt-3"><strong>Hình ảnh:</strong></p>
                <img src="{{ asset('storage/'.$serviceStep->image) }}"
                     class="img-thumbnail"
                     style="max-width:300px">
            @endif

            <p class="mt-3 text-muted">
                Tạo lúc: {{ $serviceStep->created_at->format('d/m/Y H:i') }} <br>
                Cập nhật: {{ $serviceStep->updated_at->format('d/m/Y H:i') }}
            </p>

        </div>
    </div>

</div>
@endsection
