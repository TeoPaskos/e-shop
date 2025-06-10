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

// Επεξεργασία φόρμας σύνδεσης
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = trim($_POST['login'] ?? ''); // Μπορεί να είναι username ή email
    $password_input = $_POST['password'] ?? '';

    // Επικύρωση δεδομένων
    if (empty($login_input)) {
        $errors[] = "Το όνομα χρήστη/email είναι υποχρεωτικό";
    }

    if (empty($password_input)) {
        $errors[] = "Ο κωδικός πρόσβασης είναι υποχρεωτικός";
    }

    // Αν δεν υπάρχουν λάθη, έλεγχος διαπιστευτηρίων
    if (empty($errors)) {
        try {
            // Αναζήτηση χρήστη με username ή email
            $stmt = $pdo->prepare("SELECT id, username, email, password, first_name, last_name FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$login_input, $login_input]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password_input, $user['password'])) {
                // Επιτυχής σύνδεση
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                // Redirect στην αρχική σελίδα
                $redirect_url = $_GET['redirect'] ?? 'index.php';
                header("Location: " . $redirect_url);
                exit();
            } else {
                $errors[] = "Λάθος όνομα χρήστη/email ή κωδικός πρόσβασης";
            }
        } catch(PDOException $e) {
            $errors[] = "Σφάλμα κατά τη σύνδεση: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Σύνδεση - AEGEANESHOP</title>
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

    <!-- Login Form Section -->
    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-header">
                    <h1>Σύνδεση</h1>
                    <p>Συνδεθείτε στον λογαριασμό σας για να συνεχίσετε</p>
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
                    <div class="form-group">
                        <label for="login">Όνομα Χρήστη ή Email</label>
                        <input type="text" id="login" name="login" 
                               value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="password">Κωδικός Πρόσβασης</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="auth-button">Σύνδεση</button>
                </form>

                <div class="auth-footer">
                    <p>Δεν έχετε λογαριασμό; <a href="register.php">Εγγραφείτε εδώ</a></p>
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