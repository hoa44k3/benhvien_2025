@extends('admin.master')

@section('title', 'Danh sách Bác sĩ')

@section('body')
<div class="container-fluid mt-4">

    {{-- Header & Nút Thêm mới --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-user-md me-2 text-primary"></i> Quản lý Bác sĩ
        </h3>
        <a href="{{ route('doctorsite.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="fas fa-plus me-2"></i> Thêm Bác sĩ Mới
        </a>
    </div>

    {{-- Thông báo Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Bảng Danh sách --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover align-middle small table-striped text-nowrap">
                    <thead class="bg-light text-primary">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Thông tin Bác sĩ</th>
                            <th>Chuyên môn & Bằng cấp</th> {{-- Cột này hiển thị cả Chuyên khoa và Học vị --}}
                            <th>Tài chính </th>
                            <th>Ngân hàng</th>
                            <th class="text-center">Đánh giá</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            <td class="text-center fw-bold text-muted">
                                {{ $doctors->firstItem() + $loop->index }}
                            </td>
                            
                            {{-- 1. Ảnh & Tên & Email --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $doctor->image ? asset('storage/' . $doctor->image) : asset('assets/img/default-doctor.png') }}" 
                                         alt="doctor" width="45" height="45" 
                                         class="rounded-circle object-fit-cover shadow-sm border border-2 border-white me-2">
                                    <div>
                                        {{-- Hiển thị Học vị trước tên (VD: ThS.BS Nguyễn Văn A) --}}
                                        <div class="fw-bold text-dark">
                                            @if($doctor->degree) 
                                                <span class="text-primary fw-bold">{{ $doctor->degree }}</span> 
                                            @endif
                                            {{ $doctor->user->name ?? 'Không rõ' }}
                                        </div>
                                        <div class="text-muted small">{{ $doctor->user->email ?? '-' }}</div>
                                        
                                        {{-- Label check chứng chỉ --}}
                                        @if($doctor->license_number)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 mt-1" style="font-size: 0.65rem;" title="Số CCHN: {{ $doctor->license_number }}">
                                                <i class="fas fa-check-circle me-1"></i> Đã có CCHN
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2 mt-1" style="font-size: 0.65rem;">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Thiếu CCHN
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            {{-- 2. Chuyên môn & Nơi đào tạo --}}
                            <td>
                                <span class="badge bg-soft-primary text-primary mb-1">
                                    {{ $doctor->department->name ?? 'Chưa gán khoa' }}
                                </span>
                                <div class="fw-semibold text-dark" style="font-size: 0.8rem;">
                                    <i class="fas fa-stethoscope text-info me-1"></i> {{ $doctor->specialization ?? 'Chưa cập nhật' }}
                                </div>
                                
                                {{-- Hiển thị nơi cấp bằng/đào tạo --}}
                                @if($doctor->license_issued_by)
                                    <div class="text-muted small fst-italic mt-1">
                                        <i class="fas fa-university me-1 text-secondary"></i> {{ Str::limit($doctor->license_issued_by, 20) }}
                                    </div>
                                @endif
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-briefcase me-1"></i> {{ $doctor->experience_years }} năm KN
                                </div>
                            </td>
                            
                            {{-- 3. Tài chính --}}
                            <td>
                                <div class="fw-bold text-success mb-1"><i class="fas fa-money-bill-wave me-1"></i> {{ number_format($doctor->base_salary, 0, ',', '.') }} đ</div>
                                <div class="d-flex gap-1 flex-wrap" style="font-size: 0.75rem;">
                                    <span class="badge bg-white text-dark border">HH Khám: {{ $doctor->commission_exam_percent }}%</span>
                                </div>
                            </td>

                            {{-- 4. Ngân hàng --}}
                            <td>
                                @if($doctor->bank_account_number)
                                    <div class="fw-bold text-dark" style="font-size: 0.8rem;">{{ $doctor->bank_name }}</div>
                                    <div class="text-primary font-monospace">{{ $doctor->bank_account_number }}</div>
                                    <div class="text-muted small fst-italic">{{ Str::limit($doctor->bank_account_holder, 15) }}</div>
                                @else
                                    <span class="text-muted small fst-italic">---</span>
                                @endif
                            </td>

                            {{-- 5. Đánh giá --}}
                            <td class="text-center">
                                <div class="fw-bold text-warning">
                                    {{ number_format($doctor->rating, 1) }} <i class="fas fa-star"></i>
                                </div>
                                <small class="text-muted" style="font-size: 0.7rem;">({{ $doctor->reviews_count }} lượt)</small>
                            </td>

                            {{-- 6. Trạng thái --}}
                            <td class="text-center">
                                @if($doctor->status)
                                    <span class="badge bg-success rounded-pill px-2">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-2">Đang ẩn</span>
                                @endif
                            </td>

                            {{-- 7. Hành động --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog text-secondary"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('doctorsite.show', $doctor) }}">
                                                <i class="fas fa-eye text-info me-2"></i> Xem chi tiết
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('doctorsite.edit', $doctor) }}">
                                                <i class="fas fa-edit text-warning me-2"></i> Sửa hồ sơ
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('doctorsite.finance', $doctor) }}">
                                                <i class="fas fa-wallet text-success me-2"></i> Xem lương/HH
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('doctor_attendances.index', ['doctor_id' => $doctor->user_id]) }}">
                                                <i class="fas fa-calendar-check text-primary me-2"></i> Lịch sử chấm công
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('doctorsite.destroy', $doctor) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bác sĩ này? Hành động này không thể hoàn tác.')">
                                                    <i class="fas fa-trash-alt me-2"></i> Xóa bác sĩ
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-md fa-3x mb-3 text-secondary opacity-25"></i>
                                    <p class="mb-0">Chưa có dữ liệu bác sĩ nào.</p>
                                    <a href="{{ route('doctorsite.create') }}" class="btn btn-sm btn-outline-primary mt-2">Thêm ngay</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        @if($doctors->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-end">
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Style riêng nhỏ --}}
<style>
    .object-fit-cover { object-fit: cover; }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    /* Hover effect cho dòng */
    tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endsection