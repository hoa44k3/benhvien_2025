@extends('admin.master')

@section('title','Các bước dịch vụ')

@section('body')
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Danh sách bước dịch vụ</h4>
        <a href="{{ route('service_steps.create') }}" class="btn btn-primary">
            + Thêm bước
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Dịch vụ</th>
                <th>Tiêu đề</th>
                <th>Thứ tự</th>
                <th>Ảnh</th>
                <th width="15%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($steps as $key => $step)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $step->service->name ?? '-' }}</td>
                    <td>{{ $step->title }}</td>
                    <td>{{ $step->step_order }}</td>
                    <td>
                        @if($step->image)
                            <img src="{{ asset('storage/'.$step->image) }}" width="80">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('service_steps.edit', $step->id) }}"
                           class="btn btn-sm btn-warning">Sửa</a>
<a href="{{ route('service-steps.show', $step->id) }}"
   class="btn btn-sm btn-info">Xem</a>

                        <form action="{{ route('service_steps.destroy', $step->id) }}"
                              method="POST" style="display:inline"
                              onsubmit="return confirm('Xóa bước này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
