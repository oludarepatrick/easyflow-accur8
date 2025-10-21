@extends('layouts.app')

@section('title', 'Staff Salary Statement Secondary')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Staff Salary Statement Secondary</h4>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="card-body">
        <form method="GET" action="{{ route('staff.salary.statement_sec') }}" class="form-inline mb-3">
            <select name="month" class="form-control mr-2">
                <option value="">-- Select Month --</option>
                @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>

            <select name="year" class="form-control mr-2">
                <option value="">-- Select Year --</option>
                @for ($y=2024; $y<=2030; $y++)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <div class="mb-3">
            <a href="{{ route('staff.salary.statement.download_sec', request()->all()) }}" class="btn btn-success">Download PDF</a>
            <!-- Trigger button -->
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#sendStatementModal">
                Send Statement
            </button>
        </div>

        <table class="table table-bordered">
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
                @forelse($salaries as $salary)
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
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No records found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sendStatementModal" tabindex="-1" aria-labelledby="sendStatementLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('staff.salary.statement.email_sec') }}">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="sendStatementLabel">Send Staff Salary Statement</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label for="firstname">Firstname</label>
                <input type="text" name="firstname" id="firstname" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="email">Recipient Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
              </div>
              <input type="hidden" name="month" value="{{ $month ?? request('month') }}">
              <input type="hidden" name="year" value="{{ $year ?? request('year') }}">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary btn-send">Send</button>
            </div>
          </div>
        </form>
      </div>
    </div>  
</div>

<!-- SweetAlert confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-send").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to send this statement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest("form").submit();
                }
            });
        });
    });
});
</script>
@endsection
