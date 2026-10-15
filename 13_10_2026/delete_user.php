<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

$userId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($userId <= 0) {
    header("Location: dashboard.php?error=Invalid user ID.");
    exit();
}

// Prevent user from deleting their own currently logged-in account
if ($userId === (int)$_SESSION["user_id"]) {
    header("Location: dashboard.php?error=You cannot delete your own logged-in account.");
    exit();
}

// Delete user using prepared statement
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    header("Location: dashboard.php?success=User deleted successfully.");
} else {
    header("Location: dashboard.php?error=Failed to delete user: " . urlencode($conn->error));
}

$stmt->close();
exit();
?>
