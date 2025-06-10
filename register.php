<?php
session_start();

// Αν ο χρήστης είναι ήδη συνδεδεμένος, redirect στην αρχική
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

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

$errors = [];
$success_message = '';

// Επεξεργασία φόρμας εγγραφής
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_input = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');

    // Επικύρωση δεδομένων
    if (empty($username_input)) {
        $errors[] = "Το όνομα χρήστη είναι υποχρεωτικό";
    } elseif (strlen($username_input) < 3) {
        $errors[] = "Το όνομα χρήστη πρέπει να έχει τουλάχιστον 3 χαρακτήρες";
    }

    if (empty($email)) {
        $errors[] = "Το email είναι υποχρεωτικό";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Μη έγκυρη διεύθυνση email";
    }

    if (empty($password_input)) {
        $errors[] = "Ο κωδικός πρόσβασης είναι υποχρεωτικός";
    } elseif (strlen($password_input) < 6) {
        $errors[] = "Ο κωδικός πρόσβασης πρέπει να έχει τουλάχιστον 6 χαρακτήρες";
    }

    if ($password_input !== $confirm_password) {
        $errors[] = "Οι κωδικοί πρόσβασης δεν ταιριάζουν";
    }

    if (empty($first_name)) {
        $errors[] = "Το όνομα είναι υποχρεωτικό";
    }

    if (empty($last_name)) {
        $errors[] = "Το επώνυμο είναι υποχρεωτικό";
    }

    // Έλεγχος αν υπάρχει ήδη το username ή email
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username_input, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Το όνομα χρήστη ή το email υπάρχει ήδη";
        }
    }

    // Αν δεν υπάρχουν λάθη, δημιουργία λογαριασμού
    if (empty($errors)) {
        $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, phone, address, city, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username_input, $email, $hashed_password, $first_name, $last_name, $phone, $address, $city, $postal_code]);
            
            // Αυτόματη σύνδεση μετά την εγγραφή
            $user_id = $pdo->lastInsertId();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            $_SESSION['username'] = $username_input;
            
            // Redirect στην αρχική με μήνυμα επιτυχίας
            header("Location: index.php?registered=1");
            exit();
            
        } catch(PDOException $e) {
            $errors[] = "Σφάλμα κατά τη δημιουργία του λογαριασμού: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εγγραφή - AEGEANESHOP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">🛒</div>
                    <span>AEGEANESHOP</span>
                </div>
                <nav class="nav-links">
                    <a href="index.php">Αρχική</a>
                    <a href="login.php">Σύνδεση</a>
                    <a href="register.php">Εγγραφή</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Registration Form Section -->
    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-header">
                    <h1>Δημιουργία Λογαριασμού</h1>
                    <p>Εγγραφείτε για να ξεκινήσετε τις αγορές σας στο TechStore</p>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">Όνομα *</label>
                            <input type="text" id="first_name" name="first_name" 
                                   value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" 
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Επώνυμο *</label>
                            <input type="text" id="last_name" name="last_name" 
                                   value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Όνομα Χρήστη *</label>
                        <input type="text" id="username" name="username" 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                               required>
                        <small>Τουλάχιστον 3 χαρακτήρες</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                               required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Κωδικός Πρόσβασης *</label>
                            <input type="password" id="password" name="password" required>
                            <small>Τουλάχιστον 6 χαρακτήρες</small>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Επιβεβαίωση Κωδικού *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Τηλέφωνο</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="address">Διεύθυνση</label>
                        <textarea id="address" name="address" rows="2"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">Πόλη</label>
                            <input type="text" id="city" name="city" 
                                   value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Ταχυδρομικός Κώδικας</label>
                            <input type="text" id="postal_code" name="postal_code" 
                                   value="<?php echo htmlspecialchars($_POST['postal_code'] ?? ''); ?>">
                        </div>
                    </div>

                    <button type="submit" class="auth-button">Δημιουργία Λογαριασμού</button>
                </form>

                <div class="auth-footer">
                    <p>Έχετε ήδη λογαριασμό; <a href="login.php">Συνδεθείτε εδώ</a></p>
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
</body>
</html>   