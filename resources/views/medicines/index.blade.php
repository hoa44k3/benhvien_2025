@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-prescription-bottle-alt me-2"></i> Quản lý Thuốc & Kho thuốc
    </h3>
    <hr>

    <div class="row g-4 mb-5">
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Tổng số loại thuốc</div>
                            <h3 class="display-6 fw-bolder">{{ number_format($totalMedicines) }}</h3>
                        </div>
                        <i class="fas fa-pills fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Tổng giá trị tồn kho</div>
                            <h3 class="fw-bolder fs-4">{{ $formattedTotalStock }}</h3>
                        </div>
                        <i class="fas fa-warehouse fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Giá trị sắp hết kho</div>
                            <h3 class="fw-bolder fs-4">{{ $formattedLowStockValue }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white shadow-lg h-100 border-0 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Số loại sắp hết hạn</div>
                            <h3 class="display-6 fw-bolder">{{ number_format($expiredCount ?? 5) }}</h3> 
                        </div>
                        <i class="fas fa-calendar-times fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <a href="{{ route('medicines.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Thuốc Mới
            </a>
        </div>
        
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo Mã hoặc Tên thuốc...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Phân loại</option>
                    <option value="khang_sinh">Kháng sinh</option>
                    <option value="giam_dau">Giảm đau</option>
                </select>
                <select class="form-select w-auto">
                    <option selected>Lọc theo Cảnh báo</option>
                    <option value="low_stock">Tồn kho thấp</option>
                    <option value="expired">Sắp/Hết hạn</option>
                </select>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã thuốc</th>
                            <th>Tên thuốc</th>
                            <th>Phân loại</th>
                            <th class="text-center">Tồn kho</th>
                            <th class="text-end">Giá bán (VNĐ)</th>
                            <th class="text-center">Hạn sử dụng</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" style="width: 120px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicines as $medicine)
                            <tr>
                                <td class="fw-bold">{{ $medicine->code }}</td>
                                <td><i class="fas fa-capsules me-1 text-primary"></i> {{ $medicine->name }}</td>
                                <td><span class="badge bg-secondary">{{ $medicine->category }}</span></td>
                                <td class="text-center {{ $medicine->stock < ($medicine->min_stock ?? 10) ? 'text-danger fw-bold' : '' }}">
                                    {{ number_format($medicine->stock) }}
                                    @if($medicine->min_stock)
                                        <small class="d-block text-muted">(Min: {{ $medicine->min_stock }})</small>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($medicine->price) }}</td>
                                <td class="text-center">
                                    @php
                                        $expiryDate = $medicine->expiry_date ? \Carbon\Carbon::parse($medicine->expiry_date) : null;
                                        $isExpired = $expiryDate && $expiryDate->isPast();
                                        $isExpiringSoon = $expiryDate && $expiryDate->diffInDays(\Carbon\Carbon::now()) <= 90 && !$isExpired;
                                        $expiryClass = $isExpired ? 'text-danger fw-bold' : ($isExpiringSoon ? 'text-warning fw-bold' : '');
                                    @endphp
                                    <span class="{{ $expiryClass }}">
                                        {{ $expiryDate ? $expiryDate->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($isExpired)
                                        <span class="badge bg-danger py-2 px-3"><i class="fas fa-times-circle"></i> Hết Hạn</span>
                                    @elseif($medicine->stock < ($medicine->min_stock ?? 10))
                                        <span class="badge bg-warning text-dark py-2 px-3"><i class="fas fa-bell"></i> Sắp Hết</span>
                                    @else
                                        <span class="badge bg-success py-2 px-3"><i class="fas fa-check-circle"></i> Bình Thường</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa thuốc này?')" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-box-open me-2"></i> Chưa có dữ liệu thuốc.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Nếu có phân trang --}}
    {{-- <div class="mt-4 d-flex justify-content-center">
        {{ $medicines->links() }}
    </div> --}}

</div>
@endsection