<?php
require 'db.php';
include 'lang.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = clean_input($_POST['username']);
    $password = password_hash(clean_input($_POST['password']), PASSWORD_DEFAULT);
    $real_name = clean_input($_POST['real_name']);
    $room_number = clean_input($_POST['room_number']);
    $phone = clean_input($_POST['phone']);

    try {
        $stmt = $conn->prepare("INSERT INTO owners (username, password, real_name, room_number, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $real_name, $room_number, $phone);
        
        if ($stmt->execute()) {
            header("Location: owner_login.php?success=1");
            exit;
        } else {
            throw new Exception(t('register_failed'));
        }
    } catch (Exception $e) {
        $error = t('register_failed') . '：' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('owner_register')); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <div class="container">
        <div class="header">
            <h2><?php echo htmlspecialchars(t('owner_register')); ?></h2>
            <a href="index.php" class="back-btn"><?php echo htmlspecialchars(t('return_home')); ?></a>
        </div>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="username"><?php echo htmlspecialchars(t('username')); ?></label>
                <input id="username" type="text" name="username" placeholder="<?php echo htmlspecialchars(t('username')); ?>" required
                    pattern="[a-zA-Z0-9_]{4,20}" title="<?php echo htmlspecialchars(t('username_pattern')); ?>">
            </div>

            <div class="form-group">
                <label for="password"><?php echo htmlspecialchars(t('password')); ?></label>
                <input id="password" type="password" name="password" placeholder="<?php echo htmlspecialchars(t('password')); ?>" required
                    pattern=".{6,}" title="<?php echo htmlspecialchars(t('password_pattern')); ?>">
            </div>

            <div class="form-group">
                <label for="confirm_password"><?php echo htmlspecialchars(t('confirm_password')); ?></label>
                <input id="confirm_password" type="password" name="confirm_password" placeholder="<?php echo htmlspecialchars(t('confirm_password')); ?>" required>
            </div>

            <div class="form-group">
                <label for="real_name"><?php echo htmlspecialchars(t('real_name')); ?></label>
                <input id="real_name" type="text" name="real_name" placeholder="<?php echo htmlspecialchars(t('real_name')); ?>" required
                pattern="[a-zA-Z\s\u4e00-\u9fa5]{2,50}" title="<?php echo htmlspecialchars(t('real_name_pattern')); ?>">
            </div>

            <div class="form-group">
                <label for="room_number"><?php echo htmlspecialchars(t('room_number')); ?></label>
                <input id="room_number" type="text" name="room_number" placeholder="<?php echo htmlspecialchars(t('room_number')); ?>" required
                    pattern="[0-9]{1,3}-[0-9]{1,4}" title="<?php echo htmlspecialchars(t('room_number_pattern')); ?>">
            </div>

            <div class="form-group">
                <label for="phone"><?php echo htmlspecialchars(t('phone')); ?></label>
                <input id="phone" type="tel" name="phone" placeholder="<?php echo htmlspecialchars(t('phone')); ?>" required
                    pattern="01[0-9]{8,9}" title="<?php echo htmlspecialchars(t('phone_pattern')); ?>">
            </div>

            <button type="submit"><?php echo htmlspecialchars(t('register')); ?></button>
            <p class="text-center">
                <?php echo htmlspecialchars(t('login')); ?>? <a href="owner_login.php"><?php echo htmlspecialchars(t('login')); ?></a>
            </p>
        </form>
    </div>

    <script>
    function validateForm() {
        var password = document.getElementById("password").value;
        var confirm_password = document.getElementById("confirm_password").value;

        if (password != confirm_password) {
            alert("<?php echo htmlspecialchars(t('password_mismatch')); ?>");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>