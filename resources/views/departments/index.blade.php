@extends('admin.master')

@section('title', 'Quản lý chuyên khoa & dịch vụ')

@section('body')
<div class="container-fluid mt-4">
    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-hospital-alt me-2"></i> Quản lý Chuyên khoa & Dịch vụ
    </h3>
    <hr>

    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <a href="{{ route('departments.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Chuyên khoa
            </a>
        </div>
        
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo Tên hoặc Mã chuyên khoa...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Trạng thái</option>
                    <option value="active">Đang hoạt động</option>
                    <option value="inactive">Tạm dừng</option>
                </select>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                   <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Tên chuyên khoa</th>
                            <th class="text-center">Trưởng khoa</th>
                            <th class="text-center">Nhân sự</th>
                            <th class="text-center">Số phòng</th>
                            <th class="text-start">Mô tả</th>
                            <th class="text-end">Phí khám (VNĐ)</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Ảnh</th>
                            <th class="text-center">Ngày tạo</th>
                            <th class="text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $item)
                        <tr>
                            <td class="text-center fw-bold">{{ $item->code }}</td>
                            <td class="fw-bold text-primary">{{ $item->name }}</td>
                            <td class="text-center">{{ $item->head_name ?? '-' }}</td>
                            <td class="text-center text-nowrap">
                                <i class="fas fa-user-md me-1 text-primary"></i> {{ $item->num_doctors }} Bác sĩ <br>
                                <i class="fas fa-user-nurse me-1 text-info"></i> {{ $item->num_nurses }} Y tá
                            </td>
                            <td class="text-center">{{ $item->num_rooms }}</td>
                            <td class="text-start">
                                @if($item->description)
                                    <span title="{{ $item->description }}">{{ \Illuminate\Support\Str::limit($item->description, 80) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-success">{{ number_format($item->fee, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->status == 'active')
                                    <span class="badge bg-success py-2 px-3">Đang hoạt động</span>
                                @else
                                    <span class="badge bg-warning text-dark py-2 px-3">Tạm dừng</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Ảnh chuyên khoa" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <i class="fas fa-hospital text-muted fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('departments.show', $item) }}" class="btn btn-sm btn-outline-info me-1" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('departments.edit', $item) }}" class="btn btn-sm btn-outline-warning me-1" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('departments.destroy', $item) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn XÓA chuyên khoa {{ $item->name }}?')" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-box-open me-2"></i> Chưa có chuyên khoa nào được tạo.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection