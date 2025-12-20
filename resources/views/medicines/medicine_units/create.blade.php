@extends('admin.master')

@section('title', 'Thêm đơn vị thuốc')

@section('body')
<div class="container">
    <h4 class="mb-3">Thêm đơn vị thuốc</h4>

    <form action="{{ route('medicine_units.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên đơn vị</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('medicine_units.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
