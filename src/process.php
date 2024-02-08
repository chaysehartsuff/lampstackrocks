<?php
require 'db_connection.php';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);

    $emails = $_POST['emails'] ?? [];
    $addresses = $_POST['addresses'] ?? [];
    $phones = $_POST['phones'] ?? [];

    if (!empty($_POST['id'])) {
        $id = $conn->real_escape_string($_POST['id']);

        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
        $stmt->bind_param("ssi", $first_name, $last_name, $id);
        if (!$stmt->execute()) {
            echo "Error updating user: " . $stmt->error;
        }
        $stmt->close();
        $userId = $id;
        $success = true;

        // delete previous records for user 
        $deleteQueries = [
            "DELETE FROM emails WHERE user_id = $userId",
            "DELETE FROM addresses WHERE user_id = $userId",
            "DELETE FROM phones WHERE user_id = $userId",
        ];
        foreach ($deleteQueries as $query) {
            if (!$conn->query($query)) {
                echo "Error deleting related records: " . $conn->error;
                $success = false;
            }
        }
    } else {
        // create new user
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name) VALUES (?, ?)");
        $stmt->bind_param("ss", $first_name, $last_name);
        if ($stmt->execute()) {
            $userId = $stmt->insert_id; // Get the new user's ID
            $success = true;
        } else {
            echo "Error: " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }

    if ($success && $userId) {
        // Insert emails
        foreach ($emails as $email) {
            $email = $conn->real_escape_string($email);
            $stmt = $conn->prepare("INSERT INTO emails (user_id, email) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $email);
            $stmt->execute();
            $stmt->close();
        }

        // Insert addresses
        foreach ($addresses as $address) {
            $address = $conn->real_escape_string($address);
            $stmt = $conn->prepare("INSERT INTO addresses (user_id, address) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $address);
            $stmt->execute();
            $stmt->close();
        }

        // Insert phones
        foreach ($phones as $phone) {
            $phone = $conn->real_escape_string($phone);
            $stmt = $conn->prepare("INSERT INTO phones (user_id, phone) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $phone);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<body>
<script>
    <?php if ($success): ?>
        window.location = "index.php?success";
    <?php endif; ?>
</script>
</body>
</html>
