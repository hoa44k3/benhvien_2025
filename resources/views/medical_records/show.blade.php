@extends('admin.master')

@section('title', 'Chi tiết hồ sơ bệnh án')

@section('body')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-file-medical-alt me-2 text-info"></i> Chi tiết Hồ sơ Bệnh án: <span class="text-primary">{{ $medical_record->title }}</span>
        </h3>
        <a href="{{ route('medical_records.index') }}" class="btn btn-secondary shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
        </a>
    </div>

    {{-- Thông tin chính --}}
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i> Thông tin chung</h5>
        </div>
        <div class="card-body p-4 small">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <p class="mb-1"><strong><i class="fas fa-user me-2 text-primary"></i> Bệnh nhân:</strong> {{ $medical_record->user->name }}</p>
                    <p class="mb-1"><strong><i class="fas fa-user-md me-2 text-primary"></i> Bác sĩ:</strong> {{ $medical_record->doctor->name ?? 'Chưa gán' }}</p>
                    <p class="mb-1"><strong><i class="fas fa-clinic-medical me-2 text-primary"></i> Chuyên khoa:</strong> {{ $medical_record->department->name ?? 'Chưa gán' }}</p>
                    <p class="mb-1"><strong><i class="fas fa-calendar-alt me-2 text-primary"></i> Ngày khám:</strong> {{ optional($medical_record->date)->format('d/m/Y') }}</p>
                    <p class="mb-0"><strong><i class="fas fa-calendar-check me-2 text-primary"></i> Tái khám:</strong> {{ optional($medical_record->next_checkup)->format('d/m/Y') ?? 'Chưa hẹn' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong><i class="fas fa-stethoscope me-2 text-success"></i> Chẩn đoán:</strong></p>
                    <blockquote class="blockquote small bg-light p-2 rounded border-start border-3 border-success">
                        <p class="mb-0">{{ $medical_record->diagnosis ?? '---' }}</p>
                    </blockquote>
                    <p class="mb-1"><strong><i class="fas fa-notes-medical me-2 text-warning"></i> Điều trị:</strong></p>
                    <blockquote class="blockquote small bg-light p-2 rounded border-start border-3 border-warning">
                        <p class="mb-0">{{ $medical_record->treatment ?? '---' }}</p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>


    {{-- Form thêm Clinical Exam --}}
    <div class="card shadow-lg border-primary border-3 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-heartbeat me-2"></i> Thêm Xét nghiệm Lâm sàng (Dấu hiệu sinh tồn)</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('clinical_exams.store') }}" method="POST">
                @csrf
                <input type="hidden" name="medical_record_id" value="{{ $medical_record->id }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-1">Huyết áp</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="blood_pressure" class="form-control" placeholder="Ví dụ: 120/80">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-1">Nhiệt độ</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="temperature" class="form-control" placeholder="Giá trị">
                            <span class="input-group-text">°C</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-1">Nhịp tim</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="heart_rate" class="form-control" placeholder="Giá trị">
                            <span class="input-group-text">Lần/phút</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mt-3 shadow-sm fw-bold">
                    <i class="fas fa-save me-1"></i> Lưu Xét nghiệm
                </button>
            </form>
        </div>
    </div>


    {{-- Form thêm Prescription (Dynamic Rows) --}}
    <div class="card shadow-lg border-success border-3 mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 fw-bold"><i class="fas fa-prescription-bottle-alt me-2"></i> Kê Đơn Thuốc Mới</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('prescriptions.store') }}" method="POST" id="prescriptionForm">
                @csrf
                <input type="hidden" name="medical_record_id" value="{{ $medical_record->id }}">

                <div id="prescriptionRows" class="space-y-2">
                    {{-- Dòng thuốc mẫu --}}
                    <div class="row g-2 align-items-end mb-2 prescription-item-row">
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-1">Thuốc</label>
                            <input type="text" name="medicine_name[]" class="form-control form-control-sm" placeholder="Tên thuốc">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold mb-1">Số lượng</label>
                            <input type="number" name="quantity[]" class="form-control form-control-sm" placeholder="SL" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-1">Cách dùng (Liều/Tần suất)</label>
                            <input type="text" name="dosage[]" class="form-control form-control-sm" placeholder="VD: 1 viên x 2 lần/ngày">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-bold mb-1">Ngày</label>
                            <input type="number" name="duration[]" class="form-control form-control-sm" placeholder="Số ngày" min="1">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm w-100 removeRow" title="Xóa dòng">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" id="addPrescriptionRow" class="btn btn-outline-success btn-sm fw-bold">
                        <i class="fas fa-plus me-1"></i> Thêm thuốc
                    </button>
                    <button type="submit" class="btn btn-success shadow-sm fw-bold">
                        <i class="fas fa-save me-1"></i> Lưu Đơn thuốc
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- Các đơn thuốc đã có --}}
    <h4 class="fw-bold mt-5 mb-3"><i class="fas fa-list-alt me-2"></i> Danh sách Đơn thuốc Đã Kê</h4>
    <div class="row g-4">
        @forelse($medical_record->prescriptions as $prescription)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-info border-start border-5 h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold text-info"><i class="fas fa-file-prescription me-2"></i> Đơn số: {{ $prescription->code ?? $prescription->id }}</h6>
                        <p class="card-text small mb-1"><strong>Ngày:</strong> {{ optional($prescription->created_at)->format('d/m/Y H:i') }}</p>
                        <p class="card-text small mb-1"><strong>Bác sĩ:</strong> {{ $prescription->doctor->name ?? '---' }}</p>
                        <p class="card-text small mb-0"><strong>Ghi chú:</strong> {{ Str::limit($prescription->note, 50) ?? '---' }}</p>
                        <div class="mt-2 text-end">
                            <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-3">
                    <i class="fas fa-info-circle me-2"></i> Hồ sơ này chưa có đơn thuốc nào được kê.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Nút Hoàn tất khám và Tạo hóa đơn --}}
    <div class="d-flex gap-3 mt-5">
        <form action="{{ route('medical_records.complete', $medical_record->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning shadow fw-bold">
                <i class="fas fa-check-double me-2"></i> Hoàn tất Khám
            </button>
        </form>

        <button type="button" class="btn btn-purple shadow fw-bold" data-bs-toggle="modal" data-bs-target="#invoiceModal">
            <i class="fas fa-file-invoice me-2"></i> Tạo Hóa đơn
        </button>
    </div>

