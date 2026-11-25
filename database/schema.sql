-- Database: booking-system
-- Import in phpMyAdmin, or run:  mysql -u root booking-system < database/schema.sql

CREATE DATABASE IF NOT EXISTS `booking-system`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `booking-system`;

CREATE TABLE IF NOT EXISTS `demo_requests` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(100)  NOT NULL,
  `business`     VARCHAR(150)  NULL,
  `email`        VARCHAR(190)  NOT NULL,
  `phone`        VARCHAR(20)   NULL,
  `service`      VARCHAR(50)   NOT NULL,
  `demo_date`    DATE          NULL,
  `message`      TEXT          NULL,
  `source_page`  VARCHAR(50)   NULL,
  `status`       ENUM('new','contacted','booked','closed') NOT NULL DEFAULT 'new',
  `ip_address`   VARCHAR(45)   NULL,
  `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_status_created` (`status`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Upgrade for databases created before `source_page` existed:
-- ALTER TABLE `demo_requests` ADD COLUMN `source_page` VARCHAR(50) NULL AFTER `message`;
