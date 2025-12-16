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
                        <td>{{ $leave->admin_note }}</td>
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

{{-- Script xử lý nhập lý do từ chối --}}
<script>
    function rejectLeave(id) {
        let note = prompt("Vui lòng nhập lý do từ chối:");
        if (note !== null && note.trim() !== "") {
            document.getElementById('admin-note-' + id).value = note;
            document.getElementById('reject-form-' + id).submit();
        }
    }
</script>
@endsection