<?php
session_start();
include 'lang.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('scan_verify')); ?></title>
    <link rel="stylesheet" href="style.css">
    <!-- 添加 jsQR 库用于扫描二维码 -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <style>
        #preview {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
        }
        #videoContainer {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        #qrVideo {
            width: 100%;
            border-radius: 8px;
        }
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid #4a90e2;
            border-radius: 8px;
        }
        .status-valid { color: #28a745; }
        .status-early { color: #ffc107; }
        .status-expired { color: #dc3545; }
        #visitorInfo {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo htmlspecialchars(t('scan_verify')); ?></h2>
            <a href="index.php" class="back-btn"><?php echo htmlspecialchars(t('return_home')); ?></a>
        </div>

        <div id="scannerSection">
            <div id="videoContainer">
                <video id="qrVideo" playsinline></video>
                <div class="scanner-overlay"></div>
            </div>
            <p class="hint"><?php echo htmlspecialchars(t('scan_hint')); ?></p>
        </div>

        <div id="visitorInfo"></div>
    </div>

    <script>
        const video = document.getElementById('qrVideo');
        const visitorInfo = document.getElementById('visitorInfo');

        // 启动摄像头
        async function startScanner() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "environment" }
                });
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(scan);
            } catch (err) {
                console.error('无法访问摄像头:', err);
                alert('<?php echo htmlspecialchars(t('camera_unavailable')); ?>');
            }
        }

        // 扫描二维码
        function scan() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                
                if (code) {
                    // 检查是否是我们系统的二维码URL
                    if (code.data.includes('scan_verify.php?id=')) {
                        const url = new URL(code.data);
                        const visitorId = url.searchParams.get('id');
                        if (visitorId) {
                            // 停止扫描
                            const stream = video.srcObject;
                            if (stream) {
                                stream.getTracks().forEach(track => track.stop());
                            }
                            // 加载访客信息
                            window.location.href = `scan_verify.php?id=${visitorId}`;
                            return;
                        }
                    }
                }
            }
            requestAnimationFrame(scan);
        }

        // 如果是直接访问页面（没有访客ID参数），启动扫描器
        if (!window.location.search.includes('id=')) {
            startScanner();
        }
    </script>

    <?php
    if (isset($_GET['id'])) {
        require 'db.php';
        
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM visitors WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

            if ($visitor = $result->fetch_assoc()) {
            // 检查访问日期
            $visit_date = date('Y-m-d', strtotime($visitor['visit_time']));
            $today = date('Y-m-d');

            $status = "valid";
            $message = "";

            if ($visit_date > $today) {
                $status = "early";
                $message = t('not_yet');
            } elseif ($visit_date < $today) {
                $status = "expired";
                $message = t('expired_msg');
            }
            ?>
            <script>
                document.getElementById('scannerSection').style.display = 'none';
                const infoDiv = document.getElementById('visitorInfo');
                infoDiv.style.display = 'block';
                infoDiv.innerHTML = `
                    <div class="verification-result status-<?php echo $status; ?>">
                        <h3><?php echo $status === "valid" ? '✅ ' . htmlspecialchars(t('verification_passed')) : htmlspecialchars($message); ?></h3>
                    </div>
                    <div class="visitor-details">
                        <h3><?php echo htmlspecialchars(t('visitor_info')); ?></h3>
                        <table>
                            <tr>
                                <td><strong><?php echo htmlspecialchars(t('visitor_name')); ?>：</strong></td>
                                <td><?php echo htmlspecialchars($visitor['visitor_name']); ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo htmlspecialchars(t('visitor_id_card')); ?>：</strong></td>
                                <td><?php echo htmlspecialchars($visitor['visitor_id_card']); ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo htmlspecialchars(t('owner_name')); ?>：</strong></td>
                                <td><?php echo htmlspecialchars($visitor['owner_name']); ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo htmlspecialchars(t('visit_date')); ?>：</strong></td>
                                <td><?php echo date('Y年m月d日', strtotime($visitor['visit_time'])); ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php if ($status === "valid"): ?>
                    <div class="approval-section">
                        <p class="notice"><?php echo htmlspecialchars(t('check_id')); ?></p>
                    </div>
                    <?php endif; ?>
                    <button onclick="window.location.href='scan_verify.php'" class="secondary-btn"><?php echo htmlspecialchars(t('continue_scan')); ?></button>
                `;
            </script>
            <?php
        }
    }
    ?>
</body>
</html>