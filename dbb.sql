CREATE DATABASE IF NOT EXISTS skl_exports;
USE skl_exports;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_name VARCHAR(100),
    buy_dept VARCHAR(100),
    merchandiser VARCHAR(100),
    order_type VARCHAR(100),
    oc_no VARCHAR(100),
    style_no VARCHAR(100),
    po_num VARCHAR(100),
    order_date DATE,
    delivery_date DATE,
    order_qty INT,
    production_qty INT,
    uom VARCHAR(20),
    order_status VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS time_management (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    task_name VARCHAR(255),
    is_completed TINYINT(1) DEFAULT 0,
    base_task VARCHAR(255),
    base_process VARCHAR(255),
    incharge VARCHAR(255),
    based_on VARCHAR(255),
    lead_days INT,
    work_days INT,
    start_date DATE,
    end_date DATE,
    oc_no VARCHAR(50),
    completion_status VARCHAR(20),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    merchandiser VARCHAR(100)
);

