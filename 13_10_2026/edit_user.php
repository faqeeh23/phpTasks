<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

$userId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($userId <= 0) {
    header("Location: dashboard.php?error=Invalid user ID.#users");
    exit();
}

$error = "";
$success = "";

// Fetch user data
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: dashboard.php?error=User not found.#users");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Prevent duplicate email for other users
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkStmt->bind_param("si", $email, $userId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "This email is already in use by another user.";
        } else {
            // Check if password should be updated
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $error = "Password must be at least 6 characters long.";
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                    $updateStmt->bind_param("sssi", $name, $email, $hashed, $userId);
                    if ($updateStmt->execute()) {
                        // If updating current user's profile, update session name/email
                        if ($userId === (int)$_SESSION["user_id"]) {
                            $_SESSION["user_name"] = $name;
                            $_SESSION["user_email"] = $email;
                        }
                        header("Location: dashboard.php?success=User updated successfully.#users");
                        exit();
                    } else {
                        $error = "Failed to update user: " . $conn->error;
                    }
                    $updateStmt->close();
                }
            } else {
                $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $updateStmt->bind_param("ssi", $name, $email, $userId);
                if ($updateStmt->execute()) {
                    if ($userId === (int)$_SESSION["user_id"]) {
                        $_SESSION["user_name"] = $name;
                        $_SESSION["user_email"] = $email;
                    }
                    header("Location: dashboard.php?success=User updated successfully.#users");
                    exit();
                } else {
                    $error = "Failed to update user: " . $conn->error;
                }
                $updateStmt->close();
            }
        }
        $checkStmt->close();
    }
} else {
    $name = $user["name"];
    $email = $user["email"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <p class="subtitle">Update user information</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_user.php?id=<?php echo $userId; ?>">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" minlength="6" placeholder="Enter new password if changing">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn" style="flex: 1;">Save Changes</button>
                <a href="dashboard.php#users" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
