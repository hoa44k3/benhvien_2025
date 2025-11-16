<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản | Chọn Vai Trò</title>
    
    <style>
        /* ==================================== */
        /* CSS STYLES START */
        /* ==================================== */
        
        /* Thiết lập chung */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8; /* Màu nền nhẹ hơn */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Container chính của form (Card) */
        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px; /* Tăng chiều rộng để phù hợp với nhiều trường */
            text-align: center;
        }

        /* Tiêu đề & Mô tả */
        .register-container h1 {
            color: #10b981; /* Màu Xanh Lục (Emerald) cho y tế */
            margin-bottom: 5px;
            font-size: 2.2em;
        }

        .register-container p {
            color: #374151;
            margin-bottom: 30px;
            font-size: 1em;
            font-weight: 500;
        }

        /* Nhóm Input và Select */
        .input-group {
            margin-bottom: 15px; /* Giảm khoảng cách giữa các nhóm */
            text-align: left;
        }

        /* Thiết lập chung cho Input và Select */
        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px 15px; /* Giảm padding một chút */
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: white;
            appearance: none;
        }

        /* Hiệu ứng focus */
        .input-group input:focus,
        .input-group select:focus {
            border-color: #10b981;
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        /* Hiển thị label cho Vai trò */
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 0.9em;
        }

        /* Biểu tượng cho Select box */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: '▼';
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            pointer-events: none;
            color: #10b981;
            font-size: 0.8em;
        }

        /* Nút Đăng ký */
        .register-button {
            width: 100%;
            padding: 14px; /* Giảm padding một chút */
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .register-button:hover {
            background-color: #059669;
            transform: translateY(-2px);
        }

        /* Liên kết cuối trang */
        .footer-links {
            margin-top: 20px;
            font-size: 0.9em;
            display: flex;
            justify-content: space-around;
            gap: 15px;
        }

        .footer-links a {
            color: #6b7280;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #10b981;
            text-decoration: underline;
        }

        .footer-links .login-link {
            font-weight: bold;
            color: #10b981;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h1>Đăng Ký Tài Khoản 🏥</h1>
        <p>Vui lòng chọn vai trò và điền đầy đủ thông tin</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="input-group">
                <input type="text" name="name" id="name" placeholder="👤 Tên đầy đủ" required>
            </div>
            
            <div class="input-group">
                <label for="role">Vai trò của bạn là gì?</label>
                <div class="select-wrapper">
                    <select name="role" id="role" required>
                        <option value="">-- Chọn vai trò của bạn --</option>
                        <option value="doctor">Bác sĩ</option>
                        <option value="nurse">Y tá</option>
                        <option value="pharmacist">Dược sĩ</option>
                        <option value="receptionist">Lễ tân</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                        <option value="patient">Khách hàng / Bệnh nhân</option>
                    </select>
                </div>
            </div>
            
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="📧 Địa chỉ Email" required>
            </div>
            
            <div class="input-group">
                <input type="text" name="phone" id="phone" placeholder="📞 Số điện thoại" required>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="🔒 Mật khẩu (tối thiểu 8 ký tự)" required>
            </div>
            
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="✅ Xác nhận mật khẩu" required>
            </div>
            
            <button type="submit" class="register-button">Đăng ký tài khoản</button>
        </form>
        @if ($errors->any())
            <div style="background-color:#fee2e2;color:#991b1b;padding:10px;margin-top:15px;border-radius:8px;">
                <ul style="margin:0;list-style:none;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="footer-links">
            <a href="{{ route('login') }}" class="login-link">Đã có tài khoản? **Đăng nhập**</a>
            <a href="{{ route('home') }}">Về trang chủ</a>
        </div>
    </div>

</body>
@if(session('success'))
    <div style="background-color:#d1fae5;color:#065f46;padding:10px;border-radius:8px;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background-color:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;">
        {{ session('error') }}
    </div>
@endif

</html>