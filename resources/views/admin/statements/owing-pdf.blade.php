<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; }
        .watermark { position: fixed; top: 40%; left: 20%; opacity: 0.1; font-size: 80px; transform: rotate(-30deg); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $school->schoolname ?? 'School Name' }}</h2>
        <h4>Owing Students Report ({{ $term ?? 'All Terms' }} - {{ $session ?? 'All Sessions' }})</h4>
    </div>

    <div class="watermark">{{ $school->schoolname ?? 'School' }}</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Class</th>
                <th>Term</th>
                <th>Session</th>
                <th>Expected Fees</th>
                <th>Amount Paid</th>
                <th>Amount Due</th>
                <th>Last Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipts as $receipt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $receipt->student->firstname }} {{ $receipt->student->lastname }}</td>
                    <td>{{ $receipt->student->class }}</td>
                    <td>{{ $receipt->term }}</td>
                    <td>{{ $receipt->session }}</td>
                    <td>₦{{ number_format($receipt->total_expected) }}</td>
                    <td>₦{{ number_format($receipt->amount_paid) }}</td>
                    <td>₦{{ number_format($receipt->amount_due) }}</td>
                    <td>
                        @if($receipt->payments->isNotEmpty())
                            {{ $receipt->payments->last()->payment_date->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
