<!DOCTYPE html>
<html>
<body>
    <h2>Thông báo quan trọng từ SmartHospital</h2>
    <p>Xin chào {{ $appointment->patient_name }},</p>
    
    <p>Chúng tôi rất tiếc phải thông báo rằng lịch khám của bạn với <strong>Bác sĩ {{ $doctorName }}</strong> vào ngày <strong>{{ $appointment->date }} lúc {{ $appointment->time }}</strong> đã bị hủy do bác sĩ có lịch nghỉ đột xuất.</p>
    
    <p>Vui lòng truy cập website để đặt lại lịch khám mới hoặc liên hệ Hotline 1900-1234 để được hỗ trợ sắp xếp bác sĩ thay thế.</p>
    
    <p>Thành thật xin lỗi vì sự bất tiện này.</p>
    <p>Trân trọng,<br>SmartHospital Team.</p>
</body>
</html>