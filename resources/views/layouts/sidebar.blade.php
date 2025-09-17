<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <img src="{{ asset('favi1.png') }}" alt="App Logo" class="mx-auto h-16 w-auto">
            <h5 class="mt-2">School Account Management</h5>
        </div>

        {{-- Admin-only links --}}
        @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin')
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

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
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.fees.index') }}">
                        <i class="bi bi-cash-stack"></i> Fee Setup
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-badge"></i> Staff
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" 
                       data-bs-toggle="collapse" href="#reportsMenu" role="button" aria-expanded="false" aria-controls="reportsMenu">
                        <span><i class="bi bi-people"></i> Reports</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="collapse" id="reportsMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.statements.payments') }}">
                                    <i class="bi bi-file-text"></i> Payment Statement
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reports.owing-students') }}">
                                    <i class="bi bi-pencil-square"></i> Debtor List
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            </ul>
        @endif

        {{-- Clerk (and Admin too) links --}}
        @if(Auth::guard('admin')->check() && in_array(Auth::guard('admin')->user()->role, ['admin','clerk']))
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('clerk/dashboard') ? 'active' : '' }}"
                       href="{{ route('clerk.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>    
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
            </ul>
        @endif

        {{-- Logout --}}
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
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
