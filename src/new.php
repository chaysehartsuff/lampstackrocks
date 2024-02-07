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
    <div class="form-container">
        <form action="process.php" method="post">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="first_name"><br>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="last_name"><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address"><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone"><br>

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
