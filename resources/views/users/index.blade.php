@extends('admin.master')

@section('title', 'Danh sách người dùng')

@section('body')
<div class="container-fluid mt-4">

    <div class="card shadow-lg border-0 rounded-3">
        {{-- Card Header --}}
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top-3">
            <h4 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i> Danh sách người dùng</h4>
            <a href="{{ route('users.create') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Thêm người dùng mới
            </a>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Bảng Dữ liệu --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped border align-middle mb-4" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="width: 70px;">Ảnh</th>
                            <th style="width: 180px;">Họ & tên</th>
                            <th style="width: 150px;">Mã BN / Email</th>
                            <th style="width: 180px;">Vai trò</th>
                            <th>Địa chỉ (Sơ lược)</th>
                            <th class="text-center" style="width: 100px;">Trạng thái</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            {{-- Số thứ tự chính xác theo phân trang --}}
                            <td class="text-center">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle object-fit-cover shadow-sm" width="40" height="40">
                                @else
                                    <i class="fas fa-user-circle fs-3 text-secondary"></i>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">{{ $user->name }}</strong>
                                <br><small class="text-muted">{{ $user->phone }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary me-1">{{ $user->patient_code ?? 'N/A' }}</span>
                                <br><small class="text-truncate d-block">{{ $user->email }}</small>
                            </td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @php
                                        // Định nghĩa vai trò dịch sang tiếng Việt
                                        $roleNames = [
                                            'admin' => 'Quản trị viên',
                                            'pharmacist' => 'Dược sĩ',
                                            'user' => 'Bệnh nhân',
                                            'receptionist' => 'Lễ tân',
                                            'doctor' => 'Bác sĩ',
                                            'nurse' => 'Điều dưỡng',
                                        ];
                                    @endphp
                                    @foreach($user->roles as $role)
                                        <span class="badge rounded-pill bg-info text-dark mb-1" title="{{ $role->name }}">
                                            {{ $roleNames[$role->name] ?? $role->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-danger">Chưa gán</span>
                                @endif
                            </td>
                            <td>
                                {{ Str::limit($user->address, 30) }} 
                                <br><small class="text-muted">Ngày sinh: {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'N/A' }}</small>
                            </td>
                            <td class="text-center">
                                @switch($user->status)
                                    @case('active')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Hoạt động</span>
                                        @break
                                    @case('inactive')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Ngừng</span>
                                        @break
                                    @case('banned')
                                        <span class="badge bg-danger"><i class="fas fa-ban me-1"></i> Bị cấm</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Không rõ</span>
                                @endswitch
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-2" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa người dùng {{ $user->name }}? Hành động này không thể hoàn tác.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-2"></i> Không tìm thấy người dùng nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Footer Card - Phân trang --}}
            @if($users->total() > $users->perPage())
            <div class="card-footer bg-white border-top pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Hiển thị **{{ $users->firstItem() }}** đến **{{ $users->lastItem() }}** trong tổng số **{{ $users->total() }}** người dùng.
                    </div>
                    {{-- Laravel Pagination Links (Sử dụng view Bootstrap 5) --}}
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </div>
</div>

<style>
    /* CSS nội tuyến để làm đẹp thêm */
    .bg-gradient-primary {
        /* Đổi màu gradient cho header */
        background: linear-gradient(135deg, #0d6efd 0%, #0037a3 100%); 
    }
    .card-header h4 {
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    /* Đảm bảo ảnh avatar hiển thị đẹp */
    .object-fit-cover {
        object-fit: cover;
    }
    /* Đảm bảo các badge vai trò có khoảng cách */
    .badge {
        font-weight: 500;
    }
</style>
@endsection