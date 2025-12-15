-- SQL dump for manual management of application users.
-- Passwords are pre-hashed with bcrypt so that they continue to work with Laravel's authentication.
-- Plain-text values for reference:
--   test@example.com / password
--   admin@example.com / admin

SET NAMES utf8mb4;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `email_verification_codes`;
CREATE TABLE `email_verification_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(10) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evc_user_expiry_index` (`user_id`, `expires_at`),
  CONSTRAINT `evc_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Test User', 'testuser', 'test@example.com', '2024-01-01 00:00:00', '$2y$12$oAjM5upvUDsqF/Mc00cq1.OpeU/Y4AnyG/GIC7Mpj1Damtn9t4v1O2', NULL, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
  (2, 'Admin', 'admin', 'admin@example.com', '2024-01-01 00:00:00', '$2y$12$H4xV/ki5jPny1uMNSWmGjuF6j17UWEfzHbYnFGFGdJHOCdI4DpNb2', NULL, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

INSERT INTO `email_verification_codes` (`id`, `user_id`, `code`, `expires_at`, `created_at`, `updated_at`) VALUES
  (1, 1, '123456', '2025-12-31 23:59:59', '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- To change a password manually, replace the hashed string in the INSERT statement
-- with a new bcrypt hash (e.g. generated via `php -r "echo password_hash('newpass', PASSWORD_BCRYPT), "\\n";"`).
