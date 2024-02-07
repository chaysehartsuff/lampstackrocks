<?php

// Assuming environment variables are set for database connection
$host = getenv('MYSQL_HOST') ?: 'db'; // Default to 'db' if MYSQL_HOST is not set
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$database = getenv('MYSQL_DATABASE');

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
