@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-edit me-2"></i> Chỉnh sửa Phòng Bệnh: {{ $hospital_room->room_code }}
            </h4>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    <strong>Lỗi!</strong> Vui lòng kiểm tra lại các trường bên dưới.
                    <ul class="mt-2 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('hospital_rooms.update', $hospital_room->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="mb-3 text-secondary">Thông tin Phòng</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Mã phòng --}}
                    <div class="col-md-6">
                        <label for="roomCode" class="form-label fw-semibold">Mã phòng <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="text" name="room_code" id="roomCode" class="form-control" 
                                   value="{{ old('room_code', $hospital_room->room_code) }}" required placeholder="VD: A101">
                        </div>
                    </div>

                    {{-- Khoa --}}
                    <div class="col-md-6">
                        <label for="departmentId" class="form-label fw-semibold">Khoa phụ trách <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hospital-alt"></i></span>
                            <select name="department_id" id="departmentId" class="form-select" required>
                                @foreach ($departments as $dept)
                                    @php $selected = old('department_id', $hospital_room->department_id) == $dept->id ? 'selected' : ''; @endphp
                                    <option value="{{ $dept->id }}" {{ $selected }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    {{-- Loại phòng --}}
                    <div class="col-md-6">
                        <label for="roomType" class="form-label fw-semibold">Loại phòng <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-star"></i></span>
                            <input type="text" name="room_type" id="roomType" class="form-control" 
                                   value="{{ old('room_type', $hospital_room->room_type) }}" required placeholder="VD: Phòng đơn, Phòng VIP...">
                        </div>
                    </div>

                    {{-- Trạng thái phòng --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Trạng thái phòng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <select name="status" id="status" class="form-select">
                                @php $currentStatus = old('status', $hospital_room->status); @endphp
                                <option value="available" {{ $currentStatus == 'available' ? 'selected' : '' }}>Trống</option>
                                <option value="in_use" {{ $currentStatus == 'in_use' ? 'selected' : '' }}>Đang sử dụng</option>
                                <option value="cleaning" {{ $currentStatus == 'cleaning' ? 'selected' : '' }}>Đang dọn</option>
                                <option value="maintenance" {{ $currentStatus == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                            </select>
                        </div>
                    </div>

                </div>

                <h5 class="mt-4 mb-3 text-secondary">Quản lý Giường & Bệnh nhân</h5>
                <hr>
                <div class="row g-3">

                    {{-- Tổng số giường --}}
                    <div class="col-md-4">
                        <label for="totalBeds" class="form-label fw-semibold">Tổng số giường <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-bed"></i></span>
                            <input type="number" name="total_beds" id="totalBeds" class="form-control" 
                                   value="{{ old('total_beds', $hospital_room->total_beds) }}" min="1" required>
                        </div>
                    </div>

                    {{-- Số giường đã sử dụng --}}
                    <div class="col-md-4">
                        <label for="occupiedBeds" class="form-label fw-semibold">Số giường đã sử dụng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-minus"></i></span>
                            <input type="number" name="occupied_beds" id="occupiedBeds" class="form-control" 
                                   value="{{ old('occupied_beds', $hospital_room->occupied_beds) }}" min="0">
                        </div>
                    </div>
                    
                    {{-- Số giường còn trống (Readonly) --}}
                    <div class="col-md-4">
                        <label for="availableBeds" class="form-label fw-semibold">Số giường còn trống</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                            <input type="number" id="availableBeds" class="form-control bg-light fw-bold text-success" 
                                   value="{{ $hospital_room->total_beds - $hospital_room->occupied_beds }}" readonly 
                                   title="Giá trị này được tính tự động (Tổng giường - Đã sử dụng)">
                        </div>
                    </div>
                    
                    {{-- Chọn bệnh nhân --}}
                    <div class="col-md-12">
                        <label for="userIds" class="form-label fw-semibold">Chọn bệnh nhân đang nằm</label>
                        @php
                            // Lấy user IDs hiện tại từ model, hoặc từ old() nếu có lỗi
                            $currentUsers = old('user_ids', $hospital_room->user_ids ?? []);
                        @endphp
                        <select name="user_ids[]" id="userIds" class="form-select" multiple size="5">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ in_array($user->id, $currentUsers) ? 'selected' : '' }}>
                                    {{ $user->name }} (Mã: {{ $user->code ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Giữ Ctrl/Cmd để chọn nhiều bệnh nhân.</div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('hospital_rooms.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-warning shadow-sm text-dark fw-bold">
                        <i class="fas fa-sync-alt me-1"></i> Cập nhật Phòng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection