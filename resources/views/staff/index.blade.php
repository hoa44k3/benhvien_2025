@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-users-cog me-2"></i> Quản lý Nhân sự
    </h3>
    <hr>
    
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <a href="{{ route('staff.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus me-1"></i> Thêm Nhân viên
            </a>
        </div>
        
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo Mã NV, Họ tên hoặc Email...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Khoa</option>
                    {{-- Thêm logic lọc theo khoa tại đây --}}
                </select>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Trạng thái</option>
                    <option value="Hoạt động">Hoạt động</option>
                    <option value="Nghỉ phép">Nghỉ phép</option>
                    <option value="Tạm dừng">Tạm dừng</option>
                </select>
            </div>
        </div>
    </div>

    @if (session('success'))
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
                            <th class="text-center">Mã NV</th>
                            <th>Họ tên</th>
                            <th class="text-center">Khoa & Chức vụ</th>
                            <th class="text-center">Liên hệ</th>
                            <th class="text-center">Kinh nghiệm</th>
                            <th class="text-center">Vai trò</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" style="width: 100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $s)
                            <tr>
                                <td class="text-center fw-bold text-primary">{{ $s->staff_code }}</td>
                                <td><i class="fas fa-user-md me-1 text-info"></i> {{ $s->name }}</td>
                                
                                {{-- Gộp Khoa và Chức vụ --}}
                                <td>
                                    <span class="d-block fw-semibold text-truncate" style="max-width: 150px;" title="{{ $s->department->name ?? '—' }}">
                                        {{ $s->department->name ?? '—' }}
                                    </span>
                                    <small class="d-block text-muted">({{ $s->position }})</small>
                                </td>
                                
                                {{-- Gộp Điện thoại và Email --}}
                                <td>
                                    <small class="d-block"><i class="fas fa-phone-alt me-1 text-success"></i> {{ $s->phone ?? '—' }}</small>
                                    <small class="d-block"><i class="fas fa-envelope me-1 text-primary"></i> {{ $s->email ?? '—' }}</small>
                                </td>

                                <td class="text-center">
                                    <span class="d-block fw-bold">{{ $s->experience_years }} năm</span>
                                    @if($s->rating)
                                        <small class="text-warning">
                                            <i class="fas fa-star me-1"></i> {{ $s->rating }} điểm
                                        </small>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge bg-secondary py-2 px-3">{{ $s->role->name ?? '—' }}</span>
                                </td>

                                {{-- Cột Trạng thái --}}
                                <td class="text-center">
                                    @switch($s->status)
                                        @case('Hoạt động') 
                                            <span class="badge bg-success py-2 px-3"><i class="fas fa-check-circle"></i> Hoạt động</span> 
                                            @break
                                        @case('Nghỉ phép') 
                                            <span class="badge bg-warning text-dark py-2 px-3"><i class="fas fa-shoe-prints"></i> Nghỉ phép</span> 
                                            @break
                                        @default 
                                            <span class="badge bg-secondary py-2 px-3"><i class="fas fa-pause-circle"></i> Tạm dừng</span> 
                                            @break
                                    @endswitch
                                </td>
                                
                                {{-- Cột Thao tác --}}
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('staff.edit', $s->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('staff.destroy', $s->id) }}" method="POST" class="d-inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên {{ $s->name }}?')" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-user-times me-2"></i> Chưa có dữ liệu nhân viên nào được tìm thấy.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $staff->links() }}
    </div>

</div>
@endsection