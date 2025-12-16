@extends('admin.master')

@section('title','Quản lý FAQ')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Danh sách FAQ</h4>
        <a href="{{ route('faqs.create') }}" class="btn btn-primary">
            + Thêm FAQ
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th width="5%">#</th>
                <th>Câu hỏi</th>
                <th width="10%">Thứ tự</th>
                <th width="10%">Trạng thái</th>
                <th width="15%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faqs as $key => $faq)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $faq->question }}</td>
                    <td>{{ $faq->order }}</td>
                    <td>
                        @if($faq->is_active)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('faqs.edit', $faq->id) }}"
                           class="btn btn-sm btn-warning">
                            Sửa
                        </a>

                        <form action="{{ route('faqs.destroy', $faq->id) }}"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Xóa FAQ này?')">
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
