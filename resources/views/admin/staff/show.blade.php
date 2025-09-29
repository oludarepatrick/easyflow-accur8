@extends('layouts.app')

@section('title', 'Staff Details')

@section('content')
<div class="container my-4">
    <!-- Staff Header Card -->
    <div class="card shadow-lg border-0 mb-4">
    <div class="card-body bg-primary text-white rounded-top">
        <h3 class="fw-bold mb-0">
            {{ $staff->firstname }} {{ $staff->lastname }}
        </h3>
        <p class="mb-0">
            <i class="fas fa-envelope me-2"></i>{{ $staff->email }}
        </p>
        <p class="mb-0">
            <i class="fas fa-phone me-2"></i>{{ $staff->phone ?? 'No phone number' }}
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="staffTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                <i class="fas fa-user me-1"></i> Profile
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                <i class="fas fa-university me-1"></i> Account Details
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary" type="button" role="tab">
                <i class="fas fa-money-bill-wave me-1"></i> Salary
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-4" id="staffTabContent">
        <!-- Profile -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.updateProfile', $staff->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" name="firstname" value="{{ $staff->firstname }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" name="lastname" value="{{ $staff->lastname }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" value="{{ $staff->email }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" name="phone" value="{{ $staff->phone }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Class</label>
                                <select name="class" class="form-select">
                                    <option value="">-- Select Class --</option>
                                    <option value="ADMIN" {{ $staff->class == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                                    <option value="SUPPORT STAFF" {{ $staff->class == 'SUPPORT STAFF' ? 'selected' : '' }}>SUPPORT STAFF</option>
                                    <option value="CRECHE" {{ $staff->class == 'CRECHE' ? 'selected' : '' }}>CRECHE</option>
                                    <option value="PREP" {{ $staff->class == 'PREP' ? 'selected' : '' }}>PREP</option>
                                    <option value="KG 1" {{ $staff->class == 'KG 1' ? 'selected' : '' }}>KG 1</option>
                                    <option value="KG 2" {{ $staff->class == 'KG 2' ? 'selected' : '' }}>KG 2</option>
                                    <option value="NURSERY 1" {{ $staff->class == 'NURSERY 1' ? 'selected' : '' }}>NURSERY 1</option>
                                    <option value="NURSERY 2" {{ $staff->class == 'NURSERY 2' ? 'selected' : '' }}>NURSERY 2</option>
                                    <option value="GRADE 1" {{ $staff->class == 'GRADE 1' ? 'selected' : '' }}>GRADE 1</option>
                                    <option value="GRADE 2" {{ $staff->class == 'GRADE 2' ? 'selected' : '' }}>GRADE 2</option>
                                    <option value="GRADE 3" {{ $staff->class == 'GRADE 3' ? 'selected' : '' }}>GRADE 3</option>
                                    <option value="GRADE 4" {{ $staff->class == 'GRADE 4' ? 'selected' : '' }}>GRADE 4</option>
                                    <option value="GRADE 5" {{ $staff->class == 'GRADE 5' ? 'selected' : '' }}>GRADE 5</option>
                                    <option value="JSS 1" {{ $staff->class == 'JSS 1' ? 'selected' : '' }}>JSS 1</option>
                                    <option value="JSS 2" {{ $staff->class == 'JSS 2' ? 'selected' : '' }}>JSS 2</option>
                                    <option value="JSS 3" {{ $staff->class == 'JSS 3' ? 'selected' : '' }}>JSS 3</option>
                                    <option value="SSS 1" {{ $staff->class == 'SSS 1' ? 'selected' : '' }}>SSS 1</option>
                                    <option value="SSS 2" {{ $staff->class == 'SSS 2' ? 'selected' : '' }}>SSS 2</option>
                                    <option value="SSS 3" {{ $staff->class == 'SSS 3' ? 'selected' : '' }}>SSS 3</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account -->
        <div class="tab-pane fade" id="account" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.updateBank', $staff->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ $bank->bank_name ?? '' }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Account Name</label>
                                <input type="text" name="account_name" value="{{ $bank->account_name ?? '' }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Account No</label>
                                <input type="text" name="account_no" value="{{ $bank->account_no ?? '' }}" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-save me-1"></i> Update Bank Details
                        </button>
                    </form>
                </div>
            </div>
        </div>
<!-- Salary -->
<div class="tab-pane fade" id="salary" role="tabpanel">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Salary Records</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Basic</th>
                            <th>Bonus</th>
                            <th>Loan</th>
                            <th>Tax</th>
                            <th>Social</th>
                            <th>Health</th>
                            <th>Lesson</th>
                            <th>Net Pay</th>
                            <th>Gross Pay</th>
                            <th>Date Paid</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                            <tr>
                                <td>{{ date("F", mktime(0,0,0,$salary->month,1)) }}</td>
                                <td>{{ $salary->year }}</td>
                                <td>
                                    <span class="badge bg-{{ $salary->status == 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($salary->status) }}
                                    </span>
                                </td>
                                <td>₦{{ number_format($salary->basic, 2) }}</td>
                                <td>₦{{ number_format($salary->bonus, 2) }}</td>
                                <td>₦{{ number_format($salary->loan_repayment, 2) }}</td>
                                <td>₦{{ number_format($salary->health, 2) }}</td>
                                <td>₦{{ number_format($salary->lesson_amount, 2) }}</td>
                                <td>₦{{ number_format($salary->tax_deduction, 2) }}</td>
                                <td>₦{{ number_format($salary->social_deduction, 2) }}</td>
                                <td>₦{{ number_format($salary->net_pay, 2) }}</td>
                                <td>₦{{ number_format($salary->gross, 2) }}</td>
                                <td>{{ $salary->date_paid ?? '---' }}</td>
                                <td class="text-nowrap">

                                    {{-- Download Payslip --}}
                                    <a href="{{ route('salary.download', $salary->id) }}" 
                                    class="btn btn-sm btn-primary custom-download"
                                    data-bs-toggle="tooltip" 
                                    title="Download Payslip">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    {{-- Email Receipt --}}
                                    <a href="{{ route('salary.email', $salary->id) }}" 
                                    class="btn btn-sm btn-info custom-email"
                                    data-bs-toggle="tooltip" 
                                    title="Email Payslip">
                                        <i class="fas fa-envelope"></i>
                                    </a>

                                    {{-- Mark as Paid (only if pending) --}}
                                    @if($salary->status == 'pending')
                                        <form action="{{ route('salary.markPaid', $salary->id) }}" 
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-success custom-success"
                                                    data-bs-toggle="tooltip" 
                                                    title="Mark as Paid & Email Payslip">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete Salary --}}
                                    <form action="{{ route('staff.salary.delete', $salary->id) }}" 
                                        method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                data-bs-toggle="tooltip" title="Delete Salary">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No salary records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h5 class="fw-bold mt-4">Add Salary</h5>
            <form method="POST" action="{{ route('staff.salary.store', $staff->id) }}">
                @csrf
                <div class="row g-3">
                    <!-- Month -->
                    <div class="col-md-2">
                        <select name="month" class="form-control" required>
                            <option value="">-- Month --</option>
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}">{{ date("F", mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Year -->
                    <div class="col-md-2">
                        <select name="year" class="form-control" required>
                            <option value="">Select Year</option>
                            @for($y=2025; $y<=2030; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <!-- Basic -->
                    <div class="col-md-2">
                        <input name="basic" type="number" step="0.01" class="form-control" placeholder="Basic" required>
                    </div>

                    <!-- Bonus -->
                    <div class="col-md-2">
                        <input name="bonus" type="number" step="0.01" class="form-control" placeholder="Bonus">
                    </div>

                    <!-- Loan Repayment -->
                    <div class="col-md-2">
                        <input name="loan_repayment" type="number" step="0.01" class="form-control" placeholder="Loan Deduction">
                    </div>

                    <!-- Health Allowance -->
                    <div class="col-md-2">
                        <input name="health" type="number" step="0.01" class="form-control" placeholder="Health Allowance">
                    </div>

                    <!-- Lesson -->
                    <div class="col-md-2">
                        <input name="lesson_amount" type="number" step="0.01" class="form-control" placeholder="Lesson Amount">
                    </div>
                    <!-- Tax Deduction -->
                    <div class="col-md-2">
                        <input name="tax_deduction" type="number" step="0.01" class="form-control" placeholder="Tax Deduction">
                    </div>

                    <!-- Social -->
                    <div class="col-md-2">
                        <input name="social_deduction" type="number" step="0.01" class="form-control" placeholder="Social">
                    </div>

                    <!-- Date Paid -->
                    <div class="col-md-2">
                        <input name="date_paid" type="date" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_email" value="1" id="sendEmail">
                        <label class="form-check-label" for="sendEmail">
                            Send Email Alert
                        </label>
                    </div>
                </div>
                </div>


                


                <button class="btn btn-success mt-3">
                    <i class="fas fa-plus me-1"></i> Add Salary
                </button>
            </form>
        </div>
    </div>
</div>


</div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Delete confirmation
    document.querySelectorAll(".btn-delete").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            let form = this.closest("form");
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>


<style>
    .custom-download {
        background: linear-gradient(45deg, #fd0d0dff, #1b0303ff) !important;
    }

    .custom-email {
        background: linear-gradient(45deg, #c7f010ff, #000002ff) !important;
    }
    .custom-success {
        background: linear-gradient(45deg, #f0e10fff, #ce9f07ff) !important;
    }
</style>

@endsection
