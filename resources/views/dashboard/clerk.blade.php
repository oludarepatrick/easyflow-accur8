<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="https://images.unsplash.com/photo-1557683311-eac922347aa1" class="rounded-circle" width="80" height="80" alt="Logo">
                        <h5 class="mt-2">School Management</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-person-badge"></i> Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-cash-stack"></i> Payments
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard Overview</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card h-100 border-primary">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Active Students</h6>
                                <h2 class="mt-3 mb-0">1,234</h2>
                                <p class="text-success mb-0"><i class="bi bi-arrow-up"></i> 12% increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 border-warning">
                            <div class="card-body">
                                <h6 class="card-title text-warning">Inactive Students</h6>
                                <h2 class="mt-3 mb-0">89</h2>
                                <p class="text-danger mb-0"><i class="bi bi-arrow-down"></i> 5% decrease</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 border-success">
                            <div class="card-body">
                                <h6 class="card-title text-success">Active Staff</h6>
                                <h2 class="mt-3 mb-0">45</h2>
                                <p class="text-success mb-0"><i class="bi bi-arrow-up"></i> 3% increase</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 border-info">
                            <div class="card-body">
                                <h6 class="card-title text-info">Total Revenue</h6>
                                <h2 class="mt-3 mb-0">$52,489</h2>
                                <p class="text-success mb-0"><i class="bi bi-arrow-up"></i> 8% increase</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Fee Collection Trend</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <canvas id="revenueChart" height="300"></canvas>
                                    </div>
                                    <div class="col-md-4">
                                        <canvas id="termPieChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Recent Payments</h5>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">John Doe</h6>
                                            <small>Today</small>
                                        </div>
                                        <p class="mb-1">Term 1 Fees</p>
                                        <small class="text-success">$1,200</small>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Jane Smith</h6>
                                            <small>Yesterday</small>
                                        </div>
                                        <p class="mb-1">Term 2 Fees</p>
                                        <small class="text-success">$1,500</small>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Mike Johnson</h6>
                                            <small>2 days ago</small>
                                        </div>
                                        <p class="mb-1">Term 1 Fees</p>
                                        <small class="text-success">$1,350</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const pieCtx = document.getElementById('termPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['First Term', 'Second Term', 'Third Term'],
                datasets: [{
                    data: [35, 40, 25],
                    backgroundColor: ['#0d6efd', '#ffc107', '#198754']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>


Here is the css file referenced in the HTML above. it was initially inside index.css but has been moved here for now:
:root {
    --primary: #0070f3;
    --primary-foreground: #ffffff;
    --secondary: #f5f5f5;
    --secondary-foreground: #111111;
    --accent: #f8f9fa;
    --accent-foreground: #343a40;
    --background: #ffffff;
    --foreground: #111111;
    --card: #ffffff;
    --card-foreground: #111111;
    --border: #e9ecef;
    --input: #e9ecef;
    --ring: #0070f3;
    --radius: 0.5rem;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

body {
    font-family: system-ui, -apple-system, sans-serif;
    background-color: var(--background);
    color: var(--foreground);
}

.sidebar {
    box-shadow: var(--shadow);
    min-height: 100vh;
    background-color: var(--card) !important;
}

.nav-link {
    color: var(--foreground);
    padding: 0.5rem 1rem;
    border-radius: var(--radius);
}

.nav-link:hover {
    background-color: var(--accent);
    color: var(--accent-foreground);
}

.nav-link.active {
    background-color: var(--primary);
    color: var(--primary-foreground);
}

.card {
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item {
    border-left: none;
    border-right: none;
    border-radius: 0;
    padding: 1rem;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

.btn-outline-secondary {
    border-color: var(--border);
    color: var(--foreground);
}

.btn-outline-secondary:hover {
    background-color: var(--accent);
    border-color: var(--accent);
    color: var(--accent-foreground);
}

.border-bottom {
    border-color: var(--border) !important;
}

.text-success {
    color: #28a745 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-primary {
    color: var(--primary) !important;
}

.bi {
    margin-right: 0.5rem;
}