<?php
$translations = [
    'app_title' => '公寓访客系统',
    'select_role' => '请选择您的身份',
    'owner_portal' => '业主入口',
    'visitor_verification' => '访客验证',
    'security_scan' => '保安扫码',

    'owner_login' => '业主登录',
    'username' => '用户名',
    'password' => '密码',
    'login' => '登录',
    'no_account' => '还没有账号？注册',
    'return_home' => '返回首页',

    'owner_register' => '业主注册',
    'confirm_password' => '确认密码',
    'real_name' => '真实姓名',
    'room_number' => '房间号',
    'phone' => '手机号',
    'register' => '注册',

    'visitor_registration' => '访客登记',
    'welcome' => '欢迎，',
    'logout' => '退出登录',
    'visitor_name' => '访客姓名',
    'visitor_id_card' => '身份证号',
    'visit_date' => '访问日期',
    'generate_qr' => '生成访客二维码',
    'recent_visitors' => '最近访客记录',
    'status' => '状态',
    'valid' => '有效',
    'expired' => '已过期',

    'qr_generated' => '访客二维码已生成',
    'owner_name' => '业主姓名',
    'please_save_qr' => '请保存此二维码，在访问时出示给保安',
    'qr_failed' => '二维码生成失败，请稍后重试',
    'print_qr' => '打印二维码',
    'back_register' => '返回登记页面',

    'scan_verify' => '访客扫码验证',
    'scan_hint' => '请将访客二维码对准摄像头',
    'verification_passed' => '验证通过',
    'not_yet' => '还未到访问日期',
    'expired_msg' => '访问日期已过期',
    'visitor_info' => '访问信息',
    'check_id' => '请核对访客身份证件',
    'continue_scan' => '继续扫码',

    'query_visit' => '查询访问信息',
    'not_found' => '未找到访客记录',
    'today_valid' => '今日有效',
    'early' => '未到访问日期',
    'incorrect_credentials' => '用户名或密码错误',
    'register_success' => '注册成功，请登录',
    'password_mismatch' => '两次输入的密码不一致',
    'visit_date_past' => '访问日期不能早于今天',
    'visit_date_range_exceed' => '最多只能预约7天内的访问日期',
    'idcard_format_invalid' => '请输入正确的马来西亚身份证格式(如990101-01-1234)'
    , 'illegal_access' => '非法访问'
    , 'db_prepare_failed' => '数据库准备语句失败'
    , 'save_visitor_failed' => '保存访客信息失败'
    , 'register_failed' => '注册失败'
    , 'camera_unavailable' => '无法访问摄像头，请确保已授予摄像头权限。'
    , 'qr_file_not_found' => '二维码文件未找到'
    , 'username_pattern' => '用户名需要4-20个字符，只能包含字母、数字和下划线'
    , 'password_pattern' => '密码至少需要6个字符'
    , 'real_name_pattern' => '请输入2-50位真实姓名（支持中英文、空格）'
    , 'room_number_pattern' => '请输入正确的房间号格式，如：1-101'
    , 'phone_pattern' => '请输入正确的马来西亚手机号(如0123456789)'
    , 'date_format' => 'Y-m-d'
];

return $translations;
