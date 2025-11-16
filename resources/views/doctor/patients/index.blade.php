@extends('doctor.master')

@section('title', 'Danh sách lịch khám')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .table-appointments thead th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #666;
    }
    .table-appointments tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge-status {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
    }
</style>
@endsection

@php
    function getStatusStyle($status) {
        return match($status) {
            'Đã xác nhận'   => ['color' => 'info', 'icon' => 'bi-check2-circle'],
            'Đang chờ khám' => ['color' => 'warning', 'icon' => 'bi-hourglass-split'],
            'Đang khám'     => ['color' => 'primary', 'icon' => 'bi-person-lines-fill'],
            'Hoàn thành'    => ['color' => 'success', 'icon' => 'bi-check-circle-fill'],
            'Đã hủy'        => ['color' => 'danger', 'icon' => 'bi-x-octagon-fill'],
            default         => ['color' => 'secondary', 'icon' => 'bi-question-circle']
        };
    }
@endphp

@section('body')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-clipboard2-pulse text-primary me-2"></i>
            Danh sách lịch khám
        </h4>
        <form action="" method="GET" class="d-flex" style="max-width: 300px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm rounded-start-pill" placeholder="Tìm bệnh nhân...">
            <button class="btn btn-primary btn-sm rounded-end-pill"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-striped table-appointments">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">BỆNH NHÂN</th>
                            <th>MÃ / SĐT</th>
                            <th>NGÀY KHÁM</th>
                            <th>CHẨN ĐOÁN</th>
                            <th class="text-center">TRẠNG THÁI</th>
                            <th class="text-center">HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $item)
                            @php
                                $status = getStatusStyle($item->status);
                            @endphp
                            <tr>
                                {{-- Bệnh nhân --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle text-muted fs-3 me-3"></i>
                                        <div>
                                            <div class="fw-bold">{{ $item->patient_name ?? 'Chưa có tên' }}</div>

                                            <small class="text-muted">{{ $item->reason ?? 'Không có lý do' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Mã / SĐT --}}
                                <td>
                                    <div>Mã: <span class="fw-semibold text-primary">{{ $item->user->patient_code ?? '---' }}</span></div>
                                   <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $item->patient_phone ?? '---' }}</small>

                                </td>

                                {{-- Ngày khám --}}
                                <td class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                </td>

                                {{-- Chẩn đoán --}}
                                <td class="text-secondary small">
                                    {{ $item->diagnosis ?? 'Chưa cập nhật' }}
                                </td>

                                {{-- Trạng thái --}}
                                <td class="text-center">
                                    <span class="badge bg-{{ $status['color'] }} badge-status">
                                        <i class="{{ $status['icon'] }} me-1"></i>
                                        {{ $item->status }}
                                    </span>
                                </td>

                                {{-- Hành động --}}
                               <td class="text-center">
    <div class="d-flex justify-content-center gap-2">
        @switch($item->status)
            @case('Đang chờ')
                <a href="{{ route('doctor.startExam', $item->id) }}" 
                   class="btn btn-sm btn-outline-success" title="Bắt đầu khám">
                    <i class="bi bi-camera-video-fill"></i>
                </a>
                @break

            @case('Đang khám')
                <a href="{{ route('doctor.finishExam', $item->id) }}" 
                   class="btn btn-sm btn-outline-primary" title="Hoàn tất khám">
                    <i class="bi bi-prescription2"></i>
                </a>
                @break

            @case('Hoàn thành')
                <a href="{{ route('doctor.viewRecord', $item->id) }}" 
                   class="btn btn-sm btn-outline-secondary" title="Xem hồ sơ">
                    <i class="bi bi-file-earmark-medical-fill"></i>
                </a>
                @break
        @endswitch

        {{-- Chi tiết --}}
        <a href="{{ route('doctor.patients.show', $item->id) }}" 
           class="btn btn-sm btn-outline-dark" title="Chi tiết lịch hẹn">
            <i class="bi bi-eye"></i>
        </a>
    </div>
</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted bg-light">
                                    <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
                                    Chưa có lịch khám nào trong danh sách.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
