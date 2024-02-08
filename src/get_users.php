<?php
require_once 'db_connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Single search input
$searchInput = $_GET['search'] ?? '';

$columnNames = [
    'users.id',
    'users.first_name',
    'users.last_name',
    'emails.email',
    'addresses.address',
    'phones.phone'
];
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

// Base SQL with joins to include addresses, phones, and emails
$sql = "SELECT users.id, users.first_name, users.last_name, addresses.address, phones.phone, emails.email 
        FROM users 
        LEFT JOIN addresses ON users.id = addresses.user_id 
        LEFT JOIN phones ON users.id = phones.user_id 
        LEFT JOIN emails ON users.id = emails.user_id";

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
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        if (!isset($users[$userId])) {
            $users[$userId] = [
                'id' => $userId,
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'addresses' => [],
                'phones' => [],
                'emails' => []
            ];
        }
        if ($row['address'] && !in_array($row['address'], $users[$userId]['addresses'])) {
            $users[$userId]['addresses'][] = $row['address'];
        }
        if ($row['phone'] && !in_array($row['phone'], $users[$userId]['phones'])) {
            $users[$userId]['phones'][] = $row['phone'];
        }
        if ($row['email'] && !in_array($row['email'], $users[$userId]['emails'])) {
            $users[$userId]['emails'][] = $row['email'];
        }
    }

    echo json_encode(['users' => array_values($users)]);

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
