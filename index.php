<?php
session_start();

// Ενεργοποίηση εμφάνισης λαθών
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Σύνδεση με τη βάση δεδομένων
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eshop_db";
$port = 3320;

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Σφάλμα σύνδεσης: " . $e->getMessage());
}

// Ανάκτηση προϊόντων από τη βάση
$stmt = $pdo->prepare("SELECT * FROM products LIMIT 6");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);







// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
$isLoggedIn = isset($_SESSION['user_id']);
$welcomeMessage = '';  
if ($isLoggedIn) {
    $welcomeMessage = "Καλώς ήρθες, " . $_SESSION['user_name'] . "!";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESHOPAEGEAN- Ηλεκτρονικό Κατάστημα Τεχνολογίας</title>
 		  <link rel="stylesheet" href="style.css" />
 		
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">🛒</div>
                    <span>AEGEAN SHOP</span>
                </div>
                <nav class="nav-links">
                    <a href="index.php">Αρχική</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="profile.php">Προφίλ</a>
                        <a href="cart.php">Καλάθι</a>
                        <a href="logout.php">Αποσύνδεση</a>
                    <?php else: ?>
                        <a href="login.php">Σύνδεση</a>
                        <a href="register.php">Εγγραφή</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Welcome Message για συνδεδεμένους χρήστες -->
    <?php if ($isLoggedIn): ?>
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-message">
                <?php echo htmlspecialchars($welcomeMessage); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php if (!$isLoggedIn): ?>
<section class="hero-section">

    

    <!-- Hero Section -->
   
        <div class="container">
            <div class="hero-content">
                <h1>Καλώς ήρθατε στο AEGEANSHOP</h1>
                <p>Ανακαλύψτε τα καλύτερα προϊόντα τεχνολογίας στις καλύτερες τιμές της αγορας. 
                   (smartphones , laptops , tablet ,smart tv  .</p>
                <?php if (!$isLoggedIn): ?>
                    <a href="register.php" class="cta-button">Ξεκινήστε Τώρα</a>
                <?php else: ?>
                    <a href="#products" class="cta-button">Δείτε τα Προϊόντα</a>
                <?php endif; ?>
            </div>
        </div>

</section>
<?php endif; ?>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="container">
            <h2 class="section-title">Δημοφιλή Προϊόντα</h2>
            
            <?php if (!$isLoggedIn): ?>
            <div class="cart-info">
                <strong>💡 Συμβουλή:</strong> Συνδεθείτε ή εγγραφείτε για να προσθέσετε προϊόντα στο καλάθι σας!
            </div>
            <?php endif; ?>
                        <div class="products-grid">
                <?php if (empty($products)): ?>
                    <!-- Προϊόντα δείγματος αν δεν υπάρχουν στη βάση -->
                    <div class="product-card">
                        <div class="product-image">📱</div>
                        <div class="product-name">iPhone 15 Pro</div>
                        <div class="product-price">€1,199.00</div>
                        <div class="product-description">Το νέο iPhone με προηγμένη κάμερα και A17 Pro chip</div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(1)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>

                    <div class="product-card">
                        <div class="product-image">💻</div>
                        <div class="product-name">MacBook Air M2</div>
                        <div class="product-price">€1,399.00</div>
                        <div class="product-description">Ισχυρός και φορητός με chip M2 και 18 ώρες μπαταρία</div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(2)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>

                    <div class="product-card">
                        <div class="product-image">🎮</div>
                        <div class="product-name">PlayStation 5</div>
                        <div class="product-price">€549.00</div>
                        <div class="product-description">Νέα γενιά gaming με ultra-high speed SSD</div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(3)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>

                    <div class="product-card">
                        <div class="product-image">⌚</div>
                        <div class="product-name">Apple Watch Series 9</div>
                        <div class="product-price">€429.00</div>
                        <div class="product-description">Παρακολουθήστε την υγεία σας με το πιο προηγμένο smartwatch</div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(4)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>

                    <div class="product-card">
                        <div class="product-image">🎧</div>
                        <div class="product-name">AirPods Pro 2</div>
                        <div class="product-price">€279.00</div>
                        <div class="product-description">Ακύρωση θορύβου και χωρικός ήχος νέας γενιάς</div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(5)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>


                    <div class="product-card">
                        <div class="product-image">📷</div>
                        <div class="product-name">Canon EOS R6</div>
                        <div class="product-price">€2,299.00</div>
                        <div class="product-description">Profesional mirrorless κάμερα με 4K video</div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(6)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>


                    <!-- Προϊόντα από τη βάση δεδομένων -->
                    <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image"><?php echo htmlspecialchars($product['emoji'] ?? '📦'); ?></div>
                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price">€<?php echo number_format($product['price'], 2); ?></div>
                        <div class="product-description"><?php echo htmlspecialchars($product['description']); ?></div>
                        <?php if ($isLoggedIn): ?>
                            <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">Προσθήκη στο Καλάθι</button>
                        <?php else: ?>
                            <a href="login.php" class="add-to-cart" style="text-decoration: none; display: inline-block;">Συνδεθείτε για Αγορά</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Company Info Section -->
    <section class="company-info">
        <div class="container">
            <h2 class="section-title">Οι υπηρεσιες μας </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">🚚</div>
                    <div class="info-title">Δωρεάν Μεταφορικά</div>
                    <div class="info-text">Δωρεάν παράδοση για παραγγελίες άνω των €50 σε όλη την Ελλάδα</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">🛡️</div>
                    <div class="info-title">Εγγύηση Ποιότητας</div>
                    <div class="info-text">2 χρόνια εγγύηση σε όλα τα προϊόντα και άμεση τεχνική υποστήριξη</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">💳</div>
                    <div class="info-title">Ασφαλείς Πληρωμές</div>
                    <div class="info-text">Ασφαλής επεξεργασία πληρωμών με SSL encryption</div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-title">24/7 Υποστήριξη</div>
                    <div class="info-text">Η ομάδα μας είναι διαθέσιμη όλο το 24ωρο για να σας βοηθήσει</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025AEGEANESHOP| Σχεδιασμός κ Ανάπτυξη( κωδικος μαθητη ) : st120-10841</p>
        </div>
    </footer>

    <!-- JavaScript για το καλάθι -->
   <script src="script.js"></script>
 </body>
 </html>
