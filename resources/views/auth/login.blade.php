<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Thật Đẹp | Tên Ứng Dụng Của Bạn</title>
    
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6; /* Màu nền nhẹ nhàng */
            display: flex;
            justify-content: center; /* Căn giữa ngang */
            align-items: center; /* Căn giữa dọc */
            height: 100vh;
            margin: 0;
        }

        /* Container chính của form đăng nhập (Card) */
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px; /* Bo góc mềm mại */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Hiệu ứng đổ bóng 3D nổi bật */
            width: 100%;
            max-width: 400px; /* Giới hạn chiều rộng */
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .login-container:hover {
            transform: translateY(-5px); /* Hiệu ứng nhấc nhẹ khi rê chuột */
        }

        /* Tiêu đề & Mô tả */
        .login-container h1 {
            color: #1e3a8a; /* Màu xanh đậm cho thương hiệu */
            margin-bottom: 5px;
            font-size: 2em;
        }

        .login-container p {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 1em;
        }

        /* Nhóm Input */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        /* Nhãn (Label) */
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600; /* Đậm vừa */
            color: #374151;
            font-size: 0.9em;
        }

        /* Ô Input (email, password) */
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-group input:focus {
            border-color: #3b82f6; /* Màu xanh khi focus */
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); /* Hiệu ứng sáng */
        }

        /* Nút Đăng nhập */
        .login-button {
            width: 100%;
            padding: 15px;
            background-color: #3b82f6; /* Màu xanh dương hiện đại */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .login-button:hover {
            background-color: #2563eb; /* Tối hơn khi hover */
            transform: translateY(-2px);
        }

        /* Liên kết cuối trang */
        .footer-links {
            margin-top: 25px;
            font-size: 0.9em;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: #4b5563; /* Màu xám đậm */
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #3b82f6;
            text-decoration: underline;
        }

        /* Định dạng riêng cho liên kết Đăng ký */
        .footer-links .register-link {
            color: #3b82f6;
            font-weight: bold;
        }

        .footer-links .register-link:hover {
            color: #2563eb;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>Đăng nhập 🚀</h1>
        <p>Chào mừng trở lại! Vui lòng nhập thông tin của bạn</p>

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Ví dụ: email@congty.com" required>
            </div>
            
            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            </div>
            
            <button type="submit" class="login-button">Đăng nhập ngay</button>
        </form>

        <div class="footer-links">
            <a href="{{ route('register') }}" class="register-link">Bạn chưa có tài khoản? **Đăng ký**</a>
            <a href="#">Quên mật khẩu?</a>
        </div>
        <a href="{{ route('home') }}">Về trang chủ</a>
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