@extends('admin.master')

@section('title', 'Quản lý Hồ sơ Bệnh án')

@section('body')
<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-file-medical me-2 text-primary"></i> Quản lý Hồ sơ Bệnh án
        </h3>
        <a href="{{ route('medical_records.create') }}" class="btn btn-success shadow-sm fw-bold">
            <i class="fas fa-plus me-1"></i> Thêm hồ sơ mới
        </a>
    </div>

    {{-- Alert Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Thẻ Lọc & Tìm kiếm --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <form id="record-filters" method="GET" action="{{ route('medical_records.index') }}">
                <div class="row g-2 align-items-center">
                    {{-- Ô Tìm kiếm --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                placeholder="Tìm theo tên bệnh nhân hoặc tiêu đề..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    {{-- Các Dropdown Lọc --}}
                    <div class="col-md-7 col-lg-8">
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            @if(isset($departments) && count($departments) > 0)
                            <select name="department" class="form-select w-auto">
                                <option value="" selected>-- Khoa --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @endif

                            <select name="status" class="form-select w-auto">
                                <option value="">-- Trạng thái --</option>
                                <option value="chờ_khám" {{ request('status') == 'chờ_khám' ? 'selected' : '' }}>Chờ khám</option>
                                <option value="đang_khám" {{ request('status') == 'đang_khám' ? 'selected' : '' }}>Đang khám</option>
                                <option value="đã_khám" {{ request('status') == 'đã_khám' ? 'selected' : '' }}>Hoàn thành</option>
                            </select>

                            <select name="sort_date" class="form-select w-auto">
                                <option value="desc" {{ request('sort_date', 'desc') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_date') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            
                            @if(request()->hasAny(['search', 'department', 'status', 'sort_date']))
                            <a href="{{ route('medical_records.index') }}" class="btn btn-outline-danger w-auto" title="Xóa bộ lọc">
                                <i class="fas fa-filter-circle-xmark"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng Hồ sơ Bệnh án --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-striped">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="py-3 ps-3">ID</th>
                            <th class="py-3">Bệnh nhân</th>
                            <th class="py-3">Tiêu đề / Lý do</th>
                            <th class="py-3">Ngày khám</th>
                            <th class="py-3">Bác sĩ</th>
                            <th class="py-3">Khoa</th>
                            <th class="py-3">Chỉ số sinh tồn</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3 text-center" style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicalRecords as $record)
                        <tr id="record-row-{{ $record->id }}">
                            <td class="ps-3 fw-bold text-muted">#{{ $record->id }}</td>
                            
                            <td>
                                <div class="fw-bold text-primary">{{ $record->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $record->user->phone ?? '' }}</small>
                            </td>
                            
                            <td title="{{ $record->title }}">
                                {{ Str::limit($record->title, 30) }}
                            </td>
                            
                            <td>
                                {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                            </td>
                            
                            <td>{{ $record->doctor->name ?? '-' }}</td>
                            
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $record->department->name ?? 'Chưa phân khoa' }}
                                </span>
                            </td>
                            
                            {{-- CỘT CHỈ SỐ SINH TỒN (AN TOÀN) --}}
                            <td>
                                @php
                                    $vitals = json_decode($record->vital_signs, true);
                                @endphp

                                @if(is_array($vitals) && count($vitals) > 0)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-info dropdown-toggle py-0" type="button" data-bs-toggle="dropdown">
                                            Xem
                                        </button>
                                        <ul class="dropdown-menu p-2 shadow border-0" style="min-width: 200px;">
                                            @foreach($vitals as $key => $val)
                                                @if(!is_array($val))
                                                    <li class="d-flex justify-content-between border-bottom py-1">
                                                        <strong class="me-2 text-capitalize">{{ str_replace('_', ' ', $key) }}:</strong> 
                                                        <span>{{ $val }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            
                            {{-- TRẠNG THÁI --}}
                            <td class="text-center">
                                @php
                                    $statusConfig = [
                                        'chờ_khám' => ['class' => 'bg-secondary', 'label' => 'Chờ khám'],
                                        'đang_khám' => ['class' => 'bg-primary', 'label' => 'Đang khám'], // Màu xanh dương nổi bật
                                        'đã_khám' => ['class' => 'bg-success', 'label' => 'Hoàn thành'],
                                        'hủy' => ['class' => 'bg-danger', 'label' => 'Hủy'],
                                    ];
                                    $currentStatus = $statusConfig[$record->status] ?? ['class' => 'bg-secondary', 'label' => $record->status];
                                @endphp
                                <span class="badge {{ $currentStatus['class'] }} rounded-pill px-3 py-2">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>
                            
                            {{-- HÀNH ĐỘNG --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Nút chính: Thay đổi theo trạng thái --}}
                                    @if($record->status == 'đang_khám')
                                        <a href="{{ route('medical_records.show', $record->id) }}" class="btn btn-sm btn-primary fw-bold" title="Tiếp tục khám">
                                            <i class="fas fa-stethoscope"></i> Tiếp tục
                                        </a>
                                    @elseif($record->status == 'chờ_khám')
                                        <a href="{{ route('medical_records.show', $record->id) }}" class="btn btn-sm btn-outline-primary" title="Bắt đầu">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('medical_records.show', $record->id) }}" class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    
                                    {{-- Nút Sửa/Xóa (Chỉ hiện khi chưa hoàn thành) --}}
                                    @if($record->status != 'đã_khám' && $record->status != 'hủy')
                                        <a href="{{ route('medical_records.edit', $record->id) }}" class="btn btn-sm btn-warning" title="Sửa thông tin">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-record-btn" 
                                                data-id="{{ $record->id }}" 
                                                data-name="{{ $record->title }}"
                                                title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif

                                    {{-- Nếu đã hoàn thành -> Hiện nút xem hóa đơn nhanh (Optional) --}}
                                    @if($record->status == 'đã_khám')
                                        @php $invoice = \App\Models\Invoice::where('medical_record_id', $record->id)->first(); @endphp
                                        @if($invoice)
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-success" title="Xem hóa đơn">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>

                                <form id="delete-form-{{ $record->id }}" action="{{ route('medical_records.destroy', $record->id) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0 fs-5">Chưa có hồ sơ bệnh án nào.</p>
                                <small>Hãy thêm hồ sơ mới để bắt đầu quản lý.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        @if($medicalRecords->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị <strong>{{ $medicalRecords->firstItem() }}</strong> - <strong>{{ $medicalRecords->lastItem() }}</strong> 
                    trong tổng số <strong>{{ $medicalRecords->total() }}</strong> hồ sơ.
                </div>
                <div>
                    {{ $medicalRecords->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL XÓA (Giữ nguyên) --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="text-danger mb-3"><i class="fas fa-trash-alt fa-3x"></i></div>
                <p class="mb-1">Bạn có chắc chắn muốn xóa hồ sơ này?</p>
                <h5 class="fw-bold text-dark" id="record-name-to-delete"></h5>
                <p class="text-muted small mt-2">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="confirmDeleteButton">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Tự động submit bộ lọc
        const filterForm = document.getElementById('record-filters');
        if(filterForm) {
            filterForm.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => filterForm.submit());
            });
        }

        // 2. Logic Modal Xóa
        const modalElement = document.getElementById('deleteConfirmationModal');
        if(modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            const confirmBtn = document.getElementById('confirmDeleteButton');
            let recordId = null;

            document.querySelectorAll('.delete-record-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    recordId = this.dataset.id;
                    document.getElementById('record-name-to-delete').textContent = this.dataset.name;
                    modal.show();
                });
            });

            confirmBtn.addEventListener('click', function() {
                if (!recordId) return;
                const form = document.getElementById(`delete-form-${recordId}`);
                form.submit();
            });
        }
    });
</script>
@endpush

@endsection