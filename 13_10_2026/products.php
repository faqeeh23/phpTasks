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

// Handle Add Product Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "add_product") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = $_POST["price"] ?? "";
    $quantity = $_POST["quantity"] ?? "";

    if (empty($name) || $price === "" || $quantity === "") {
        $error = "Product name, price, and quantity are required.";
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $error = "Price must be a valid positive number.";
    } elseif (!is_numeric($quantity) || (int)$quantity < 0) {
        $error = "Quantity must be a valid non-negative integer.";
    } else {
        $priceFloat = (float)$price;
        $quantityInt = (int)$quantity;

        // Insert product using prepared statement
        $insertStmt = $conn->prepare("INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssdi", $name, $description, $priceFloat, $quantityInt);

        if ($insertStmt->execute()) {
            $success = "Product added successfully.";
        } else {
            $error = "Failed to add product: " . $conn->error;
        }
        $insertStmt->close();
    }
}

// Fetch All Products
$products = [];
$productsQuery = $conn->query("SELECT id, name, description, price, quantity, created_at FROM products ORDER BY id DESC");
if ($productsQuery && $productsQuery->num_rows > 0) {
    while ($row = $productsQuery->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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
                <li><a href="users.php">Users</a></li>
                <li><a href="products.php" class="active">Products</a></li>
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

        <!-- Product Management Block -->
        <section class="section-block">
            <div class="section-header">
                <h2>Products List (<?php echo count($products); ?>)</h2>
                <button type="button" class="btn btn-sm" onclick="toggleForm('addProductCard')">+ Add Product</button>
            </div>

            <!-- Add Product Form -->
            <div id="addProductCard" class="form-card" style="display: none;">
                <h3>Add New Product</h3>
                <form method="POST" action="products.php">
                    <input type="hidden" name="action" value="add_product">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="p_name">Product Name *</label>
                            <input type="text" id="p_name" name="name" required placeholder="e.g. Mechanical Keyboard">
                        </div>
                        <div class="form-group">
                            <label for="p_price">Price ($) *</label>
                            <input type="number" id="p_price" name="price" step="0.01" min="0" required placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="p_quantity">Quantity *</label>
                            <input type="number" id="p_quantity" name="quantity" min="0" required placeholder="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="p_desc">Description</label>
                        <textarea id="p_desc" name="description" placeholder="Brief description of the product..."></textarea>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-sm">Create Product</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('addProductCard')">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th># ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p["id"]); ?></td>
                                    <td><strong><?php echo htmlspecialchars($p["name"]); ?></strong></td>
                                    <td><?php echo htmlspecialchars($p["description"] ?: "—"); ?></td>
                                    <td class="price-tag">$<?php echo number_format((float)$p["price"], 2); ?></td>
                                    <td>
                                        <?php if ((int)$p["quantity"] > 5): ?>
                                            <span class="badge badge-stock"><?php echo (int)$p["quantity"]; ?> in stock</span>
                                        <?php elseif ((int)$p["quantity"] > 0): ?>
                                            <span class="badge badge-low"><?php echo (int)$p["quantity"]; ?> low stock</span>
                                        <?php else: ?>
                                            <span class="badge badge-out">Out of stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($p["created_at"]); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                            <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete product <?php echo htmlspecialchars(addslashes($p['name'])); ?>?');">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">No products found. Click "+ Add Product" to create one.</td>
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
