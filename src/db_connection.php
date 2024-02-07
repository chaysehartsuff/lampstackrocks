<?php

$host = getenv('MYSQL_HOST') ?: 'db';
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$database = getenv('MYSQL_DATABASE');

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
