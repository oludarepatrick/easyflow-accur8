<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            background: #f9fafb;
            color: #333;
        }
        h3 {
            text-align: center;
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }
        h5 {
            text-align: center;
            margin: 2px 0;
            font-weight: normal;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: #fff;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            border-radius: 6px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: center;
        }
        th {
            background: #3498db;
            color: #fff;
            font-weight: bold;
        }
        tbody tr:nth-child(odd) {
            background: #f9f9f9;
        }
        tbody tr:nth-child(even) {
            background: #eef6fb;
        }
        tfoot td {
            font-weight: bold;
            background: #2ecc71;
            color: #fff;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h3>{{ $school->schoolname ?? 'School Name' }}</h3>
    <h5>{{ $school->email ?? '' }}</h5>
    <h5>Payout List for {{ date("F", mktime(0,0,0,$month,1)) }}</h5>

    @php
        $totalPayout = $staffs->sum(function($staff) {
            return optional($staff->salaries)->gross ?? 0;
        });
    @endphp

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Bank Name</th>
                <th>Account No</th>
                <th>Account Name</th>
                <th>Payout Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staffs as $index => $staff)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $staff->firstname }}</td>
                    <td>{{ $staff->lastname }}</td>
                    <td>{{ $staff->bankDetail->bank_name ?? '---' }}</td>
                    <td>{{ $staff->bankDetail->account_no ?? '---' }}</td>
                    <td>{{ $staff->bankDetail->account_name ?? '---' }}</td>
                    <td>₦{{ number_format(optional($staff->salaries)->gross ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total Payout</td>
                <td>₦{{ number_format($totalPayout, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
