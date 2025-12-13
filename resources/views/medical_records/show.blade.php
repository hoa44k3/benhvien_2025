@extends('admin.master')

@section('title', 'Chi tiết hồ sơ bệnh án')

@push('styles')
<style>
    /* Fix lỗi Modal bị mờ hoặc không click được */
    .modal-backdrop { z-index: 1040 !important; }
    .modal { z-index: 1050 !important; }
    
    /* Trạng thái */
    .status-badge { font-size: 0.9rem; padding: 0.5em 1em; border-radius: 20px; }
</style>
@endpush

@section('body')
<div class="container-fluid mt-4 mb-5">
{{-- 🔥 THÊM ĐOẠN NÀY ĐỂ HIỂN THỊ LỖI --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-danger" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Đã xảy ra lỗi:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    {{-- HEADER & TRẠNG THÁI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-file-medical-alt me-2 text-info"></i> Hồ sơ: <span class="text-primary">{{ $medical_record->title }}</span>
            </h3>
            @php
                $statusConfig = [
                    'chờ_khám' => ['class' => 'bg-secondary', 'label' => 'Chờ khám'],
                    'đang_khám' => ['class' => 'bg-primary', 'label' => 'Đang khám'],
                    'đã_khám' => ['class' => 'bg-success', 'label' => 'Hoàn thành'],
                    'hủy' => ['class' => 'bg-danger', 'label' => 'Đã hủy'],
                ];
                $currentStatus = $statusConfig[$medical_record->status] ?? ['class' => 'bg-secondary', 'label' => $medical_record->status];
            @endphp
            <span class="badge {{ $currentStatus['class'] }} status-badge">
                {{ strtoupper($currentStatus['label']) }}
            </span>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('medical_records.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>

            {{-- LOGIC NÚT BẤM DỰA TRÊN TRẠNG THÁI --}}
            
            {{-- 1. Nếu CHỜ KHÁM -> Hiện nút BẮT ĐẦU --}}
            @if($medical_record->status == 'chờ_khám')
                <form action="{{ route('medical_records.start', $medical_record->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary shadow fw-bold">
                        <i class="fas fa-play me-1"></i> BẮT ĐẦU KHÁM
                    </button>
                </form>
                <form action="{{ route('medical_records.cancel', $medical_record->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger shadow" onclick="return confirm('Hủy ca khám này?')">
                        <i class="fas fa-times me-1"></i> Hủy
                    </button>
                </form>
            @endif

            {{-- 2. Nếu ĐÃ KHÁM -> Hiện nút XEM HÓA ĐƠN --}}
            @if($medical_record->status == 'đã_khám')
                @php
                    $invoice = \App\Models\Invoice::where('medical_record_id', $medical_record->id)->first();
                @endphp
                @if($invoice)
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-success shadow fw-bold">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Xem Hóa đơn
                    </a>
                @endif
            @endif
        </div>
    </div>

    {{-- ALERT NẾU CHƯA BẮT ĐẦU --}}
    @if($medical_record->status == 'chờ_khám')
        <div class="alert alert-warning text-center border-warning shadow-sm">
            <h5 class="mb-0 text-dark"><i class="fas fa-user-clock me-2"></i> Bệnh nhân đang chờ. Vui lòng bấm nút <strong>"Bắt đầu khám"</strong> ở trên để nhập liệu.</h5>
        </div>
    @endif

    {{-- =========================================================
         NỘI DUNG CHÍNH (Chỉ hiện khi ĐANG KHÁM hoặc ĐÃ KHÁM) 
       ========================================================= --}}
    @if($medical_record->status == 'đang_khám' || $medical_record->status == 'đã_khám')

        {{-- 1. THÔNG TIN CHUNG --}}
        <div class="card shadow-lg border-0 rounded-3 mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i> Thông tin bệnh nhân</h5>
            </div>
            <div class="card-body p-4 small">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <p class="mb-1"><strong><i class="fas fa-user me-2 text-primary"></i> Bệnh nhân:</strong> {{ $medical_record->user->name }}</p>
                        <p class="mb-1"><strong><i class="fas fa-phone me-2 text-primary"></i> SĐT:</strong> {{ $medical_record->user->phone ?? '---' }}</p>
                        <p class="mb-1"><strong><i class="fas fa-birthday-cake me-2 text-primary"></i> Ngày sinh:</strong> {{ $medical_record->user->dob ?? '---' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="fas fa-user-md me-2 text-primary"></i> Bác sĩ:</strong> {{ $medical_record->doctor->name ?? '---' }}</p>
                        <p class="mb-1"><strong><i class="fas fa-clinic-medical me-2 text-primary"></i> Khoa:</strong> {{ $medical_record->department->name ?? '---' }}</p>
                        <p class="mb-1"><strong><i class="fas fa-calendar-alt me-2 text-primary"></i> Ngày tạo:</strong> {{ \Carbon\Carbon::parse($medical_record->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. KẾT QUẢ XÉT NGHIỆM --}}
        <div class="card shadow-lg border-warning border-3 mb-4">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-microscope me-2"></i> Chỉ định & Kết quả Xét nghiệm</h5>
                @if($medical_record->status == 'đang_khám')
                    <button type="button" class="btn btn-sm btn-dark shadow" data-bs-toggle="modal" data-bs-target="#addTestModal">
                        <i class="fas fa-plus me-1"></i> Chỉ định mới
                    </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Tên xét nghiệm</th>
                                <th>Phòng Lab</th>
                                <th>Kết quả</th>
                                <th>Đánh giá BS</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medical_record->testResults as $test)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $test->test_name }}</td>
                                <td>{{ $test->lab_name ?? '---' }}</td>
                                <td class="text-primary fw-bold">{{ $test->result ?? '---' }}</td>
                                <td>{{ Str::limit($test->diagnosis, 30) ?? '-' }}</td>
                                {{-- <td>
                                    @if($test->status == 'completed')
                                        <span class="badge bg-success">Đã có KQ</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Chờ KQ</span>
                                    @endif
                                </td> --}}
                                <td>
    @if($test->status == 'pending')
        <span class="badge bg-warning text-dark">Chờ KQ</span>
    @elseif($test->status == 'completed')
        <span class="badge bg-primary">Đã có KQ</span>
    @elseif($test->status == 'reviewed')
        <span class="badge bg-success">Đã duyệt</span>
    @else
        <span class="badge bg-secondary">Lưu trữ</span>
    @endif
</td>
                                <td class="text-center">
                                    {{-- Chỉ cho phép nhập KQ khi đang khám --}}
                                    @if($medical_record->status == 'đang_khám')
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateTestModal-{{ $test->id }}">
                                            <i class="fas fa-edit"></i> Nhập KQ
                                        </button>
                                        
                                        {{-- Include Modal Cập nhật (Viết Inline để tránh lỗi include) --}}
                                        <div class="modal fade" id="updateTestModal-{{ $test->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog text-start">
                                                <div class="modal-content">
                                                    <form action="{{ route('test_results.update', $test->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Cập nhật: {{ $test->test_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Kết quả <span class="text-danger">*</span></label>
                                                                <input type="text" name="result" class="form-control" value="{{ $test->result }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Đánh giá/Kết luận</label>
                                                                <textarea name="diagnosis" class="form-control" rows="2">{{ $test->diagnosis }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">File đính kèm</label>
                                                                <input type="file" name="file" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Lưu</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled><i class="fas fa-lock"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Chưa có chỉ định xét nghiệm nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 3. KÊ ĐƠN THUỐC --}}
        <div class="card shadow-lg border-success border-3 mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-pills me-2"></i> Đơn thuốc</h5>
                @if($medical_record->status == 'đang_khám')
                    <a href="{{ route('prescriptions.create', ['medical_record_id' => $medical_record->id]) }}" class="btn btn-sm btn-light text-success fw-bold">
                        <i class="fas fa-plus me-1"></i> Kê đơn mới
                    </a>
                @endif
            </div>
            <div class="card-body">
                @if($medical_record->prescriptions->count() > 0)
                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-check me-2"></i> Đã kê {{ $medical_record->prescriptions->count() }} đơn thuốc.</span>
                        <a href="{{ route('prescriptions.edit', $medical_record->prescriptions->first()->id) }}" class="btn btn-sm btn-success">Xem chi tiết</a>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Chưa có đơn thuốc nào.</p>
                @endif
            </div>
        </div>

        {{-- 4. CHẨN ĐOÁN & ĐIỀU TRỊ (FORM CHÍNH) --}}
        <div class="card shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold"><i class="fas fa-user-md me-2"></i> Kết luận Khám bệnh</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('medical_records.update', $medical_record->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    {{-- Các trường bắt buộc hidden --}}
                    <input type="hidden" name="user_id" value="{{ $medical_record->user_id }}">
                    <input type="hidden" name="title" value="{{ $medical_record->title }}">
                    <input type="hidden" name="date" value="{{ $medical_record->date }}">
                    <input type="hidden" name="status" value="{{ $medical_record->status }}"> {{-- Giữ nguyên status --}}

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Chẩn đoán chính</label>
                            <input type="text" name="diagnosis" class="form-control" value="{{ $medical_record->diagnosis }}" {{ $medical_record->status != 'đang_khám' ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Hướng điều trị</label>
                            <input type="text" name="treatment" class="form-control" value="{{ $medical_record->treatment }}" {{ $medical_record->status != 'đang_khám' ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Triệu chứng lâm sàng</label>
                            <textarea name="symptoms" class="form-control" rows="3" {{ $medical_record->status != 'đang_khám' ? 'readonly' : '' }}>{{ $medical_record->symptoms }}</textarea>
                        </div>
                    </div>

                    @if($medical_record->status == 'đang_khám')
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Lưu thông tin</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- 5. NÚT HOÀN TẤT (QUAN TRỌNG NHẤT) --}}
        @if($medical_record->status == 'đang_khám')
            <div class="card bg-light border-0 mb-5">
                <div class="card-body text-end">
                    <p class="text-muted small mb-2"><i class="fas fa-info-circle"></i> Bấm nút dưới đây để kết thúc ca khám và tự động tạo hóa đơn.</p>
                    <form action="{{ route('medical_records.complete', $medical_record->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-lg shadow fw-bold w-100 py-3" onclick="return confirm('Xác nhận hoàn tất ca khám? Hóa đơn sẽ được tạo tự động.')">
                            <i class="fas fa-check-double me-2"></i> HOÀN TẤT KHÁM & TẠO HÓA ĐƠN
                        </button>
                    </form>
                </div>
            </div>
        @endif

    @endif {{-- End if đang_khám || đã_khám --}}

</div>

{{-- MODAL THÊM CHỈ ĐỊNH XÉT NGHIỆM --}}
@if($medical_record->status == 'đang_khám')
<div class="modal fade" id="addTestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Chỉ định Xét nghiệm Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('test_results.store') }}" method="POST">
                @csrf
                <input type="hidden" name="medical_record_id" value="{{ $medical_record->id }}">
                <input type="hidden" name="user_id" value="{{ $medical_record->user_id }}">
                <input type="hidden" name="doctor_id" value="{{ Auth::id() }}">
                
                {{-- Mặc định ngày xét nghiệm là hôm nay --}}
                <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold form-label">Loại xét nghiệm <span class="text-danger">*</span></label>
                        <select name="test_name" class="form-select" required>
                            <option value="">-- Chọn loại xét nghiệm --</option>
                            <option value="Công thức máu toàn phần">Công thức máu toàn phần</option>
                            <option value="Sinh hóa máu">Sinh hóa máu</option>
                            <option value="X-Quang ngực thẳng">X-Quang ngực thẳng</option>
                            <option value="Siêu âm ổ bụng">Siêu âm ổ bụng</option>
                            <option value="Nước tiểu 10 thông số">Nước tiểu 10 thông số</option>
                            <option value="Test nhanh Covid-19">Test nhanh Covid-19</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phòng thực hiện</label>
                        <input type="text" name="lab_name" class="form-control" placeholder="VD: Phòng X-Quang 1" value="Phòng xét nghiệm trung tâm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú chỉ định</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú cho KTV..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning fw-bold">Tạo Chỉ định</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection