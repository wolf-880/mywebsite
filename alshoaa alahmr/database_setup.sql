-- Create database for Alshoaa Alahmr website
CREATE DATABASE IF NOT EXISTS alshoaa_alahmr;
USE alshoaa_alahmr;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create pages table for content management
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create contact_messages table for storing contact form submissions
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE
);

-- Create portfolio_items table for portfolio page
CREATE TABLE IF NOT EXISTS portfolio_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert some initial data for pages
INSERT INTO pages (title, content, slug) VALUES
('Home', 'Welcome to Alshoaa Alahmr', 'home'),
('About', 'About Alshoaa Alahmr', 'about'),
('Portfolio', 'Our portfolio', 'portfolio'),
('Contact', 'Contact us', 'contact');

-- Create a database user for the website with limited permissions
CREATE USER IF NOT EXISTS 'alshoaa_user'@'localhost' IDENTIFIED BY 'alshoaa_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON alshoaa_alahmr.* TO 'alshoaa_user'@'localhost';
FLUSH PRIVILEGES;