</div>

{{-- Modal tạo hóa đơn --}}
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title fw-bold" id="invoiceModalLabel"><i class="fas fa-cash-register me-2"></i> Tạo Hóa đơn Dịch vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf
                <input type="hidden" name="medical_record_id" value="{{ $medical_record->id }}">
                <div class="modal-body">
                    <div id="invoiceItems">
                        {{-- Dòng dịch vụ mẫu --}}
                        <div class="row g-2 align-items-end mb-2 invoice-item-row">
                            <div class="col-md-5">
                                <label class="form-label fw-bold mb-1">Tên dịch vụ</label>
                                <input type="text" name="service_name[]" class="form-control form-control-sm" placeholder="VD: Khám bệnh, xét nghiệm...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold mb-1">Số lượng</label>
                                <input type="number" name="quantity[]" class="form-control form-control-sm" value="1" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold mb-1">Đơn giá</label>
                                <input type="number" name="price[]" class="form-control form-control-sm" placeholder="Giá tiền">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm w-100 removeInvoiceRow" title="Xóa dòng">
                                    <i class="fas fa-times"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addInvoiceRow" class="btn btn-outline-primary btn-sm mt-2 fw-bold">
                        <i class="fas fa-plus me-1"></i> Thêm dịch vụ
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="fas fa-save me-1"></i> Lưu Hóa đơn
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>

{{-- Custom CSS cho màu tím của nút Hóa đơn --}}
<style>
    .btn-purple {
        background-color: #6f42c1; /* Bootstrap purple */
        color: white;
    }
    .btn-purple:hover {
        background-color: #5d35a3;
        color: white;
    }
    .bg-purple {
        background-color: #6f42c1 !important;
    }
</style>

{{-- JS Dynamic Rows --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prescriptionRowsContainer = document.getElementById('prescriptionRows');
        const invoiceItemsContainer = document.getElementById('invoiceItems');

        // Hàm tạo row mới (cho đơn thuốc)
        document.getElementById('addPrescriptionRow').addEventListener('click', function() {
            // Lấy dòng đầu tiên (hoặc mẫu)
            let rowToClone = prescriptionRowsContainer.querySelector('.prescription-item-row');
            if (!rowToClone) return;

            let newRow = rowToClone.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            prescriptionRowsContainer.appendChild(newRow);
        });

        // Hàm tạo row mới (cho hóa đơn)
        document.getElementById('addInvoiceRow').addEventListener('click', function() {
            // Lấy dòng đầu tiên (hoặc mẫu)
            let rowToClone = invoiceItemsContainer.querySelector('.invoice-item-row');
            if (!rowToClone) return;

            let newRow = rowToClone.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            // Đảm bảo số lượng mặc định là 1
            newRow.querySelector('input[name="quantity[]"]').value = 1;

            invoiceItemsContainer.appendChild(newRow);
        });

        // Xử lý xóa row (cho cả đơn thuốc và hóa đơn)
        document.addEventListener('click', function(e) {
            // Xóa dòng thuốc
            if (e.target.closest('.removeRow')) {
                const row = e.target.closest('.prescription-item-row');
                // Đảm bảo không xóa dòng cuối cùng
                if (prescriptionRowsContainer.children.length > 1) {
                    row.remove();
                } else {
                    alert('Đơn thuốc phải có ít nhất một dòng thuốc.');
                }
            }

            // Xóa dòng hóa đơn
            if (e.target.closest('.removeInvoiceRow')) {
                const row = e.target.closest('.invoice-item-row');
                 // Đảm bảo không xóa dòng cuối cùng
                if (invoiceItemsContainer.children.length > 1) {
                    row.remove();
                } else {
                     alert('Hóa đơn phải có ít nhất một dịch vụ.');
                }
            }
        });
    });
</script>

@endsection