<?php
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Προφίλ Χρήστη</title>
    <style>
body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; }
.profile-section { background: #f8f9fa; border-radius: 18px; box-shadow: 0 8px 32px rgba(44,62,80,0.10); padding: 40px 30px; max-width: 600px; margin: 50px auto 40px auto; border: 2px solid #1e88e5; text-align: center; }
.profile-section h2 { color: #1e88e5; margin-bottom: 30px; font-size: 2.2rem; font-weight: bold; letter-spacing: 1px; }
.profile-details { list-style: none; padding: 0; margin: 0 auto; max-width: 400px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(30,136,229,0.08); border: 1px solid #e0e0e0; }
.profile-details li { padding: 12px 0; border-bottom: 1px solid #f0f0f0; font-size: 1.08rem; color: #333; display: flex; justify-content: space-between; align-items: center; }
.profile-details li:last-child { border-bottom: none; }
.profile-label { font-weight: bold; color: #1e88e5; }
.profile-value { color: #333; }
.profile-logout {
    display: inline-block;
    margin: 30px 10px 0 0;
    background: linear-gradient(45deg, #e74c3c, #ff7675);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 30px;
    font-size: 1.05rem;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(231,76,60,0.08);
}
.profile-logout:hover {
    background: linear-gradient(45deg, #ff7675, #e74c3c);
    box-shadow: 0 4px 16px rgba(231,76,60,0.15);
}
.profile-back {
    display: inline-block;
    margin: 30px 0 0 10px;
    background: linear-gradient(45deg, #1e88e5, #45a049);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 30px;
    font-size: 1.05rem;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(30,136,229,0.08);
}
.profile-back:hover {
    background: linear-gradient(45deg, #45a049, #1e88e5);
    box-shadow: 0 4px 16px rgba(30,136,229,0.15);
}
@media (max-width: 800px) { .profile-section { padding: 20px 5px; max-width: 98vw; } .profile-details li { flex-direction: column; align-items: flex-start; gap: 8px; } }
    </style>
</head>
<body>
    <div class="profile-section">
        <h2>Προφίλ Χρήστη</h2>
        <ul class="profile-details">
            <li><span class="profile-label">Όνομα Χρήστη:</span> <span class="profile-value"><?php echo htmlspecialchars($user['username']); ?></span></li>
            <li><span class="profile-label">Email:</span> <span class="profile-value"><?php echo htmlspecialchars($user['email']); ?></span></li>
            <li><span class="profile-label">Όνομα:</span> <span class="profile-value"><?php echo htmlspecialchars($user['first_name']); ?></span></li>
            <li><span class="profile-label">Επώνυμο:</span> <span class="profile-value"><?php echo htmlspecialchars($user['last_name']); ?></span></li>
            <li><span class="profile-label">Τηλέφωνο:</span> <span class="profile-value"><?php echo htmlspecialchars($user['phone']); ?></span></li>
            <li><span class="profile-label">Διεύθυνση:</span> <span class="profile-value"><?php echo htmlspecialchars($user['address']); ?></span></li>
            <li><span class="profile-label">Πόλη:</span> <span class="profile-value"><?php echo htmlspecialchars($user['city']); ?></span></li>
            <li><span class="profile-label">Τ.Κ.:</span> <span class="profile-value"><?php echo htmlspecialchars($user['postal_code']); ?></span></li>
            <li><span class="profile-label">Ημ/νία Εγγραφής:</span> <span class="profile-value"><?php echo htmlspecialchars($user['created_at']); ?></span></li>
        </ul>
        <a href="logout.php" class="profile-logout">Αποσύνδεση</a>
        <a href="index.php" class="profile-back">Πίσω στην Αρχική</a>
    </div>
</body>
</html>

