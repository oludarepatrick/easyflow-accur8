@extends('layouts.app')

@section('title', 'Register Student / Staff')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success text-center custom-success fade-in mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Logo --}}
                <div class="text-center mb-4">
                    <img src="{{ asset('favi1.png') }}" alt="School Logo" height="60" class="fade-in custom-logo">
                </div>

                <h2 class="text-center mb-4 fw-bold custom-text text-gradient">Register Student / Staff</h2>

                {{-- Use correct route --}}
                <form method="POST" action="{{ route('register.store') }}" class="fade-in-up custom-form">
                    @csrf

                    {{-- Firstname --}}
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control form-control-lg shadow-sm custom-rounded @error('firstname') is-invalid @enderror"
                               id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                        @error('firstname')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Lastname --}}
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control form-control-lg shadow-sm custom-rounded @error('lastname') is-invalid @enderror"
                               id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                        @error('lastname')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control form-control-lg shadow-sm custom-rounded2 @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control form-control-lg shadow-sm custom-rounded3 @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select form-select-lg shadow-sm custom-rounded4 @error('category') is-invalid @enderror"
                                id="category" name="category" required>
                            <option value="">-- Select --</option>
                            <option value="student" {{ old('category')=='student' ? 'selected':'' }}>Student</option>
                            <option value="staff" {{ old('category')=='staff' ? 'selected':'' }}>Staff</option>
                        </select>
                        @error('category')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Class for Students --}}
                    <div class="mb-3 slide-toggle" id="classDiv" style="display:none;">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select form-select-lg shadow-sm custom-rounded2 @error('class') is-invalid @enderror"
                                id="class" name="class">
                            <option value="">-- Select Class --</option>
                            @php
                                $classes = ['ADMIN','SUPPORT STAFF','CRECHE','PREP','KG 1','KG 2','NURSERY 1','NURSERY 2','GRADE 1','GRADE 2','GRADE 3','GRADE 4','GRADE 5','GRADE 6','JSS 1','JSS 2','JSS 3','SSS 1','SSS 2','SSS 3'];
                            @endphp
                            @foreach($classes as $cls)
                                <option value="{{ $cls }}" {{ old('class')==$cls?'selected':'' }}>{{ $cls }}</option>
                            @endforeach
                        </select>
                        @error('class')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" 
                            class="btn px-5 py-2 fw-bold text-white"
                            style="border-radius: 12px; 
                                background: linear-gradient(45deg, #0d6efd, #20c997); 
                                text-align: center;">
                            Register
                        </button>
                    </div>

                    {{-- Secure Sign Up --}}
                    <div class="text-center mt-3 text-muted custom-secure small">
                        <i class="bi bi-lock-fill"></i> Secure Sign Up
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script to toggle class field --}}
<script>
    const categorySelect = document.getElementById('category');
    const classDiv = document.getElementById('classDiv');

    function toggleClassField() {
        if (categorySelect.value === 'student') {
            classDiv.style.display = 'block';
            classDiv.classList.add('fade-in-up');
        } else {
            classDiv.style.display = 'none';
        }
    }

    categorySelect.addEventListener('change', toggleClassField);
    window.addEventListener('DOMContentLoaded', toggleClassField);
</script>
<style>
    .custom-logo {
        width: 20px !important;
        height: 20px !important;
        margin-left: 500px !important;
    }

    .custom-text {
        margin-top: -50px !important;
        
    }
</style>
@endsection
