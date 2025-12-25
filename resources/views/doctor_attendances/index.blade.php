@extends('admin.master')

@section('body')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary fw-bold"><i class="fas fa-headset me-2"></i> Quản lý Ca trực Bác sĩ</h4>
        
        {{-- Form lọc tháng (cho Admin) --}}
        <form action="{{ route('doctor_attendances.index') }}" method="GET" class="d-flex gap-2">
            @if(auth()->user()->role == 'admin' || auth()->user()->type == 'admin')
            <select name="doctor_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Tất cả Bác sĩ --</option>
                @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>
                        BS. {{ $doc->name }}
                    </option>
                @endforeach
            </select>
            @endif
            <input type="month" name="month" class="form-control form-select-sm" value="{{ $month }}" onchange="this.form.submit()">
        </form>
    </div>

    {{-- PHẦN ĐIỀU KHIỂN CA TRỰC (Chỉ hiển thị cho Bác sĩ) --}}
    @if(auth()->user()->role == 'doctor' || auth()->user()->type == 'doctor') 
        @php
            $currentShift = \App\Models\DoctorAttendance::where('doctor_id', auth()->id())
                ->whereNull('check_out')
                ->latest('created_at')
                ->first();
        @endphp

        <div class="card mb-5 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h5 class="fw-bold text-dark">Trạng thái hoạt động</h5>
                        @if($currentShift)
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-success px-3 py-2 rounded-pill animate-pulse">
                                    <i class="fas fa-wifi me-1"></i> Đang Online
                                </span>
                                <span class="text-muted small">
                                    Bắt đầu lúc: <strong>{{ \Carbon\Carbon::parse($currentShift->check_in)->format('H:i d/m/Y') }}</strong>
                                </span>
                                <span class="text-muted small">
                                    Ca: <strong>{{ ucfirst($currentShift->shift) }}</strong>
                                </span>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                    <i class="fas fa-power-off me-1"></i> Offline
                                </span>
                                <span class="text-muted small">Bạn chưa bắt đầu ca trực nào.</span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-5 text-end">
                        @if(!$currentShift)
                            {{-- FORM BẮT ĐẦU CA --}}
                            <form method="POST" action="{{ route('doctor_attendances.checkin') }}" class="d-flex gap-2 justify-content-end">
                                @csrf
                                <select name="shift" class="form-select w-auto" required>
                                    <option value="Sáng">Ca Sáng (7h-11h)</option>
                                    <option value="Chiều">Ca Chiều (13h-17h)</option>
                                    <option value="Tối">Ca Tối (18h-22h)</option>
                                    <option value="Tăng cường">Ca Tăng cường</option>
                                </select>
                                <button class="btn btn-primary fw-bold px-4">
                                    <i class="fas fa-play me-1"></i> Bắt đầu Ca
                                </button>
                            </form>
                        @else
                            {{-- FORM KẾT THÚC CA --}}
                            <form method="POST" action="{{ route('doctor_attendances.checkout') }}" class="d-flex gap-2 justify-content-end">
                                @csrf
                                <input type="text" name="note" class="form-control w-auto" placeholder="Ghi chú công việc..." size="30">
                                <button class="btn btn-danger fw-bold px-4">
                                    <i class="fas fa-stop me-1"></i> Kết thúc Ca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- DANH SÁCH LỊCH SỬ HOẠT ĐỘNG --}}
    @php
        $groupedAttendances = $attendances->groupBy('doctor_id');
    @endphp

    @forelse($groupedAttendances as $docId => $records)
        @php
            $docInfo = $records->first()->user;
            $totalHours = $records->sum('total_hours');
            $totalShifts = $records->count();
        @endphp

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $docInfo->image ? asset('storage/'.$docInfo->image) : 'https://ui-avatars.com/api/?name='.urlencode($docInfo->name) }}" 
                         class="rounded-circle" width="40" height="40" alt="Avatar">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">BS. {{ $docInfo->name }}</h6>
                        <small class="text-muted">{{ $docInfo->email }}</small>
                    </div>
                </div>
                <div>
                    <span class="badge bg-info text-dark me-2">Tổng: {{ number_format($totalHours, 1) }} giờ online</span>
                    <span class="badge bg-light text-dark border">{{ $totalShifts }} ca trực</span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Ngày</th>
                            <th>Ca trực</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Thời lượng</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $att)
                        <tr>
                            <td class="ps-4 fw-bold">
                                {{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}
                                <small class="text-muted fw-normal d-block">
                                    {{ \Carbon\Carbon::parse($att->date)->locale('vi')->dayName }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    {{ $att->shift ?? 'Tự do' }}
                                </span>
                            </td>
                            <td class="text-success fw-bold">{{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }}</td>
                            <td>
                                @if($att->check_out)
                                    <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($att->check_out)->format('H:i') }}</span>
                                @else
                                    <span class="badge bg-success animate-pulse">Đang online</span>
                                @endif
                            </td>
                            <td>
                                @if($att->total_hours > 0)
                                    <strong>{{ number_format($att->total_hours, 1) }}</strong> giờ
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-muted small fst-italic">{{ $att->note }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
            <p>Không có dữ liệu ca trực nào trong tháng này.</p>
        </div>
    @endforelse

</div>
@endsection