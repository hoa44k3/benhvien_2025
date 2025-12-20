@extends('admin.master')

@section('title', 'Danh mục thuốc')

@section('body')
<div class="container">
    <h4 class="mb-3">Danh mục thuốc</h4>

    <a href="{{ route('medicine_categories.create') }}" class="btn btn-primary mb-3">
        + Thêm danh mục
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th width="150">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $key => $category)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <a href="{{ route('medicine_categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('medicine_categories.destroy', $category->id) }}"
                              method="POST" style="display:inline"
                              onsubmit="return confirm('Xóa danh mục này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
</div>
@endsection
