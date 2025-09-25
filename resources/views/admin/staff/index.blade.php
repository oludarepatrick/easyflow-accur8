@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="container mt-4">
    <h2>Active Staff</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Phone</th>
                <th>View</th>
                <th>Deactivate</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $s)
                <tr>
                    <td>{{ $s->firstname }}</td>
                    <td>{{ $s->lastname }}</td>
                    <td>{{ $s->email }}</td>
                    <td>{{ $s->phone }}</td>
                    <td>
                        <a href="{{ route('staff.show', $s->id) }}" class="btn btn-info btn-sm">View</a>
                    </td>
                    <td>
                        <form action="{{ route('staff.deactivate', $s->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Deactivate</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('staff.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No staff found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
