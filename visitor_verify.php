<?php
require 'db.php';
include 'lang.php';

$message = "";
$visitor = null;

if (isset($_POST['visitor_id_card'])) {
    $id_card = clean_input($_POST['visitor_id_card']);
    
    $stmt = $conn->prepare("SELECT * FROM visitors WHERE visitor_id_card = ? ORDER BY visit_time DESC LIMIT 1");
    $stmt->bind_param("s", $id_card);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $visitor = $result->fetch_assoc();
    } else {
        $message = t('not_found');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('visitor_verification')); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo htmlspecialchars(t('visitor_verification')); ?></h2>
            <a href="index.php" class="back-btn"><?php echo htmlspecialchars(t('return_home')); ?></a>
        </div>

        <?php if (!$visitor): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="visitor_id_card"><?php echo htmlspecialchars(t('visitor_id_card')); ?></label>
                    <input id="visitor_id_card" type="text" name="visitor_id_card" 
                        placeholder="<?php echo htmlspecialchars(t('visitor_id_card')); ?>" 
                        pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}" 
                        title="<?php echo htmlspecialchars(t('idcard_format_invalid')); ?>"
                        required>
                </div>
                <button type="submit"><?php echo htmlspecialchars(t('query_visit')); ?></button>
            </form>

            <?php if ($message): ?>
                <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        <?php else: ?>
            <div class="visitor-info">
                <h3><?php echo htmlspecialchars(t('visitor_info')); ?></h3>
                <table>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(t('visitor_name')); ?>：</strong></td>
                        <td><?php echo htmlspecialchars($visitor['visitor_name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(t('owner_name')); ?>：</strong></td>
                        <td><?php echo htmlspecialchars($visitor['owner_name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(t('visit_date')); ?>：</strong></td>
                        <td><?php echo date('Y年m月d日', strtotime($visitor['visit_time'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(t('status')); ?>：</strong></td>
                        <td><?php 
                            $visit_date = date('Y-m-d', strtotime($visitor['visit_time']));
                            $today = date('Y-m-d');
                            if ($visit_date == $today) {
                                echo '<span class="valid">' . htmlspecialchars(t('today_valid')) . '</span>';
                            } elseif ($visit_date > $today) {
                                echo '<span class="early">' . htmlspecialchars(t('early')) . '</span>';
                            } else {
                                echo '<span class="expired">' . htmlspecialchars(t('expired')) . '</span>';
                            }
                        ?></td>
                    </tr>
                </table>

                <?php if ($visit_date == $today): ?>
                    <div class="qr-code">
                        <h4><?php echo htmlspecialchars(t('qr_generated')); ?></h4>
                        <?php
                        $qrFile = "qrcodes/visitor_" . $visitor['id'] . ".png";
                        if (file_exists($qrFile)): ?>
                            <img src="<?php echo htmlspecialchars($qrFile); ?>" alt="<?php echo htmlspecialchars(t('qr_generated')); ?>">
                        <?php else: ?>
                            <p class="error-message"><?php echo htmlspecialchars('QR file not found'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <button onclick="window.location.href='visitor_verify.php'" class="secondary-btn"><?php echo htmlspecialchars(t('query_visit')); ?></button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>