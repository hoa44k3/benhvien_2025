@extends('admin.master')

@section('title', 'Sửa đơn vị thuốc')

@section('body')
<div class="container">
    <h4 class="mb-3">Sửa đơn vị thuốc</h4>

    <form action="{{ route('medicine_units.update', $medicineUnit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên đơn vị</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $medicineUnit->name }}" required>
        </div>

        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('medicine_units.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
