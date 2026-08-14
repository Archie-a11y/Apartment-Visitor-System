<?php include 'lang.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(t('app_title')); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard {
            text-align: center;
            padding: 40px 20px;
        }

        .role-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .role-button {
            background-color: #fff;
            border: 2px solid #4a90e2;
            color: #4a90e2;
            padding: 30px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .role-button:hover {
            background-color: #4a90e2;
            color: #fff;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
        }

        .role-button i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .role-button span {
            font-size: 18px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .role-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="dashboard">
            <h1><?php echo htmlspecialchars(t('app_title')); ?></h1>
            <p><?php echo htmlspecialchars(t('select_role')); ?></p>
            <div style="margin-top:10px;">
                <form method="get" id="langForm" style="display:inline-block">
                    <select name="lang" onchange="document.getElementById('langForm').submit();">
                        <option value="en" <?php echo lang_selected('en'); ?>>English</option>
                        <option value="zh" <?php echo lang_selected('zh'); ?>>中文</option>
                        <option value="ms" <?php echo lang_selected('ms'); ?>>Bahasa</option>
                    </select>
                </form>
            </div>
            
            <div class="role-buttons">
                <a href="owner_login.php" class="role-button">
                    <i class="fas fa-home"></i>
                    <span><?php echo htmlspecialchars(t('owner_portal')); ?></span>
                </a>
                
                <a href="visitor_verify.php" class="role-button">
                    <i class="fas fa-user-friends"></i>
                    <span><?php echo htmlspecialchars(t('visitor_verification')); ?></span>
                </a>
                
                <a href="scan_verify.php" class="role-button">
                    <i class="fas fa-qrcode"></i>
                    <span><?php echo htmlspecialchars(t('security_scan')); ?></span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>