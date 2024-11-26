<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user = get_user_by_id($_SESSION['user_id']);
$projects = get_projects_by_user($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Web Design Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Web Design Agency</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        
        <h2 class="mb-3">Your Projects</h2>
        <div class="row">
            <?php foreach ($projects as $project): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($project['name']); ?></h5>
                            <p class="card-text">Status: <?php echo htmlspecialchars($project['status']); ?></p>
                            <a href="project.php?id=<?php echo $project['id']; ?>" class="btn btn-primary">View Project</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="mb-3 mt-5">Analytics Summary</h2>
        <div class="row">
            <div class="col-md-6">
                <canvas id="projectsChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Projects Chart
            var ctxProjects = document.getElementById('projectsChart').getContext('2d');
            var projectsChart = new Chart(ctxProjects, {
                type: 'pie',
                data: {
                    labels: ['Completed', 'In Progress', 'Not Started'],
                    datasets: [{
                        data: [5, 3, 2],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Project Status'
                    }
                }
            });

            // Revenue Chart
            var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 3000, 5000, 2000, 3000],
                        backgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Monthly Revenue'
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>