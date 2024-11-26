<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if project ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$project = get_project_by_id($_GET['id']);
$updates = get_updates_by_project($_GET['id']);
$files = get_files_by_project($_GET['id']);

// Check if the project exists and belongs to the logged-in user
if (!$project || $project['user_id'] != $_SESSION['user_id']) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['name']); ?> - Web Design Agency</title>
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
        <h1 class="mb-4"><?php echo htmlspecialchars($project['name']); ?></h1>
        <p>Status: <?php echo htmlspecialchars($project['status']); ?></p>

        <h2 class="mb-3 mt-5">Project Updates</h2>
        <div id="updates-container">
            <?php foreach ($updates as $update): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($update['content']); ?></p>
                        <small class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($update['created_at'])); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form id="update-form" class="mb-5">
            <div class="mb-3">
                <label for="update-content" class="form-label">New Update</label>
                <textarea class="form-control" id="update-content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Update</button>
        </form>

        <h2 class="mb-3 mt-5">Project Files</h2>
        <div id="files-container">
            <?php foreach ($files as $file): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($file['file_name']); ?></h5>
                        <a href="<?php echo htmlspecialchars($file['file_path']); ?>" class="btn btn-sm btn-secondary" download>Download</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form id="file-upload-form" class="mb-5">
            <div class="mb-3">
                <label for="file-upload" class="form-label">Upload File</label>
                <input class="form-control" type="file" id="file-upload" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload File</button>
        </form>

        <h2 class="mb-3 mt-5">Payment</h2>
        <button id="payment-button" class="btn btn-success">Make Payment</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function() {
            const projectId = <?php echo $project['id']; ?>;

            // Handle project update submission
            $('#update-form').submit(function(e) {
                e.preventDefault();
                const content = $('#update-content').val();
                $.post('api/add_update.php', { project_id: projectId, content: content }, function(response) {
                    if (response.success) {
                        $('#updates-container').prepend(`
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text">${content}</p>
                                    <small class="text-muted">Just now</small>
                                </div>
                            </div>
                        `);
                        $('#update-content').val('');
                    } else {
                        alert('Failed to add update. Please try again.');
                    }
                });
            });

            // Handle file upload
            $('#file-upload-form').submit(function(e) {
                e.preventDefault();
                const formData = new FormData();
                formData.append('project_id', projectId);
                formData.append('file', $('#file-upload')[0].files[0]);

                $.ajax({
                    url: 'api/upload_file.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#files-container').prepend(`
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${response.file_name}</h5>
                                        <a href="${response.file_path}" class="btn btn-sm btn-secondary" download>Download</a>
                                    </div>
                                </div>
                            `);
                            $('#file-upload').val('');
                        } else {
                            alert('Failed to upload file. Please try again.');
                        }
                    }
                });
            });

            // Handle payment button click
            $('#payment-button').click(function() {
                $.post('api/create_payment.php', { project_id: projectId }, function(response) {
                    if (response.success) {
                        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
                        stripe.redirectToCheckout({ sessionId: response.session_id });
                    } else {
                        alert('Failed to initiate payment. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>