@extends('admin.master')

@section('title', 'Quản lý Hồ sơ Bệnh án')
@section('body')
<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold text-dark">
            <i class="fas fa-file-medical me-2 text-primary"></i> Quản lý Hồ sơ Bệnh án
        </h3>
        <a href="{{ route('medical_records.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm hồ sơ mới
        </a>
    </div>

    {{-- Alert Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Thẻ Lọc & Tìm kiếm --}}
    <div class="card shadow-sm mb-3">
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
                            {{-- Lọc theo Khoa (Department) - Nếu có danh sách khoa --}}
                            @if(isset($departments))
                            <select name="department" class="form-select w-auto">
                                <option value="" selected>Lọc theo Khoa</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                            @endif

                            {{-- Sắp xếp theo ngày --}}
                            <select name="sort_date" class="form-select w-auto">
                                <option value="desc" {{ request('sort_date', 'desc') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_date') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            
                            {{-- Nút Reset Lọc --}}
                            @if(request()->hasAny(['search', 'department', 'sort_date']))
                            <a href="{{ route('medical_records.index') }}" class="btn btn-outline-danger w-auto">
                                <i class="fas fa-times me-1"></i> Xóa lọc
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng Hồ sơ Bệnh án --}}
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
    <tr>
        <th>ID</th>
        <th>Bệnh nhân</th>
        <th>Tiêu đề</th>
        <th>Ngày khám</th>
        <th>Bác sĩ</th>
        <th>Khoa</th>
        <th>Chẩn đoán chính</th>
        <th>Chẩn đoán phụ</th>
        <th>Triệu chứng</th>
        <th>Chỉ số sinh tồn</th>
        <th>Điều trị</th>
        <th>Tái khám</th>
        <th>Lịch hẹn</th>
        <th>Trạng thái</th>
        <th class="text-center">Hành động</th>
    </tr>
</thead>
<tbody>
    @forelse($medicalRecords as $record)
    <tr id="record-row-{{ $record->id }}">
        <td>{{ $record->id }}</td>
        <td>{{ $record->user->name ?? 'Không rõ' }}</td>
        <td>{{ $record->title }}</td>
        <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
        <td>{{ $record->doctor->name ?? 'Chưa chọn bác sĩ' }}</td>
        <td>{{ $record->department->name ?? 'Không có khoa' }}</td>
        <td>{{ $record->diagnosis_primary ?? '-' }}</td>
        <td>{{ $record->diagnosis_secondary ?? '-' }}</td>
        <td>{{ Str::limit($record->symptoms, 50) }}</td>
        <td>
            @if($record->vital_signs)
                @php $vitals = json_decode($record->vital_signs, true); @endphp
                <ul class="mb-0 ps-3">
                    @foreach($vitals as $key => $val)
                        <li><strong>{{ ucfirst($key) }}:</strong> {{ $val }}</li>
                    @endforeach
                </ul>
            @else
                -
            @endif
        </td>
        <td>{{ Str::limit($record->treatment, 50) }}</td>
        <td>{{ $record->next_checkup ? \Carbon\Carbon::parse($record->next_checkup)->format('d/m/Y') : '-' }}</td>
        <td>{{ $record->appointment_id ?? '-' }}</td>
        <td>
            @php
                $status = $record->status ?? 'Mới';
                $badgeClass = match($status) {
                    'chờ_khám' => 'bg-secondary',
                    'đang_khám' => 'bg-primary',
                    'đã_khám' => 'bg-success',
                    'hủy' => 'bg-danger',
                    default => 'bg-secondary',
                };
            @endphp
            <span class="badge {{ $badgeClass }} px-2 py-1">{{ $status }}</span>
        </td>
        <td class="text-center">
            <a href="{{ route('medical_records.show', $record) }}" class="btn btn-sm btn-info me-1 text-white" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('medical_records.edit', $record) }}" class="btn btn-sm btn-warning me-1" title="Sửa">
                <i class="fas fa-edit"></i>
            </a>
            <button type="button" class="btn btn-sm btn-danger delete-record-btn" 
                    data-id="{{ $record->id }}" 
                    data-name="{{ $record->title }} ({{ $record->user->name ?? 'Người dùng không rõ' }})"
                    title="Xóa">
                <i class="fas fa-trash-alt"></i>
            </button>
            <form id="delete-form-{{ $record->id }}" action="{{ route('medical_records.destroy', $record->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="15" class="text-center text-muted py-5">
            <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
            <p class="mb-0">Chưa có hồ sơ bệnh án nào.</p>
        </td>
    </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        @if(method_exists($medicalRecords, 'links') && $medicalRecords->lastPage() > 1)
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị {{ $medicalRecords->firstItem() }} đến {{ $medicalRecords->lastItem() }} trong tổng số {{ $medicalRecords->total() }} hồ sơ.
                </div>
                <div>
                    {{ $medicalRecords->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal xác nhận xóa và Script đi kèm --}}
{{-- (Giữ nguyên phần Modal và Script bạn cung cấp vì chúng đã hoàn thiện) --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Xác nhận xóa hồ sơ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa hồ sơ: <br> 
                <strong><span id="record-name-to-delete" class="text-danger"></span></strong>?<br>
                <span class="text-muted small">Hành động này không thể hoàn tác.</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // 1. Tự động submit form lọc
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('record-filters');
        
        document.querySelectorAll('#record-filters select').forEach(select => {
            select.addEventListener('change', () => filterForm.submit());
        });

        const searchInput = document.querySelector('#record-filters input[name="search"]');
        if(searchInput){
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterForm.submit();
                }
            });
        }
    });

    // 2. Xử lý Modal Xóa & AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('deleteConfirmationModal');
        const modal = new bootstrap.Modal(modalElement);
        const confirmBtn = document.getElementById('confirmDeleteButton');
        let recordId = null;

        // Bắt sự kiện click nút xóa trên bảng
        document.querySelectorAll('.delete-record-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                recordId = this.dataset.id;
                const recordName = this.dataset.name;
                document.getElementById('record-name-to-delete').textContent = recordName;
                modal.show();
            });
        });

        // Bắt sự kiện click nút xác nhận trong modal
        confirmBtn.addEventListener('click', function() {
            if (!recordId) return;

            const form = document.getElementById(`delete-form-${recordId}`);
            
            // Xử lý AJAX để xóa không cần reload trang
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                // Giả định thành công (res.ok) hoặc server redirect sau xóa
                if(res.ok || res.redirected) { 
                    modal.hide();
                    const row = document.getElementById(`record-row-${recordId}`);
                    if(row) row.remove();
                    
                    // Hiển thị thông báo thành công (có thể thay bằng Toast của Bootstrap)
                    alert('Đã xóa hồ sơ thành công!');
                } else {
                    // Nếu cần xử lý lỗi JSON từ backend
                    // return res.json(); 
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối.');
            });
        });
    });
</script>
@endpush
@endsection