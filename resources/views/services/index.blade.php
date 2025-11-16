@extends('admin.master')

@section('body')
<div class="container">
    <h1>Dịch vụ</h1>
    <a href="{{ route('services.create') }}" class="btn btn-primary mb-3">Thêm dịch vụ</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên dịch vụ</th>
                <th>Mô tả</th>
                <th>Chi tiết</th>
                <th>Phí</th>
                <th>Thời gian (phút)</th>
                <th>Trạng thái</th>
                <th>Danh mục</th>
                <th>Chuyên khoa</th>
                <th>Ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ Str::limit($service->description, 50) }}</td>
                <td>{{ Str::limit($service->content, 50) }}</td>
                {{-- <td>{{ number_format($service->fee, 0, ',', '.') }} VND</td> --}}
                <td>
                @if(!$service->fee || $service->fee == 0)
                    Liên hệ
                @else
                    {{ number_format($service->fee, 0, ',', '.') }} VND
                @endif
            </td>
                {{-- <td>{{ $service->duration }}</td> --}}
            <td>
                @if($service->duration == 0 || $service->duration === null)
                    Liên tục
                @else
                    {{ $service->duration }} phút
                @endif
            </td>


                <td>{{ $service->status ? 'Active' : 'Inactive' }}</td>
                <td>{{ $service->category->name ?? '-' }}</td>
                <td>{{ $service->department->name ?? '-' }}</td>
                <td>
                    @if($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" alt="Ảnh" width="100">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-info">Xem chi tiết</a>

                    <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <button class="btn btn-sm btn-danger delete-service" data-id="{{ $service->id }}">Xóa</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $services->links() }}
</div>

<script>
document.querySelectorAll('.delete-service').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Bạn có chắc muốn xóa?')) {
            const id = this.dataset.id;
            fetch(`/services/destroy/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            }).then(res => res.json()).then(data => {
                if(data.success) location.reload();
            });
        }
    });
});
</script>
@endsection
