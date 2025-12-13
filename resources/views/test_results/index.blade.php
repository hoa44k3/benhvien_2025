@extends('admin.master')

@section('title', 'Danh sách kết quả xét nghiệm')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-microscope me-2 text-primary"></i> Quản lý Xét nghiệm
        </h3>
        {{-- <a href="{{ route('test_results.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="fas fa-plus me-1"></i> Thêm xét nghiệm mới
        </a> --}}
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="py-3 ps-3">ID</th>
                            <th class="py-3">Bệnh nhân</th>
                            <th class="py-3">Tên xét nghiệm</th>
                            <th class="py-3">Phòng Lab</th>
                            <th class="py-3">Ngày làm</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3 text-center">Kết quả / File</th>
                            <th class="py-3 text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $item)
                        <tr>
                            <td class="ps-3 fw-bold text-muted">#{{ $item->id }}</td>
                            
                            {{-- Tên bệnh nhân (Xử lý nếu user bị null) --}}
                            <td>
                                <div class="fw-bold text-primary">{{ $item->user->name ?? $item->patient->name ?? 'N/A' }}</div>
                                <small class="text-muted">Mã HS: {{ $item->medical_record_id ?? '---' }}</small>
                            </td>

                            <td class="fw-bold">{{ $item->test_name }}</td>

                            <td>{{ $item->lab_name ?? '---' }}</td>

                            <td>
                                {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d/m/Y') : '' }}
                            </td>

                            <td class="text-center">
                                @php
                                    $statusClass = match($item->status) {
                                        'completed' => 'bg-success', // Đã có KQ
                                        'pending' => 'bg-warning text-dark', // Chờ KQ
                                        'reviewed' => 'bg-primary', // Đã duyệt
                                        'archived' => 'bg-secondary', // Lưu trữ
                                        default => 'bg-light text-dark'
                                    };
                                    $statusLabel = match($item->status) {
                                        'completed' => 'Đã có KQ',
                                        'pending' => 'Chờ KQ',
                                        'reviewed' => 'Đã duyệt',
                                        'archived' => 'Lưu trữ',
                                        default => $item->status
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="text-center">
                                {{-- Kiểm tra file_main (Theo đúng migration bạn gửi) --}}
                                @if($item->file_main)
                                    <a href="{{ asset('storage/'.$item->file_main) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Xem file gốc">
                                        <i class="fas fa-file-download"></i> File
                                    </a>
                                @elseif($item->result)
                                    <span class="text-success fw-bold" title="{{ $item->result }}">Đã nhập KQ</span>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('test_results.show', $item->id) }}" class="btn btn-info text-white" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('test_results.edit', $item->id) }}" class="btn btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="deleteItem({{ $item->id }})" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-microscope fa-3x mb-3 opacity-50"></i>
                                <p>Chưa có dữ liệu xét nghiệm nào.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang (Chỉ hiện nếu Controller dùng paginate) --}}
        @if(method_exists($results, 'links'))
        <div class="mt-3">
            {{ $results->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Script Xóa AJAX --}}
<script>
function deleteItem(id) {
    if (!confirm("Bạn có chắc chắn muốn xóa kết quả xét nghiệm này không?")) return;

    fetch('/test-results/' + id, { // Lưu ý: Route prefix trong web.php là 'test-results' hay 'test_results'? Kiểm tra lại route:list
        method: 'DELETE',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => {
        if (res.ok) {
            alert('Xóa thành công!');
            location.reload();
        } else {
            alert('Có lỗi xảy ra khi xóa.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi kết nối server.');
    });
}
</script>
@endsection