<?php
require 'db_connection.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);

    if (!empty($_POST['id'])) {
        // ID was passed, attempt to update the user
        $id = $conn->real_escape_string($_POST['id']);

        // Check if user exists
        $checkUser = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $checkUser->bind_param("i", $id);
        $checkUser->execute();
        $result = $checkUser->get_result();
        $checkUser->close();

        if ($result->num_rows == 0) {
            echo "User does not exist.";
            exit;
        }

        // User exists, proceed to update
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, address = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $address, $phone, $id);
    } else {
        // No ID was passed, proceed to insert a new user
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, address, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $address, $phone);
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        $success = true;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
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

