<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 13px; 
            margin: 0; 
            padding: 0; 
            background: #f9f9f9;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(90deg, #ffcc00, #007bff, #dc3545, #6f42c1);
        }
        .school-name {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #444;
            margin-top: 10px;
        }
        .details {
            padding: 15px;
        }
        .details p {
            margin: 5px 0;
            color: #333;
        }
        table {
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        th, td { 
            padding: 10px; 
            border: 1px solid #ddd; 
            text-align: left; 
        }
        th {
            background: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .summary {
            margin: 20px 15px;
            padding: 15px;
            border-radius: 8px;
            background: linear-gradient(90deg, #ffefba, #ffffff);
            box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
        }
        .summary p {
            margin: 8px 0;
            font-weight: bold;
            color: #444;
        }
        .status-paid {
            color: green;
        }
        .status-pending {
            color: orange;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Payslip - {{ date("F", mktime(0,0,0,$salary->month,1)) }} {{ $salary->year }}
        </div>

        <div class="school-name">
            {{ $school->schoolname ?? 'School Name Here' }}
        </div>

        <div class="details">
            <p><strong>Staff:</strong> {{ $staff->firstname }} {{ $staff->lastname }}</p>
            <p><strong>Email:</strong> {{ $staff->email }}</p>
        </div>

        <table>
            <tr><th>Basic</th><td>₦{{ number_format($salary->basic, 2) }}</td></tr>
            <tr><th>Bonus</th><td>₦{{ number_format($salary->bonus, 2) }}</td></tr>
            <tr><th>Lesson Amount</th><td>₦{{ number_format($salary->lesson_amount, 2) }}</td></tr>
            <tr><th>Health Allowance</th><td>₦{{ number_format($salary->health, 2) }}</td></tr>
            <tr><th>Loan Repayment</th><td>₦{{ number_format($salary->loan_repayment, 2) }}</td></tr>
            <tr><th>Tax</th><td>₦{{ number_format($salary->tax_deduction, 2) }}</td></tr>
            <tr><th>Social</th><td>₦{{ number_format($salary->social_deduction, 2) }}</td></tr>
        </table>

        @php
            $totalDeductions = ($salary->loan_repayment ?? 0)
                            + ($salary->tax_deduction ?? 0)
                            + ($salary->social_deduction ?? 0);
        @endphp

        <div class="summary">
            <p><strong>Net Pay:</strong> ₦{{ number_format($salary->net_pay, 2) }}</p>
            <p><strong>Deductions:</strong> ₦{{ number_format($totalDeductions, 2) }}</p>
            <p><strong>Gross Pay:</strong> ₦{{ number_format($salary->gross, 2) }}</p>
            <p><strong>Status:</strong> 
                <span class="status-{{ $salary->status == 'paid' ? 'paid' : 'pending' }}">
                    {{ ucfirst($salary->status) }}
                </span>
            </p>
            <p><strong>Date Paid:</strong> {{ $salary->date_paid ?? '---' }}</p>
        </div>
    </div>
</body>
</html>
