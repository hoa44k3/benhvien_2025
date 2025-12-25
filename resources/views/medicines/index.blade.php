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
                {{-- Lọc theo Phân loại --}}
                    <select class="form-select w-auto" name="medicine_category_id" onchange="this.form.submit()">
                        <option value="">-- Tất cả Phân loại --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('medicine_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
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
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicines as $medicine)
                       
                                <td class="fw-bold text-primary">{{ $medicine->code }}</td>
                                
                                <td>
                                    <span class="fw-semibold">{{ $medicine->name }}</span>
                                </td>

                         {{-- Cột Phân loại --}}
                                <td>
                                    {{-- Sửa $medicine->category thành $medicine->medicineCategory --}}
                                    @if($medicine->medicineCategory)
                                        <span class="badge bg-info text-dark">{{ $medicine->medicineCategory->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{-- Sửa $medicine->unit thành $medicine->medicineUnit --}}
                                    {{ $medicine->medicineUnit ? $medicine->medicineUnit->name : '-' }}
                                </td>
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