<!DOCTYPE html>
<html>
<head>
    <style>
        /* CSS tương tự ở trên */
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { background-color: #17a2b8; color: white; padding: 15px; text-align: center; }
        .section { margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 10px; }
        .prescription-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .prescription-table th, .prescription-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>THÔNG BÁO KẾT QUẢ KHÁM BỆNH</h2>
        </div>
        
        <p>Chào <strong>{{ $record->user->name }}</strong>,</p>
        <p>Bác sĩ <strong>{{ $record->doctor->name }}</strong> đã hoàn tất hồ sơ khám bệnh của bạn vào ngày {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}.</p>

        <div class="section">
            <h3>🩺 Kết luận chuyên môn</h3>
            <p><strong>Chẩn đoán chính:</strong> {{ $record->diagnosis_primary ?? $record->diagnosis }}</p>
            <p><strong>Triệu chứng:</strong> {{ $record->symptoms }}</p>
            <p><strong>Lời dặn của bác sĩ:</strong> {{ $record->treatment }}</p>
            @if($record->next_checkup)
            <p style="color: blue;"><strong>📅 Lịch tái khám:</strong> {{ \Carbon\Carbon::parse($record->next_checkup)->format('d/m/Y') }}</p>
            @endif
        </div>

        @if($record->prescriptions->count() > 0)
        <div class="section">
            <h3>💊 Đơn thuốc</h3>
            <table class="prescription-table">
                <thead>
                    <tr>
                        <th>Tên thuốc</th>
                        <th>Số lượng</th>
                        <th>Cách dùng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->prescriptions->first()->items as $item)
                    <tr>
                        <td>{{ $item->medicine_name }}</td> <td>{{ $item->quantity }} {{ $item->unit }}</td>
                        <td>{{ $item->dosage }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="section">
            <h3>💰 Thông tin thanh toán</h3>
            <p>Hóa đơn phí dịch vụ đã được tạo. Vui lòng kiểm tra mục "Hóa đơn" trong tài khoản của bạn để thanh toán.</p>
        </div>

        <p>Chúc bạn mau khỏe!</p>
    </div>
</body>
</html>