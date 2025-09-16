@extends('layouts.app')

@section('title', 'Fee Setup')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                <h2 class="text-center mb-4 fw-bold text-gradient">Set Student Fees</h2>

                {{-- Fee Setup Form --}}
                <form method="POST" action="{{ route('admin.fees.store') }}">
                    @csrf

                    {{-- Class --}}
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" name="class" id="class" required>
                            <option value="">-- Select Class --</option>
                            @php
                                $classes = ['GRADE 1','GRADE 2','GRADE 3','GRADE 4','GRADE 5','GRADE 6','JSS 1','JSS 2','JSS 3','SSS 1','SSS 2','SSS 3'];
                            @endphp
                            @foreach($classes as $cls)
                                <option value="{{ $cls }}">{{ $cls }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Term --}}
                    <div class="mb-3">
                        <label for="term" class="form-label">Term</label>
                        <select class="form-select" name="term" id="term" required>
                            <option value="">-- Select Term --</option>
                            <option value="First Term">First Term</option>
                            <option value="Second Term">Second Term</option>
                            <option value="Third Term">Third Term</option>
                        </select>
                    </div>

                    {{-- Session --}}
                    <div class="mb-3">
                        <label for="session" class="form-label">Session</label>
                        <input type="text" class="form-control" id="session" name="session" placeholder="e.g. 2024/2025" required>
                    </div>

                    {{-- Tuition --}}
                    <div class="mb-3">
                        <label for="tuition" class="form-label">Tuition Fee</label>
                        <input type="number" class="form-control" id="tuition" name="tuition" required>
                    </div>

                    {{-- Uniform --}}
                    <div class="mb-3">
                        <label for="uniform" class="form-label">Uniform Fee</label>
                        <input type="number" class="form-control" id="uniform" name="uniform">
                    </div>

                    {{-- Exam Fee --}}
                    <div class="mb-3">
                        <label for="exam_fee" class="form-label">Exam Fee</label>
                        <input type="number" class="form-control" id="exam_fee" name="exam_fee">
                    </div>

                    {{-- Submit --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            Save Fee Setup
                        </button>
                    </div>
                </form>
            </div>

            {{-- Display Existing Fees --}}
            <div class="card mt-5 shadow-lg border-0 rounded-4 p-4">
                <h4 class="mb-3">Existing Fee Structures</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Term</th>
                            <th>Session</th>
                            <th>Tuition</th>
                            <th>Uniform</th>
                            <th>Exam Fee</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fees as $fee)
                            <tr>
                                <td>{{ $fee->class }}</td>
                                <td>{{ $fee->term }}</td>
                                <td>{{ $fee->session }}</td>
                                <td>{{ $fee->tuition }}</td>
                                <td>{{ $fee->uniform }}</td>
                                <td>{{ $fee->exam_fee }}</td>
                                <td><strong>{{ $fee->total }}</strong></td>
                                <td>
                                    <a href="{{ route('admin.fees.edit', $fee->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.fees.destroy', $fee->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this fee setup?');">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if($fees->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center">No fee setup found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>
@endsection
