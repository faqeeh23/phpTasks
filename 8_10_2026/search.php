<?php

$search = $_GET["q"] ?? "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>

<form method="get">

    <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>">

    <button type="submit">Search</button>

</form>

<?php
if ($search != "") {
    echo "Search result for: " . htmlspecialchars($search);
}
?>

</body>
</html>