@extends('admin.master')

@section('body')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Chi tiết bác sĩ</h3>
            <a href="{{ route('doctorsite.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Ảnh bác sĩ -->
                <div class="col-md-4 text-center mb-3">
                    <img src="{{ $doctor->image ? asset('storage/'.$doctor->image) : asset('assets/img/default-doctor.png') }}" 
                         class="img-fluid rounded-circle shadow" 
                         alt="doctor">
                </div>

                <!-- Thông tin chi tiết -->
                <div class="col-md-8">
                    <h4 class="mb-2">{{ $doctor->user->name ?? 'Không rõ' }}</h4>
                    <p class="text-primary mb-2"><strong>Chuyên khoa chính:</strong> {{ $doctor->specialty ?? '-' }}</p>
                    <p class="mb-2"><strong>Khoa:</strong> {{ $doctor->department->name ?? '-' }}</p>
                    <p class="mb-2"><strong>Giới thiệu:</strong> {{ $doctor->bio ?? '-' }}</p>
                    <p class="mb-2"><strong>Điểm đánh giá:</strong> ⭐ {{ $doctor->rating }}/5</p>
                    <p class="mb-2"><strong>Số lượt đánh giá:</strong> {{ $doctor->reviews_count }}</p>
                    <p class="mb-2"><strong>Trạng thái:</strong>
                        @if($doctor->status)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </p>
                    <p class="mb-2"><strong>Ngày tạo:</strong> {{ $doctor->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-2"><strong>Cập nhật lần cuối:</strong> {{ $doctor->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
