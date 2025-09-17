<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { text-align:center; margin-bottom: 10px; }
    .watermark {
      position: fixed;
      top: 30%;
      left: 10%;
      width: 80%;
      opacity: 0.08;
      z-index: 0;
    }
    table { width:100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; text-align:left; }
    .title { font-size: 25px; font-weight: 700; color: #990814ff; }
    .address { font-size: 15px; font-weight: 400; color: #4a07c5ff; }
    .right { text-align: right; }
  </style>
</head>
<body>
    <img class="watermark"  src="{{ asset('favi1.png') }}" alt="App Logo" class="mx-auto h-16 w-auto">
 

  <div class="header">
    <div class="title">{{ $school->schoolname ?? 'School Name' }}</div>
    <div class="address">{{ $school->address ?? '' }}</div>
    <div style="margin-top:8px;">Student Payments Statement</div>
    <div style="margin-top:6px;">Generated: {{ now()->format('d M Y, h:i A') }}</div>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Student</th>
        <th>Class</th>
        <th>Term</th>
        <th>Session</th>
        <th>Amount</th>
        <th>Method</th>
      </tr>
    </thead>
    <tbody>
      @foreach($payments as $idx => $p)
      <tr>
        <td>{{ $idx + 1 }}</td>
        <td>{{ optional($p->payment_date)->format('d M Y, h:i A') }}</td>
        <td>{{ $p->receipt->student->firstname }} {{ $p->receipt->student->lastname }}</td>
        <td>{{ $p->receipt->student->class }}</td>
        <td>{{ $p->receipt->term }}</td>
        <td>{{ $p->receipt->session }}</td>
        <td class="right">₦{{ number_format($p->amount_paid, 2) }}</td>
        <td>{{ ucfirst($p->payment_method) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="6" class="right"><strong>Total</strong></td>
        <td class="right"><strong>₦{{ number_format($total, 2) }}</strong></td>
        <td></td>
      </tr>
    </tbody>
  </table>
</body>
</html>
