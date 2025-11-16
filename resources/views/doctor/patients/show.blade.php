@extends('doctor.master')
@section('title', 'Chi tiết bệnh nhân')

@section('body')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-user me-2"></i> Hồ sơ bệnh nhân</h4>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4 text-center">
                    <i class="fas fa-user-circle fa-6x text-muted mb-3"></i>
                    <h5 class="text-primary">{{ $appointment->user->name ?? 'Không rõ' }}</h5>
                    <p class="text-muted mb-0">
                        Mã BN: <strong>{{ $appointment->user->patient_code ?? 'PTxxx' }}</strong>
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-phone me-2"></i>{{ $appointment->user->phone ?? 'Chưa có số điện thoại' }}
                    </p>
                </div>

                <div class="col-md-8">
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary">Thông tin cá nhân</h5>
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Giới tính:</strong> {{ $appointment->user->gender ?? '---' }}</div>
                        <div class="col-md-6"><strong>Ngày sinh:</strong> {{ $appointment->user->birthdate ?? '---' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Email:</strong> {{ $appointment->user->email ?? '---' }}</div>
                        <div class="col-md-6"><strong>Địa chỉ:</strong> {{ $appointment->user->address ?? '---' }}</div>
                    </div>
                </div>
            </div>

            <hr>

            <h5 class="fw-bold border-bottom pb-2 mb-3 text-secondary">Thông tin khám gần nhất</h5>
            <div class="mb-3">
                <strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') ?? '---' }}
            </div>
            <div class="mb-3">
                <strong>Chẩn đoán:</strong><br>
                {{ $appointment->diagnosis ?? 'Chưa có chẩn đoán' }}
            </div>
            <div class="mb-3">
                <strong>Trạng thái:</strong>
                <span class="badge bg-info">{{ $appointment->status ?? 'Đang theo dõi' }}</span>
            </div>
        </div>

        <div class="card-footer bg-light text-end">
            <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            <a href="{{ route('doctor.patients.edit', $appointment->user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa hồ sơ
            </a>
        </div>
    </div>
</div>
@endsection
