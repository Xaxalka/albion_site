-- SQL dump for manual management of application users.
--   xaxalka@example.com / asddsa123321

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
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Test User', 'testuser', 'test@example.com', '2024-01-01 00:00:00', '$2y$12$oAjM5upvUDsqF/Mc00cq1.OpeU/Y4AnyG/GIC7Mpj1Damn9t4v1O2', 0, NULL, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
  (2, 'Admin', 'admin', 'admin@example.com', '2024-01-01 00:00:00', '$2y$12$H4xV/ki5jPny1uMNSWmGjuF6j17UWEfzHbYnFGFGdJHOCdI4DpNb2', 1, NULL, '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
  (3, 'xaxalka', 'xaxalka', 'xaxalka@example.com', '2024-01-01 00:00:00', '$2y$12$ZfW23iLFDep3OhUZ.TEJ8OocOL9HBrYq7Xq2zmYSuFlrLJ0yxjw8O', 1, NULL, '2024-01-01 00:00:00', '2024-01-01 00:00:00');

-- To change a password manually, replace the hashed string in the INSERT statement
-- with a new bcrypt hash (e.g. generated via `php -r "echo password_hash('newpass', PASSWORD_BCRYPT), "\\n";"`).
