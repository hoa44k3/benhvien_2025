@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-calendar-alt me-2"></i> Quản lý Lịch hẹn
    </h3>
    <hr>

    <a href="{{ route('appointments.create') }}" class="btn btn-primary shadow-sm mb-3">
        <i class="fas fa-plus me-1"></i> Thêm Lịch hẹn Mới
    </a>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã LH</th>
                            <th>Mã BN</th>
                            <th>Bệnh nhân</th>
                            <th>SĐT</th>
                            <th>Lý do khám</th>
                            <th>Chuẩn đoán</th>
                            <th>Ghi chú</th>
                            <th>Bác sĩ</th>
                            <th>Chuyên khoa</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Duyệt bởi</th>
                            <th>Check-in bởi</th>
                            <th>Ngày tạo</th>
                            <th>Cập nhật</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($appointments as $a)
                        <tr>
                            <td class="fw-bold">{{ $a->code }}</td>

                            <td>{{ $a->patient_code ?? '---' }}</td>

                            <td>
                                <div class="fw-bold">{{ $a->patient_name }}</div>
                            </td>

                            <td>{{ $a->patient_phone ?? '---' }}</td>

                            <td>{{ Str::limit($a->reason, 30) ?? '---' }}</td>

                            <td>{{ Str::limit($a->diagnosis, 25) ?? '---' }}</td>

                            <td>{{ Str::limit($a->notes, 25) ?? '---' }}</td>

                            <td>{{ $a->doctor->name ?? 'Chưa phân công' }}</td>

                            <td>{{ $a->department->name ?? 'Không xác định' }}</td>

                            <td>{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>

                            <td>{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}</td>

                            <td>
                                @if($a->approved_by)
                                    <span class="fw-bold text-success">{{ $a->approver->name }}</span>
                                @else
                                    ---
                                @endif
                            </td>

                            <td>
                                @if($a->checked_in_by)
                                    <span class="fw-bold text-primary">{{ $a->checkinUser->name }}</span>
                                @else
                                    ---
                                @endif
                            </td>

                            <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $a->updated_at->format('d/m/Y H:i') }}</td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'Đang chờ'      => 'warning text-dark',
                                        'Đã xác nhận'   => 'success',
                                        'Đang khám'     => 'info',
                                        'Hoàn thành'    => 'primary',
                                        'Đã hẹn'        => 'secondary',
                                        'Hủy'           => 'danger',
                                    ][$a->status] ?? 'dark';
                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $a->status }}
                                </span>
                            </td>

                            {{-- Thao tác --}}
                            <td class="text-center text-nowrap">

                                {{-- DUYỆT --}}
                                @if($a->status == 'Đang chờ')
                                    <form action="{{ route('appointments.approve', $a->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Duyệt</button>
                                    </form>
                                @endif

                                {{-- CHECK-IN --}}
                                @if($a->status == 'Đã xác nhận')
                                    <form action="{{ route('appointments.checkin', $a->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Check-in</button>
                                    </form>
                                @endif

                                {{-- Sửa --}}
                                <a href="{{ route('appointments.edit', $a->id) }}"
                                   class="btn btn-sm btn-outline-primary mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Xóa --}}
                                <form action="{{ route('appointments.destroy', $a->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Xóa lịch hẹn {{ $a->code }}?')"
                                            class="btn btn-sm btn-outline-danger">
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

</div>
@endsection
