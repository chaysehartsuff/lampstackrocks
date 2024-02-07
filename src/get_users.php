<?php
require_once 'db_connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Single search input
$searchInput = $_GET['search'] ?? '';

$columnNames = ['id', 'first_name', 'last_name', 'email', 'address', 'phone'];
$conditions = [];
$params = [];
$types = ''; 

if (!empty($searchInput)) {
    foreach ($columnNames as $columnName) {
        $conditions[] = "$columnName LIKE ?";
        $params[] = "%$searchInput%";
        $types .= 's';
    }
}

$sql = "SELECT * FROM users";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" OR ", $conditions);
}

try {
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        echo "0 results";
    }

    echo json_encode(['users' => $users]);

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
