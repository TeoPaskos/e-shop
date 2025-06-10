<?php
require 'database.php';

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Καλάθι Αγορών</title>
</head>
<body>
    <h2>Το καλάθι σου</h2>

    <?php if (empty($cart)): ?>
        <p>Το καλάθι είναι άδειο.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cart as $id => $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['name']); ?> -
                    Ποσότητα: <?php echo $item['quantity']; ?>
                    <form action="remove_from_cart.php" method="post" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <button type="submit">Αφαίρεση</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="index.php">Συνέχεια αγορών</a>
</body>
</html>
