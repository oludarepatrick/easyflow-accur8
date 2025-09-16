@extends('layouts.app')

@section('title', 'Active Students')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Active Students ({{ $currentSession }} - {{ $currentTerm }})</h3>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('students.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="form-control" placeholder="Search by name or username...">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Create</th>
                    <th>View</th>

                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->firstname }}</td>
                        <td>{{ $student->lastname }}</td>
                        <td>{{ $student->class }}</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>

                         <!-- Edit Button -->
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                       
                    </td>
                    <td>
                         <!-- Delete Button -->
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                    <td>
                        <!-- Create receipt Button -->
                            <a href="{{ route('students.receipts.create', $student->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Create
                            </a>
                    </td>

                    <td>
                       @if($student->activeReceipt)
                        <a href="{{ route('students.receipts.show', $student->activeReceipt->id) }}" 
                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-receipt"></i> View
                        </a>

                        <!-- Info icon -->
                        @php
                            $balance = $student->activeReceipt->amount_due ?? 0;
                            $isPaid = $balance <= 0;
                        @endphp
                        <i class="bi bi-info-circle-fill ms-2"
                        style="cursor: pointer; color: {{ $isPaid ? 'green' : 'red' }};"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="{{ $isPaid ? 'Fully Paid: ₦' . number_format($student->activeReceipt->amount_paid, 2) : 'Balance: ₦' . number_format($balance, 2) }}">
                        </i>
                    @else
                        <span class="badge bg-warning text-dark">No Receipt</span>
                    @endif

                    
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $students->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
