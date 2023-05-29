-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2023 at 01:56 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chl_complain`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `remarks` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_type`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Corporate Office', 'head office', '1', 'corporate office', NULL, NULL),
(2, 'Head Office', 'head office', '1', 'Head Office', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complains`
--

CREATE TABLE `complains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` text DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 1 COMMENT '1=normal, 2=urgent, 3=very-urgent, 4=lazy',
  `details` text DEFAULT NULL,
  `to_dept` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active,2=processing,3=solved,4=pending,5=reject,6=delete,7=inactive\r\n',
  `progress` int(11) DEFAULT NULL,
  `forward_to` bigint(20) UNSIGNED DEFAULT NULL,
  `forward_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `document_img` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complains`
--

INSERT INTO `complains` (`id`, `user_id`, `title`, `priority`, `details`, `to_dept`, `status`, `progress`, `forward_to`, `forward_by`, `updated_by`, `document_img`, `created_at`, `updated_at`) VALUES
(1, 1, 'hello', 1, '<h1>hello</h1><figure class=\"image\"><img src=\"http://127.0.0.1:8000/image/media/Screenshot_20230227_125502_1681891736.png\"></figure>', 2, 1, NULL, NULL, NULL, 1, NULL, '2023-04-19 02:30:49', '2023-05-29 01:52:02'),
(2, 1, 'Test', 2, '<p><i><strong>আমার সোনার বাংলা&nbsp;</strong></i></p><figure class=\"image\"><img src=\"http://127.0.0.1:8000/image/media/template_1681893374.jpg\"></figure><figure class=\"media\"><oembed url=\"https://www.youtube.com/watch?v=lTPt_rCviOM&amp;ab_channel=GSeriesMusic\"></oembed></figure>', 1, 2, NULL, 2, NULL, NULL, NULL, '2023-04-19 02:37:43', '2023-04-19 02:37:43'),
(3, 2, 'Internet Problem', 4, '<p>My computer not connet to internet</p>', 1, 1, NULL, 1, NULL, NULL, NULL, '2023-04-29 03:03:24', '2023-04-29 03:03:24'),
(4, 1, 'PiHr Location Problem', 2, '<p>PiHr Location Problem</p>', 1, 1, NULL, NULL, NULL, 1, NULL, '2023-04-30 00:21:16', '2023-05-29 01:50:59'),
(5, 1, 'ERP Login Problem', 2, '<h1>ERP Terning Schedule</h1><p><img src=\"http://127.0.0.1:8000/image/media/123_1683175526.jpg\"></p><p>ERP Terning Schedule</p><figure class=\"image image-style-side\"><img src=\"http://127.0.0.1:8000/image/media/WhatsApp Image 2023-05-02 at 16.12.32_1683175566.jpg\"></figure>', 1, 7, NULL, NULL, NULL, 1, NULL, '2023-05-03 22:46:27', '2023-05-29 01:55:52'),
(6, 1, 'ERP Login Problem', 3, '<h1>ERP Terning Schedule</h1><figure class=\"image\"><img src=\"http://192.168.50.66/chl/public/image/media/Screenshot (4)_1685341536.png\"></figure><p>&nbsp;</p><p>ERP Terning Schedule</p>', 1, 7, NULL, NULL, NULL, 1, NULL, '2023-05-29 00:07:54', '2023-05-29 01:11:52'),
(7, 1, 'Internet Problem', 3, '<p><strong>My PC internet is very slow</strong></p><p><img src=\"http://192.168.50.66/chl/public/image/media/Screenshot (4)_1685347547.png\"></p>', 1, 1, NULL, NULL, NULL, NULL, NULL, '2023-05-29 02:06:09', '2023-05-29 02:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dept_code` varchar(255) NOT NULL,
  `dept_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `dept_code`, `dept_name`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, '100', 'IT', 1, 'Information Technology Department', NULL, NULL),
(2, '200', 'HR & Administrator', 1, 'Human Resource And Administrator', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_04_15_051654_laratrust_setup_tables', 2),
(6, '2023_04_15_074849_create_departments_table', 3),
(7, '2023_04_15_093951_create_branches_table', 4),
(8, '2023_04_17_035846_create_complain_table', 5),
(9, '2023_04_18_074021_update_complains_table', 6),
(10, '2023_04_18_075737_create_priority_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'users-create', 'Create Users', 'Create Users', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(2, 'users-read', 'Read Users', 'Read Users', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(3, 'users-update', 'Update Users', 'Update Users', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(4, 'users-delete', 'Delete Users', 'Delete Users', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(5, 'payments-create', 'Create Payments', 'Create Payments', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(6, 'payments-read', 'Read Payments', 'Read Payments', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(7, 'payments-update', 'Update Payments', 'Update Payments', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(8, 'payments-delete', 'Delete Payments', 'Delete Payments', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(9, 'profile-read', 'Read Profile', 'Read Profile', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(10, 'profile-update', 'Update Profile', 'Update Profile', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(11, 'module_1_name-create', 'Create Module_1_name', 'Create Module_1_name', '2023-04-14 23:21:46', '2023-04-14 23:21:46'),
(12, 'module_1_name-read', 'Read Module_1_name', 'Read Module_1_name', '2023-04-14 23:21:46', '2023-04-14 23:21:46'),
(13, 'module_1_name-update', 'Update Module_1_name', 'Update Module_1_name', '2023-04-14 23:21:47', '2023-04-14 23:21:47'),
(14, 'module_1_name-delete', 'Delete Module_1_name', 'Delete Module_1_name', '2023-04-14 23:21:47', '2023-04-14 23:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(11, 6),
(12, 6),
(13, 6),
(14, 6);

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--

CREATE TABLE `priorities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  `title` varchar(255) NOT NULL,
  `priority_number` bigint(20) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`id`, `status`, `title`, `priority_number`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'normal', 1, 'Normal', NULL, NULL, NULL, NULL),
