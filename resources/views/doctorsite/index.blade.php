@extends('admin.master')

@section('title', 'Danh sách Bác sĩ')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-user-md me-2 text-primary"></i> Danh sách Bác sĩ
        </h3>
        <a href="{{ route('doctorsite.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="fas fa-plus me-2"></i> Thêm Bác sĩ Mới
        </a>
    </div>

    {{-- Thông báo success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-hover table-striped align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 30px;">#</th>
                            <th style="width: 70px;">Ảnh</th>
                            <th style="width: 150px;">Tên bác sĩ</th>
                            <th style="width: 150px;">Khoa & Email</th>
                            <th style="width: 130px;">Chuyên môn</th>
                            <th style="width: 100px;">Kinh nghiệm</th>
                            <th>Giới thiệu (Sơ lược)</th>
                            <th class="text-center" style="width: 100px;">Đánh giá</th>
                            <th class="text-center" style="width: 80px;">Trạng thái</th>
                            <th class="text-center" style="width: 130px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            {{-- Số thứ tự --}}
                            <td class="text-center">
                                {{ $doctors->firstItem() + $loop->index }}
                            </td>
                            
                            {{-- Ảnh --}}
                            <td>
                                @if($doctor->image)
                                    <img src="{{ asset('storage/' . $doctor->image) }}" alt="doctor" width="50" height="50" class="rounded-circle object-fit-cover shadow-sm border border-light">
                                @else
                                    <img src="{{ asset('assets/img/default-doctor.png') }}" width="50" height="50" alt="Default Doctor" class="rounded-circle object-fit-cover shadow-sm border border-light">
                                @endif
                            </td>

                            {{-- Tên bác sĩ --}}
                            <td>
                                <strong class="text-primary">{{ $doctor->user->name ?? 'Không rõ' }}</strong>
                            </td>
                            
                            {{-- Khoa & Email --}}
                            <td>
                                <span class="badge bg-secondary mb-1">{{ $doctor->department->name ?? 'Chưa gán' }}</span>
                                <br><small class="text-muted text-truncate d-block">{{ $doctor->user->email ?? '-' }}</small>
                            </td>
                            
                            {{-- Chuyên khoa chính --}}
                            <td>
                                <span class="text-dark fw-medium">{{ $doctor->specialization ?? '-' }}</span>
                            </td>
                            
                            {{-- Số năm kinh nghiệm --}}
                            <td class="text-center">
                                <span class="fw-bold text-success">{{ $doctor->experience_years ?? 0 }}</span> năm
                            </td>
                            
                            {{-- Giới thiệu --}}
                            <td style="max-width:300px;">
                                <span class="text-muted">{{ Str::limit($doctor->bio, 80) }}</span>
                            </td>

                            {{-- Điểm đánh giá & Lượt đánh giá --}}
                            <td class="text-center">
                                <i class="fas fa-star text-warning me-1"></i>
                                <span class="fw-bold text-dark">{{ number_format($doctor->rating, 1) }}/5</span>
                                <br><small class="text-muted">({{ $doctor->reviews_count }} lượt)</small>
                            </td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @if($doctor->status)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-eye-slash me-1"></i> Ẩn</span>
                                @endif
                            </td>

                            {{-- Hành động --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('doctorsite.show', $doctor) }}" class="btn btn-sm btn-info text-white me-1" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('doctorsite.edit', $doctor) }}" class="btn btn-sm btn-warning me-1" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('doctorsite.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa bác sĩ {{ $doctor->user->name ?? $doctor->id }}? Hành động này không thể hoàn tác.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-2"></i> Không tìm thấy dữ liệu bác sĩ nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Card - Phân trang --}}
        @if($doctors->total() > $doctors->perPage())
            <div class="card-footer bg-light border-top pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Hiển thị **{{ $doctors->firstItem() }}** đến **{{ $doctors->lastItem() }}** trong tổng số **{{ $doctors->total() }}** bác sĩ.
                    </div>
                    {{-- Laravel Pagination Links --}}
                    <div>
                        {{ $doctors->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom style for object-fit on image */
    .object-fit-cover {
        object-fit: cover;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa; /* Light background on hover */
    }
</style>
@endsection