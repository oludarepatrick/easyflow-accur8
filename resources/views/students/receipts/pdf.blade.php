<div style="text-align:center;">
    <img src="" alt="School Logo" width="80">
    <h2>{{ $school->schoolname }}</h2>
    <p>{{ $school->address }}</p>
    <hr>
</div>

<h3>Student Receipt</h3>
<p><b>Name:</b> {{ $receipt->student->firstname }} {{ $receipt->student->lastname }}</p>
<p><b>Term:</b> {{ $receipt->term }} | <b>Session:</b> {{ $receipt->session }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Total Expected</th>
        <th>Discount</th>
        <th>Amount Paid</th>
        <th>Balance Due</th>
        <th>Last Updated</th>
    </tr>
    <tr>
        <td>{{ number_format($receipt->total_expected, 2) }}</td>
        <td>{{ number_format($receipt->discount, 2) }}</td>
        <td>{{ number_format($receipt->amount_paid, 2) }}</td>
        <td>{{ number_format($receipt->amount_due, 2) }}</td>
        <td>{{ $receipt->updated_at->format('d M Y') }}</td>
    </tr>
</table>
<h4 class="mt-4">Payment History</h4>
<table width="100%" border="1" cellspacing="0" cellpadding="5"> 
    <tr>
        <th>Date</th>
        <th>Amount Paid</th>
        <th>Method</th>
    </tr>
    @foreach($receipt->payments as $payment)
    <tr>
        <td>{{ $payment->payment_date->format('d M Y, h:i A') }}</td>
        <td>{{ number_format($payment->amount_paid, 2) }}</td>
        <td>{{ ucfirst($payment->payment_method) }}</td>
    </tr>
    @endforeach     
</table>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    h2, h3, h4 {
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
    }
    hr {
        border: 0;
        border-top: 1px solid #eee;
        margin: 20px 0;
    }
    .text-center {
        text-align: center;
    }
    .mt-4 {
        margin-top: 1.5rem;
    }
    .mb-0 {
        margin-bottom: 0;
    }
    .fw-bold {
        font-weight: bold;
    }   

    .bg-gradient-primary {
        background: linear-gradient(45deg, #0d6efd, #20c997);
        color: white;
    }

    .text-white {
        color: white;
    }   
    .py-4 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    .p-4 {
        padding: 1.5rem;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
