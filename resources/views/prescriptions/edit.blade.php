@extends('admin.master')

@section('title', 'Chỉnh sửa đơn thuốc: ' . $prescription->code)

@section('body')
<div class="container-fluid mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-edit me-2 text-warning"></i> Chỉnh sửa Đơn thuốc: <span class="text-info">{{ $prescription->code }}</span>
        </h3>
        <a href="{{ route('prescriptions.show', $prescription->id) }}" class="btn btn-secondary shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i> Quay lại chi tiết
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- THÔNG TIN CHUNG (FORM) --}}
            <div class="card shadow-lg border-0 rounded-3 mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i> Thông tin cơ bản</h5>
                </div>
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('prescriptions.update', $prescription->id) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Thông báo lỗi (Validation errors) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Lỗi nhập liệu!</strong> Vui lòng kiểm tra lại các trường đã điền.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Mã đơn thuốc (Readonly) --}}
                        <div class="mb-3">
                            <label for="code" class="form-label fw-bold">Mã đơn thuốc</label>
                            <input type="text" name="code_disabled" id="code"
                                class="form-control bg-light"
                                value="{{ $prescription->code }}" disabled>
                            <small class="text-muted">Mã đơn thuốc không thể thay đổi.</small>
                        </div>

                        <div class="row g-3 mb-3">
                            {{-- Bác sĩ --}}
                            <div class="col-md-6">
                                <label for="doctor_id" class="form-label fw-bold">Bác sĩ <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                    @foreach ($doctors as $d)
                                        <option value="{{ $d->id }}" @selected(old('doctor_id', $prescription->doctor_id) == $d->id)>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bệnh nhân --}}
                            <div class="col-md-6">
                                <label for="patient_id" class="form-label fw-bold">Bệnh nhân <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    @foreach ($patients as $p)
                                        <option value="{{ $p->id }}" @selected(old('patient_id', $prescription->patient_id) == $p->id)>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Hồ sơ bệnh án --}}
                        <div class="mb-3">
                            <label for="medical_record_id" class="form-label fw-bold">Hồ sơ bệnh án</label>
                            <select name="medical_record_id" id="medical_record_id" class="form-select @error('medical_record_id') is-invalid @enderror">
                                <option value="">-- Không chọn --</option>
                                @foreach ($records as $r)
                                    <option value="{{ $r->id }}" @selected(old('medical_record_id', $prescription->medical_record_id) == $r->id)>
                                        {{ $r->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medical_record_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chẩn đoán --}}
                        <div class="mb-3">
                            <label for="diagnosis" class="form-label fw-bold">Chẩn đoán</label>
                            <textarea name="diagnosis" id="diagnosis" rows="3"
                                class="form-control @error('diagnosis') is-invalid @enderror"
                                placeholder="Tóm tắt chẩn đoán của bác sĩ">{{ old('diagnosis', $prescription->diagnosis) }}</textarea>
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ghi chú --}}
                        <div class="mb-3">
                            <label for="note" class="form-label fw-bold">Ghi chú</label>
                            <textarea name="note" id="note" rows="3"
                                class="form-control @error('note') is-invalid @enderror"
                                placeholder="Các ghi chú đặc biệt về đơn thuốc hoặc bệnh nhân">{{ old('note', $prescription->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Trạng thái đơn thuốc <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Đang kê" @selected(old('status', $prescription->status) == 'Đang kê')>
                                    Đang kê
                                </option>
                                <option value="Đã duyệt" @selected(old('status', $prescription->status) == 'Đã duyệt')>
                                    Đã duyệt
                                </option>
                                <option value="Đã phát thuốc" @selected(old('status', $prescription->status) == 'Đã phát thuốc')>
                                    Đã phát thuốc
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- DANH SÁCH THUỐC --}}
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-pills me-2"></i> Thuốc trong đơn</h5>

                    <a href="{{ route('prescription_items.create', $prescription->id) }}"
                        class="btn btn-light btn-sm fw-bold shadow-sm">
                        <i class="fas fa-plus me-1"></i> Thêm Thuốc
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover table-striped align-middle small">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="p-2 text-start">Tên thuốc</th>
                                    <th class="p-2">Hàm lượng</th>
                                    <th class="p-2">Đơn vị</th>
                                    <th class="p-2">Số lượng</th>
                                    <th class="p-2">Liều dùng</th>
                                    <th class="p-2">Ghi chú</th>
                                    <th class="p-2">Thành tiền</th>
                                    <th class="p-2">Hành động</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($prescription->items as $item)
                                <tr>
                                    <td class="p-3 text-start fw-medium">{{ $item->medicine_name }}</td>
                                    <td class="p-3 text-center">{{ $item->strength ?? '-' }}</td>
                                    <td class="p-3 text-center">{{ $item->unit ?? '-' }}</td>
                                    <td class="p-3 text-center fw-bold text-success">{{ $item->quantity }}</td>
                                    <td class="p-3 text-start">{{ $item->dosage }}</td>
                                    <td class="p-3 text-start">{{ $item->note ? Str::limit($item->note, 40) : '-' }}</td>
                                    <td class="p-3 text-center fw-bold text-danger">
                                        @if(isset($item->price))
                                            {{ number_format($item->price * $item->quantity) }} đ
                                        @else
                                            ---
                                        @endif
                                    </td>

                                    <td class="p-3 text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('prescription_items.edit', $item->id) }}"
                                               class="btn btn-sm btn-warning me-1" title="Sửa chi tiết thuốc">
                                               <i class="fas fa-edit"></i>
                                            </a>

                                            <button onclick="deleteItem({{ $item->id }})"
                                                    class="btn btn-sm btn-danger" title="Xóa thuốc">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">
                                        <i class="fas fa-info-circle me-2"></i> Đơn thuốc chưa có thuốc.
                                        <a href="{{ route('prescription_items.create', $prescription->id) }}" class="text-primary fw-medium">Thêm ngay</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            
                            {{-- THÔNG TIN TỔNG CỘNG --}}
                            @if($prescription->items->isNotEmpty())
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end p-2 fs-6">TỔNG THÀNH TIỀN:</th>
                                    <th class="p-2 fs-6 text-danger fw-bold text-center">
                                        {{ number_format($prescription->items->sum(fn($item) => $item->price * $item->quantity)) }} đ
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteItem(id) {
        if (!confirm("Xác nhận xóa thuốc này khỏi đơn? Hành động này không thể hoàn tác.")) return;

        // Sử dụng route DELETE chuẩn của Laravel API (hoặc resource controller)
        // Lưu ý: Cần đảm bảo route này trỏ đúng đến controller method DELETE cho prescription_items
        fetch(`/prescription_items/${id}`, {
            method: "POST", // Sử dụng POST vì DELETE thường bị chặn trong Fetch API nếu không dùng @method('DELETE') trong form, nhưng trong JS/API call ta dùng method: "DELETE"
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-HTTP-Method-Override": "DELETE" // Giả lập method DELETE
            }
        }).then(r => r.json()).then(d => {
            if (d.success) {
                // Sử dụng thông báo flash hoặc reload
                location.reload();
            } else {
                 alert('Xóa thuốc không thành công: ' + (d.message || 'Lỗi không xác định.'));
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi khi cố gắng xóa thuốc.');
        });
    }
</script>

@endsection