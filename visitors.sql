-- 创建数据库（如果不存在）
CREATE DATABASE IF NOT EXISTS apartment_visitor_system;
USE apartment_visitor_system;

-- 业主表（需要先创建，因为它被访客表引用）
CREATE TABLE owners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(20) UNIQUE,
  password VARCHAR(255),
  real_name VARCHAR(100),
  room_number VARCHAR(10),
  phone VARCHAR(11),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 访客表（后创建，因为它依赖于业主表）
CREATE TABLE visitors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  owner_id INT,
  owner_name VARCHAR(100),
  visitor_name VARCHAR(100),
  visitor_id_card VARCHAR(20),
  visit_time DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (owner_id) REFERENCES owners(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
