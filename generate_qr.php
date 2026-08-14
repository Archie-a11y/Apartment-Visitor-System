<?php
session_start();
require 'db.php';
include 'lang.php';

// 检查是否是POST请求
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php?error=" . urlencode(t('illegal_access')));
    exit;
}

try {
    // 验证并清理输入数据
    $owner_name = clean_input($_POST['owner_name']);
    $visitor_name = clean_input($_POST['visitor_name']);
    $visitor_id_card = clean_input($_POST['visitor_id_card']);
    $visit_date = clean_input($_POST['visit_date']);

    // 验证马来西亚身份证格式
    if (!preg_match('/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/', $visitor_id_card)) {
        throw new Exception(t('idcard_format_invalid'));
    }

    // 验证访问日期
    $visit_timestamp = strtotime($visit_date);
    $today = strtotime(date('Y-m-d'));
    $max_date = strtotime('+7 days', $today);

    if ($visit_timestamp < $today) {
        throw new Exception(t('visit_date_past'));
    }
    if ($visit_timestamp > $max_date) {
        throw new Exception(t('visit_date_range_exceed'));
    }

    // 准备SQL语句
    $stmt = $conn->prepare("INSERT INTO visitors (owner_name, visitor_name, visitor_id_card, visit_time) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception(t('db_prepare_failed'));
    }

    $stmt->bind_param("ssss", $owner_name, $visitor_name, $visitor_id_card, $visit_date);
    
    if (!$stmt->execute()) {
        throw new Exception(t('save_visitor_failed'));
    }

    $visitor_id = $stmt->insert_id;
    
    // 确保qrcodes目录存在
    if (!file_exists('qrcodes')) {
        mkdir('qrcodes', 0777, true);
    }

    // 引入phpqrcode库
    if (!class_exists('QRcode')) {
        require_once 'phpqrcode/qrlib.php';
    }
    
    // 生成二维码
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $path = dirname($_SERVER['PHP_SELF']);
    $qrData = $current_url . $path . "/scan_verify.php?id=" . $visitor_id;
    
    $qrFile = "qrcodes/visitor_" . $visitor_id . ".png";
    
    // 使用 QRcode 类生成二维码，设置错误级别
    QRcode::png($qrData, $qrFile, constant('QR_ECLEVEL_L'), 10);
    
    // 显示成功页面
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('qr_generated')); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars(t('qr_generated')); ?></h2>
        <div class="visitor-info">
            <p><strong><?php echo htmlspecialchars(t('owner_name')); ?>：</strong><?php echo htmlspecialchars($owner_name); ?></p>
            <p><strong><?php echo htmlspecialchars(t('visitor_name')); ?>：</strong><?php echo htmlspecialchars($visitor_name); ?></p>
            <p><strong><?php echo htmlspecialchars(t('visitor_id_card')); ?>：</strong><?php echo htmlspecialchars($visitor_id_card); ?></p>
            <p><strong><?php echo htmlspecialchars(t('visit_date')); ?>：</strong><?php echo date('Y年m月d日', strtotime($visit_date)); ?></p>
        </div>
        <div class="qr-code">
            <h3><?php echo htmlspecialchars(t('qr_generated')); ?></h3>
            <?php if (file_exists($qrFile)): ?>
                <img src="<?php echo htmlspecialchars($qrFile); ?>" alt="访客二维码">
                <p class="hint"><?php echo htmlspecialchars(t('please_save_qr')); ?></p>
            <?php else: ?>
                <p class="error-message"><?php echo htmlspecialchars(t('qr_failed')); ?></p>
            <?php endif; ?>
        </div>
        <div class="actions">
            <button onclick="window.print()"><?php echo htmlspecialchars(t('print_qr')); ?></button>
            <a href="register.php"><button type="button"><?php echo htmlspecialchars(t('back_register')); ?></button></a>
        </div>
    </div>
</body>
</html>
<?php
} catch (Exception $e) {
    header("Location: register.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>