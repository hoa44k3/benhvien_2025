@extends('admin.master')

@section('body')
<div class="container mt-4">
    <!-- Header + Nút Thêm -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Danh sách bác sĩ</h3>
        <a href="{{ route('doctorsite.create') }}" class="btn btn-primary">+ Thêm bác sĩ</a>
    </div>

    <!-- Thông báo success -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0 table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ảnh</th>
                        <th>Tên bác sĩ</th>
                        <th>Khoa</th>
                        <th>Giới thiệu</th>
                        <th>Điểm đánh giá</th>
                        <th>Lượt đánh giá</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctors->firstItem() + $loop->index }}</td>

                        <!-- Ảnh -->
                        <td>
                            {{-- @if($doctor->image)
                                <img src="{{ asset('storage/'.$doctor->image) }}" alt="doctor" width="60" class="rounded-circle">
                            @else
                                <img src="{{ asset('assets/img/default-doctor.png') }}" width="60" class="rounded-circle">
                            @endif --}}
                            @if($doctor->image)
    <img src="{{ asset('storage/' . $doctor->image) }}" alt="doctor" width="60" class="rounded-circle">
@else
    <img src="{{ asset('assets/img/default-doctor.png') }}" width="60" class="rounded-circle">
@endif

                        </td>

                        <!-- Tên bác sĩ -->
                        <td>{{ $doctor->user->name ?? 'Không rõ' }}</td>

                        <!-- Khoa -->
                        <td>{{ $doctor->department->name ?? '-' }}</td>

                        <!-- Giới thiệu -->
                        <td style="max-width:250px;">{{ Str::limit($doctor->bio, 80) }}</td>

                        <!-- Điểm đánh giá -->
                        <td>⭐ {{ $doctor->rating }}/5</td>

                        <!-- Lượt đánh giá -->
                        <td>{{ $doctor->reviews_count }}</td>

                        <!-- Trạng thái -->
                        <td>
                            @if($doctor->status)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>

                        <!-- Hành động -->
                        <td class="text-end">
                            <a href="{{ route('doctorsite.show', $doctor) }}" class="btn btn-sm btn-info">Xem</a>
                            <a href="{{ route('doctorsite.edit', $doctor) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('doctorsite.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bác sĩ này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            {{ $doctors->links() }}
        </div>
    </div>
</div>
@endsection
