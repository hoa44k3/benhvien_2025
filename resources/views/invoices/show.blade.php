@extends('admin.master')
@section('title','Chi tiết hóa đơn')

@section('body')
<div class="container mx-auto max-w-7xl px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Hóa đơn #{{ $invoice->id }}</h1>

    <p><strong>Bệnh án:</strong> {{ $invoice->medicalRecord->title ?? 'Chưa gán' }}</p>
    <p><strong>Bệnh nhân:</strong> {{ $invoice->medicalRecord->user->name ?? 'Chưa gán' }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($invoice->total_amount) }} VND</p>

    <h3 class="mt-4 mb-2">Danh sách item</h3>
    <div class="space-y-2">
        @foreach($invoice->items as $item)
            <div class="bg-white p-3 rounded shadow-sm">
                <p>[{{ $item->item_type }}] {{ $item->description }} x{{ $item->quantity }} - {{ number_format($item->total_price) }} VND</p>
            </div>
        @endforeach
    </div>

    <a href="{{ route('invoices.index') }}" class="btn btn-secondary mt-4">Quay lại</a>
</div>
@endsection
