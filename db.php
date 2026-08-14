<?php
// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visitor";

try {
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // 检查连接
    if ($conn->connect_error) {
        throw new Exception("连接失败: " . $conn->connect_error);
    }
    
    // 设置字符集
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("数据库错误: " . $e->getMessage());
}

// 通用的数据清理函数
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>