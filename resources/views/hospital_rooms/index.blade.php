@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-bed me-2"></i> Giám sát Phòng bệnh
    </h3>
    <hr>

    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <a href="{{ route('hospital_rooms.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Phòng Mới
            </a>
        </div>
        
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo Mã phòng hoặc Tên khoa...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Trạng thái</option>
                    <option value="available">Trống</option>
                    <option value="in_use">Đang sử dụng</option>
                    <option value="cleaning">Đang dọn</option>
                    <option value="maintenance">Bảo trì</option>
                </select>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Loại phòng</option>
                    <option value="standard">Phòng thường</option>
                    <option value="vip">Phòng VIP</option>
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
                            <th class="text-center">Mã phòng</th>
                            <th>Khoa phụ trách</th>
                            <th class="text-center">Loại phòng</th>
                            <th class="text-center">Giường</th>
                            <th class="text-center">Trạng thái</th>
                            <th>Bệnh nhân trong phòng</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                        <tr>
                            <td class="text-center fw-bold text-primary">{{ $room->room_code }}</td>
                            <td><i class="fas fa-hospital-alt me-1 text-info"></i> {{ $room->department->name ?? 'Không có' }}</td>
                            <td class="text-center">{{ $room->room_type }}</td>
                            
                            {{-- Cột thống kê giường --}}
                            <td class="text-center text-nowrap">
                                <span class="d-block text-success fw-bold">
                                    <i class="fas fa-check-circle me-1"></i> Trống: {{ $room->available_beds }}
                                </span>
                                <span class="d-block text-danger">
                                    <i class="fas fa-user-minus me-1"></i> Đã dùng: {{ $room->occupied_beds }} / {{ $room->total_beds }}
                                </span>
                            </td>
                            
                            {{-- Cột Trạng thái --}}
                            <td class="text-center">
                                @switch($room->status)
                                    @case('available') <span class="badge bg-success py-2 px-3"><i class="fas fa-door-open"></i> Trống</span> @break
                                    @case('in_use') <span class="badge bg-warning text-dark py-2 px-3"><i class="fas fa-user-friends"></i> Đang sử dụng</span> @break
                                    @case('cleaning') <span class="badge bg-info text-dark py-2 px-3"><i class="fas fa-broom"></i> Đang dọn</span> @break
                                    @case('maintenance') <span class="badge bg-secondary py-2 px-3"><i class="fas fa-wrench"></i> Bảo trì</span> @break
                                @endswitch
                            </td>
                            
                            {{-- Cột Bệnh nhân --}}
                            <td>
                                @php
                                    $users = $room->users; // Lấy qua accessor getUsersAttribute()
                                @endphp
                                @forelse ($users as $user)
                                    <span class="badge bg-primary me-1 mb-1">{{ $user->name }}</span>
                                @empty
                                    <span class="text-muted fst-italic">Chưa có bệnh nhân</span>
                                @endforelse
                            </td>

                            {{-- Cột Hành động --}}
                            <td class="text-center text-nowrap">
                                <a href="{{ route('hospital_rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('hospital_rooms.destroy', $room->id) }}" method="POST" class="d-inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn XÓA phòng {{ $room->room_code }}?')" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-hospital-alt me-2"></i> Hiện chưa có phòng bệnh nào được tạo.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $rooms->links() }}
    </div>

</div>
@endsection