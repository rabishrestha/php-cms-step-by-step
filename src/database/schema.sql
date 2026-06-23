-- 1. Initialize Advanced Isolated Database Space Layer
CREATE DATABASE IF NOT EXISTS hamro_news_advance DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hamro_news_advance;

-- 2. Construct Normalized Categories Table Layout
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Construct Normalized Users Table Layout
-- Re-run or replace the users table definition block:
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contact_no VARCHAR(20) DEFAULT NULL,
    role ENUM('Admin', 'Reporter') NOT NULL DEFAULT 'Reporter',
    status ENUM('Pending', 'Active', 'Inactive') NOT NULL DEFAULT 'Pending',
    email_confirmed TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Pre-seed default administrator credentials safely matching active status rules
INSERT INTO users (username, password, full_name, email, contact_no, role, status, email_confirmed) VALUES
('admin', '$2a$12$DJNmlORunjJpelglMwnCXOw4nrJRNkM9g4rRvDAiFU2fr2kQ.yyKm', 'Rabi Shrestha', 'admin@hamronews.com', '+977-01-4444444', 'Admin', 'Active', 1)
ON DUPLICATE KEY UPDATE id=id;

-- 4. Construct Advanced Relational Posts Table Layout
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(100) DEFAULT 'default.jpg',
    published_at DATETIME NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Establish Performance Optimization Full-Text Search Indices
ALTER TABLE posts ADD FULLTEXT idx_search_content (title, summary, content);

-- 6. Pre-Seed Entities (Categories and User Accounts)
INSERT INTO categories (name, slug) VALUES 
('National', 'national'), ('Tourism', 'tourism'), ('Technology', 'technology'), 
('Sports', 'sports'), ('Business', 'business'), ('Culture', 'culture');


-- 7. Pre-Seed Relational Posts Mapped via Foreign Keys
INSERT INTO posts (user_id, category_id, title, slug, summary, content, published_at, image) VALUES
(1, 1, 'Monsoon Rainfall Alerts Active Across Koshi and Bagmati Provinces', 'monsoon-rainfall-alerts-koshi-bagmati', 'The Department of Hydrology and Meteorology issues warnings urging caution in mountainous areas due to rising river levels.', 'As the monsoon season intensifies across Nepal, continuous heavy downpours have triggered elevated risks of flash floods and landslides.', '2026-06-22 08:30:00', 'monsoon.jpg'),
(1, 2, 'Nepal Tourism Board Reports Surge in Summer Trekking Registrations', 'nepal-tourism-board-trekking-surge', 'Despite the seasonal rains, off-peak high-altitude trekking applications see a noticeable rise.', 'The Nepal Tourism Board highlighted a growing market segment shifting toward unique flora and rain-shadow region treks like Upper Mustang.', '2026-06-21 14:15:00', 'tourism.jpg'),
(1, 3, 'Kathmandu Tech Hub Initiative Launches Free Coding Bootcamps for Youth', 'kathmandu-tech-hub-free-coding-bootcamp', 'A new collaborative ecosystem aims to upskill 5,000 local developers in modern programming stacks.', 'Backing local digital transformation efforts, industry leaders in Lalitpur and Kathmandu have pooled resources to create open-access tech institutes.', '2026-06-19 10:00:00', 'tech.jpg');