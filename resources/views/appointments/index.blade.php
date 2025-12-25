@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold mb-0">
            <i class="fas fa-calendar-alt me-2"></i> Quản lý Lịch hẹn (Theo Khoa)
        </h3>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm Lịch hẹn
        </a>
    </div>
    
    <hr>

    {{-- VÒNG LẶP CHIA BẢNG THEO KHOA --}}
    @forelse($appointments->groupBy('department_id') as $deptId => $groupApps)
        @php
            // Lấy tên Khoa từ bản ghi đầu tiên trong nhóm
            $deptName = $groupApps->first()->department->name ?? 'Chưa phân khoa / Tổng quát';
            
            // Đổi màu header để phân biệt
            // Nếu không có khoa (null) thì màu xám, có khoa thì màu xanh
            $headerClass = $deptId ? 'bg-info bg-opacity-10 text-primary' : 'bg-secondary bg-opacity-10 text-dark';
        @endphp

        <div class="card shadow-sm mb-5 border-0">
            {{-- Header tên Khoa --}}
            <div class="card-header {{ $headerClass }} py-3">
                <h5 class="mb-0 fw-bold text-uppercase">
                    <i class="fas fa-clinic-medical me-2"></i> {{ $deptName }}
                    <span class="badge bg-white text-dark ms-2 shadow-sm fs-6">{{ $groupApps->count() }} lịch</span>
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-3">Mã LH</th>
                                <th>Bệnh nhân</th>
                                <th>Thông tin khám</th> {{-- Gộp Lý do & SĐT --}}
                                <th>Bác sĩ</th>
                                <th>Thời gian</th>
                                <th>Người xử lý</th> {{-- Gộp Duyệt & Check-in --}}
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($groupApps as $a)
                            <tr>
                                {{-- 1. Mã --}}
                                <td class="ps-3 fw-bold text-muted">{{ $a->code }}</td>

                                {{-- 2. Bệnh nhân --}}
                                <td>
                                    <div class="fw-bold text-dark">{{ $a->patient_name }}</div>
                                    <small class="text-muted"><i class="fas fa-id-card me-1"></i>{{ $a->patient_code ?? '---' }}</small>
                                </td>

                                {{-- 3. Thông tin (SĐT + Lý do) --}}
                                <td style="max-width: 250px;">
                                    <div class="text-primary small mb-1">
                                        <i class="fas fa-phone-alt me-1"></i> {{ $a->patient_phone ?? '---' }}
                                    </div>
                                    @if($a->reason)
                                    <div class="text-muted small fst-italic text-truncate" title="{{ $a->reason }}">
                                        Lý do: {{ Str::limit($a->reason, 40) }}
                                    </div>
                                    @endif
                                </td>

                                {{-- 4. Bác sĩ (Bỏ cột Khoa vì đã ở Header) --}}
                                <td>
                                    @if($a->doctor)
                                        <div class="fw-bold text-dark">{{ $a->doctor->name }}</div>
                                    @else
                                        <span class="text-muted fst-italic">Chưa phân công</span>
                                    @endif
                                </td>

                                {{-- 5. Thời gian --}}
                                <td>
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</small>
                                </td>

                                {{-- 6. Người xử lý --}}
                                <td>
                                    @if($a->approved_by)
                                        <div class="small text-success mb-1" title="Người duyệt">
                                            <i class="fas fa-check-circle me-1"></i> {{ $a->approver->name }}
                                        </div>
                                    @endif
                                    @if($a->checked_in_by)
                                        <div class="small text-primary" title="Người check-in">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $a->checkinUser->name }}
                                        </div>
                                    @endif
                                    @if(!$a->approved_by && !$a->checked_in_by)
                                        <span class="text-muted small">---</span>
                                    @endif
                                </td>

                                {{-- 7. Trạng thái --}}
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'Đang chờ'      => 'warning text-dark',
                                            'Đã xác nhận'   => 'success',
                                            'Đang khám'     => 'info text-white',
                                            'Hoàn thành'    => 'primary',
                                            'Đã hẹn'        => 'secondary',
                                            'Hủy'           => 'danger',
                                        ][$a->status] ?? 'dark';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} rounded-pill px-3">
                                        {{ $a->status }}
                                    </span>
                                </td>

                                {{-- 8. Thao tác --}}
                                <td class="text-center text-nowrap">
                                    {{-- DUYỆT --}}
                                    @if($a->status == 'Đang chờ')
                                        <form action="{{ route('appointments.approve', $a->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success" title="Duyệt lịch">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- CHECK-IN --}}
                                    @if($a->status == 'Đã xác nhận')
                                        <form action="{{ route('appointments.checkin', $a->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-primary" title="Check-in vào khám">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Sửa --}}
                                    <a href="{{ route('appointments.edit', $a->id) }}" class="btn btn-sm btn-outline-primary ms-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Xóa --}}
                                    <form action="{{ route('appointments.destroy', $a->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Xóa lịch hẹn {{ $a->code }}?')" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="Empty" width="100" class="opacity-50 mb-3">
            <h5 class="text-muted">Chưa có lịch hẹn nào trong hệ thống.</h5>
        </div>
    @endforelse

    <div class="d-flex justify-content-center py-4">
        {{ $appointments->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection