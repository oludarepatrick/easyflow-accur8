@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
</div>
@endsection
