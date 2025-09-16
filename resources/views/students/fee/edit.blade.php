@extends('layouts.app')

@section('title', 'Edit Fee Setup')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                <h2 class="text-center mb-4 fw-bold text-gradient">Edit Fee Setup</h2>

                <form method="POST" action="{{ route('admin.fees.update', $fee->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Class --}}
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <input type="text" class="form-control" name="class" value="{{ $fee->class }}" required>
                    </div>

                    {{-- Term --}}
                    <div class="mb-3">
                        <label for="term" class="form-label">Term</label>
                        <input type="text" class="form-control" name="term" value="{{ $fee->term }}" required>
                    </div>

                    {{-- Session --}}
                    <div class="mb-3">
                        <label for="session" class="form-label">Session</label>
                        <input type="text" class="form-control" name="session" value="{{ $fee->session }}" required>
                    </div>

                    {{-- Tuition --}}
                    <div class="mb-3">
                        <label for="tuition" class="form-label">Tuition Fee</label>
                        <input type="number" class="form-control" name="tuition" value="{{ $fee->tuition }}" required>
                    </div>

                    {{-- Uniform --}}
                    <div class="mb-3">
                        <label for="uniform" class="form-label">Uniform Fee</label>
                        <input type="number" class="form-control" name="uniform" value="{{ $fee->uniform }}">
                    </div>

                    {{-- Exam Fee --}}
                    <div class="mb-3">
                        <label for="exam_fee" class="form-label">Exam Fee</label>
                        <input type="number" class="form-control" name="exam_fee" value="{{ $fee->exam_fee }}">
                    </div>

                    {{-- Submit --}}
                   <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2">Update Fee</button>
                        <a href="{{ route('admin.fees.index') }}" class="btn btn-secondary px-4 py-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection