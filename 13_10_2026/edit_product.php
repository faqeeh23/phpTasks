<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "db.php";

$productId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($productId <= 0) {
    header("Location: dashboard.php?error=Invalid product ID.#products");
    exit();
}

$error = "";

// Fetch product details
$stmt = $conn->prepare("SELECT id, name, description, price, quantity FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: dashboard.php?error=Product not found.#products");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = $_POST["price"] ?? "";
    $quantity = $_POST["quantity"] ?? "";

    if (empty($name) || $price === "" || $quantity === "") {
        $error = "Name, price, and quantity are required.";
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $error = "Price must be a valid positive number.";
    } elseif (!is_numeric($quantity) || (int)$quantity < 0) {
        $error = "Quantity must be a valid non-negative integer.";
    } else {
        $priceFloat = (float)$price;
        $quantityInt = (int)$quantity;

        // Update product using prepared statement
        $updateStmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ?");
        $updateStmt->bind_param("ssdii", $name, $description, $priceFloat, $quantityInt, $productId);

        if ($updateStmt->execute()) {
            header("Location: dashboard.php?success=Product updated successfully.#products");
            exit();
        } else {
            $error = "Failed to update product: " . $conn->error;
        }
        $updateStmt->close();
    }
} else {
    $name = $product["name"];
    $description = $product["description"];
    $price = $product["price"];
    $quantity = $product["quantity"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <p class="subtitle">Update product information</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_product.php?id=<?php echo $productId; ?>">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($price); ?>" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="0" value="<?php echo htmlspecialchars($quantity); ?>" required>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn" style="flex: 1;">Save Changes</button>
                <a href="dashboard.php#products" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
