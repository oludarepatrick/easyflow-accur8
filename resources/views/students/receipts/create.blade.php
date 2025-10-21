@extends('layouts.app')
@section('title', 'Create Receipt')
@section('content')
<div class="container">
    <h4>Create Receipt for {{ $student->firstname }} {{ $student->lastname }}</h4>

    <form method="POST" action="{{ route('students.receipts.store', $student->id) }}">
        @csrf

        <div class="mb-3">
            <label>Total Expected (Auto from fees setup)</label>
            <input type="text" name="total_expected" class="form-control" value="{{ $expectedFees->total ?? 0 }}" >
        </div>

        <div class="mb-3">
            <label>Tuition</label>
            <input type="number" name="tuition" class="form-control calc-field" required>
        </div>

        <div class="mb-3">
            <label>Uniform</label>
            <input type="number" name="uniform" class="form-control calc-field" required>
        </div>

        <div class="mb-3">
            <label>Exam Fee</label>
            <input type="number" name="exam_fee" class="form-control calc-field" required>
        </div>

        <div class="mb-3">
            <label>Discount</label>
            <input type="number" name="discount" class="form-control calc-field" value="0">
        </div>

        <div class="mb-3">
            <label>Amount Paid</label>
            <input type="number" name="amount_paid" class="form-control" required readonly>
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

{{-- Calculation Script --}}
<script>
    function calculateAmountPaid() {
        let tuition = parseFloat(document.querySelector('[name="tuition"]').value) || 0;
        let uniform = parseFloat(document.querySelector('[name="uniform"]').value) || 0;
        let exam    = parseFloat(document.querySelector('[name="exam_fee"]').value) || 0;
        let discount = parseFloat(document.querySelector('[name="discount"]').value) || 0;

        let total = (tuition + uniform + exam) - discount;
        document.querySelector('[name="amount_paid"]').value = total >= 0 ? total : 0;
    }

    // Attach event listeners
    document.querySelectorAll('.calc-field').forEach(el => {
        el.addEventListener('input', calculateAmountPaid);
    });
</script>
@endsection
