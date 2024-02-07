<?php
require 'db_connection.php';
$success = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $id;

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = true;
            } else {
                echo "User not found or already deleted.";
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $stmt->close();
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
        window.location = "index.php?success";
    <?php endif; ?>
</script>
</body>
</html>
