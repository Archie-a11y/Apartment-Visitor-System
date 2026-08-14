<?php
session_start();
require 'db.php';
include 'lang.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    $stmt = $conn->prepare("SELECT id, username, password, real_name FROM owners WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['owner_id'] = $user['id'];
            $_SESSION['owner_name'] = $user['real_name'];
            $_SESSION['username'] = $user['username'];
            header("Location: register.php");
            exit;
        }
    }
    $error = t('incorrect_credentials');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('owner_login')); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <div class="container">
        <div class="header">
            <h2><?php echo htmlspecialchars(t('owner_login')); ?></h2>
            <a href="index.php" class="back-btn"><?php echo htmlspecialchars(t('return_home')); ?></a>
        </div>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars(t('register_success')); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username"><?php echo htmlspecialchars(t('username')); ?></label>
                <input id="username" type="text" name="username" placeholder="<?php echo htmlspecialchars(t('username')); ?>" required>
            </div>

            <div class="form-group">
                <label for="password"><?php echo htmlspecialchars(t('password')); ?></label>
                <input id="password" type="password" name="password" placeholder="<?php echo htmlspecialchars(t('password')); ?>" required>
            </div>

            <button type="submit"><?php echo htmlspecialchars(t('login')); ?></button>
            <p class="text-center">
                <?php echo htmlspecialchars(t('no_account')); ?> <a href="owner_register.php"><?php echo htmlspecialchars(t('register')); ?></a>
            </p>
        </form>
    </div>
</body>
</html>