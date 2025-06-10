<?php
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if ($product_id) {
        // Έλεγξε αν υπάρχει ήδη το προϊόν στο καλάθι του χρήστη
        $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $item = $stmt->fetch();

        if ($item) {
            // Αν υπάρχει πάνω από 1, μείωσε κατά 1, αλλιώς διαγραφή
            if ($item['quantity'] > 1) {
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$user_id, $product_id]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$user_id, $product_id]);
            }
        }
    }
}

header('Location: cart.php');
exit;
