<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { background: #0d9488; color: white; padding: 15px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { text-align: right; font-size: 18px; font-weight: bold; color: #0d9488; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>XÁC NHẬN THANH TOÁN THÀNH CÔNG</h2>
        </div>
        <p>Xin chào <strong>{{ $invoice->user->name }}</strong>,</p>
        <p>Chúng tôi đã nhận được thanh toán cho hóa đơn <strong>#{{ $invoice->code }}</strong>.</p>
        
        <p><strong>Thông tin chi tiết:</strong></p>
        <ul>
            <li>Ngày thanh toán: {{ \Carbon\Carbon::parse($invoice->paid_at)->format('H:i d/m/Y') }}</li>
            <li>Phương thức: {{ strtoupper($invoice->payment_method) }}</li>
        </ul>

        <table class="table">
            <thead>
                <tr style="background-color: #f9f9f9;">
                    <th>Dịch vụ / Thuốc</th>
                    <th>SL</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->item_name ?? $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price ?? $item->unit_price) }} đ</td>
                    <td>{{ number_format($item->total ?? $item->total_price) }} đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Tổng cộng: {{ number_format($invoice->total) }} VND
        </div>

        <p>Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi.</p>
        <p><i>Đây là email tự động, vui lòng không trả lời.</i></p>
    </div>
</body>
</html>