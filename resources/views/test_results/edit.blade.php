@extends('admin.master')

@section('title', 'Cập nhật kết quả xét nghiệm')

@section('body')
<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-edit me-2 text-warning"></i> Cập nhật Xét nghiệm #{{ $testResult->id }}
        </h3>
        <a href="{{ route('test_results.index') }}" class="btn btn-secondary shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            
            <form action="{{ route('test_results.update', $testResult->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nếu đang sửa từ trang chi tiết hồ sơ, cần giữ ID hồ sơ để redirect về đúng chỗ --}}
                <input type="hidden" name="medical_record_id" value="{{ $testResult->medical_record_id }}">

                <div class="row g-3">
                    
                    {{-- Cột Trái: Thông tin hành chính --}}
                    <div class="col-md-4 border-end">
                        <h5 class="fw-bold text-primary mb-3">Thông tin chung</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bệnh nhân <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select select2">
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ $testResult->user_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} - {{ $p->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bác sĩ chỉ định</label>
                            <select name="doctor_id" class="form-select">
                                <option value="">-- Chọn bác sĩ --</option>
                                @foreach($doctors as $d)
                                    <option value="{{ $d->id }}" {{ $testResult->doctor_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Khoa / Phòng ban</label>
                            <select name="department_id" class="form-select">
                                <option value="">-- Chọn khoa --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ $testResult->department_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ngày xét nghiệm</label>
                            <input type="date" name="date" class="form-control" 
                                   value="{{ \Carbon\Carbon::parse($testResult->date)->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Cột Phải: Kết quả chi tiết --}}
                    <div class="col-md-8 ps-md-4">
                        <h5 class="fw-bold text-success mb-3">Kết quả & Chẩn đoán</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên xét nghiệm <span class="text-danger">*</span></label>
                                <input type="text" name="test_name" class="form-control" value="{{ $testResult->test_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phòng thực hiện</label>
                                <input type="text" name="lab_name" class="form-control" value="{{ $testResult->lab_name }}" placeholder="VD: Phòng Lab 1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">KẾT QUẢ XÉT NGHIỆM</label>
                            <textarea name="result" class="form-control border-primary" rows="2" placeholder="Nhập chỉ số hoặc kết quả...">{{ $testResult->result }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá / Kết luận của Bác sĩ</label>
                            <textarea name="diagnosis" class="form-control" rows="2">{{ $testResult->diagnosis ?? $testResult->evaluation }}</textarea>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">File đính kèm (PDF/Ảnh)</label>
                                <input type="file" name="file_path" class="form-control">
                                <small class="text-muted">Tải lên file mới sẽ thay thế file cũ.</small>
                            </div>
                            <div class="col-md-6">
                                @if($testResult->file_main)
                                    <div class="mt-4">
                                        <a href="{{ asset('storage/'.$testResult->file_main) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-file-download me-1"></i> Xem file hiện tại
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="st1" value="pending" {{ $testResult->status == 'pending' ? 'checked' : '' }}>
                                    <label class="form-check-label badge bg-warning text-dark" for="st1">Chờ kết quả</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="st2" value="completed" {{ $testResult->status == 'completed' ? 'checked' : '' }}>
                                    <label class="form-check-label badge bg-primary" for="st2">Đã có KQ</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="st3" value="reviewed" {{ $testResult->status == 'reviewed' ? 'checked' : '' }}>
                                    <label class="form-check-label badge bg-success" for="st3">Đã duyệt</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end border-top pt-3">
                            <button type="submit" class="btn btn-warning btn-lg shadow fw-bold">
                                <i class="fas fa-save me-2"></i> Cập nhật Dữ liệu
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection