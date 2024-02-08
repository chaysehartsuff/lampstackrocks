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

    $addresses = [];
    $emails = [];
    $phones = [];
    if ($userData) {
        // Fetch addresses
        $stmt = $conn->prepare("SELECT address FROM addresses WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row['address'];
        }
        $stmt->close();

        // Fetch emails
        $stmt = $conn->prepare("SELECT email FROM emails WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $emails[] = $row['email'];
        }
        $stmt->close();

        // Fetch phones
        $stmt = $conn->prepare("SELECT phone FROM phones WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $phones[] = $row['phone'];
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
            margin-bottom: 5px;
        }
        .input-group{
            margin-top: 10px;
        }
        .input-box{
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .input-box label{
            display: block;
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

            <!-- Dynamic Email Fields -->
            <div id="email-fields" class="input-box">
                <label for="email">Emails:
                    <button type="button" onclick="addInput('email-fields', 'email', 'emails[]')">Add Email</button>
                </label>
                <?php foreach ($emails as $email): ?>
                    <div class="input-group">
                        <input type="email" name="emails[]" value="<?php echo htmlspecialchars($email); ?>">
                        <button type="button" onclick="removeInput(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Dynamic Address Fields -->
            <div id="address-fields" class="input-box">
                <label for="address">Addresses:
                    <button type="button" onclick="addInput('address-fields', 'text', 'addresses[]')">Add Address</button>
                </label>
                <?php foreach ($addresses as $address): ?>
                    <div class="input-group">
                        <input type="text" name="addresses[]" value="<?php echo htmlspecialchars($address); ?>">
                        <button type="button" onclick="removeInput(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Dynamic Phone Fields -->
            <div id="phone-fields" class="input-box">
                <label for="phone">Phones:
                    <button type="button" onclick="addInput('phone-fields', 'text', 'phones[]')">Add Phone</button>
                </label>
                <?php foreach ($phones as $phone): ?>
                    <div class="input-group">
                        <input type="text" name="phones[]" value="<?php echo htmlspecialchars($phone); ?>">
                        <button type="button" onclick="removeInput(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>

    <script>
        function addInput(containerId, inputType, name) {
            const container = document.getElementById(containerId);
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group');

            const input = document.createElement('input');
            input.type = inputType;
            input.name = name;
            inputGroup.appendChild(input);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = 'Remove';
            removeBtn.onclick = function() { removeInput(removeBtn); };
            inputGroup.appendChild(removeBtn);

            container.appendChild(inputGroup);
        }

        function removeInput(btn) {
            const inputGroup = btn.parentNode;
            inputGroup.parentNode.removeChild(inputGroup);
        }
    </script>
</body>

</html>
