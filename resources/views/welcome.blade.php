@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accur8 - School Account Management Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container text-center mt-5">
    <div class="card shadow-lg p-5 mx-auto" style="max-width: 600px;">
        <h1 class="mb-4">Welcome to Accur8</h1>
        <p class="lead">Manage invoices, payrolls, and more with ease.</p>

        <div class="d-grid gap-3 mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Admin / Clerk Login</a>
            <a href="{{ route('register') }}" class="btn btn-success btn-lg">Register Student / Staff</a>
        </div>
    </div>
</div>
@endsection
