@extends('admin.master') {{-- Kế thừa layout của Admin --}}

@section('title', 'Quản lý Đơn nghỉ phép')

@section('body')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý Đơn nghỉ phép</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Đơn nghỉ phép</li>
    </ol>

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách bác sĩ xin nghỉ
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Bác sĩ</th>
                        <th>Thời gian nghỉ</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú Admin</th>
                        <th style="width: 150px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $leave->user->name ?? 'N/A' }}</td>
                        <td>
                            <div>Từ: {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</div>
                            <div>Đến: {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</div>
                        </td>
                        <td>{{ $leave->reason }}</td>
                        <td>
                            @if($leave->status == 'pending')
                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @elseif($leave->status == 'approved')
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-danger">Từ chối</span>
                            @endif
                        </td>
                       {{-- Trong vòng lặp foreach --}}
                        <td>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $leave->admin_note }}</span>
                                
                                {{-- Nút mở Modal sửa ghi chú --}}
                                <button type="button" class="btn btn-link text-decoration-none p-0 ms-2" 
                                        onclick="openNoteModal({{ $leave->id }}, '{{ $leave->admin_note }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            @if($leave->status == 'pending')
                                {{-- Nút DUYỆT --}}
                                <form action="{{ route('leaves.update', $leave->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success btn-sm" 
                                            onclick="return confirm('Duyệt đơn này sẽ HỦY các lịch hẹn trùng ngày và gửi mail cho bệnh nhân. Bạn chắc chắn chứ?')">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                </form>

                                {{-- Nút TỪ CHỐI (Dùng JS để nhập lý do) --}}
                                <button type="button" class="btn btn-danger btn-sm" onclick="rejectLeave({{ $leave->id }})">
                                    <i class="fas fa-times"></i> Từ chối
                                </button>

                                {{-- Form ẩn để xử lý Từ chối --}}
                                <form id="reject-form-{{ $leave->id }}" action="{{ route('leaves.update', $leave->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <input type="hidden" name="admin_note" id="admin-note-{{ $leave->id }}">
                                </form>
                            @else
                                {{-- Nút Xóa (Chỉ hiện khi đã xử lý xong) --}}
                                <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Xóa đơn này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Chưa có đơn xin nghỉ nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- Phân trang --}}
            <div class="d-flex justify-content-end">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
{{-- MODAL CẬP NHẬT GHI CHÚ --}}
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật Ghi chú Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Form cập nhật ghi chú --}}
            <form id="noteForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_admin_note" class="form-label">Nội dung ghi chú:</label>
                        <textarea class="form-control" name="admin_note" id="modal_admin_note" rows="4" placeholder="Nhập ghi chú vào đây..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu ghi chú</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ --}}
<script>
    // 1. Hàm mở Modal Ghi chú
    function openNoteModal(id, currentNote) {
        // Gán action cho form (URL update)
        // Lưu ý: route 'leaves.update' cần ID, ta thay placeholder '0' bằng ID thật
        let url = "{{ route('leaves.update', '0') }}";
        url = url.replace('/0', '/' + id); // Thay số 0 cuối đường dẫn bằng ID

        document.getElementById('noteForm').action = url;

        // Điền nội dung cũ vào textarea
        document.getElementById('modal_admin_note').value = currentNote;

        // Hiển thị Modal bằng Bootstrap 5
        var myModal = new bootstrap.Modal(document.getElementById('noteModal'));
        myModal.show();
    }

    // 2. Hàm từ chối (Code cũ của bạn giữ nguyên hoặc sửa lại chút cho đẹp)
    function rejectLeave(id) {
        let note = prompt("Vui lòng nhập lý do từ chối:");
        if (note !== null && note.trim() !== "") {
            document.getElementById('admin-note-' + id).value = note;
            document.getElementById('reject-form-' + id).submit();
        }
    }


</script>
@endsection