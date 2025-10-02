@extends('layouts.app')

@section('title', 'Clerk Dashboard')

@section('content')

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="h3">{{ Auth::guard('admin')->user()->name }}</h4>
    </div>

    <div class="row g-3 mb-4">
        <!-- Active Students -->
        <div class="col-md-4">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <h6 class="card-title text-primary">Active Students</h6>
                    <h2 class="mt-3 mb-0">{{ number_format($activeStudents) }}</h2>
                </div>
            </div>
        </div>

        <!-- Today’s Collections -->
        <div class="col-md-4">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h6 class="card-title text-success">Today’s Collections</h6>
                    <h2 class="mt-3 mb-0">₦{{ number_format($todaysCollections, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="col-md-4">
            <div class="card h-100 border-warning">
                <div class="card-body">
                    <h6 class="card-title text-warning">Outstanding Balance</h6>
                    <h2 class="mt-3 mb-0">₦{{ number_format($outstandingBalance, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Recent Payments</h5>
                    <div class="list-group list-group-flush">
                        @foreach($recentInvoices as $invoice)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $invoice->student->firstname ?? 'Unknown' }}</h6>
                                                <small>{{ $invoice->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">Term {{ ucfirst($invoice->term) }} Fees</p>
                                            <small class="text-success">#{{ number_format($invoice->amount_paid, 2) }}</small>
                                        </div>
                         @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <a href="{{ route('students.index') }}" class="btn btn-primary w-100 mb-2">View Students</a>
                    <a href="" class="btn btn-success w-100 mb-2">Record Payment</a>
                    <a href="" class="btn btn-info w-100">View Receipts</a>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
