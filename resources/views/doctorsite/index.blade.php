@extends('admin.master')

@section('title', 'Danh sách Bác sĩ')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-user-md me-2 text-primary"></i> Quản lý Bác sĩ
        </h3>
        <a href="{{ route('doctorsite.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="fas fa-plus me-2"></i> Thêm Bác sĩ Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover align-middle small table-striped text-nowrap">
                    <thead class="bg-light text-primary">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Thông tin Bác sĩ</th>
                            <th>Chuyên môn</th>
                            <th>Tài chính (Lương/HH)</th>
                            <th>Thông tin Ngân hàng</th> <th class="text-center">Đánh giá</th>
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
                            
                            {{-- Ảnh & Tên --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $doctor->image ? asset('storage/' . $doctor->image) : asset('assets/img/default-doctor.png') }}" 
                                         alt="doctor" width="45" height="45" 
                                         class="rounded-circle object-fit-cover shadow-sm border border-2 border-white me-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $doctor->user->name ?? 'Không rõ' }}</div>
                                        <div class="text-muted small">{{ $doctor->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Chuyên môn --}}
                            <td>
                                <span class="badge bg-soft-primary text-primary mb-1">
                                    {{ $doctor->department->name ?? 'Chưa gán khoa' }}
                                </span>
                                <div class="small mt-1">
                                    <i class="fas fa-stethoscope text-info me-1"></i> {{ $doctor->specialization ?? 'N/A' }}
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-briefcase me-1"></i> {{ $doctor->experience_years }} năm KN
                                </div>
                            </td>
                            
                            {{-- Tài chính --}}
                            <td>
                                <div class="fw-bold text-success mb-1">
                                    <i class="fas fa-money-bill-wave me-1"></i> {{ number_format($doctor->base_salary, 0, ',', '.') }} đ
                                </div>
                                <div class="d-flex gap-1 flex-wrap" style="font-size: 0.75rem;">
                                    <span class="badge bg-white text-dark border" title="Hoa hồng Khám">K: {{ $doctor->commission_exam_percent }}%</span>
                                    <span class="badge bg-white text-dark border" title="Hoa hồng Thuốc">T: {{ $doctor->commission_prescription_percent }}%</span>
                                    <span class="badge bg-white text-dark border" title="Hoa hồng Dịch vụ">D: {{ $doctor->commission_service_percent }}%</span>
                                </div>
                            </td>

                            {{-- Ngân hàng (MỚI) --}}
                            <td>
                                @if($doctor->bank_account_number)
                                    <div class="fw-semibold text-dark">{{ $doctor->bank_name }}</div>
                                    <div class="text-primary font-monospace">{{ $doctor->bank_account_number }}</div>
                                    <div class="text-muted small fst-italic">{{Str::limit($doctor->bank_account_holder, 15)}}</div>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </td>

                            {{-- Đánh giá --}}
                            <td class="text-center">
                                <div class="fw-bold text-warning">
                                    {{ number_format($doctor->rating, 1) }} <i class="fas fa-star"></i>
                                </div>
                                <small class="text-muted">({{ $doctor->reviews_count }} lượt)</small>
                            </td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @if($doctor->status)
                                    <span class="badge bg-success rounded-pill px-2">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-2">Đang ẩn</span>
                                @endif
                            </td>

                            {{-- Hành động --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li><a class="dropdown-item" href="{{ route('doctorsite.show', $doctor) }}"><i class="fas fa-eye text-info me-2"></i> Chi tiết</a></li>
                                        <li><a class="dropdown-item" href="{{ route('doctorsite.edit', $doctor) }}"><i class="fas fa-edit text-warning me-2"></i> Sửa hồ sơ</a></li>
                                        <li><a class="dropdown-item" href="{{ route('doctorsite.finance', $doctor) }}"><i class="fas fa-wallet text-success me-2"></i> Tài chính</a></li>
                                        <li><a class="dropdown-item" href="{{ route('doctor_attendances.index', ['doctor_id' => $doctor->user_id]) }}"><i class="fas fa-calendar-alt text-primary me-2"></i> Chấm công</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('doctorsite.destroy', $doctor) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Xóa bác sĩ này?')">
                                                    <i class="fas fa-trash-alt me-2"></i> Xóa
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-user-md fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">Chưa có dữ liệu bác sĩ nào.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($doctors->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-end">
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
<style>
    .object-fit-cover { object-fit: cover; }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
</style>
@endsection