-- ARCHIVIO DATABASE
-- Database: archivio_db

CREATE DATABASE IF NOT EXISTS archivio_db;
USE archivio_db;

-- 1. Categories Table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 2. Archives Table
CREATE TABLE IF NOT EXISTS archives (
    archive_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size_kb DECIMAL(10,2) UNSIGNED NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_archive_category
    FOREIGN KEY (category_id)
    REFERENCES categories(category_id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

-- 3. Sample Categories
INSERT IGNORE INTO categories (category_name)
VALUES
    ('School Documents'),
    ('Personal Documents'),
    ('Projects'),
    ('Others');