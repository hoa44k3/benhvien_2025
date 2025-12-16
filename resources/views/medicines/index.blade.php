@extends('admin.master')

@section('body')
<div class="container-fluid mt-4">

    <h3 class="mb-4 text-primary fw-bold">
        <i class="fas fa-prescription-bottle-alt me-2"></i> Quản lý Thuốc & Kho thuốc
    </h3>
    <hr>

    {{-- Dashboard Thống kê --}}
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
                            <div class="text-uppercase fw-semibold mb-1 opacity-75">Số loại đã hết hạn</div>
                            <h3 class="display-6 fw-bolder">{{ number_format($expiredCount) }}</h3> 
                        </div>
                        <i class="fas fa-calendar-times fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Thanh công cụ & Tìm kiếm --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-3">
            <a href="{{ route('medicines.create') }}" class="btn btn-primary shadow-sm w-100">
                <i class="fas fa-plus me-1"></i> Thêm Thuốc Mới
            </a>
        </div>
        
        <div class="col-md-9">
            <form action="{{ route('medicines.index') }}" method="GET">
                <div class="input-group">
                    {{-- Input tìm kiếm --}}
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="Tìm kiếm theo Mã hoặc Tên thuốc..." 
                           value="{{ request('keyword') }}">
                    
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>

                    {{-- Lọc theo Phân loại --}}
                    <select class="form-select w-auto" name="category" onchange="this.form.submit()">
                        <option value="">-- Tất cả Phân loại --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Lọc theo Cảnh báo --}}
                    <select class="form-select w-auto" name="alert" onchange="this.form.submit()">
                        <option value="">-- Tất cả Trạng thái --</option>
                        <option value="low_stock" {{ request('alert') == 'low_stock' ? 'selected' : '' }}>Tồn kho thấp</option>
                        <option value="expired" {{ request('alert') == 'expired' ? 'selected' : '' }}>Sắp/Hết hạn</option>
                    </select>
                    
                    {{-- Nút Reset --}}
                    @if(request()->hasAny(['keyword', 'category', 'alert']))
                        <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Bảng dữ liệu --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0 text-nowrap">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã thuốc</th>
                            <th>Tên thuốc</th>
                            <th>Phân loại</th>
                            <th class="text-center">Đơn vị</th>
                            <th class="text-center">Tồn kho</th>
                            <th class="text-center">Tồn Min</th>
                            <th class="text-end">Giá bán (VNĐ)</th>
                            <th class="text-center">Hạn sử dụng</th>
                            <th>Nhà cung cấp</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicines as $medicine)
                            <tr>
                                <td class="fw-bold text-primary">{{ $medicine->code }}</td>
                                
                                <td>
                                    <span class="fw-semibold">{{ $medicine->name }}</span>
                                </td>

                                <td><span class="badge bg-info text-dark">{{ $medicine->category }}</span></td>
                                
                                <td class="text-center">{{ $medicine->unit }}</td>

                                <td class="text-center {{ $medicine->stock <= ($medicine->min_stock ?? 0) ? 'text-danger fw-bold' : '' }}">
                                    {{ number_format($medicine->stock) }}
                                </td>

                                <td class="text-center text-muted">
                                    {{ number_format($medicine->min_stock) }}
                                </td>

                                <td class="text-end fw-bold text-success">{{ number_format($medicine->price) }}</td>

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

                                <td>{{ Str::limit($medicine->supplier, 20) }}</td>

                                <td class="text-center">
                                    @if($isExpired)
                                        <span class="badge bg-danger">Hết Hạn</span>
                                    @elseif($medicine->stock <= ($medicine->min_stock ?? 10))
                                        <span class="badge bg-warning text-dark">Sắp Hết</span>
                                    @else
                                        <span class="badge bg-success">Hoạt động</span>
                                    @endif
                                </td>

                                <td class="text-center">
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
                            <tr><td colspan="11" class="text-center text-muted py-4"><i class="fas fa-box-open me-2"></i> Không tìm thấy dữ liệu thuốc phù hợp.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Phân trang --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $medicines->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection