<h3>Staff Salary Statement - {{ $month ? date('F', mktime(0,0,0,$month,1)) : '' }} {{ $year }}</h3>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Staff Name</th>
            <th>Net Pay</th>
            <th>Total Deduction</th>
            <th>Gross Pay</th>
            <th>Month</th>
            <th>Year</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salaries as $salary)
            @php
                $totalDeductions = ($salary->loan_repayment ?? 0) +
                                   ($salary->tax_deduction ?? 0) +
                                   ($salary->social_deduction ?? 0);
            @endphp
            <tr>
                <td>{{ $salary->staff->firstname }} {{ $salary->staff->lastname }}</td>
                <td>₦{{ number_format($salary->net_pay, 2) }}</td>
                <td>₦{{ number_format($totalDeductions, 2) }}</td>
                <td>₦{{ number_format($salary->gross, 2) }}</td>
                <td>{{ date('F', mktime(0,0,0,$salary->month,1)) }}</td>
                <td>{{ $salary->year }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
