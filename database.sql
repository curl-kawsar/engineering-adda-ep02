-- Todo List Database Schema
-- Run this SQL in your cPanel phpMyAdmin to create the necessary table

CREATE TABLE IF NOT EXISTS `todos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('pending', 'completed') DEFAULT 'pending',
  `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional)
INSERT INTO `todos` (`title`, `description`, `priority`) VALUES
('Welcome to your Todo List', 'Start managing your tasks efficiently!', 'high'),
('Complete your first task', 'Mark this task as completed when done', 'medium');
