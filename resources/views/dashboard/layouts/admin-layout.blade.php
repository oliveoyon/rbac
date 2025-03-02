<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dashboard/css/style.css') }}">
    @stack('styles')

</head>

<body>
    <!-- Overlay for Mobile -->
    <div id="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">Admin</div>
        <ul>
            <li><a href="{{ route('dashboard.index') }}"><i class="fas fa-cogs"></i> Dashboard</a></a></li>
            <li class="has-submenu">
                <i class="fas fa-users"></i> General Settings 
                <ul class="submenu">
                    <li><a href="{{ route('dashboard.districts') }}"><i class="fas fa-map-marker-alt"></i> District Management</a></li>
                    <li><a href="{{ route('dashboard.pngos') }}"><i class="fas fa-handshake"></i> PNGOs Management</a></li>
                    <li><a href="#"><i class="fas fa-user"></i> Users</a></li>
                </ul>
            </li>

            <li>Logout</li>
        </ul>
    </div>

    <!-- Header -->
    <div class="header">
        <button id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <div class="profile-menu">
            <i class="fas fa-user"></i> Profile
        </div>
    </div>

    <!-- Content -->
    <div class="content" id="content">

        @yield('content')

    </div>
    <!-- Bootstrap JS & jQuery (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <script src="{{ asset('dashboard/js/custom.js') }}"></script> <!-- Your custom JS file -->

    @stack('scripts')
    <script>
        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Pending', 'In Progress'],
                datasets: [{
                    data: [152, 104, 45],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8'],
                }]
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'Interventions',
                    data: [30, 45, 60, 50, 80],
                    backgroundColor: '#007bff',
                }]
            }
        });
    </script>

    <script>
        // District-wise Bar Chart
        const districtBarCtx = document.getElementById('districtBarChart').getContext('2d');
        new Chart(districtBarCtx, {
            type: 'bar',
            data: {
                labels: ['Dhaka', 'Chattogram', 'Khulna'],
                datasets: [{
                        label: 'Total Cases',
                        data: [120, 100, 80],
                        backgroundColor: '#007bff',
                    },
                    {
                        label: 'Resolved',
                        data: [90, 70, 50],
                        backgroundColor: '#28a745',
                    },
                    {
                        label: 'Pending',
                        data: [30, 30, 30],
                        backgroundColor: '#ffc107',
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            },
        });

        // PNGO-wise Doughnut Chart
        const pngoDoughnutCtx = document.getElementById('pngoDoughnutChart').getContext('2d');
        new Chart(pngoDoughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['PNGO A', 'PNGO B', 'PNGO C'],
                datasets: [{
                    data: [100, 80, 70],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
            },
        });
    </script>
</body>

</html>
