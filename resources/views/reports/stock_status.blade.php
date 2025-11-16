@extends('admin.master')

@section('body')
<div class="container">
    <h3 class="fw-bold mb-3">Báo cáo Tình trạng Kho thuốc</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Tên thuốc</th>
                <th>Số lượng còn</th>
                <th>Ngưỡng cảnh báo</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicines as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td>{{ $m->quantity }}</td>
                    <td>{{ $m->threshold }}</td>
                    <td>
                        @if($m->quantity <= $m->threshold)
                            <span class="badge bg-warning text-dark">Sắp hết</span>
                        @else
                            <span class="badge bg-success">Còn hàng</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
