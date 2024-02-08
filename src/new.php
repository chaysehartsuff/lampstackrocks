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
    <div class="form-container">
    <form action="process.php" method="post" id="userForm">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="first_name"><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="last_name"><br>

        <div class="input-box">
            <label>Emails:
            <button type="button" class="add-button" onclick="addEmailField()">Add</button>
            </label>
            <div id="emailFields"></div>
        </div>

        <div class="input-box">
            <label>Addresses:
            <button type="button" onclick="addAddressField()">Add</button>
            </label>
            <div id="addressFields"></div>
        </div>

        <div class="input-box">
            <label>Phones:
            <button type="button" onclick="addPhoneField()">Add</button>
            </label>
            <div id="phoneFields"></div>
        </div>

        <input type="submit" value="Add User">
    </form>
</div>

<script>
    function addEmailField() {
        const container = document.getElementById('emailFields');
        const newField = document.createElement('div');
        newField.innerHTML = '<input type="email" name="emails[]"><button type="button" onclick="removeField(this)">Remove</button>';
        container.appendChild(newField);
    }

    function addAddressField() {
        const container = document.getElementById('addressFields');
        const newField = document.createElement('div');
        newField.innerHTML = '<input type="text" name="addresses[]"><button type="button" onclick="removeField(this)">Remove</button>';
        container.appendChild(newField);
    }

    function addPhoneField() {
        const container = document.getElementById('phoneFields');
        const newField = document.createElement('div');
        newField.innerHTML = '<input type="text" name="phones[]"><button type="button" onclick="removeField(this)">Remove</button>';
        container.appendChild(newField);
    }

    function removeField(button) {
        button.parentNode.remove();
    }
</script>

</body>
</html>
