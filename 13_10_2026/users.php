<?php
session_start();

// Authentication check
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

$error = $_GET["error"] ?? "";
$success = $_GET["success"] ?? "";

// Handle Add User Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "add_user") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Prevent duplicate emails using prepared statements
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "A user with email '$email' already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $name, $email, $hashed);

            if ($insertStmt->execute()) {
                $success = "User created successfully.";
            } else {
                $error = "Failed to create user: " . $conn->error;
            }
            $insertStmt->close();
        }
        $checkStmt->close();
    }
}

// Retrieve registered users from database (NEVER SELECT PASSWORD)
$users = [];
$usersQuery = $conn->query("SELECT id, name, email, created_at FROM users ORDER BY id ASC");
if ($usersQuery && $usersQuery->num_rows > 0) {
    while ($row = $usersQuery->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Header -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="dashboard.php" class="navbar-brand">
                ⚡ <span>Admin</span>Dashboard
            </a>
            <ul class="navbar-nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="users.php" class="active">Users</a></li>
                <li><a href="products.php">Products</a></li>
            </ul>
            <div class="user-section">
                <span class="user-name">Welcome, <strong><?php echo htmlspecialchars($_SESSION["user_name"]); ?></strong></span>
                <a href="logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>
    </header>

    <main class="dashboard-container">

        <!-- Global Alerts -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- User Management Block -->
        <section class="section-block">
            <div class="section-header">
                <h2>Registered Users (<?php echo count($users); ?>)</h2>
                <button type="button" class="btn btn-sm" onclick="toggleForm('addUserCard')">+ Add User</button>
            </div>

            <!-- Add User Form -->
            <div id="addUserCard" class="form-card" style="display: none;">
                <h3>Add New User</h3>
                <form method="POST" action="users.php">
                    <input type="hidden" name="action" value="add_user">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="u_name">Full Name *</label>
                            <input type="text" id="u_name" name="name" required placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label for="u_email">Email Address *</label>
                            <input type="email" id="u_email" name="email" required placeholder="john@example.com">
                        </div>
                        <div class="form-group">
                            <label for="u_password">Password *</label>
                            <input type="password" id="u_password" name="password" minlength="6" required placeholder="Min 6 characters">
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-sm">Create User</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('addUserCard')">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th># ID</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u["id"]); ?></td>
                                    <td><strong><?php echo htmlspecialchars($u["name"]); ?></strong></td>
                                    <td><?php echo htmlspecialchars($u["email"]); ?></td>
                                    <td><?php echo htmlspecialchars($u["created_at"]); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                            <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                                <a href="delete_user.php?id=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars(addslashes($u['name'])); ?>?');">Delete</a>
                                            <?php else: ?>
                                                <span class="badge badge-stock" title="You are currently logged in as this user">Current User</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">No users registered yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        function toggleForm(id) {
            var el = document.getElementById(id);
            if (el.style.display === "none" || el.style.display === "") {
                el.style.display = "block";
                var firstInput = el.querySelector("input[type='text']");
                if (firstInput) firstInput.focus();
            } else {
                el.style.display = "none";
            }
        }
    </script>
</body>
</html>
