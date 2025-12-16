@extends('admin.master')

@section('body')
<div class="container">

    <h4 class="mb-3">Quản lý Chấm công</h4>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- PHẦN CHẤM CÔNG CÁ NHÂN (Cho Bác sĩ) --}}
    @php
        // Lấy giờ hiện tại theo múi giờ Việt Nam để so sánh
        $todayDate = \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        
        $todayAttendance = \App\Models\DoctorAttendance::where('doctor_id', auth()->id())
            ->where('date', $todayDate)
            ->first();
    @endphp

    {{-- Nếu user là doctor hoặc có quyền chấm công --}}
    @if(auth()->user()->role == 'doctor' || auth()->user()->type == 'doctor') 
    <div class="card mb-4 shadow-sm border-primary">
        <div class="card-header bg-primary text-white font-weight-bold">
            <i class="fas fa-clock"></i> Chấm công ngày {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
        </div>
        <div class="card-body text-center">
            @if(!$todayAttendance)
                <div class="mb-3 text-muted">Bạn chưa bắt đầu ca làm việc hôm nay.</div>
                <form method="POST" action="{{ route('doctor_attendances.checkin') }}">
                    @csrf
                    <div class="mb-3" style="max-width: 400px; margin: 0 auto;">
                        <input type="text" name="note" class="form-control" placeholder="Ghi chú (nếu có)...">
                    </div>
                    <button class="btn btn-lg btn-success px-5">
                        <i class="fas fa-sign-in-alt"></i> CHECK IN (ĐẾN)
                    </button>
                </form>

            @elseif(!$todayAttendance->check_out)
                <div class="alert alert-info d-inline-block">
                    Đã Check-in lúc: <strong>{{ $todayAttendance->check_in }}</strong>
                </div>
                <div class="mt-3">
                    <form method="POST" action="{{ route('doctor_attendances.checkout') }}">
                        @csrf
                        <div class="mb-3" style="max-width: 400px; margin: 0 auto;">
                            <input type="text" name="note" class="form-control" placeholder="Cập nhật ghi chú trước khi về...">
                        </div>
                        <button class="btn btn-lg btn-warning px-5">
                            <i class="fas fa-sign-out-alt"></i> CHECK OUT (VỀ)
                        </button>
                    </form>
                </div>

            @else
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Hôm nay bạn đã hoàn thành công việc.<br>
                    <strong>Vào: {{ $todayAttendance->check_in }} - Ra: {{ $todayAttendance->check_out }}</strong>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- DANH SÁCH LỊCH SỬ CHẤM CÔNG --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lịch sử chấm công tháng {{ $month }}</span>
            <span class="badge bg-info text-dark">Số ngày công: {{ $workingDays }}</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Ngày</th>
                        <th>Bác sĩ</th> {{-- Cột mới thêm --}}
                        <th>Giờ đến</th>
                        <th>Giờ về</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                            <td class="font-weight-bold text-primary">
                                {{-- Hiển thị tên bác sĩ từ quan hệ user --}}
                                {{ $att->user->name ?? 'Không rõ ID: '.$att->doctor_id }}
                            </td>
                            <td>{{ $att->check_in ?? '--:--' }}</td>
                            <td>{{ $att->check_out ?? '--:--' }}</td>
                            <td>
                                @if($att->status === 'present')
                                    <span class="badge bg-success">Đúng giờ</span>
                                @elseif($att->status === 'late')
                                    <span class="badge bg-danger">Đi muộn</span>
                                @elseif($att->status === 'absent')
                                    <span class="badge bg-secondary">Vắng</span>
                                @else
                                    <span class="badge bg-dark">{{ $att->status }}</span>
                                @endif
                            </td>
                            <td>{{ $att->note }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Chưa có dữ liệu chấm công trong tháng này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection