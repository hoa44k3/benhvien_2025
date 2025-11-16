@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-calendar-alt me-2"></i> Quản lý Lịch hẹn
    </h3>
    <hr>

    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Lịch hẹn Mới
            </a>
        </div>
        
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo Tên Bệnh nhân, Mã LH...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Trạng thái</option>
                    <option value="waiting">Đang chờ</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="completed">Hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </div>
        </div>
    </div>

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
    <th>Bác sĩ</th>
    <th>Chuyên khoa</th>
    <th>Ngày</th>
    <th>Giờ</th>
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
        <small class="text-muted">{{ Str::limit($a->reason, 30) ?? '---' }}</small>
    </td>
    <td>{{ $a->patient_phone ?? '---' }}</td>
    <td>{{ Str::limit($a->reason, 20) ?? '---' }}</td>
    <td>{{ $a->doctor->name ?? 'Chưa phân công' }}</td>
    <td>{{ $a->department->name ?? 'Không xác định' }}</td>
    <td>{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
    <td>{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}</td>
    <td class="text-center">
        @php
            $statusClass = [
                'Đang chờ' => 'warning text-dark',
                'Đã xác nhận' => 'success',
                'Đang khám' => 'info',
                'Hoàn thành' => 'primary',
                'Đã hủy' => 'danger',
            ][$a->status] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $statusClass }}">{{ $a->status }}</span>
    </td>
    <td class="text-center text-nowrap">
        <a href="{{ route('appointments.edit', $a->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('appointments.destroy', $a->id) }}" method="POST" style="display:inline-block">
            @csrf @method('DELETE')
            <button type="submit" onclick="return confirm('Xóa lịch hẹn {{ $a->code }}?')" class="btn btn-sm btn-outline-danger">
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

    <div class="mt-4 d-flex justify-content-center">
        {{ $appointments->links() }}
    </div>

</div>
@endsection