<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

$productId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($productId <= 0) {
    header("Location: dashboard.php?error=Invalid product ID.");
    exit();
}

// Delete product using prepared statement
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);

if ($stmt->execute()) {
    header("Location: dashboard.php?success=Product deleted successfully.#products");
} else {
    header("Location: dashboard.php?error=Failed to delete product: " . urlencode($conn->error) . "#products");
}

$stmt->close();
exit();
?>
