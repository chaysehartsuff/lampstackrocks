<?php
require 'db_connection.php';

$userData = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "No user found with ID: $id";
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-right: auto;
            margin-left: auto;
            margin-top: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label, input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav style="background-color: #f0f0f0; padding: 10px; text-align: center;">
        <h2>LAMP Stack Rocks</h2>
        <a href="index.php" style="margin-right: 20px;">Users</a>
        <a href="new.php">New User</a>
    </nav>
    <?php if ($userData): ?>
        <form class="form-container" action="process.php" method="post">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($userData['id']); ?>" readonly><br><br>

            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="first_name" value="<?php echo htmlspecialchars($userData['first_name']); ?>"><br><br>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="last_name" value="<?php echo htmlspecialchars($userData['last_name']); ?>"><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>"><br><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($userData['address']); ?>"><br><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>"><br><br>

            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
</body>
</html>
