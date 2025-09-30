@extends('layouts.app')

@section('title', 'Owing Students Report')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Owing Students Report</h3>

    <!-- Filter -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="term" class="form-select">
                <option value="">-- Select Term --</option>
                @foreach($terms as $t)
                    <option value="{{ $t }}" @if(request('term') == $t) selected @endif>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="session" class="form-select">
                <option value="">-- Select Session --</option>
                @foreach($sessions as $s)
                    <option value="{{ $s }}" @if(request('session') == $s) selected @endif>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
        <div class="col-md-3">
            <a href="{{ request()->fullUrlWithQuery(['download' => 'pdf']) }}" class="btn btn-danger w-100">
                <i class="bi bi-file-earmark-pdf"></i> Download PDF
            </a>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
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
                @forelse($receipts as $receipt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ optional($receipt->student)->firstname }} {{ optional($receipt->student)->lastname }}</td>
                        <td>{{ optional($receipt->student)->class }}</td>
                        <td>{{ $receipt->term }}</td>
                        <td>{{ $receipt->session }}</td>
                        <td>₦{{ number_format($receipt->total_expected) }}</td>
                        <td class="text-success">₦{{ number_format($receipt->amount_paid) }}</td>
                        <td class="text-danger fw-bold">₦{{ number_format($receipt->amount_due) }}</td>
                        <td>
                            @if($receipt->payments->isNotEmpty() && $receipt->payments->last()->payment_date)
                                {{ \Carbon\Carbon::parse($receipt->payments->last()->payment_date)->format('d M Y') }}
                            @else
                                <span class="badge bg-warning">No Payment</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No owing students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pie Chart -->
    <div class="mt-4" style="max-width: 500px; margin: 0 auto;">
    <canvas id="owingChart" style="max-height: 300px;"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('owingChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Total Paid', 'Total Debt'],
        datasets: [{
            data: [{{ $totalPaid }}, {{ $totalDebt }}],
            backgroundColor: ['#28a745', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
@endsection
