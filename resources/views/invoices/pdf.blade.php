<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn #{{ $invoice->code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #333; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; }
        .items-table th { background-color: #f0f0f0; }
        .total-section { text-align: right; margin-top: 20px; font-size: 14px; }
        .status-paid { color: green; font-weight: bold; border: 2px solid green; padding: 5px; display: inline-block; transform: rotate(-10deg); }
        .status-unpaid { color: red; font-weight: bold; border: 2px solid red; padding: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HÓA ĐƠN VIỆN PHÍ</h1>
        <p>Phòng Khám Đa Khoa - Hotline: 1900 xxxx</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 60%">
                <strong>Khách hàng:</strong> {{ $invoice->user->name }}<br>
                <strong>SĐT:</strong> {{ $invoice->user->phone ?? '---' }}<br>
                <strong>Địa chỉ:</strong> {{ $invoice->user->address ?? '---' }}
            </td>
            <td style="width: 40%; text-align: right; vertical-align: top;">
                <strong>Mã HĐ:</strong> {{ $invoice->code }}<br>
                <strong>Ngày tạo:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}<br>
                <strong>Trạng thái:</strong> 
                @if($invoice->status == 'paid')
                    <span class="status-paid">ĐÃ THANH TOÁN</span>
                @else
                    <span class="status-unpaid">CHƯA THANH TOÁN</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Nội dung</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $item->item_name ?? $item->description }}</td>
                <td style="text-align: right">{{ number_format($item->price ?? $item->unit_price) }}</td>
                <td style="text-align: center">{{ $item->quantity }}</td>
                <td style="text-align: right">{{ number_format($item->total ?? $item->total_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p>Tổng tiền: <strong>{{ number_format($invoice->total) }} VND</strong></p>
        @if($invoice->status == 'paid')
            <p>Đã thanh toán qua: {{ strtoupper($invoice->payment_method) }}</p>
            <p>Ngày thanh toán: {{ \Carbon\Carbon::parse($invoice->paid_at)->format('d/m/Y H:i') }}</p>
        @endif
    </div>

    <div style="margin-top: 50px; text-align: center;">
        <p><i>Cảm ơn quý khách!</i></p>
    </div>
</body>
</html>