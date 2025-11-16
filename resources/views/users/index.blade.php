@extends('admin.master')

@section('title', 'Danh sách người dùng')

{{-- Thêm CSS DataTables (Nếu bạn dùng DataTables) --}}
{{-- @push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
@endpush --}}

@section('body')
{{-- ĐÃ THAY ĐỔI: Sử dụng container-fluid để tận dụng tối đa chiều rộng màn hình --}}
<div class="container-fluid mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        {{-- Card Header --}}
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Danh sách người dùng</h4>
            <a href="{{ route('users.create') }}" class="btn btn-light text-primary fw-bold shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i>Thêm người dùng mới
            </a>
        </div>
        
        {{-- Card Body --}}
        <div class="card-body p-4">
            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Bảng DataTables --}}
            <div class="table-responsive">
                <table id="userTable" class="table table-hover table-striped border" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Ảnh</th>
                            <th>Họ & tên</th>
                            <th>Mã BN / Email</th>
                            <th>Vai trò</th>
                            <th>Địa chỉ (Sơ lược)</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                {{-- Kiểm tra và hiển thị ảnh --}}
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle object-fit-cover" width="40" height="40">
                                @else
                                    <i class="bi bi-person-circle fs-3 text-secondary"></i>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                <br><small class="text-muted">{{ $user->phone }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary me-1">{{ $user->patient_code ?? 'N/A' }}</span>
                                <br><small>{{ $user->email }}</small>
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
                                        <span class="badge rounded-pill bg-info text-dark mb-1">
                                            {{ $roleNames[$role->name] ?? $role->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-danger">Chưa gán</span>
                                @endif
                            </td>
                            <td>
                                {{ Str::limit($user->address, 30) }} 
                                <br><small class="text-muted">Ngày sinh: {{ $user->date_of_birth }}</small>
                            </td>
                            <td class="text-center">
                                @switch($user->status)
                                    @case('active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Hoạt động</span>
                                        @break
                                    @case('inactive')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i>Ngừng</span>
                                        @break
                                    @case('banned')
                                        <span class="badge bg-danger"><i class="bi bi-slash-circle-fill me-1"></i>Bị cấm</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Không rõ</span>
                                @endswitch
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Xác nhận xóa người dùng {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS nội tuyến để làm đẹp thêm */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); 
    }
    .card-header h4 {
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }
    /* Đảm bảo ảnh avatar hiển thị đẹp */
    .object-fit-cover {
        object-fit: cover;
    }
</style>

{{-- Script để khởi tạo DataTables (Cần có jQuery và DataTables JS/CSS trong master template) --}}
{{-- @push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
             "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json" // Thêm gói ngôn ngữ tiếng Việt
            },
            "pageLength": 10,
            "order": [[ 2, "asc" ]] // Sắp xếp theo cột "Họ & tên"
        });
    });
</script>
@endpush --}}

@endsection