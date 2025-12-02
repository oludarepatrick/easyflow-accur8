<!DOCTYPE html>
<html>
<head>
    <title>Owing Report</title>
    <style>
        @page {
            size: landscape;
            margin: 10px;
        }

        body { 
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header { 
            text-align: center; 
            color: #005f99; /* deep sky blue */
        }

        .watermark { 
            position: fixed; 
            top: 40%; 
            left: 25%; 
            opacity: 0.06; 
            font-size: 120px; 
            transform: rotate(-30deg); 
            color: #ff4d4d; /* soft red */ 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }

        th { 
            background: #b3e0ff; /* light sky blue */
            color: #333; 
            border: 1px solid #333; 
            padding: 6px; 
            font-weight: bold;
        }

        td {
            border: 1px solid #333; 
            padding: 6px; 
            text-align: center;
        }

        .amount-due { 
            color: #cc0000; /* red emphasis */ 
            font-weight: bold; 
        }

        .amount-paid { 
            color: #008000; 
            font-weight: bold; 
        }
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
                <th>Amt Paid</th>
                <th>Amt Due</th>
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
                    <td class="amount-paid">₦{{ number_format($receipt->amount_paid) }}</td>
                    <td class="amount-due">₦{{ number_format($receipt->amount_due) }}</td>
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
