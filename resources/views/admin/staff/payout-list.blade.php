@extends('layouts.app')

@section('title', 'Staff Payout List')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Staff Payout List</h4>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <!-- Filter -->
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <select name="month" class="form-select">
                @foreach($months as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ date("F", mktime(0,0,0,$m,1)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Buttons -->
    <div class="mb-3">
        <a href="{{ route('staff.payouts.download', ['month' => $month]) }}" class="btn btn-success me-2">
            <i class="bi bi-download"></i> Download PDF
        </a>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#emailModal">
            <i class="bi bi-envelope"></i> Email Payout List
        </button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
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
                @forelse($staffs as $index => $staff)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $staff->firstname }}</td>
                        <td>{{ $staff->lastname }}</td>
                        <td>{{ $staff->bankDetail->bank_name ?? '---' }}</td>
                        <td>{{ $staff->bankDetail->account_no ?? '---' }}</td>
                        <td>{{ $staff->bankDetail->account_name ?? '---' }}</td>
                        <td>₦{{ number_format(optional($staff->salaries->first())->gross ?? 0, 2) }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No staff payouts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('staff.payouts.email') }}">
      @csrf
      <input type="hidden" name="month" value="{{ $month }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Email Payout List</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <p class="text-warning">Are you sure you want to send this payout list?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
