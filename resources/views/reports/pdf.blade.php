<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo Tồn kho Thuốc</title>
    <style>
        /* Cấu hình Font chữ hỗ trợ tiếng Việt cho DomPDF */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Header thông tin phòng khám */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }
        .header-table td {
            border: none;
            vertical-align: top;
        }
        .company-info {
            font-size: 13px;
        }
        .company-name {
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        /* Tiêu đề báo cáo */
        .report-title {
            text-align: center;
            margin: 20px 0;
        }
        .report-title h1 {
            margin: 0;
            text-transform: uppercase;
            font-size: 20px;
        }
        .report-date {
            text-align: center;
            font-style: italic;
            margin-top: 5px;
        }

        /* Bảng dữ liệu chính */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #333;
            padding: 6px;
        }
        .main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }
        .main-table td {
            font-size: 11px;
        }

        /* Các lớp tiện ích căn chỉnh */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-danger { color: red; }

        /* Phần tổng kết và chữ ký */
        .summary-section {
            margin-top: 10px;
            text-align: right;
            font-size: 13px;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border: none;
            text-align: center;
        }
        .signature-table td {
            border: none;
        }
        .signature-title {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    {{-- 1. Header: Thông tin phòng khám & Thời gian --}}
    <table class="header-table">
        <tr>
            <td width="60%">
                <div class="company-info">
                    <div class="company-name">PHÒNG KHÁM ĐA KHOA TRỰC TUYẾN</div>
                    <div>Địa chỉ: Số 123, Đường Lê Mao, TP. Vinh, Nghệ An</div>
                    <div>Hotline: 0987.654.321 | Email: contact@phongkham.com</div>
                </div>
            </td>
            <td width="40%" class="text-right">
                <div>Mẫu số: 01-BCTK</div>
                <div>Ngày in: {{ date('d/m/Y H:i') }}</div>
                <div>Người lập: {{ auth()->check() ? auth()->user()->name : 'Admin' }}</div>
            </td>
        </tr>
    </table>

    {{-- 2. Tiêu đề báo cáo --}}
    <div class="report-title">
        <h1>BÁO CÁO TỒN KHO THUỐC & VẬT TƯ</h1>
        <div class="report-date">(Tính đến ngày {{ date('d/m/Y') }})</div>
    </div>

    {{-- 3. Bảng dữ liệu --}}
    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th width="12%">Mã thuốc</th>
                <th width="25%">Tên thuốc / Hoạt chất</th>
                <th width="10%">Phân loại</th>
                <th width="8%">Đơn vị</th>
                <th width="8%">SL Tồn</th>
                <th width="10%">Giá vốn (VNĐ)</th>
                <th width="12%">Thành tiền (VNĐ)</th>
                <th width="10%">Hạn dùng</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalStockValue = 0;
                $count = 0;
            @endphp

            @foreach($data as $key => $item)
            @php
                $count++;
                $price = $item->price ?? 0;
                $stock = $item->stock ?? 0;
                $rowTotal = $price * $stock;
                $totalStockValue += $rowTotal;

                // Kiểm tra hạn sử dụng để bôi đỏ nếu cần
                $isExpired = $item->expiry_date && \Carbon\Carbon::parse($item->expiry_date)->isPast();
            @endphp
            <tr>
                <td class="text-center">{{ $count }}</td>
                <td class="text-center">{{ $item->code }}</td>
                <td>
                    <span class="font-bold">{{ $item->name }}</span>
                    @if($isExpired) <br><small class="text-danger">(Đã hết hạn)</small> @endif
                </td>
                <td class="text-center">{{ $item->category }}</td>
                <td class="text-center">{{ $item->unit }}</td>
                
                {{-- Cảnh báo tồn kho thấp --}}
                <td class="text-center {{ $stock <= ($item->min_stock ?? 10) ? 'font-bold' : '' }}">
                    {{ number_format($stock) }}
                </td>
                
                <td class="text-right">{{ number_format($price, 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($rowTotal, 0, ',', '.') }}</td>
                
                <td class="text-center">
                    {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @endforeach
            
            {{-- Dòng tổng cộng (Nếu không có dữ liệu thì không hiện) --}}
            @if($count > 0)
            <tr style="background-color: #f9f9f9;">
                <td colspan="7" class="text-right font-bold text-uppercase">Tổng giá trị tồn kho:</td>
                <td class="text-right font-bold" style="font-size: 13px;">{{ number_format($totalStockValue, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            @else
            <tr>
                <td colspan="9" class="text-center">Không có dữ liệu thuốc.</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- 4. Tổng hợp thông tin --}}
    <div class="summary-section">
        <div><strong>Tổng số mặt hàng:</strong> {{ $count }} loại thuốc</div>
        <div><strong>Tổng tiền bằng chữ:</strong> 
            {{-- Chỗ này bạn có thể dùng thư viện đọc số thành chữ nếu cần, tạm thời để trống hoặc điền thủ công --}}
            ........................................................................................
        </div>
    </div>

    {{-- 5. Chữ ký --}}
    <table class="signature-table">
        <tr>
            <td width="33%">
                <div class="signature-title">Người lập biểu</div>
                <div style="font-style: italic;">(Ký, ghi rõ họ tên)</div>
                <br><br><br><br>
                <div>{{ auth()->check() ? auth()->user()->name : '.....................' }}</div>
            </td>
            <td width="33%">
                <div class="signature-title">Thủ kho</div>
                <div style="font-style: italic;">(Ký, ghi rõ họ tên)</div>
                <br><br><br><br>
            </td>
            <td width="33%">
                <div class="signature-title">Giám đốc</div>
                <div style="font-style: italic;">(Ký, đóng dấu)</div>
                <br><br><br><br>
            </td>
        </tr>
    </table>

</body>
</html>