(2, 1, 'urgent', 2, 'Urgent', NULL, NULL, NULL, NULL),
(3, 1, ' very-urgent', 3, 'Very Urgent', NULL, NULL, NULL, NULL),
(4, 1, 'lazy', 4, 'lazy', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Superadmin', 'Superadmin', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(2, 'admin', 'Admin', 'Admin', '2023-04-14 23:21:45', '2023-04-14 23:21:45'),
(3, 'it', 'It', 'It', '2023-04-14 23:21:46', '2023-04-14 23:21:46'),
(4, 'hr', 'Hr', 'Hr', '2023-04-14 23:21:46', '2023-04-14 23:21:46'),
(5, 'user', 'User', 'User', '2023-04-14 23:21:46', '2023-04-14 23:21:46'),
(6, 'role_name', 'Role Name', 'Role Name', '2023-04-14 23:21:46', '2023-04-14 23:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`) VALUES
(5, 1, 'App\\Models\\User'),
(5, 2, 'App\\Models\\User');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dept_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive, 4=cool',
  `designation` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `profile_pic` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `name`, `phone`, `email`, `dept_id`, `status`, `designation`, `branch_id`, `joining_date`, `profile_pic`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'H100001', 'Abu Oubaida', '01877772225', 'abuoubaida36@gmail.com', 1, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$vqP3TtbTcvE474bof5sb8.xEy9U1YYr5wxl1.mJbZEkdzPHnK.FbG', NULL, '2023-04-16 00:35:47', '2023-04-16 00:35:47'),
(2, 'H100001', 'Ahnaf', '01894940525', 'ahnafwub19@gmail.com', 1, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$ovQoM4cjagasLXkk7MGkKeodIHanq6rJEesqa7cUofh0XXxVgEC5K', NULL, '2023-04-29 03:01:16', '2023-04-29 03:01:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complains`
--
ALTER TABLE `complains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complain_user_id_foreign` (`user_id`),
  ADD KEY `complain_to_dept_foreign` (`to_dept`),
  ADD KEY `complain_forward_to_foreign` (`forward_to`),
  ADD KEY `complain_forward_by_foreign` (`forward_by`),
  ADD KEY `complain_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_dept_code_unique` (`dept_code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `priority_priority_number_unique` (`priority_number`),
  ADD KEY `priority_created_by_foreign` (`created_by`),
  ADD KEY `priority_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complains`
--
ALTER TABLE `complains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complains`
--
ALTER TABLE `complains`
  ADD CONSTRAINT `complain_forward_by_foreign` FOREIGN KEY (`forward_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complain_forward_to_foreign` FOREIGN KEY (`forward_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complain_to_dept_foreign` FOREIGN KEY (`to_dept`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complain_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complain_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `priorities`
--
ALTER TABLE `priorities`
  ADD CONSTRAINT `priority_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `priority_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
