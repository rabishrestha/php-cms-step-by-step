-- 1. Create the Database
CREATE DATABASE IF NOT EXISTS hamro_news DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hamro_news;

-- 2. Create the Posts Table Layout
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'General',
    author VARCHAR(100) NOT NULL,
    published_at VARCHAR(100) NOT NULL,
    image VARCHAR(100) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Pre-seed Table with the 6 Nepali News Stories
INSERT INTO posts (title, slug, summary, content, category, author, published_at, image) VALUES
('Monsoon Rainfall Alerts Active Across Koshi and Bagmati Provinces', 'monsoon-rainfall-alerts-koshi-bagmati', 'The Department of Hydrology and Meteorology issues warnings urging caution in mountainous areas due to rising river levels.', 'As the monsoon season intensifies across Nepal, continuous heavy downpours have triggered elevated risks of flash floods and landslides. Authorities have advised travelers along major highways to check route statuses before setting off.', 'National', 'Ramesh Thapa', 'June 22, 2026', 'monsoon.jpg'),

('Nepal Tourism Board Reports Surge in Summer Trekking Registrations', 'nepal-tourism-board-trekking-surge', 'Despite the seasonal rains, off-peak high-altitude trekking applications see a noticeable rise from international adventure enthusiasts.', 'The Nepal Tourism Board highlighted a growing market segment shifting toward unique flora and rain-shadow region treks like Upper Mustang and Dolpo, marking a solid boost for local teahouses.', 'Tourism', 'Sita Shrestha', 'June 21, 2026', 'tourism.jpg'),

('Kathmandu Tech Hub Initiative Launches Free Coding Bootcamps for Youth', 'kathmandu-tech-hub-free-coding-bootcamp', 'A new collaborative ecosystem aims to upskill 5,000 local developers in modern programming stacks over the next year.', 'Backing local digital transformation efforts, industry leaders in Lalitpur and Kathmandu have pooled resources to create open-access tech institutes. The goal is to establish Nepal as an emerging regional software service hub.', 'Technology', 'Anil Tamang', 'June 19, 2026', 'tech.jpg'),

('Nepal Cricket Team Secures Thrilling Victory in T20 Tri-Series Opener', 'nepal-cricket-team-win-t20-tri-series', 'A spectacular final-over boundary seals the win for Nepal in front of a packed, roaring crowd at the TU Cricket Ground.', 'The national team showcased exceptional death-overs bowling before chasing down a target of 168 runs with only two balls to spare. The outstanding performance sets an optimistic tone for the remainder of the international tournament.', 'Sports', 'Binod Chaudhary', 'June 18, 2026', 'cricket.jpg'),

('Hydropower Exports to India Reach Record Highs This Quarter', 'hydropower-exports-india-record-highs', 'Increased generational capacity during the wet season enables Nepal to maximize clean energy exports via the cross-border transmission line.', 'According to the Nepal Electricity Authority (NEA), optimized surplus power management generated substantial revenue this month. New generation projects hitting the grid are positioning renewable power as a primary driver of national economic growth.', 'Business', 'Pooja Adhikari', 'June 17, 2026', 'hydro.jpg'),

('Restoration of Historical Baseline Monemunts Begins in Bhaktapur Durbar Square', 'bhaktapur-durbar-square-restoration-begins', 'Local heritage conservation groups collaborate with expert craftsmen to preserve intricate woodwork on centuries-old structures.', 'Using traditional techniques passed down through generations, master artisans have begun reconstructing delicate courtyard lattices and structural beams. The initiative is heavily supported by community participation to maintain historical authenticity.', 'Culture', 'Rabi Shrestha', 'June 15, 2026', 'culture.jpg');