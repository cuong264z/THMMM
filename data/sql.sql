CREATE DATABASE IF NOT EXISTS my_store
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE my_store;

-- CATEGORY
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCT
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(15,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_product_category
    FOREIGN KEY (category_id)
    REFERENCES categories(id)
    ON DELETE SET NULL
);

-- PRODUCT IMAGES
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,

    CONSTRAINT fk_product_image
    FOREIGN KEY (product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);

-- ORDERS
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ORDER DETAILS
CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(15,2) NOT NULL,

    CONSTRAINT fk_order_detail_order
    FOREIGN KEY (order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_order_detail_product
    FOREIGN KEY (product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);

-- SAMPLE DATA
INSERT INTO categories(name, description)
VALUES
('Laptop','Laptop các loại'),
('Điện thoại','Smartphone'),
('Phụ kiện','Thiết bị phụ kiện');

INSERT INTO products (
    name,
    description,
    price,
    image,
    category_id
)
VALUES
(
    'Macbook Pro M3',
    'Laptop Apple',
    45000000,
    'macbook.jpg',
    1
),
(
    'iPhone 15 Pro Max',
    'Điện thoại Apple',
    32000000,
    'iphone.jpg',
    2
),
(
    'AirPods Pro',
    'Tai nghe Apple',
    6500000,
    'airpods.jpg',
    3
);


CREATE TABLE account (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL UNIQUE,
fullname VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('admin', 'user') DEFAULT 'user'
);account