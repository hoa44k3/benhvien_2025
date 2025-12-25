<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Đơn thuốc {{ $prescription->code }}</title>
  <style>
    body{font-family: DejaVu Sans, sans-serif; font-size:13px;}
    .header{border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:15px;}
    h2 { margin: 0 0 5px 0; }
    .items table{width:100%; border-collapse:collapse; margin-top: 15px;}
    .items th, .items td{border:1px solid #333; padding:8px; text-align:left;}
    .items th { background-color: #f0f0f0; }
  </style>
</head>
<body>
  <div class="header">
    <h2>PHÒNG KHÁM SMARTHOSPITAL</h2>
    <div><strong>Mã đơn:</strong> {{ $prescription->code }}</div>
    <div><strong>Ngày kê:</strong> {{ \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') }}</div>
  </div>

  <div style="margin-bottom: 15px;">
    <strong>Bệnh nhân:</strong> {{ $prescription->patient->name ?? '—' }} <br>
    <strong>Bác sĩ kê đơn:</strong> {{ $prescription->doctor->name ?? '—' }} <br>
    <strong>Chẩn đoán:</strong> {{ $prescription->diagnosis ?? '—' }}
  </div>

  <div class="items">
    <table>
      <thead>
        <tr>
          <th width="5%" style="text-align:center">STT</th>
          <th width="40%">Tên thuốc / Hàm lượng</th>
          <th width="15%" style="text-align:center">ĐVT</th>
          <th width="10%" style="text-align:center">SL</th>
          <th width="30%">Cách dùng</th>
        </tr>
      </thead>
      <tbody>
        @foreach($prescription->items as $i => $item)
        <tr>
          <td style="text-align:center">{{ $i+1 }}</td>
          <td>
              <b>{{ $item->medicine_name }}</b><br>
              <small>{{ $item->strength }}</small>
          </td>
          <td style="text-align:center">{{ $item->unit }}</td>
          <td style="text-align:center; font-weight:bold;">{{ $item->quantity }}</td>
          <td>
              {{ $item->dosage }} <br>
              <i>{{ $item->instruction ?? $item->usage_instruction }}</i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px; font-style: italic;">
    <strong>Ghi chú:</strong> {{ $prescription->note ?? 'Không có ghi chú.' }}
  </div>
  
  <div style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 5px;">
    <em>* Đơn thuốc này có giá trị mua thuốc tại các nhà thuốc trên toàn quốc.</em>
  </div>

  <div style="position:fixed; bottom:50px; right:20px; text-align:center">
    <div>Ngày ..... tháng ..... năm 20...</div>
    <div><strong>Bác sĩ điều trị</strong></div>
    <div style="margin-top:60px;">{{ $prescription->doctor->name ?? '' }}</div>
  </div>
</body>
</html>