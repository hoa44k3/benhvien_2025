@extends('admin.master')
@section('title', 'Thêm Bác sĩ Mới')

@section('body')
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i> Tạo hồ sơ Bác sĩ</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('doctorsite.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Phần 1: Thông tin cơ bản --}}
                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">1. Thông tin chung</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Chọn tài khoản User <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Chọn User (Role Doctor) --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Khoa trực thuộc</label>
                        <select name="department_id" class="form-select">
                            <option value="">-- Chọn Khoa --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chuyên khoa chính</label>
                        <input type="text" name="specialization" class="form-control" placeholder="VD: Tim mạch can thiệp">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số năm kinh nghiệm</label>
                        <input type="number" name="experience_years" class="form-control" value="0">
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Số bệnh nhân tối đa mỗi ngày</label>
                        <input type="number" name="max_patients" class="form-control" value="20" min="1">
                    </div>  
                </div>
                
                {{-- Phần 2: Cấu hình Lương & Hoa hồng --}}
                <h6 class="fw-bold text-success border-bottom pb-2 mb-3">2. Lương & Hoa hồng</h6>
                <div class="row g-3 mb-4 bg-light p-3 rounded">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-success">Lương cứng (VNĐ)</label>
                        <input type="number" name="base_salary" class="form-control" value="0" min="0" step="1000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hoa hồng Khám bệnh (%)</label>
                        <div class="input-group">
                            <input type="number" name="commission_exam_percent" class="form-control" value="0" min="0" max="100" step="0.1">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                {{-- Phần 3: Thông tin Ngân hàng --}}
                <h6 class="fw-bold text-info border-bottom pb-2 mb-3">3. Thông tin Tài khoản Ngân hàng</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Ngân hàng</label>
                        <input type="text" name="bank_name" class="form-control" placeholder="VD: Vietcombank, MB Bank...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số tài khoản (STK)</label>
                        <input type="text" name="bank_account_number" class="form-control" placeholder="Nhập số tài khoản">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tên chủ tài khoản</label>
                        <input type="text" name="bank_account_holder" class="form-control" placeholder="Tên in hoa không dấu">
                    </div>
                </div>

                {{-- Phần 4: Hồ sơ Năng lực & Chứng chỉ (MỚI) --}}
                <h6 class="fw-bold text-warning text-uppercase border-bottom pb-2 mb-3">
                    <i class="fas fa-certificate me-1"></i> 4. Hồ sơ Năng lực & Chứng chỉ
                </h6>
                <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Học vị / Bằng cấp</label>
                                <input type="text" name="degree" class="form-control" placeholder="VD: ThS.BS, Tiến sĩ, BS.CKII...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Số chứng chỉ hành nghề (CCHN)</label>
                                <input type="text" name="license_number" class="form-control" placeholder="VD: 001234/BYT-CCHN">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nơi cấp / Đơn vị đào tạo</label>
                                <input type="text" name="license_issued_by" class="form-control" placeholder="VD: Đại học Y Hà Nội">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Ảnh chụp chứng chỉ / Bằng cấp (Minh chứng)</label>
                                <input type="file" name="license_image" class="form-control" accept="image/*,.pdf">
                                <div class="form-text text-muted">Upload ảnh rõ nét hoặc file PDF để xác thực.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Phần 5: Thông tin hiển thị --}}
                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">5. Thông tin hiển thị</h6>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Giới thiệu ngắn (Bio)</label>
                        <textarea name="bio" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                            <label class="form-check-label fw-bold">Kích hoạt tài khoản ngay</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('doctorsite.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Lưu hồ sơ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection