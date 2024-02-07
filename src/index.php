<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .success-banner {
            background-color: rgba(76, 175, 80, 0.5);
            color: grey;
            padding: 10px;
            margin: 10px;
            width: 40%;
            text-align: center;
            margin-right: auto;
            margin-left: auto;
            border: 2px solid rgba(76, 175, 80, 0.1);
            border-radius: 0px;
        }
        .clickable-row {
            cursor: pointer;
        }
        .clickable-row:hover {
            background-color: #f5f5f5;
        }
        .search{
            margin: 10px;
        }
        table{
            width: 95%;
            margin: auto;
        }
        body{
            margin: 0;
            padding: 0;
        }

    </style>
</head>
<body>
    <nav style="background-color: #f0f0f0; padding: 10px; text-align: center;">
        <h2>LAMP Stack Rocks</h2>
        <a href="index.php" style="margin-right: 20px;">Users</a>
        <a href="new.php">New User</a>
    </nav>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-banner">Success!</div>
    <?php endif; ?>
    <h2 class="search">User List</h2>
    <div style="text-align: right;">
    <input class="search" type="text" id="userSearch" placeholder="Search users..." oninput="refreshTable(this.value)">
    <button onclick="refreshTable(document.getElementById('userSearch').value)" style="border: none; background: none; cursor: pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"></path><path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"></path></svg>
    </button>
</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody"></tbody>
    </table>

    <script>
        function refreshTable(search = '') {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `get_users.php?search=${encodeURIComponent(search)}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    const tbody = document.getElementById('userTableBody');
                    tbody.innerHTML = '';
                    response.users.forEach(function(user) {
                        const row = `<tr class="clickable-row" onclick="window.location='user.php?id=${user.id}';">
                                        <td>${user.id}</td>
                                        <td>${user.first_name}</td>
                                        <td>${user.last_name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.address}</td>
                                        <td>${user.phone}</td>
                                        <td>
                                            <button onclick="event.stopPropagation(); confirmDelete(${user.id});">Delete</button>
                                        </td>
                                     </tr>`;
                        tbody.innerHTML += row;
                    });
                }
            };
            xhr.send();
        }

        function confirmDelete(id) {
            const confirmation = confirm("Are you sure you want to delete this user?");
            if (confirmation) {
                window.location.href = `delete.php?id=${id}`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            refreshTable();
        });
    </script>
</body>



</html>
