@extends('admin.master')
@section('title','Danh sách hóa đơn')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Danh sách hóa đơn</h1>

    <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-4">Tạo hóa đơn mới</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bệnh án</th>
                <th>Bệnh nhân</th>
                <th>Tổng tiền</th>
                <th>Số item</th>
                <th>Chi tiết item</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->medicalRecord->title ?? 'Chưa gán' }}</td>
                <td>{{ $invoice->medicalRecord->user->name ?? 'Chưa gán' }}</td>
                <td>{{ number_format($invoice->total_amount) }} VND</td>
                <td>{{ $invoice->items->count() }}</td>
                <td>
                    @foreach($invoice->items as $item)
                        <div>
                            [{{ $item->item_type }}] {{ $item->description }} x{{ $item->quantity }} = {{ number_format($item->total_price) }}
                        </div>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info btn-sm">Xem</a>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa hóa đơn?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $invoices->links() }}
</div>
@endsection
