<?php
session_start();
require 'db.php';
include 'lang.php';

if (!isset($_SESSION['owner_id'])) {
    header("Location: owner_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('visitor_registration')); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo htmlspecialchars(t('visitor_registration')); ?></h2>
            <div class="user-info">
                <?php echo htmlspecialchars(t('welcome')) . ' ' . htmlspecialchars($_SESSION['owner_name']); ?>
                <a href="logout.php" class="logout-btn"><?php echo htmlspecialchars(t('logout')); ?></a>
            </div>
            <div style="margin-top:10px;">
                <form method="get" id="langForm" style="display:inline-block">
                    <select name="lang" onchange="document.getElementById('langForm').submit();">
                        <option value="en" <?php echo lang_selected('en'); ?>>English</option>
                        <option value="zh" <?php echo lang_selected('zh'); ?>>中文</option>
                        <option value="ms" <?php echo lang_selected('ms'); ?>>Bahasa</option>
                    </select>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="generate_qr.php" onsubmit="return validateForm()">

            <input type="hidden" name="owner_id" value="<?php echo $_SESSION['owner_id']; ?>">
            <input type="hidden" name="owner_name" value="<?php echo $_SESSION['owner_name']; ?>">

            <div class="form-group">
                <label for="visitor_name"><?php echo htmlspecialchars(t('visitor_name')); ?></label>
                <input id="visitor_name" type="text" name="visitor_name" placeholder="<?php echo htmlspecialchars(t('visitor_name')); ?>" 
                    pattern="[\u4e00-\u9fa5]{2,10}" title="请输入2-10个中文字符" required>
            </div>

            <div class="form-group">
                <label for="visitor_id_card"><?php echo htmlspecialchars(t('visitor_id_card')); ?></label>
                <input id="visitor_id_card" type="text" name="visitor_id_card" placeholder="<?php echo htmlspecialchars(t('visitor_id_card')); ?>" 
                    pattern="[0-9]{6}-[0-9]{2}-[0-9]{4}" title="<?php echo htmlspecialchars(t('idcard_format_invalid')); ?>" required>
            </div>

            <div class="form-group">
                <label for="visit_date"><?php echo htmlspecialchars(t('visit_date')); ?>：</label>
                <input type="date" name="visit_date" id="visit_date" required>
            </div>

            <button type="submit" class="submit-btn"><?php echo htmlspecialchars(t('generate_qr')); ?></button>
        </form>

        <div class="visitor-history">
            <h3><?php echo htmlspecialchars(t('recent_visitors')); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th><?php echo htmlspecialchars(t('visitor_name')); ?></th>
                        <th><?php echo htmlspecialchars(t('visit_date')); ?></th>
                        <th><?php echo htmlspecialchars(t('status')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT visitor_name, visit_time FROM visitors WHERE owner_id = ? ORDER BY created_at DESC LIMIT 5");
                    $stmt->bind_param("i", $_SESSION['owner_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while ($row = $result->fetch_assoc()):
                        $visit_date = new DateTime($row['visit_time']);
                        $today = new DateTime();
                        $status = ($visit_date < $today) ? t('expired') : t('valid');
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['visitor_name']); ?></td>
                        <td><?php echo $visit_date->format('Y年m月d日'); ?></td>
                        <td class="<?php echo ($status === t('valid')) ? 'valid' : 'expired'; ?>"><?php echo htmlspecialchars($status); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function validateForm() {
        const visitDate = new Date(document.getElementById('visit_date').value);
        const now = new Date();
        now.setHours(0, 0, 0, 0);
        
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 7);

        if (visitDate < now) {
            alert('<?php echo htmlspecialchars(t('visit_date_past')); ?>');
            return false;
        }
        if (visitDate > maxDate) {
            alert('<?php echo htmlspecialchars(t('visit_date_range_exceed')); ?>');
            return false;
        }

        const idCard = document.getElementById('visitor_id_card').value;
        if (!/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/.test(idCard)) {
            alert('<?php echo htmlspecialchars(t('idcard_format_invalid')); ?>');
            return false;
        }

        return true;
    }

    // 设置日期选择器的最小值为今天
    const now = new Date();
    const month = (now.getMonth() + 1).toString().padStart(2, '0');
    const day = now.getDate().toString().padStart(2, '0');
    const minDate = `${now.getFullYear()}-${month}-${day}`;
    
    document.getElementById('visit_date').min = minDate;
    </script>
</body>
</html>