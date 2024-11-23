<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'web_design_agency');

// Google OAuth configuration
define('GOOGLE_CLIENT_ID', 'your_google_client_id');
define('GOOGLE_CLIENT_SECRET', 'your_google_client_secret');
define('GOOGLE_REDIRECT_URI', 'http://your-domain.com/google_callback.php');

// Stripe configuration
define('STRIPE_PUBLISHABLE_KEY', 'your_stripe_publishable_key');
define('STRIPE_SECRET_KEY', 'your_stripe_secret_key');

// Connect to the database
/*
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
*/