/*
 Navicat Premium Data Transfer

 Source Server         : lokal
 Source Server Type    : MySQL
 Source Server Version : 100428 (10.4.28-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : tabungan-bersama

 Target Server Type    : MySQL
 Target Server Version : 100428 (10.4.28-MariaDB)
 File Encoding         : 65001

 Date: 16/01/2024 00:03:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for blogs
-- ----------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of blogs
-- ----------------------------
INSERT INTO `blogs` VALUES ('01hkc9k6ksrgaatx8ct3ctpmmr', 'Title 1', 'Description 1', '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);
INSERT INTO `blogs` VALUES ('01hkc9k6kx6xr6as2z0k2tmbm2', 'Title 2', 'Description 2', '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);

-- ----------------------------
-- Table structure for configurations
-- ----------------------------
DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of configurations
-- ----------------------------
INSERT INTO `configurations` VALUES ('6NwZwlU9ibONBYztkC9JBRXShW', 'app.logo', 'Logo', 'img/I2GscINbp4Ot25tOwKmuCv2Oxb9sSodkDcF02c0q.png', 'app', NULL, '2023-12-04 14:16:21', NULL);
INSERT INTO `configurations` VALUES ('A7aYZCIOefQy3XSe7FTo6kuOm0', 'app.description', 'Description', 'Surya & Lintang', 'app', NULL, '2023-12-04 14:16:25', NULL);
INSERT INTO `configurations` VALUES ('A7aYZCIOefQy3XSe7FTo6kuOm8', 'app.name', 'Application Name', 'Tabungan Bersama', 'app', NULL, '2023-12-04 14:16:25', NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for log_users
-- ----------------------------
DROP TABLE IF EXISTS `log_users`;
CREATE TABLE `log_users`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log_users
-- ----------------------------
INSERT INTO `log_users` VALUES ('01hkccxpz6xk2ayhezzrsn9c4z', '01hkc9k6kge0sjk17tmrnqrkxd', 'Logout', NULL, '127.0.0.1', '2024-01-05 08:48:04', '2024-01-05 08:48:04');
INSERT INTO `log_users` VALUES ('01hkcgqxe0vyxer1zvtap6250y', '01hkc9k6gceepg38htqsa83pnz', 'Login', NULL, '127.0.0.1', '2024-01-05 16:54:48', '2024-01-05 16:54:48');
INSERT INTO `log_users` VALUES ('01hkcgt436k1zx9am8k94kjqzh', '01hkc9k6gceepg38htqsa83pnz', 'Logout', NULL, '127.0.0.1', '2024-01-05 16:56:01', '2024-01-05 16:56:01');
INSERT INTO `log_users` VALUES ('01hkch34djhgy1c72xwtafy0wv', '01hkc9k6gceepg38htqsa83pnz', 'Login', NULL, '127.0.0.1', '2024-01-05 17:00:56', '2024-01-05 17:00:56');
INSERT INTO `log_users` VALUES ('01hkch3f5svn9zqjw207zv80mp', '01hkc9k6gceepg38htqsa83pnz', 'Logout', NULL, '127.0.0.1', '2024-01-05 17:01:07', '2024-01-05 17:01:07');
INSERT INTO `log_users` VALUES ('01hm703n294y2mqf5csj9cc1nz', '01hkc9k6gceepg38htqsa83pnz', 'Login', NULL, '127.0.0.1', '2024-01-15 23:43:37', '2024-01-15 23:43:37');

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `main_menu` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `menu_hassub` int NULL DEFAULT NULL,
  `sort` int NOT NULL DEFAULT 0,
  `active` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('01hkc9k6mg66mpga4k79yy0jvv', 'dashboard', 'Dashboard', '/', 'bi bi-grid-fill', NULL, NULL, 0, 1, '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);
INSERT INTO `menus` VALUES ('01hkc9k6mntc1d7rfr0f7hpcad', 'master', 'Master', '', 'bi bi-justify-left', NULL, 1, 0, 1, '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);
INSERT INTO `menus` VALUES ('01hkc9k6ms99th6a0n2fzc8zpb', 'blog', 'Blog', 'blog', 'bi bi-home', '01hkc9k6mntc1d7rfr0f7hpcad', NULL, 0, 1, '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);
INSERT INTO `menus` VALUES ('nWrKL7pMiuXTdXkDiSedka2ws', 'users', 'User', 'user', '', 'wVkoIAISC7agLnIi347YZ9j0bR', NULL, 1, 1, '2024-01-05 15:24:36', NULL, NULL);
INSERT INTO `menus` VALUES ('nWrKL7pMiuXTdXkDiSedzNl859', 'roles', 'Role', 'role-menu', '', 'wVkoIAISC7agLnIi347YZ9j0bR', NULL, 0, 1, '2024-01-05 15:24:40', NULL, NULL);
INSERT INTO `menus` VALUES ('nWrKL7pMiuXTdXkDiSedzNlu81', 'configuration', 'Config App', 'configuration', '', 'wVkoIAISC7agLnIi347YZ9j0bR', NULL, 0, 1, '2024-01-05 15:24:43', NULL, NULL);
INSERT INTO `menus` VALUES ('wVkoIAISC7agLnIi347YZ9j0bR', 'setting', 'Setting', '', 'bi bi-gear', NULL, 1, 0, 1, '2024-01-05 15:24:46', NULL, NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2023_09_28_082600_create_blogs_table', 1);
INSERT INTO `migrations` VALUES (6, '2023_09_28_144221_create_menus_table', 1);
INSERT INTO `migrations` VALUES (7, '2023_10_23_130218_create_role_menus_table', 1);
INSERT INTO `migrations` VALUES (8, '2023_10_23_134338_create_roles_table', 1);
INSERT INTO `migrations` VALUES (9, '2023_11_05_065021_create_configurations_table', 1);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for role_menus
-- ----------------------------
DROP TABLE IF EXISTS `role_menus`;
CREATE TABLE `role_menus`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_menus
-- ----------------------------
INSERT INTO `role_menus` VALUES ('01hkcc3fwpbmmaes7k3kkxn95m', '01hkc9k6c0bcdcdp3gjemqwtrq', '01hkc9k6mg66mpga4k79yy0jvv', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hkcc3fwv7y5e7q2rd4cvr7jg', '01hkc9k6c0bcdcdp3gjemqwtrq', '01hkc9k6mntc1d7rfr0f7hpcad', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hkcc3fwy7sx38y7785kvfg2f', '01hkc9k6c0bcdcdp3gjemqwtrq', '01hkc9k6ms99th6a0n2fzc8zpb', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hkcc3fx1p8yyxt5wa7accq5m', '01hkc9k6c0bcdcdp3gjemqwtrq', 'wVkoIAISC7agLnIi347YZ9j0bR', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hkcc3fx5cr0211ammhte0tx5', '01hkc9k6c0bcdcdp3gjemqwtrq', 'nWrKL7pMiuXTdXkDiSedka2ws', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hkcc3fx8f5nzerpnv15nvmhm', '01hkc9k6c0bcdcdp3gjemqwtrq', 'nWrKL7pMiuXTdXkDiSedzNl859', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hkcc3fxbjafqgb6n8vs1ed39', '01hkc9k6c0bcdcdp3gjemqwtrq', 'nWrKL7pMiuXTdXkDiSedzNlu81', '2024-01-05 08:33:45', '2024-01-05 08:33:45', NULL);
INSERT INTO `role_menus` VALUES ('01hm704m1wjvtdx5q3amnk4g0e', '01hkc9k6cbknkmt7dqzrz5e3ez', '01hkc9k6mg66mpga4k79yy0jvv', '2024-01-15 23:44:09', '2024-01-15 23:44:09', NULL);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('01hkc9k6c0bcdcdp3gjemqwtrq', 'superadmin', 'Super Admin', '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);
INSERT INTO `roles` VALUES ('01hkc9k6cbknkmt7dqzrz5e3ez', 'user', 'user', '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` char(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('01hkc9k6gceepg38htqsa83pnz', ' superadmin user', 'superadmin', 'superadmin@mail.com', '2024-01-05 07:49:54', '$2y$10$lQd2ayVO1QY.uxhbxOXAcOVB1mi1qIwbOQwpAo8K9D2uAr1uO0apy', '01hkc9k6c0bcdcdp3gjemqwtrq', 'm07j72y6M6KMj8KIUCLTL1qtMpiVRmaHxnLbuk4jPk3ET0XKaYqMgKWT3GkO', '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);
INSERT INTO `users` VALUES ('01hkc9k6kge0sjk17tmrnqrkxd', ' user', 'user', 'user@mail.com', '2024-01-05 07:49:54', '$2y$10$Awj04Mbw4l0rgfuo1T/ydOs4sNvcetQ6TWsnXlnDKGx6RnsmafkPu', '01hkc9k6cbknkmt7dqzrz5e3ez', '7C5aqgFwEUNB3ykfFp2Yg4dtaRvruEi68YufoLFxaS1DRvEzLdakjYWxR7mi', '2024-01-05 07:49:54', '2024-01-05 07:49:54', NULL);

SET FOREIGN_KEY_CHECKS = 1;
