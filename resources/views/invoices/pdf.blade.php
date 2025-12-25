<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn #{{ $invoice->code }}</title>
    <style>
        /* Cấu hình Font chữ hỗ trợ tiếng Việt cho DomPDF */
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            line-height: 1.4; 
            color: #333;
        }
        
        /* Layout chung */
        .container { width: 100%; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        
        /* Header */
        .header-table td { vertical-align: top; }
        .company-info { font-size: 10px; }
        .company-name { font-size: 14px; font-weight: bold; text-transform: uppercase; color: #0056b3; margin-bottom: 5px; display: block;}
        .invoice-title { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #dc3545; margin-top: 10px; }
        
        /* Thông tin chung */
        .meta-box { margin-top: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .meta-table td { padding: 3px 0; }
        .label { font-weight: bold; width: 120px; }

        /* Bảng dịch vụ */
        .items-table { margin-top: 20px; border: 1px solid #000; }
        .items-table th { background-color: #f8f9fa; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold; }
        .items-table td { border: 1px solid #000; padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }

        /* Tổng kết */
        .summary-section { margin-top: 15px; text-align: right; }
        .amount-words { font-style: italic; margin-top: 5px; font-weight: bold; color: #555; }

        /* Chữ ký */
        .signature-table { margin-top: 40px; text-align: center; }
        .signature-title { font-weight: bold; text-transform: uppercase; }
        .signature-note { font-style: italic; font-size: 9px; }
        .signature-space { height: 80px; }

        /* Trạng thái */
        .stamp-box {
            border: 2px solid;
            padding: 5px 10px;
            font-weight: bold;
            display: inline-block;
            transform: rotate(-10deg);
            margin-top: 10px;
        }
        .paid { border-color: green; color: green; }
        .unpaid { border-color: red; color: red; }
    </style>
</head>
<body>

    <div class="container">
        {{-- 1. HEADER: THÔNG TIN PHÒNG KHÁM & TIÊU ĐỀ --}}
        <table class="header-table">
            <tr>
                <td width="60%">
                    <span class="company-name">PHÒNG KHÁM ĐA KHOA SMARTHOSPITAL</span>
                    <div class="company-info">
                        Địa chỉ: 123 Đường Nguyễn Văn Cừ, Quận 5, TP.HCM<br>
                        Hotline: 1900 888 999 | Email: contact@smarthospital.com<br>
                        Website: www.smarthospital.com<br>
                        Mã số thuế: 0312345678
                    </div>
                </td>
                <td width="40%" class="text-right">
                    <div class="invoice-title">HÓA ĐƠN DỊCH VỤ</div>
                    <div>Số: <strong>{{ $invoice->code }}</strong></div>
                    <div>Ngày: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</div>
                    
                    {{-- Dấu đóng mộc trạng thái --}}
                    @if($invoice->status == 'paid')
                        <div class="stamp-box paid">ĐÃ THANH TOÁN</div>
                    @else
                        <div class="stamp-box unpaid">CHƯA THANH TOÁN</div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- 2. THÔNG TIN KHÁCH HÀNG --}}
        <div class="meta-box">
            <table class="meta-table">
                <tr>
                    <td class="label">Khách hàng:</td>
                    <td class="text-bold">{{ $invoice->user->name ?? 'Khách vãng lai' }}</td>
                    <td class="label">Mã bệnh nhân:</td>
                    <td>BN-{{ str_pad($invoice->user_id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="label">Địa chỉ:</td>
                    <td>{{ $medical_record->user->address ?? $invoice->user->address ?? '---' }}</td>
                    <td class="label">Điện thoại:</td>
                    <td>{{ $invoice->user->phone ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="label">Lý do khám:</td>
                    <td colspan="3">{{ $invoice->note ?? 'Khám chữa bệnh theo yêu cầu' }}</td>
                </tr>
            </table>
        </div>

        {{-- 3. CHI TIẾT DỊCH VỤ --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="45%">Tên dịch vụ / Nội dung</th>
                    <th width="10%">ĐVT</th>
                    <th width="10%">SL</th>
                    <th width="15%">Đơn giá</th>
                    <th width="15%">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $item->item_name }}
                        @if($item->item_type == 'medicine') 
                            <br><small><i>(Thuốc/Dược phẩm)</i></small> 
                        @endif
                    </td>
                    <td class="text-center">Lần</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                {{-- Dòng trống để bảng đẹp hơn nếu ít item --}}
                @for($i = 0; $i < (5 - count($invoice->items)); $i++)
                <tr>
                    <td style="color:white">.</td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                @endfor
            </tbody>
        </table>

        {{-- 4. TỔNG KẾT TÀI CHÍNH --}}
        <div class="summary-section">
            <table style="width: 50%; float: right;">
                <tr>
                    <td class="text-right label">Cộng tiền hàng:</td>
                    <td class="text-right">{{ number_format($invoice->total, 0, ',', '.') }} đ</td>
                </tr>
                <tr>
                    <td class="text-right label">Thuế GTGT (0%):</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td class="text-right label" style="font-size: 13px; color: #0056b3;">TỔNG THANH TOÁN:</td>
                    <td class="text-right" style="font-size: 13px; font-weight: bold; color: #dc3545;">
                        {{ number_format($invoice->total, 0, ',', '.') }} đ
                    </td>
                </tr>
            </table>
            <div style="clear: both;"></div>
            
            {{-- Số tiền bằng chữ (Tạm thời fix cứng hoặc dùng thư viện ngoài, ở đây hiển thị text mẫu) --}}
            <div class="amount-words">
                (Số tiền bằng chữ: ....................................................................................................... đồng chẵn./.)
            </div>
        </div>

        {{-- 5. THÔNG TIN CHUYỂN KHOẢN (Chỉ hiện khi chưa thanh toán) --}}
        @if($invoice->status != 'paid')
        <div style="margin-top: 20px; border: 1px dashed #333; padding: 10px; background-color: #f9f9f9;">
            <strong>THÔNG TIN CHUYỂN KHOẢN:</strong><br>
            Ngân hàng: <strong>Vietcombank</strong> - Chi nhánh TP.HCM<br>
            Số tài khoản: <strong>0071000123456</strong><br>
            Chủ tài khoản: <strong>PHONG KHAM SMARTHOSPITAL</strong><br>
            Nội dung: <strong>TT HD {{ $invoice->code }}</strong>
        </div>
        @else
        <div style="margin-top: 20px;">
            <i>Phương thức thanh toán: {{ strtoupper($invoice->payment_method ?? 'Tiền mặt') }}</i><br>
            <i>Ngày thanh toán: {{ \Carbon\Carbon::parse($invoice->paid_at)->format('d/m/Y H:i:s') }}</i>
        </div>
        @endif

        {{-- 6. CHỮ KÝ --}}
        <table class="signature-table">
            <tr>
                <td width="33%">
                    <div class="signature-title">NGƯỜI LẬP PHIẾU</div>
                    <div class="signature-note">(Ký, ghi rõ họ tên)</div>
                    <div class="signature-space"></div>
                    <div>Admin System</div>
                </td>
                <td width="33%">
                    <div class="signature-title">NGƯỜI NỘP TIỀN</div>
                    <div class="signature-note">(Ký, ghi rõ họ tên)</div>
                    <div class="signature-space"></div>
                    <div>{{ $invoice->user->name }}</div>
                </td>
                <td width="33%">
                    <div class="signature-title">THỦ QUỸ / GIÁM ĐỐC</div>
                    <div class="signature-note">(Ký, đóng dấu)</div>
                    <div class="signature-space"></div>
                </td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 30px; font-size: 10px; font-style: italic;">
            (Cảm ơn quý khách đã tin tưởng sử dụng dịch vụ của SmartHospital)
        </div>
    </div>

</body>
</html>