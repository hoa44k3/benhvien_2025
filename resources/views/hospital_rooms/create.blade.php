@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-plus-square me-2"></i> Thêm Phòng Bệnh Mới
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

            <form action="{{ route('hospital_rooms.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3 text-secondary">Thông tin Phòng</h5>
                <hr>
                <div class="row g-3">
                    
                    {{-- Mã phòng --}}
                    <div class="col-md-6">
                        <label for="roomCode" class="form-label fw-semibold">Mã phòng <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="text" name="room_code" id="roomCode" class="form-control" value="{{ old('room_code') }}" placeholder="VD: A101" required>
                        </div>
                    </div>

                    {{-- Khoa --}}
                    <div class="col-md-6">
                        <label for="departmentId" class="form-label fw-semibold">Khoa phụ trách <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hospital-alt"></i></span>
                            <select name="department_id" id="departmentId" class="form-select" required>
                                <option value="">-- Chọn khoa --</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    {{-- Loại phòng --}}
                    <div class="col-md-6">
                        <label for="roomType" class="form-label fw-semibold">Loại phòng <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-star"></i></span>
                            <input type="text" name="room_type" id="roomType" class="form-control" value="{{ old('room_type') }}" placeholder="VD: Phòng đơn, Phòng VIP..." required>
                        </div>
                    </div>

                    {{-- Trạng thái phòng --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Trạng thái phòng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <select name="status" id="status" class="form-select">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Trống</option>
                                <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>Đang sử dụng</option>
                                <option value="cleaning" {{ old('status') == 'cleaning' ? 'selected' : '' }}>Đang dọn</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
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
                            <input type="number" name="total_beds" id="totalBeds" class="form-control" value="{{ old('total_beds', 1) }}" min="1" required>
                        </div>
                    </div>

                    {{-- Số giường đã sử dụng --}}
                    <div class="col-md-4">
                        <label for="occupiedBeds" class="form-label fw-semibold">Số giường đã sử dụng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-minus"></i></span>
                            <input type="number" name="occupied_beds" id="occupiedBeds" class="form-control" value="{{ old('occupied_beds', 0) }}" min="0">
                        </div>
                        <div class="form-text text-danger">Đảm bảo số giường đã sử dụng không vượt quá tổng số giường.</div>
                    </div>
                    
                    {{-- Chọn bệnh nhân --}}
                    <div class="col-md-12">
                        <label for="userIds" class="form-label fw-semibold">Chọn bệnh nhân đang nằm</label>
                        <select name="user_ids[]" id="userIds" class="form-select" multiple size="5">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} (Mã: {{ $user->code ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Giữ Ctrl/Cmd để chọn nhiều bệnh nhân.</div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('hospital_rooms.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu Phòng Bệnh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection