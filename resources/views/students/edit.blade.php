@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                <h2 class="text-center mb-4 fw-bold text-gradient">Edit Student</h2>

                <form method="POST" action="{{ route('students.update', $student->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Firstname --}}
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" name="firstname" id="firstname" 
                               class="form-control @error('firstname') is-invalid @enderror" 
                               value="{{ old('firstname', $student->firstname) }}" required>
                        @error('firstname') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Lastname --}}
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" name="lastname" id="lastname" 
                               class="form-control @error('lastname') is-invalid @enderror" 
                               value="{{ old('lastname', $student->lastname) }}" required>
                        @error('lastname') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $student->email) }}" required>
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $student->phone) }}">
                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Class --}}
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select name="class" id="class" 
                                class="form-select @error('class') is-invalid @enderror" required>
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls }}" {{ old('class', $student->class) == $cls ? 'selected' : '' }}>
                                    {{ $cls }}
                                </option>
                            @endforeach
                        </select>
                        @error('class') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Session --}}
                    <div class="mb-3">
                        <label for="session" class="form-label">Session</label>
                        <select name="session" id="session" 
                                class="form-select @error('session') is-invalid @enderror" required>
                            <option value="">-- Select Session --</option>
                            @foreach($sessions as $sess)
                                <option value="{{ $sess }}" 
                                    {{ old('session', $student->session ?? $currentSession) == $sess ? 'selected' : '' }}>
                                    {{ $sess }}
                                </option>
                            @endforeach
                        </select>
                        @error('session') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Term --}}
                    <div class="mb-3">
                        <label for="term" class="form-label">Term</label>
                        <select name="term" id="term" 
                                class="form-select @error('term') is-invalid @enderror" required>
                            <option value="">-- Select Term --</option>
                            @foreach($terms as $t)
                                <option value="{{ $t }}" 
                                    {{ old('term', $student->term ?? $currentTerm) == $t ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endforeach
                        </select>
                        @error('term') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" 
                                class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
