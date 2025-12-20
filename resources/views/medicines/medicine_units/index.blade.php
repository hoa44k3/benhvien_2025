@extends('admin.master')

@section('title', 'Đơn vị thuốc')

@section('body')
<div class="container">
    <h4 class="mb-3">Đơn vị thuốc</h4>

    <a href="{{ route('medicine_units.create') }}" class="btn btn-primary mb-3">
        + Thêm đơn vị
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên đơn vị</th>
                <th width="150">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $key => $unit)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $unit->name }}</td>
                    <td>
                        <a href="{{ route('medicine_units.edit', $unit->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('medicine_units.destroy', $unit->id) }}"
                              method="POST" style="display:inline"
                              onsubmit="return confirm('Xóa đơn vị này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $units->links() }}
</div>
@endsection
