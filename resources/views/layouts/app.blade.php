<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Account Management Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('index.css') }}">
    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 1rem;
            border-right: 1px solid #ddd;
            width: 250px;
        }
        main {
            margin-left: 250px;
            padding: 20px;
        }
        @media (max-width: 991px) {
            .sidebar {
                position: relative;
                height: auto;
                width: 100%;
                border-right: none;
            }
            main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Toggle button visible only on small screens --}}
            <button class="btn btn-outline-primary d-lg-none my-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <i class="bi bi-list"></i> Menu
            </button>

            {{-- Sidebar (collapsible on small screens) --}}
            <div class="col-lg-2 bg-light sidebar collapse d-lg-block" id="sidebarMenu">
                @include('layouts.sidebar')
            </div>

            {{-- Main content --}}
            <main class="col-lg-10 px-md-4 py-3">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
