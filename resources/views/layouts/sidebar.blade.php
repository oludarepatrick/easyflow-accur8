<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <img src="{{ asset('favi1.png') }}" alt="App Logo" class="mx-auto h-16 w-auto">
            <h5 class="mt-2">School Account Management</h5>
        </div>

        <ul class="nav flex-column">
            {{-- Common links for both Admin and Clerk --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            {{-- Admin-only links --}}
            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" 
                    data-bs-toggle="collapse" href="#studentsMenu" role="button" aria-expanded="false" aria-controls="studentsMenu">
                        <span><i class="bi bi-people"></i> Students</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="collapse" id="studentsMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('students.index') }}">
                                    <i class="bi bi-list-task"></i> Active Students
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register.form') }}">
                                    <i class="bi bi-pencil-square"></i> Register Student
                                </a>
                            </li>
                            <!-- Add more student-related links here -->
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-badge"></i> Staff
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-file-earmark-text"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            @endif

            {{-- Clerk (and Admin too) links --}}
            @if(Auth::guard('admin')->check() && in_array(Auth::guard('admin')->user()->role, ['admin','clerk']))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register.form') }}">
                        <i class="bi bi-pencil-square"></i> Register
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-cash-stack"></i> Create Invoice
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-printer"></i> Print Invoice
                    </a>
                </li>
            @endif

            {{-- Logout --}}
            <li class="nav-item mt-3">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
