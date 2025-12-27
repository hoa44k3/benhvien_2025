@extends('admin.master')

@section('title', 'Quản lý Lịch hẹn')

@section('body')
<div class="container-fluid mt-4">
    {{-- (Phần Header và Filter giữ nguyên như cũ...) --}}
     <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-calendar-check text-primary me-2"></i> Quản lý Lịch hẹn
            </h3>
            <span class="text-muted small">Quản lý danh sách đặt lịch khám bệnh</span>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary shadow-sm px-4 fw-bold rounded-pill">
            <i class="fas fa-plus me-2"></i> Thêm Lịch Hẹn
        </a>
    </div>

    {{-- THANH CÔNG CỤ TÌM KIẾM (FILTER BAR) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded-3">
            <form action="{{ route('appointments.index') }}" method="GET" class="row g-3 align-items-end">
                
                {{-- Tìm từ khóa --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Từ khóa</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                               value="{{ request('keyword') }}" placeholder="Mã, Tên, SĐT...">
                    </div>
                </div>

                {{-- Chọn ngày --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted">Ngày khám</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

                {{-- Chọn Bác sĩ --}}
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Bác sĩ phụ trách</label>
                    <select name="doctor_id" class="form-select">
                        <option value="">-- Tất cả bác sĩ --</option>
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                                {{ $doc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="all">-- Tất cả --</option>
                        <option value="Đang chờ" {{ request('status') == 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                        <option value="Đã xác nhận" {{ request('status') == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đang khám" {{ request('status') == 'Đang khám' ? 'selected' : '' }}>Đang khám</option>
                        <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Hủy" {{ request('status') == 'Hủy' ? 'selected' : '' }}>Đã Hủy</option>
                    </select>
                </div>

                {{-- Nút Lọc --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold"><i class="fas fa-filter me-1"></i> Lọc</button>
                </div>
            </form>
        </div>
    </div>


    {{-- BẢNG DỮ LIỆU --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3 ps-4">Thông tin Bệnh nhân</th>
                            <th class="py-3">Thời gian khám</th>
                            <th class="py-3">Bác sĩ & Khoa</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $a)
                            <tr>
                                {{-- 1. BỆNH NHÂN --}}
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light text-primary fw-bold d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            {{ substr($a->patient_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $a->patient_name }}</div>
                                            <div class="small text-muted">
                                                <i class="fas fa-phone-alt me-1" style="font-size: 10px;"></i> {{ $a->patient_phone }}
                                                <span class="text-info fw-bold ms-1">{{ $a->code }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. THỜI GIAN --}}
                                <td>
                                    <div class="fw-bold fs-5 text-dark">{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}</div>
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</div>
                                </td>

                                {{-- 3. BÁC SĨ --}}
                                <td>
                                    <div class="fw-bold">{{ $a->doctor->name ?? '---' }}</div>
                                    <span class="badge bg-light text-secondary border">{{ $a->department->name ?? 'Tổng quát' }}</span>
                                </td>

                                {{-- 4. TRẠNG THÁI --}}
                                <td class="text-center">
                                    @php
                                        $badges = [
                                            'Đang chờ' => 'warning', 'Đã xác nhận' => 'info', 
                                            'Đang khám' => 'primary', 'Hoàn thành' => 'success', 
                                            'Hủy' => 'danger'
                                        ];
                                        $color = $badges[$a->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} text-{{ $color == 'warning' ? 'dark' : 'white' }} py-2 px-3 rounded-pill">
                                        {{ $a->status }}
                                    </span>
                                    
                                    {{-- Hiển thị ai duyệt / ai checkin --}}
                                    <div class="mt-1 small">
                                        @if($a->approved_by) <div class="text-success"><i class="fas fa-check"></i> Duyệt: {{ $a->approver->name ?? 'Admin' }}</div> @endif
                                        @if($a->checked_in_by) <div class="text-primary"><i class="fas fa-user-check"></i> Check-in: {{ $a->checkinUser->name ?? 'NV' }}</div> @endif
                                    </div>
                                </td>

                                {{-- 5. THAO TÁC --}}
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-secondary"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                            
                                            {{-- NÚT GỌI MODAL DUYỆT --}}
                                            @if($a->status == 'Đang chờ')
                                                <li>
                                                    <button class="dropdown-item text-success fw-bold" 
                                                        onclick="openApproveModal({{ $a->id }}, '{{ $a->code }}')">
                                                        <i class="fas fa-check me-2"></i> Duyệt lịch
                                                    </button>
                                                </li>
                                            @endif

                                            {{-- NÚT GỌI MODAL CHECK-IN --}}
                                            @if($a->status == 'Đã xác nhận')
                                                <li>
                                                    <button class="dropdown-item text-primary fw-bold"
                                                        onclick="openCheckinModal({{ $a->id }}, '{{ $a->patient_name }}')">
                                                        <i class="fas fa-user-check me-2"></i> Check-in khám
                                                    </button>
                                                </li>
                                            @endif

                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="{{ route('appointments.edit', $a->id) }}">Sửa thông tin</a></li>
                                            <li>
                                                <form action="{{ route('appointments.cancel', $a->id) }}" method="POST">
                                                    @csrf 
                                                    <button class="dropdown-item text-danger" onclick="return confirm('Hủy lịch này?')">Hủy lịch</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Chưa có lịch hẹn nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
             {{-- Phân trang --}}
            @if($appointments->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-end">
                        {{ $appointments->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- === MODAL DUYỆT LỊCH === --}}
{{-- === MODAL DUYỆT LỊCH (Dành cho Lễ tân) === --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="approveForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Duyệt Lịch Hẹn</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn đang duyệt lịch hẹn mã: <strong id="approveCode" class="text-primary"></strong></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Người duyệt (Lễ tân):</label>
                        <select name="approver_id" class="form-select">
                            {{-- Vẫn giữ tùy chọn chính mình (Admin hoặc User hiện tại) --}}
                            <option value="{{ Auth::id() }}">-- Tôi ({{ Auth::user()->name }}) --</option>
                            
                            {{-- Chỉ lặp qua danh sách Lễ tân --}}
                            @foreach($receptionists as $staff) 
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted small">Chỉ Lễ tân hoặc Admin mới có quyền duyệt.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success fw-bold">Xác nhận Duyệt</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- === MODAL CHECK-IN === --}}
{{-- === MODAL CHECK-IN (Dành cho Y tá) === --}}
<div class="modal fade" id="checkinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="checkinForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-clock me-2"></i>Check-in Bệnh nhân</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i> Bước này Y tá tiếp nhận bệnh nhân và đo sinh hiệu.
                    </div>
                    <p>Bệnh nhân: <strong id="checkinName" class="text-primary fs-5"></strong></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Y tá thực hiện:</label>
                        <select name="checked_in_by" class="form-select">
                            <option value="{{ Auth::id() }}">-- Tôi ({{ Auth::user()->name }}) --</option>
                            
                            {{-- Chỉ lặp qua danh sách Y tá --}}
                            @foreach($nurses as $nurse)
                                <option value="{{ $nurse->id }}">{{ $nurse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary fw-bold">Xác nhận Check-in</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- SCRIPT XỬ LÝ MODAL --}}
<script>
    function openApproveModal(id, code) {
        // Cập nhật action cho form
        document.getElementById('approveForm').action = "/appointments/" + id + "/approve";
        // Hiển thị mã
        document.getElementById('approveCode').innerText = code;
        // Mở modal
        new bootstrap.Modal(document.getElementById('approveModal')).show();
    }

    function openCheckinModal(id, name) {
        var url = "{{ route('appointments.checkin', ':id') }}";
        url = url.replace(':id', id);

        document.getElementById('checkinForm').action = url;
        document.getElementById('checkinName').innerText = name;
        new bootstrap.Modal(document.getElementById('checkinModal')).show();
    }
</script>

@endsection