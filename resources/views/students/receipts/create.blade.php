@extends('layouts.app')
@section('title', 'Create Receipt')
@section('content')
<div class="container">
    <h4>Create Receipt for {{ $student->firstname }} {{ $student->lastname }}</h4>

    <form method="POST" action="{{ route('students.receipts.store', $student->id) }}">
        @csrf

        <div class="mb-3">
            <label>Total Expected (Auto from fees setup)</label>
            <input type="text" name="total_expected" class="form-control" value="{{ $expectedFees->total ?? 0 }}" readonly>
        </div>

        <div class="mb-3">
            <label>Tuition</label>
            <input type="number" name="tuition" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Uniform</label>
            <input type="number" name="uniform" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Exam Fee</label>
            <input type="number" name="exam_fee" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Discount</label>
            <input type="number" name="discount" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>Amount Paid</label>
            <input type="number" name="amount_paid" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control" required>
                <option value="">-- Select Payment Method --</option>
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>


        <button type="submit" class="btn btn-success">Generate Receipt</button>
    </form>
</div>
@endsection
