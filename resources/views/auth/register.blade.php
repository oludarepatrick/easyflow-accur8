@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center custom-parent min-vh-100 bg-light w-100">
     
<div class="card shadow-lg border-0 rounded-4 p-4 p-md-5" style="max-width: 500px; width: 100%;">
        {{-- Logo --}}
        {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success text-center custom-success fade-in mb-4">
            {{ session('success') }}
        </div>
    @endif
        <div class="text-center custom-logo mb-4">
            <img src="{{ asset('favi1.png') }}" alt="School Logo" height="60" class="fade-in">
        </div>

        <h2 class="text-center mb-4 fw-bold text-gradient">Register Student / Staff</h2>

        <form method="POST" action="{{ route('register') }}" class="fade-in-up custom-form">
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
                        $classes = ['GRADE 1','GRADE 2','GRADE 3','GRADE 4','GRADE 5','GRADE 6','JSS 1','JSS 2','JSS 3','SSS 1','SSS 2','SSS 3'];
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
            <div class="d-flex justify-content-between custom-btns mt-4">
                <a href="{{ url('/') }}" class="btn btn-gradient px-4 py-2 custom-cancel hover-scale">Close</a>
                <button type="submit" class="btn btn-gradient px-4 py-2 custom-rounded hover-scale">
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
{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


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
    .custom-rounded {
        border-radius: 5px !important;
        margin-left: 30px;
    }

    .custom-rounded2 {
        border-radius: 5px !important;
        margin-left: 70px;
    }

    .custom-rounded3 {
        border-radius: 5px !important;
        margin-left: 62px;
    }

    .custom-rounded4 {
        border-radius: 5px !important;
        margin-left: 40px;
    }

    .custom-secure {
        margin-top: 40px !important;
    }

    .custom-logo {
        width: 40px !important;
        height: 40px !important;
    }

    .custom-cancel {
        color: #881708ff;
        border-radius: 5px !important;
        background: linear-gradient(45deg, #a30930ff, #ee3636ff) !important;
        
    }
    
    .custom-parent {
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 0 10px rgba(75, 0, 130, 0.5), 0 0 20px rgba(0, 191, 255, 0.5);
    }

    .custom-form {
        margin-left: 40px !important;
    }

    .custom-btns {
        margin-left: 100px !important;
    }

    .custom-success {
        margin-left: 100px !important;
        color: green !important;
        background: linear-gradient(45deg, #007bff, #28a745);
    }

    .text-gradient {
        background: linear-gradient(45deg, #007bff, #28a745);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .btn-gradient {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: #fff;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .hover-scale:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease-in-out;
    }
    .fade-in {
        animation: fadeIn 1s ease-in-out;
    }
    .fade-in-up {
        animation: fadeInUp 0.8s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection