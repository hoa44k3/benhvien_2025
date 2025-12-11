<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Đơn thuốc {{ $prescription->code }}</title>
  <style>
    body{font-family: DejaVu Sans, sans-serif;font-size:12px;}
    .header{border-bottom:1px solid #000;padding-bottom:10px;margin-bottom:10px;}
    .items table{width:100%;border-collapse:collapse;}
    .items th, .items td{border:1px solid #000;padding:6px;text-align:left;}
  </style>
</head>
<body>
  <div class="header">
    <h2>Bệnh viện / Phòng khám</h2>
    <div>Mã đơn: {{ $prescription->code }}</div>
    <div>Ngày: {{ \Carbon\Carbon::parse($prescription->date ?? $prescription->created_at)->format('d/m/Y') }}</div>
  </div>

  <div>
    <strong>Bệnh nhân:</strong> {{ $prescription->patient->name ?? '—' }} <br>
    <strong>Bác sĩ:</strong> {{ $prescription->doctor->name ?? '—' }} <br>
    <strong>Chẩn đoán:</strong> {{ $prescription->diagnosis ?? '—' }}
  </div>

  <div class="items" style="margin-top:12px;">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Tên thuốc</th>
          <th>Liều</th>
          <th>Số lượng</th>
          <th>Hướng dẫn</th>
        </tr>
      </thead>
      <tbody>
        @foreach($prescription->items as $i => $item)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $item->medicine_name }}</td>
          <td>{{ $item->dosage ?? '—' }}</td>
          <td>{{ $item->quantity ?? 1 }}</td>
          <td>{{ $item->instruction ?? '' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px;">
    <strong>Ghi chú:</strong> {{ $prescription->note ?? '—' }}
  </div>

  <div style="position:fixed;bottom:30px;right:40px;text-align:center">
    <div>Người kê đơn</div>
    <div style="margin-top:45px;">(Ký, ghi rõ họ tên)</div>
  </div>
</body>
</html>
