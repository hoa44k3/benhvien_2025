<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background-color: #007bff; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .info-box { background: #f9f9f9; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .warning { color: #d9534f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>XÁC NHẬN LỊCH KHÁM ONLINE</h2>
        </div>
        <div class="content">
            <p>Chào <strong>{{ $appointment->patient_name }}</strong>,</p>
            <p>Lịch hẹn khám bệnh trực tuyến của bạn đã được hệ thống ghi nhận thành công.</p>
            
            <div class="info-box">
                <p><strong>Mã lịch hẹn:</strong> {{ $appointment->code }}</p>
                <p><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }} - Ngày {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</p>
                <p><strong>Bác sĩ:</strong> {{ $appointment->doctor->name ?? 'Đang cập nhật' }}</p>
                <p><strong>Chuyên khoa:</strong> {{ $appointment->department->name ?? 'Tổng quát' }}</p>
            </div>

            <h3>🔗 LINK PHÒNG KHÁM ONLINE</h3>
            <p>Vui lòng truy cập đường dẫn dưới đây đúng giờ hẹn:</p>
            <p style="text-align: center;">
                <a href="https://meet.google.com/new" class="btn">THAM GIA BUỔI KHÁM (MEETING)</a>
            </p>

            <h3>📋 Hướng dẫn trước khi khám:</h3>
            <ul>
                <li>Chuẩn bị thiết bị có <strong>Camera và Micro</strong> hoạt động tốt.</li>
                <li>Đảm bảo đường truyền Internet ổn định.</li>
                <li>Chuẩn bị sẵn các kết quả xét nghiệm cũ (nếu có) để gửi cho bác sĩ.</li>
                <li>Vui lòng vào phòng chờ trước <strong>10 phút</strong>.</li>
            </ul>
            
            <p class="warning">Lưu ý: Nếu bạn không tham gia sau 15 phút, lịch hẹn sẽ tự động bị hủy.</p>
        </div>
        <div class="footer">
            <p>Đây là email tự động, vui lòng không trả lời.<br>Hỗ trợ kỹ thuật: 1900 xxxx</p>
        </div>
    </div>
</body>
</html>