@if(Auth::guard('admin')->user()->role === 'admin')
    {{-- Full Admin Sidebar --}}
    <ul>
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="#">Manage Clerks</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Settings</a></li>
    </ul>
@elseif(Auth::guard('admin')->user()->role === 'clerk')
    {{-- Clerk Sidebar --}}
    <ul>
        <li><a href="{{ route('clerk.dashboard') }}">Dashboard</a></li>
        <li><a href="#">Create Invoice</a></li>
        <li><a href="#">Print Invoice</a></li>
    </ul>
@endif
