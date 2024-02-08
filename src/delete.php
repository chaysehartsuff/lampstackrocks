<?php
require 'db_connection.php';
$success = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete associated addresses
        $sql = "DELETE FROM addresses WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Delete associated phones
        $sql = "DELETE FROM phones WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Delete associated emails
        $sql = "DELETE FROM emails WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Finally, delete the user
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $success = true;
        } else {
            echo "User not found or already deleted.";
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();
    } catch (Exception $e) {
        // An error occurred, rollback changes
        $conn->rollback();
        echo "Error deleting records: " . $e->getMessage();
    }
} else {
    echo "No user ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<body>
<script>
     <?php if ($success): ?>
        alert('User and all associated records have been deleted successfully.');
        window.location.href = "index.php?success";
    <?php endif; ?>
</script>
</body>
</html>
