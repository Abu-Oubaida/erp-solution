-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2023 at 06:18 PM
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
-- Table structure for table `account_voucher_infos`
--

CREATE TABLE `account_voucher_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_type_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `voucher_date` date DEFAULT NULL,
  `file_count` int(11) DEFAULT NULL COMMENT 'if multiple file in a request',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `remarks` text DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_voucher_infos`
--

INSERT INTO `account_voucher_infos` (`id`, `voucher_type_id`, `voucher_number`, `voucher_date`, `file_count`, `created_by`, `remarks`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 5, '123', '2023-10-01', 2, 1, 'Bank', NULL, '2023-10-07 07:44:24', NULL);

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
-- Table structure for table `branch_transfer_histories`
--

CREATE TABLE `branch_transfer_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_user_id` bigint(20) UNSIGNED NOT NULL,
  `new_branch_id` bigint(20) UNSIGNED NOT NULL,
  `from_branch_id` bigint(20) UNSIGNED NOT NULL,
  `transfer_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 2, 'ERP access problem', 3, '<p>ERP access problem<img src=\"http://192.168.50.66/chl/public/image/media/WhatsApp Image 2023-05-31 at 4.50.55 PM_1685533560.jpeg\"></p>', 1, 1, NULL, NULL, NULL, NULL, NULL, '2023-05-31 05:46:33', '2023-05-31 05:46:33'),
(2, 1, 'PiHr Location Problem', 2, '<p>PiHr Location Problem<img src=\"http://192.168.50.66/chl/public/image/media/Screenshot (4)_1685595940.png\"></p>', 1, 7, NULL, NULL, NULL, NULL, NULL, '2023-05-31 23:05:43', '2023-06-06 02:41:53'),
(3, 1, 'PiHr Location Problem', 3, '<figure class=\"image\"><img src=\"http://127.0.0.1:8000/image/media/Screenshot (4)_1686040944.png\"></figure>', 1, 1, NULL, NULL, NULL, NULL, NULL, '2023-06-06 02:42:26', '2023-06-06 02:42:26'),
(4, 1, 'Test', 2, '<p>Test</p>', 1, 1, NULL, NULL, NULL, NULL, NULL, '2023-06-18 03:03:52', '2023-06-18 03:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `create_directory_histories`
--

CREATE TABLE `create_directory_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `disk_name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `create_directory_histories`
--

INSERT INTO `create_directory_histories` (`id`, `status`, `message`, `disk_name`, `path`, `file_name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'success', 'dirCreated', 'file-manager', 'superadmin/test', 'test', 1, '2023-06-24 05:36:14', '2023-06-24 05:36:14'),
(2, 'success', 'dirCreated', 'file-manager', 'superadmin/test', 'new', 1, '2023-06-25 05:00:19', '2023-06-25 05:00:19'),
(3, 'success', 'dirCreated', 'file-manager', 'IT', 'Back Up of BoQ Dept. (Mr. Joynal Abedin)', 3, '2023-06-25 23:47:40', '2023-06-25 23:47:40'),
(4, 'success', 'dirCreated', 'file-manager', 'Account_Finance', 'test', 1, '2023-06-25 23:51:57', '2023-06-25 23:51:57'),
(5, 'success', 'dirCreated', 'file-manager', NULL, 'test', 1, '2023-06-26 00:18:23', '2023-06-26 00:18:23'),
(6, 'success', 'dirCreated', 'file-manager', NULL, 'new', 1, '2023-06-26 00:20:07', '2023-06-26 00:20:07'),
(7, 'success', 'dirCreated', 'file-manager', 'new folder', 'test', 1, '2023-07-03 22:47:28', '2023-07-03 22:47:28'),
(8, 'success', 'dirCreated', 'file-manager', 'new folder/test', 'hello', 1, '2023-07-03 23:20:30', '2023-07-03 23:20:30'),
(9, 'success', 'dirCreated', 'file-manager', 'new folder', 'test', 1, '2023-07-04 03:00:09', '2023-07-04 03:00:09'),
(10, 'success', 'dirCreated', 'file-manager', 'new folder', 'Folder name', 1, '2023-07-04 03:41:38', '2023-07-04 03:41:38'),
(11, 'success', 'dirCreated', 'file-manager', 'new folder', 'abc', 1, '2023-07-04 03:43:29', '2023-07-04 03:43:29'),
(12, 'success', 'dirCreated', 'file-manager', 'IT', 'test', 1, '2023-07-05 05:29:51', '2023-07-05 05:29:51'),
(13, 'success', 'dirCreated', 'file-manager', 'new folder/Folder name/abc', 'aaa', 1, '2023-07-05 05:31:46', '2023-07-05 05:31:46'),
(14, 'success', 'dirCreated', 'file-manager', NULL, 'HR', 1, '2023-07-05 05:44:54', '2023-07-05 05:44:54'),
(15, 'success', 'dirCreated', 'file-manager', NULL, 'HR', 1, '2023-07-05 05:54:13', '2023-07-05 05:54:13'),
(16, 'success', 'dirCreated', 'file-manager', 'guest', 'Folder name', 1, '2023-07-06 03:06:04', '2023-07-06 03:06:04'),
(17, 'success', 'dirCreated', 'file-manager', NULL, 'Backup', 1, '2023-07-08 05:20:51', '2023-07-08 05:20:51'),
(18, 'success', 'dirCreated', 'file-manager', NULL, 'Backup Tajbid', 1, '2023-07-08 05:21:37', '2023-07-08 05:21:37'),
(19, 'success', 'dirCreated', 'file-manager', NULL, 'Backup Sakib', 1, '2023-07-08 05:21:50', '2023-07-08 05:21:50'),
(20, 'success', 'dirCreated', 'file-manager', 'Backup Tajbid', 'Folder', 10, '2023-07-08 05:28:36', '2023-07-08 05:28:36'),
(21, 'success', 'dirCreated', 'file-manager', 'IT', 'Print Today', 3, '2023-07-09 04:08:52', '2023-07-09 04:08:52'),
(22, 'success', 'dirCreated', 'file-manager', 'IT', 'Accounts Traning', 2, '2023-07-17 05:27:44', '2023-07-17 05:27:44'),
(23, 'success', 'dirCreated', 'file-manager', 'IT', 'Software', 1, '2023-08-03 07:09:21', '2023-08-03 07:09:21'),
(24, 'success', 'dirCreated', 'file-manager', NULL, 'Shopno_backup', 1, '2023-08-30 03:48:05', '2023-08-30 03:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `create_file_histories`
--

CREATE TABLE `create_file_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `disk_name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `create_file_histories`
--

INSERT INTO `create_file_histories` (`id`, `status`, `message`, `disk_name`, `path`, `file_name`, `content`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'success', 'fileCreated', 'file-manager', 'superadmin/test', 'a', '', 1, '2023-06-24 05:05:23', '2023-06-24 05:05:23'),
(2, 'success', 'fileCreated', 'file-manager', NULL, 'test.txt', '', 1, '2023-06-26 00:19:28', '2023-06-26 00:19:28'),
(3, 'success', 'fileCreated', 'file-manager', 'new folder', 'new.txt', '', 1, '2023-07-03 22:47:40', '2023-07-03 22:47:40'),
(4, 'success', 'fileCreated', 'file-manager', 'new folder', 'new.txt', '', 1, '2023-07-04 03:00:22', '2023-07-04 03:00:22'),
(5, 'success', 'fileCreated', 'file-manager', 'new folder', 'test.txt', '', 1, '2023-07-04 03:43:41', '2023-07-04 03:43:41'),
(6, 'success', 'fileCreated', 'file-manager', 'HR', 'file_1.txt', '', 1, '2023-07-05 12:04:07', '2023-07-05 12:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_histories`
--

CREATE TABLE `deleted_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `disk_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deleted_histories`
--

INSERT INTO `deleted_histories` (`id`, `disk_name`, `path`, `type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'file-manager', 'superadmin/test/IMG_1300.JPG', 'file', 1, '2023-06-25 04:59:23', '2023-06-25 04:59:23'),
(2, 'file-manager', 'superadmin/test/new', 'dir', 1, '2023-06-25 05:00:47', '2023-06-25 05:00:47'),
(3, 'file-manager', 'admin/hello', 'dir', 1, '2023-06-25 05:48:34', '2023-06-25 05:48:34'),
(4, 'file-manager', 'admin/it', 'dir', 1, '2023-06-25 05:48:34', '2023-06-25 05:48:34'),
(5, 'file-manager', 'common/IT', 'dir', 1, '2023-06-25 05:49:29', '2023-06-25 05:49:29'),
(6, 'file-manager', 'Account_Finance/test', 'dir', 1, '2023-06-25 23:52:02', '2023-06-25 23:52:02'),
(7, 'file-manager', 'test', 'dir', 1, '2023-06-25 23:53:14', '2023-06-25 23:53:14'),
(8, 'file-manager', 'test', 'dir', 1, '2023-06-26 00:18:14', '2023-06-26 00:18:14'),
(9, 'file-manager', 'test', 'dir', 1, '2023-06-26 00:18:38', '2023-06-26 00:18:38'),
(10, 'file-manager', 'test.txt', 'file', 1, '2023-06-26 00:19:59', '2023-06-26 00:19:59'),
(11, 'file-manager', 'superadmin/test/USER.zip', 'file', 1, '2023-06-26 01:27:26', '2023-06-26 01:27:26'),
(12, 'file-manager', 'superadmin/test/Amantu Billahi -  Alisha Kiyani - Heart Touching Arabic & English Nasheed  - AlJilani Production (1).mp4', 'file', 1, '2023-06-26 01:28:42', '2023-06-26 01:28:42'),
(13, 'file-manager', 'superadmin/test/USER.zip', 'file', 1, '2023-06-26 01:28:42', '2023-06-26 01:28:42'),
(14, 'file-manager', 'superadmin/test/test', 'dir', 1, '2023-06-26 01:48:39', '2023-06-26 01:48:39'),
(15, 'file-manager', 'superadmin/test/a', 'file', 1, '2023-06-26 01:48:42', '2023-06-26 01:48:42'),
(16, 'file-manager', 'new folder/test', 'dir', 1, '2023-07-04 02:59:59', '2023-07-04 02:59:59'),
(17, 'file-manager', 'new folder/new.txt', 'file', 1, '2023-07-04 02:59:59', '2023-07-04 02:59:59'),
(18, 'file-manager', 'new folder/Folder name/new.txt', 'file', 1, '2023-07-05 04:40:52', '2023-07-05 04:40:52'),
(19, 'file-manager', 'new folder/Folder name/test.txt', 'file', 1, '2023-07-05 04:40:53', '2023-07-05 04:40:53'),
(20, 'file-manager', 'new folder/Folder name/new.txt', 'file', 1, '2023-07-05 04:58:22', '2023-07-05 04:58:22'),
(21, 'file-manager', 'new folder/Folder name/test.txt', 'file', 1, '2023-07-05 04:58:22', '2023-07-05 04:58:22'),
(22, 'file-manager', 'new folder/Folder name/new.txt', 'file', 1, '2023-07-05 04:59:42', '2023-07-05 04:59:42'),
(23, 'file-manager', 'new folder/Folder name/test.txt', 'file', 1, '2023-07-05 04:59:42', '2023-07-05 04:59:42'),
(24, 'file-manager', 'new folder/Folder name/test.txt', 'file', 1, '2023-07-05 05:04:10', '2023-07-05 05:04:10'),
(25, 'file-manager', 'new folder/Folder name/test', 'dir', 1, '2023-07-05 05:10:11', '2023-07-05 05:10:11'),
(26, 'file-manager', 'new folder/Folder name/new.txt', 'file', 1, '2023-07-05 05:10:11', '2023-07-05 05:10:11'),
(27, 'file-manager', 'new folder/Folder name/test.txt', 'file', 1, '2023-07-05 05:10:11', '2023-07-05 05:10:11'),
(28, 'file-manager', 'new folder/Folder name/test', 'dir', 1, '2023-07-05 05:14:55', '2023-07-05 05:14:55'),
(29, 'file-manager', 'new folder/Folder name/new.txt', 'file', 1, '2023-07-05 05:14:56', '2023-07-05 05:14:56'),
(30, 'file-manager', 'new folder/Folder name/test.txt', 'file', 1, '2023-07-05 05:14:56', '2023-07-05 05:14:56'),
(31, 'file-manager', 'IT/test/new.txt', 'file', 1, '2023-07-05 05:30:22', '2023-07-05 05:30:22'),
(32, 'file-manager', 'new folder/Folder name/abc/test.txt', 'file', 1, '2023-07-05 05:31:40', '2023-07-05 05:31:40'),
(33, 'file-manager', 'IT/test', 'dir', 1, '2023-07-05 05:32:43', '2023-07-05 05:32:43'),
(34, 'file-manager', 'new folder', 'dir', 1, '2023-07-05 05:32:56', '2023-07-05 05:32:56'),
(35, 'file-manager', 'HR', 'dir', 1, '2023-07-05 05:48:00', '2023-07-05 05:48:00'),
(36, 'file-manager', 'HR', 'dir', 1, '2023-07-05 12:04:51', '2023-07-05 12:04:51'),
(37, 'file-manager', 'IT/Print Today/print.zip', 'file', 1, '2023-07-09 04:30:58', '2023-07-09 04:30:58'),
(38, 'file-manager', 'IT/Print Today/Daily follow up report_08-07-2023_Rezaul.docx', 'file', 1, '2023-07-09 04:31:14', '2023-07-09 04:31:14'),
(39, 'file-manager', 'IT/Print Today/Daily follow up report_08-07-2023_Robin.docx', 'file', 1, '2023-07-09 04:31:14', '2023-07-09 04:31:14'),
(40, 'file-manager', 'IT/Print Today/Daily follow up report_08-07-2023_Romel.docx', 'file', 1, '2023-07-09 04:31:14', '2023-07-09 04:31:14'),
(41, 'file-manager', 'IT/Print Today/P&D Department Report_08-07-2023.docx', 'file', 1, '2023-07-09 04:31:14', '2023-07-09 04:31:14');

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
(2, '200', 'HR & Administrator', 1, 'Human Resource And Administrator', NULL, NULL),
(3, '300', 'Account', 1, NULL, '2023-06-19 05:51:42', '2023-06-19 05:51:42'),
(4, '101', 'Accounts & Finance', 1, NULL, '2023-06-19 06:07:04', '2023-06-19 06:07:04'),
(5, '110', 'MIS', 1, 'Management Information System', '2023-07-08 04:36:51', '2023-07-08 04:36:51'),
(6, '500', 'LAW', 1, 'LAW', '2023-07-08 04:39:28', '2023-07-08 04:39:28');

-- --------------------------------------------------------

--
-- Table structure for table `department_transfer_histories`
--

CREATE TABLE `department_transfer_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_user_id` bigint(20) UNSIGNED NOT NULL,
  `new_dept_id` bigint(20) UNSIGNED NOT NULL,
  `from_dept_id` bigint(20) UNSIGNED NOT NULL,
  `transfer_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_transfer_histories`
--

INSERT INTO `department_transfer_histories` (`id`, `transfer_user_id`, `new_dept_id`, `from_dept_id`, `transfer_by`, `created_at`, `updated_at`) VALUES
(1, 3, 5, 1, 1, NULL, NULL),
(2, 3, 1, 5, 1, '2023-07-08 04:55:00', NULL),
(3, 3, 5, 1, 1, '2023-07-08 04:55:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `download_histories`
--

CREATE TABLE `download_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `disk_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `download_histories`
--

INSERT INTO `download_histories` (`id`, `disk_name`, `path`, `file_name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'file-manager', 'superadmin/test/a', 'a', 1, '2023-06-24 06:02:05', '2023-06-24 06:02:05'),
(2, 'file-manager', 'admin/Amantu Billahi -  Alisha Kiyani - Heart Touching Arabic & English Nasheed  - AlJilani Production.mp4', 'Amantu Billahi -  Alisha Kiyani - Heart Touching Arabic & English Nasheed  - AlJilani Production.mp4', 1, '2023-06-24 06:03:16', '2023-06-24 06:03:16'),
(3, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 22:49:02', '2023-06-24 22:49:02'),
(4, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 22:56:51', '2023-06-24 22:56:51'),
(5, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:00:43', '2023-06-24 23:00:43'),
(6, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:06:38', '2023-06-24 23:06:38'),
(7, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:18:45', '2023-06-24 23:18:45'),
(8, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:32:10', '2023-06-24 23:32:10'),
(9, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:32:10', '2023-06-24 23:32:10'),
(10, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:32:44', '2023-06-24 23:32:44'),
(11, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:32:45', '2023-06-24 23:32:45'),
(12, 'file-manager', 'admin/it/Classroom.rar', 'Classroom.rar', 1, '2023-06-24 23:40:25', '2023-06-24 23:40:25'),
(13, 'file-manager', 'superadmin/Tajbid D/USER.zip', 'USER.zip', 1, '2023-06-26 01:26:00', '2023-06-26 01:26:00'),
(14, 'file-manager', 'new folder/new.txt', 'new.txt', 1, '2023-07-04 03:00:28', '2023-07-04 03:00:28'),
(15, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-06 05:15:48', '2023-07-06 05:15:48'),
(16, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-06 05:43:22', '2023-07-06 05:43:22'),
(17, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-06 05:44:31', '2023-07-06 05:44:31'),
(18, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-06 06:03:09', '2023-07-06 06:03:09'),
(19, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-08 07:38:46', '2023-07-08 07:38:46'),
(20, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-08 08:12:57', '2023-07-08 08:12:57'),
(21, 'file-manager', 'Backup Sakib/Desktop.zip', 'Desktop.zip', 11, '2023-07-08 08:25:02', '2023-07-08 08:25:02'),
(22, 'file-manager', 'Backup Sakib/old pc.zip', 'old pc.zip', 11, '2023-07-08 08:25:09', '2023-07-08 08:25:09'),
(23, 'file-manager', 'Backup Sakib/Sabbir.zip', 'Sabbir.zip', 11, '2023-07-08 08:25:12', '2023-07-08 08:25:12'),
(24, 'file-manager', 'Backup Sakib/Tally Data Backup.zip', 'Tally Data Backup.zip', 11, '2023-07-08 08:25:15', '2023-07-08 08:25:15'),
(25, 'file-manager', 'Backup Sakib/Tally.ERP9.zip', 'Tally.ERP9.zip', 11, '2023-07-08 08:25:17', '2023-07-08 08:25:17'),
(26, 'file-manager', 'Backup Sakib/Tally.zip', 'Tally.zip', 11, '2023-07-08 08:25:19', '2023-07-08 08:25:19'),
(27, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-08 08:31:49', '2023-07-08 08:31:49'),
(28, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-08 09:01:11', '2023-07-08 09:01:11'),
(29, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-08 10:38:10', '2023-07-08 10:38:10'),
(30, 'file-manager', 'IT/Print Today/print.zip', 'print.zip', 1, '2023-07-09 04:29:17', '2023-07-09 04:29:17'),
(31, 'file-manager', 'IT/Print Today/att2000.mdb', 'att2000.mdb', 3, '2023-07-11 06:47:18', '2023-07-11 06:47:18'),
(32, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-11 11:42:51', '2023-07-11 11:42:51'),
(33, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-15 04:30:21', '2023-07-15 04:30:21'),
(34, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-16 10:49:43', '2023-07-16 10:49:43'),
(35, 'file-manager', 'Backup Tajbid/Accounts Traning/acc_2.webm', 'acc_2.webm', 10, '2023-07-17 05:37:19', '2023-07-17 05:37:19'),
(36, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-17 09:00:55', '2023-07-17 09:00:55'),
(37, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-17 09:06:53', '2023-07-17 09:06:53'),
(38, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-17 09:11:28', '2023-07-17 09:11:28'),
(39, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-07-31 04:22:38', '2023-07-31 04:22:38'),
(40, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-08-02 10:38:07', '2023-08-02 10:38:07'),
(41, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-08-14 03:42:08', '2023-08-14 03:42:08'),
(42, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-08-14 03:42:08', '2023-08-14 03:42:08'),
(43, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-08-14 06:14:31', '2023-08-14 06:14:31'),
(44, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-08-19 04:25:21', '2023-08-19 04:25:21'),
(45, 'file-manager', 'IT/L8180_Lite_LA.exe', 'L8180_Lite_LA.exe', 2, '2023-08-21 05:18:56', '2023-08-21 05:18:56'),
(46, 'file-manager', 'IT/L8180_Lite_LA.exe', 'L8180_Lite_LA.exe', 1, '2023-08-21 05:34:33', '2023-08-21 05:34:33'),
(47, 'file-manager', 'IT/L8180_Lite_LA.exe', 'L8180_Lite_LA.exe', 1, '2023-08-21 05:36:47', '2023-08-21 05:36:47'),
(48, 'file-manager', 'IT/L8180_Lite_LA.exe', 'L8180_Lite_LA.exe', 1, '2023-08-21 06:06:25', '2023-08-21 06:06:25'),
(49, 'file-manager', 'superadmin/test/Yaariyan (acoustic) _ Momina Mustehsan.mp4', 'Yaariyan (acoustic) _ Momina Mustehsan.mp4', 1, '2023-08-23 05:57:55', '2023-08-23 05:57:55'),
(50, 'file-manager', 'superadmin/test/Yaariyan (acoustic) _ Momina Mustehsan.mp4', 'Yaariyan (acoustic) _ Momina Mustehsan.mp4', 1, '2023-08-26 08:18:29', '2023-08-26 08:18:29'),
(51, 'file-manager', 'superadmin/test/Coke Studio _ Season 14 _ Beparwah _ Momina Mustehsan.mp4', 'Coke Studio _ Season 14 _ Beparwah _ Momina Mustehsan.mp4', 1, '2023-08-26 08:18:41', '2023-08-26 08:18:41'),
(52, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-08-28 09:13:47', '2023-08-28 09:13:47'),
(53, 'file-manager', 'superadmin/test/ভাবের দেশে থাকো কন্যা গো। লালন কন্যা মিম এর অসাধারণ গান। দোতারা ব্র্যান্ড.mp4', 'vaber deSe thakO knZa gO. laln knZa mim er osadharN gan. dOtara brZanD.mp4', 1, '2023-08-31 10:01:20', '2023-08-31 10:01:20'),
(54, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-05 05:49:25', '2023-09-05 05:49:25'),
(55, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-05 06:18:35', '2023-09-05 06:18:35'),
(56, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-12 03:17:52', '2023-09-12 03:17:52'),
(57, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-14 07:35:27', '2023-09-14 07:35:27'),
(58, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-14 08:46:25', '2023-09-14 08:46:25'),
(59, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-17 08:06:33', '2023-09-17 08:06:33'),
(60, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-17 09:03:47', '2023-09-17 09:03:47'),
(61, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2023-09-17 11:50:45', '2023-09-17 11:50:45');

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
-- Table structure for table `file_manager_permissions`
--

CREATE TABLE `file_manager_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=active, 0=deleted',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dir_name` varchar(255) NOT NULL,
  `permission_type` int(11) NOT NULL COMMENT '1=only view, 2=read/write,3=read/write/delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_manager_permissions`
--

INSERT INTO `file_manager_permissions` (`id`, `status`, `user_id`, `dir_name`, `permission_type`, `created_at`, `updated_at`) VALUES
(1, 0, 4, 'Account_Finance', 1, '2023-06-15 06:54:00', '2023-10-05 03:51:34'),
(3, 1, 4, 'Brand_Management', 2, '2023-06-15 07:27:54', '2023-06-18 05:09:37'),
(4, 1, 4, 'Chl_design', 1, '2023-06-15 07:28:33', '2023-06-18 05:02:55'),
(5, 1, 4, 'Common_Share', 2, '2023-06-15 07:29:24', '2023-06-17 23:23:07'),
(6, 0, 4, 'IT', 1, '2023-06-15 07:31:09', '2023-06-18 02:54:17'),
(7, 0, 4, 'Law', 1, '2023-06-15 07:31:17', '2023-06-18 05:09:05'),
(8, 0, 5, 'Account_Finance', 1, '2023-06-15 07:47:58', '2023-06-18 00:52:04'),
(9, 0, 5, 'Brand_Management', 1, '2023-06-17 22:56:04', '2023-06-18 00:52:08'),
(10, 0, 5, 'guest', 3, '2023-06-17 23:48:49', '2023-06-18 00:02:17'),
(11, 0, 5, 'admin', 2, '2023-06-18 00:39:22', '2023-06-18 00:46:50'),
(12, 0, 5, 'Chl_design', 2, '2023-06-18 00:43:30', '2023-06-18 00:47:20'),
(13, 0, 5, 'Credence_MIS', 2, '2023-06-18 00:44:10', '2023-06-18 00:47:44'),
(14, 0, 5, 'mnt', 2, '2023-06-18 00:52:17', '2023-06-18 06:04:16'),
(15, 1, 6, 'Account_Finance', 3, '2023-06-19 05:59:19', '2023-09-28 05:50:21'),
(16, 1, 13, 'Backup Jui', 2, '2023-07-08 05:22:08', '2023-07-08 05:22:08'),
(17, 1, 10, 'Backup Tajbid', 2, '2023-07-08 05:22:29', '2023-07-08 05:22:29'),
(18, 1, 11, 'Backup Sakib', 2, '2023-07-08 05:24:11', '2023-07-08 05:24:11'),
(19, 1, 2, 'IT', 3, '2023-07-17 05:26:45', '2023-07-17 05:26:45'),
(20, 1, 12, 'Shopno_backup', 3, '2023-08-30 03:48:37', '2023-08-30 03:48:37'),
(21, 1, 15, 'IT', 1, '2023-09-25 03:40:39', '2023-09-25 03:40:39'),
(22, 1, 15, 'common', 1, '2023-09-25 03:40:58', '2023-09-25 03:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `file_uploading_histories`
--

CREATE TABLE `file_uploading_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `disk_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `overwrite` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_uploading_histories`
--

INSERT INTO `file_uploading_histories` (`id`, `status`, `message`, `disk_name`, `path`, `file_name`, `overwrite`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'IMG_1300.JPG', '0', 1, '2023-06-25 04:42:18', '2023-06-25 04:42:18'),
(2, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'IMG_1300.JPG', '1', 1, '2023-06-25 04:43:04', '2023-06-25 04:43:04'),
(3, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'USER.zip', '0', 1, '2023-06-26 01:26:54', '2023-06-26 01:26:54'),
(4, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'USER.zip', '0', 1, '2023-06-26 01:28:25', '2023-06-26 01:28:25'),
(5, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'Amantu Billahi -  Alisha Kiyani - Heart Touching Arabic & English Nasheed  - AlJilani Production (1).mp4', '0', 1, '2023-06-26 01:28:32', '2023-06-26 01:28:32'),
(6, 'success', 'uploaded', 'file-manager', 'Backup Sakib', 'Sabbir.zip', '0', 11, '2023-07-08 05:31:58', '2023-07-08 05:31:58'),
(7, 'success', 'uploaded', 'file-manager', 'Backup Sakib', 'Desktop.zip', '0', 11, '2023-07-08 05:45:58', '2023-07-08 05:45:58'),
(8, 'success', 'uploaded', 'file-manager', 'Backup Sakib', 'old pc.zip', '0', 11, '2023-07-08 05:46:42', '2023-07-08 05:46:42'),
(9, 'success', 'uploaded', 'file-manager', 'Backup Sakib', 'Tally Data Backup.zip', '0', 11, '2023-07-08 05:47:00', '2023-07-08 05:47:00'),
(10, 'success', 'uploaded', 'file-manager', 'Backup Sakib', 'Tally.ERP9.zip', '0', 11, '2023-07-08 05:47:14', '2023-07-08 05:47:14'),
(11, 'success', 'uploaded', 'file-manager', 'Backup Sakib', 'Tally.zip', '0', 11, '2023-07-08 05:48:00', '2023-07-08 05:48:00'),
(12, 'success', 'uploaded', 'file-manager', 'IT/Print Today', 'Daily follow up report_08-07-2023_Rezaul.docx', '0', 3, '2023-07-09 04:09:07', '2023-07-09 04:09:07'),
(13, 'success', 'uploaded', 'file-manager', 'IT/Print Today', 'Daily follow up report_08-07-2023_Robin.docx', '0', 3, '2023-07-09 04:09:07', '2023-07-09 04:09:07'),
(14, 'success', 'uploaded', 'file-manager', 'IT/Print Today', 'Daily follow up report_08-07-2023_Romel.docx', '0', 3, '2023-07-09 04:09:07', '2023-07-09 04:09:07'),
(15, 'success', 'uploaded', 'file-manager', 'IT/Print Today', 'P&D Department Report_08-07-2023.docx', '0', 3, '2023-07-09 04:09:07', '2023-07-09 04:09:07'),
(16, 'success', 'uploaded', 'file-manager', 'IT/Print Today', 'att2000.mdb', '0', 3, '2023-07-11 06:45:59', '2023-07-11 06:45:59'),
(17, 'success', 'uploaded', 'file-manager', 'IT/Accounts Traning', 'acc_3.webm', '0', 2, '2023-07-17 05:30:55', '2023-07-17 05:30:55'),
(18, 'success', 'uploaded', 'file-manager', 'IT/Accounts Traning', 'acc_2.webm', '0', 2, '2023-07-17 05:33:15', '2023-07-17 05:33:15'),
(19, 'success', 'uploaded', 'file-manager', 'IT/Accounts Traning', 'Part 01.webm', '0', 2, '2023-07-17 05:33:19', '2023-07-17 05:33:19'),
(20, 'success', 'uploaded', 'file-manager', 'IT', 'L8180_Lite_LA.exe', '0', 1, '2023-08-21 05:15:19', '2023-08-21 05:15:19'),
(21, 'success', 'uploaded', 'file-manager', 'IT', 'tally.ini', '0', 1, '2023-08-22 04:29:04', '2023-08-22 04:29:04'),
(22, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'Yaariyan (acoustic) _ Momina Mustehsan.mp4', '0', 1, '2023-08-23 05:56:30', '2023-08-23 05:56:30'),
(23, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'Coke Studio _ Season 14 _ Beparwah _ Momina Mustehsan.mp4', '0', 1, '2023-08-26 08:14:24', '2023-08-26 08:14:24'),
(24, 'success', 'uploaded', 'file-manager', 'superadmin/test', 'ভাবের দেশে থাকো কন্যা গো। লালন কন্যা মিম এর অসাধারণ গান। দোতারা ব্র্যান্ড.mp4', '0', 1, '2023-08-31 09:58:03', '2023-08-31 09:58:03');

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
(1, '2023_04_15_074849_create_departments_table', 1),
(2, '2023_04_15_093951_create_branches_table', 1),
(3, '2023_10_28_144138_create_designations_table.php', 1),
(4, '2014_10_12_000000_create_users_table', 1),
(5, '2014_10_12_100000_create_password_resets_table', 1),
(6, '2019_08_19_000000_create_failed_jobs_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2023_04_15_051654_laratrust_setup_tables', 2),
(9, '2023_04_17_035846_create_complain_table', 5),
(10, '2023_04_18_075737_create_priority_table', 7),
(11, '2023_06_15_103011_create_file_manager_permissions_table', 8),
(12, '2023_06_24_095214_create_create_file_histories_table', 9),
(13, '2023_06_24_112959_create_create_directory_histories_table', 10),
(14, '2023_06_24_114612_create_download_histories_table', 11),
(15, '2023_06_25_092517_create_file_uploading_histories_table', 12),
(16, '2023_06_25_105048_create_deleted_histories_table', 13),
(17, '2023_07_04_054212_create_pest_histories_table', 14),
(18, '2023_07_06_102013_create_rename_histories_table', 15),
(19, '2023_07_08_100222_create_branch_transfer_histories_table', 16),
(20, '2023_07_06_165020_create_department_transfer_histories_table', 17),
(21, '2023_09_28_161717_create_account_voucher_types', 18),
(22, '2023_09_28_160330_create_account_voucher_infos_table', 19),
(23, '2023_09_30_154651_create_voucher_types_table', 20),
(24, '2023_10_05_161342_create_permission_user_histories_table', 21),
(25, '2023_10_05_152241_create_permission_users_table', 22),
(26, '2023_10_02_145031_create_voucher_documents_table', 23),
(27, '2023_10_17_155258_create_voucher_document_share_email_links_table.php', 24),
(28, '2023_10_17_155458_create_voucher_document_share_email_lists_table.php', 24),
(29, '2023_10_21_140827_create_user_salary_certificate_data_table.php', 25),
(30, '2023_10_30_105254_create_designation_change_histories_table.php', 26);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('oubaida.credence@gmail.com', '$2y$10$4NQsbkmISZG1sRglfFd1IeUsUSOz.QrLyDBTzTbAlL6D9JGxNAQ0K', '2023-07-06 07:23:53'),
('ahnafwub19@gmail.com', '$2y$10$NW4LkHMfRmzLtwI57B7KZOLGt3dDlYeXVAVrPqBU/9I/tlHkd7PmW', '2023-07-08 09:00:26'),
('credence.mis@gmail.com', '$2y$10$GwKUyhmXUGFSzJ8QfFwpt.VsOl/RktdR40LURj776rctNcgl9ikRu', '2023-07-11 06:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `parent_id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, 'none', 'None', 'None', NULL, NULL),
(2, NULL, 'accounts_file', 'Accounts File', 'Accounts File Upload', '2023-10-04 09:41:29', '2023-10-04 09:41:29'),
(3, 2, 'voucher_document_upload', 'Voucher Document Upload', 'Accounts Voucher Document Upload', '2023-10-04 09:41:29', '2023-10-04 09:41:29'),
(4, 2, 'voucher_document_edit', 'Voucher Document Edit', 'Accounts Voucher Document Edit', '2023-10-04 09:41:29', '2023-10-04 09:41:29'),
(5, 2, 'voucher_document_delete', 'Voucher Document Delete', 'Accounts Voucher Document Edit', '2023-10-04 09:41:29', '2023-10-04 09:41:29'),
(6, 2, 'voucher_document_list', 'Voucher Document List', 'Accounts Voucher Document List', '2023-10-04 09:41:29', '2023-10-04 09:41:29'),
(7, 2, 'voucher_document_view', 'Voucher Document View', 'Accounts Voucher Document View', '2023-10-04 09:41:29', '2023-10-04 09:41:29'),
(8, 2, 'voucher_type_add', 'Voucher Type Add', 'Voucher Type Add', '2023-10-07 07:29:21', '2023-10-07 07:29:21'),
(9, 2, 'voucher_type_edit', 'Voucher Type Edit', 'Voucher Type Edit', '2023-10-07 07:29:21', '2023-10-07 07:29:21'),
(10, 2, 'voucher_type_delete', 'Voucher Type Delete', 'Voucher Type Delete', '2023-10-07 07:29:21', '2023-10-07 07:29:21'),
(11, 2, 'voucher_type_list', 'Voucher Type List', 'Voucher Type List', '2023-10-07 07:29:21', '2023-10-07 07:29:21'),
(12, 2, 'voucher_type_view', 'Voucher Type View', 'Voucher Type View', '2023-10-07 07:29:21', '2023-10-07 07:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_users`
--

CREATE TABLE `permission_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_users`
--

INSERT INTO `permission_users` (`id`, `user_id`, `permission_name`, `parent_id`, `created_at`, `updated_at`) VALUES
(2, 1, 'voucher_document_upload', 8, '2023-10-07 07:25:00', '2023-10-07 07:25:00'),
(5, 10, 'voucher_document_upload', 8, '2023-10-07 08:12:03', '2023-10-07 08:12:03'),
(6, 10, 'voucher_document_edit', 8, '2023-10-07 08:12:03', '2023-10-07 08:12:03'),
(7, 10, 'voucher_document_list', 8, '2023-10-07 08:12:03', '2023-10-07 08:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `permission_user_histories`
--

CREATE TABLE `permission_user_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `permission_id` bigint(20) UNSIGNED DEFAULT NULL,
  `operation_name` varchar(255) NOT NULL COMMENT 'add, deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_user_histories`
--

INSERT INTO `permission_user_histories` (`id`, `admin_id`, `user_id`, `permission_id`, `operation_name`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'added', '2023-10-07 07:17:54', '2023-10-07 07:17:54'),
(2, 1, 1, 1, 'deleted', '2023-10-07 07:24:43', '2023-10-07 07:24:43'),
(3, 1, 1, 2, 'added', '2023-10-07 07:25:00', '2023-10-07 07:25:00'),
(4, 1, 1, 3, 'added', '2023-10-07 07:34:22', '2023-10-07 07:34:22'),
(5, 1, 1, 3, 'deleted', '2023-10-07 07:35:26', '2023-10-07 07:35:26'),
(6, 1, 1, 4, 'added', '2023-10-07 07:38:14', '2023-10-07 07:38:14'),
(7, 1, 10, 5, 'added', '2023-10-07 08:12:03', '2023-10-07 08:12:03'),
(8, 1, 10, 6, 'added', '2023-10-07 08:12:03', '2023-10-07 08:12:03'),
(10, 1, 1, 4, 'deleted', '2023-10-07 09:51:43', '2023-10-07 09:51:43');

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
-- Table structure for table `pest_histories`
--

CREATE TABLE `pest_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'copy/cut',
  `disk_name` varchar(255) DEFAULT NULL,
  `to` varchar(255) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `document_type` varchar(255) DEFAULT NULL COMMENT 'file/directory',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pest_histories`
--

INSERT INTO `pest_histories` (`id`, `status`, `message`, `type`, `disk_name`, `to`, `from`, `document_type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'success', 'copied', 'copy', 'file-manager', 'new folder/Folder name', 'new folder/abc', 'directories', 1, '2023-07-05 05:15:08', '2023-07-05 05:15:08'),
(2, 'success', 'copied', 'copy', 'file-manager', 'new folder/Folder name', 'new folder/test', 'directories', 1, '2023-07-05 05:15:08', '2023-07-05 05:15:08'),
(3, 'success', 'copied', 'copy', 'file-manager', 'new folder/Folder name', 'new folder/new.txt', 'files', 1, '2023-07-05 05:15:08', '2023-07-05 05:15:08'),
(4, 'success', 'copied', 'copy', 'file-manager', 'new folder/Folder name', 'new folder/test.txt', 'files', 1, '2023-07-05 05:15:08', '2023-07-05 05:15:08'),
(5, 'success', 'copied', 'copy', 'file-manager', 'new folder/abc', 'new folder/test.txt', 'files', 1, '2023-07-05 05:15:50', '2023-07-05 05:15:50'),
(6, 'success', 'copied', 'cut', 'file-manager', 'new folder/Folder name', 'new folder/abc', 'directories', 1, '2023-07-05 05:15:58', '2023-07-05 05:15:58'),
(7, 'success', 'copied', 'copy', 'file-manager', 'IT/test', 'new folder/Folder name/test/new.txt', 'files', 1, '2023-07-05 05:29:55', '2023-07-05 05:29:55'),
(8, 'success', 'copied', 'copy', 'file-manager', 'IT/test', 'new folder/Folder name/test/new.txt', 'files', 1, '2023-07-05 05:30:24', '2023-07-05 05:30:24'),
(9, 'success', 'copied', 'copy', 'file-manager', 'IT/test', 'new folder/Folder name/abc', 'directories', 1, '2023-07-05 05:32:06', '2023-07-05 05:32:06'),
(10, 'success', 'copied', 'copy', 'file-manager', 'Backup Tajbid', 'IT/Accounts Traning', 'directories', 1, '2023-07-17 05:35:29', '2023-07-17 05:35:29');

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
-- Table structure for table `rename_histories`
--

CREATE TABLE `rename_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `disk_name` varchar(255) DEFAULT NULL,
  `old_name` varchar(255) DEFAULT NULL,
  `new_name` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rename_histories`
--

INSERT INTO `rename_histories` (`id`, `status`, `message`, `disk_name`, `old_name`, `new_name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'success', 'renamed', 'file-manager', 'guest/Folder name', 'guest/Folder', 1, '2023-07-06 04:31:03', '2023-07-06 04:31:03'),
(2, 'success', 'renamed', 'file-manager', 'Backup', 'Backup Jui', 1, '2023-07-08 05:21:18', '2023-07-08 05:21:18');

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
(1, 1, 'App\\Models\\User'),
(2, 2, 'App\\Models\\User'),
(1, 3, 'App\\Models\\User'),
(5, 4, 'App\\Models\\User'),
(5, 5, 'App\\Models\\User'),
(5, 6, 'App\\Models\\User'),
(5, 7, 'App\\Models\\User'),
(5, 8, 'App\\Models\\User'),
(5, 9, 'App\\Models\\User'),
(5, 10, 'App\\Models\\User'),
(5, 11, 'App\\Models\\User'),
(5, 12, 'App\\Models\\User'),
(5, 13, 'App\\Models\\User'),
(5, 14, 'App\\Models\\User'),
(3, 15, 'App\\Models\\User'),
(5, 16, 'App\\Models\\User');

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
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive, 4=cool, 5=deleted',
  `designation_id` bigint(20) DEFAULT NULL,
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

INSERT INTO `users` (`id`, `employee_id`, `name`, `phone`, `email`, `dept_id`, `status`, `designation_id`, `branch_id`, `joining_date`, `profile_pic`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'H100001', 'Abu Oubaida', '01877772225', 'abuoubaida36@gmail.com', 1, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$kMzSJzslbUEvE.4rYha/Lu1SX6fYn799F.PJv0/FcFiA.XqWyprZe', 'A5vodrExtLXvLUILeWS6jrL5b9cn7rBLj24UffEk8oKagUa4tb0qMjHsnH6b', '2023-04-16 00:35:47', '2023-07-06 08:32:09'),
(2, 'H100002', 'Ahnaf', '01894940525', 'ahnafwub19@gmail.com', 1, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$SAxVIPMcLM2Fnnz3uMaineKui.TXaqSInmtKJS3jEM..hGWE99sRS', NULL, '2023-04-29 03:01:16', '2023-07-08 09:01:39'),
(3, 'H100003', 'Md Rafiuzzaman', '01884337718', 'credence.mis@gmail.com', 5, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$I3apcEh/OiF7fZI8hqRJmujTfPnxJ9vI1f1bu4dqEU6uaQ6oWCDNC', 'sj5e9MtoL54cVT6gkYMKSK7igIKMTsoDnkREncgemzslT4wPCeoWuCxj0QKC', '2023-06-06 03:15:39', '2023-10-03 05:39:40'),
(4, 'H200001', 'Atik Hasnat', '0170883219', 'atik.credence@gmail.com', 2, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$U31R/bYzL.YcfPYNH.MEXuzx5eQZO2.U.3q8WX9kVQWHn6nxFiiQO', NULL, '2023-06-09 22:26:49', '2023-06-18 05:00:38'),
(5, 'H100004', 'abu', '2334343443', 'oubaida.credence@gmail.com', 1, 5, NULL, 1, NULL, NULL, NULL, '$2y$10$ckNDqbDBTeumMB5pf8XlwuT33aoo4dNLLSvJheJtid8krVcJo5KtG', NULL, '2023-06-10 05:53:26', '2023-07-06 10:00:39'),
(6, 'H300001', 'Towhid Al Islam', '01877772227', 'towhid@credencehousinglimited.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$tXX86eY72XhoAVhIa6x9KemNujM6xShczx0SZC8KzMDKICZnXhRMG', NULL, '2023-06-19 05:58:45', '2023-09-28 05:50:37'),
(7, 'H101001', 'Md Osman Goni', '01742893453', 'osmankakib@gmail.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$3Z/zzHqVrYYUdGYXDTa3yuoX42UC9reaKp4JcKkvEHOkAPxGsfVKG', NULL, '2023-06-19 06:08:28', '2023-06-19 06:08:28'),
(8, 'H101002', 'Mahabub Alam', '01743727252', 'mahabubalam758@gmail.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$iGG3cS9YW.aFDbQZZF1ZMuQykM0.keQv/p3zz32BajRKXjxMQJ1gi', NULL, '2023-06-19 06:09:07', '2023-06-19 06:09:07'),
(9, 'H101003', 'Md. Shariful Islam', '01710974872', 'sumonvisc7@gmail.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$zlIcrYK2a1ZHMnNrq2JE3OBaLFw8WJOb/WrftA4LRH7EM9cExCECS', NULL, '2023-06-19 06:09:49', '2023-06-19 06:09:49'),
(10, 'H101004', 'Md. Tajbidul Islam', '01708832141', 'tajbidul@live.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$63VXTzQ/H3J8M1oxQsxNVu5IoKwb7fV9P4F09bN5gKy0kjUJfuVUC', 'o8C8q3MmZXRRHt0Vawk3AzlJZvn0u6cynbThyzFaGn0ma5L824ug0cNz9aaP', '2023-06-19 06:12:06', '2023-10-07 08:10:04'),
(11, 'H101005', 'Sakib Siraj Sabbir', '01708832131', 'sakibsiraj.acc@gmail.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$ZDPdAK/M5g4FeK9Np54QR.nOB/Uz45NgJYnvIcvzDZ4PF3Q7e2Mp.', NULL, '2023-06-19 06:13:10', '2023-07-08 05:24:38'),
(12, 'H101006', 'Sakif Hasan Shopno', '01329668471', 'sakifshopno@yahoo.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$q/B/yxPR2TO4qplNJ.cbAem1SIO.zzHDpFqQ8IWDiAY40OCl0GOs.', NULL, '2023-06-19 06:13:51', '2023-08-30 03:42:38'),
(13, 'H101007', 'Khadijatul Jannat', '01670821859', 'juijannat647@gmail.com', 4, 1, NULL, 1, NULL, NULL, NULL, '$2y$10$oaI3cyanty.uzkfoP7NbAek.6mBP54H6mpZtcD2QWRxLxWWpmNHHy', NULL, '2023-06-19 06:14:23', '2023-07-08 05:19:16'),
(14, 'H100005', 'Abu Oubaida', '312321', 'abuoubaida37@gmail.com', 1, 5, NULL, 1, NULL, NULL, NULL, '$2y$10$8FF7Klc3UbIhP/LuRqwrlutfwOGNk1tx2AQ99DFzOA70W8q9Kc13K', NULL, '2023-06-22 06:01:33', '2023-07-08 04:57:21'),
(15, 'H100012', 'Kushon', '01674389309', '99kushondas@gmail.com', 1, 1, NULL, 2, NULL, NULL, NULL, '$2y$10$ed1FO6tfznOb4yQEU2ve/OAU2LeLZGW.Mk.suR3QYps1jOtMO6656', NULL, '2023-09-25 03:37:41', '2023-09-25 03:37:41'),
(16, 'H110002', 'Keshob Chondro Mohonto', '01742535527', 'keshob392@gmail.com', 5, 1, NULL, 2, NULL, NULL, NULL, '$2y$10$Uj1Ogy.zquTRaSKy7zbhVu156p/BlFwSGbXQBb81YOcdqnSDq7xja', NULL, '2023-09-25 03:38:56', '2023-09-25 03:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_documents`
--

CREATE TABLE `voucher_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_info_id` bigint(20) UNSIGNED NOT NULL,
  `document` text DEFAULT NULL,
  `filepath` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_documents`
--

INSERT INTO `voucher_documents` (`id`, `voucher_info_id`, `document`, `filepath`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'My document.pdf', 'file-manager/Account Document/', 1, NULL, '2023-10-07 07:44:24', NULL),
(2, 1, 'Printer.pdf', 'file-manager/Account Document/', 1, NULL, '2023-10-07 07:44:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_types`
--

CREATE TABLE `voucher_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `voucher_type_title` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `remarks` text NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_types`
--

INSERT INTO `voucher_types` (`id`, `status`, `voucher_type_title`, `code`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', '300', 'test', 1, 1, '2023-09-30 10:55:08', '2023-10-03 05:37:43'),
(5, 1, 'Bank', '100', 'bank', 1, 1, '2023-10-01 11:39:21', '2023-10-01 11:39:21'),
(6, 1, 'te', '234', 'sadf', 1, 1, '2023-10-07 07:38:32', '2023-10-07 07:38:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_voucher_infos`
--
ALTER TABLE `account_voucher_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_voucher_infos_created_by_foreign` (`created_by`),
  ADD KEY `account_voucher_infos_updated_by_foreign` (`updated_by`),
  ADD KEY `account_voucher_infos_voucher_type_id_foreign` (`voucher_type_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_transfer_histories`
--
ALTER TABLE `branch_transfer_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_transfer_histories_transfer_user_id_foreign` (`transfer_user_id`),
  ADD KEY `branch_transfer_histories_new_branch_id_foreign` (`new_branch_id`),
  ADD KEY `branch_transfer_histories_from_branch_id_foreign` (`from_branch_id`),
  ADD KEY `branch_transfer_histories_transfer_by_foreign` (`transfer_by`);

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
-- Indexes for table `create_directory_histories`
--
ALTER TABLE `create_directory_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `create_directory_histories_created_by_foreign` (`created_by`);

--
-- Indexes for table `create_file_histories`
--
ALTER TABLE `create_file_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `create_file_histories_created_by_foreign` (`created_by`);

--
-- Indexes for table `deleted_histories`
--
ALTER TABLE `deleted_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted_histories_created_by_foreign` (`created_by`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_dept_code_unique` (`dept_code`);

--
-- Indexes for table `department_transfer_histories`
--
ALTER TABLE `department_transfer_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_transfer_histories_transfer_user_id_foreign` (`transfer_user_id`),
  ADD KEY `department_transfer_histories_new_dept_id_foreign` (`new_dept_id`),
  ADD KEY `department_transfer_histories_from_dept_id_foreign` (`from_dept_id`),
  ADD KEY `department_transfer_histories_transfer_by_foreign` (`transfer_by`);

--
-- Indexes for table `download_histories`
--
ALTER TABLE `download_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `download_histories_created_by_foreign` (`created_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `file_manager_permissions`
--
ALTER TABLE `file_manager_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_manager_permissions_user_id_foreign` (`user_id`);

--
-- Indexes for table `file_uploading_histories`
--
ALTER TABLE `file_uploading_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_uploading_histories_created_by_foreign` (`created_by`);

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
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_users`
--
ALTER TABLE `permission_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `permission_user_histories`
--
ALTER TABLE `permission_user_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_user_histories_permission_child_id_foreign` (`permission_id`),
  ADD KEY `permission_user_histories_admin_id_foreign` (`admin_id`),
  ADD KEY `permission_user_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pest_histories`
--
ALTER TABLE `pest_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pest_histories_created_by_foreign` (`created_by`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `priority_priority_number_unique` (`priority_number`),
  ADD KEY `priority_created_by_foreign` (`created_by`),
  ADD KEY `priority_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `rename_histories`
--
ALTER TABLE `rename_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rename_histories_created_by_foreign` (`created_by`);

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
-- Indexes for table `voucher_documents`
--
ALTER TABLE `voucher_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_documents_voucher_info_id_foreign` (`voucher_info_id`),
  ADD KEY `voucher_documents_created_by_foreign` (`created_by`),
  ADD KEY `voucher_documents_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_types_created_by_foreign` (`created_by`),
  ADD KEY `voucher_types_updated_by_foreign` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_voucher_infos`
--
ALTER TABLE `account_voucher_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branch_transfer_histories`
--
ALTER TABLE `branch_transfer_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complains`
--
ALTER TABLE `complains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `create_directory_histories`
--
ALTER TABLE `create_directory_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `create_file_histories`
--
ALTER TABLE `create_file_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deleted_histories`
--
ALTER TABLE `deleted_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department_transfer_histories`
--
ALTER TABLE `department_transfer_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `download_histories`
--
ALTER TABLE `download_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_manager_permissions`
--
ALTER TABLE `file_manager_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `file_uploading_histories`
--
ALTER TABLE `file_uploading_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permission_users`
--
ALTER TABLE `permission_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permission_user_histories`
--
ALTER TABLE `permission_user_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pest_histories`
--
ALTER TABLE `pest_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rename_histories`
--
ALTER TABLE `rename_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `voucher_documents`
--
ALTER TABLE `voucher_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voucher_types`
--
ALTER TABLE `voucher_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_voucher_infos`
--
ALTER TABLE `account_voucher_infos`
  ADD CONSTRAINT `account_voucher_infos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_voucher_infos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_voucher_infos_voucher_type_id_foreign` FOREIGN KEY (`voucher_type_id`) REFERENCES `voucher_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_transfer_histories`
--
ALTER TABLE `branch_transfer_histories`
  ADD CONSTRAINT `branch_transfer_histories_from_branch_id_foreign` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_transfer_histories_new_branch_id_foreign` FOREIGN KEY (`new_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_transfer_histories_transfer_by_foreign` FOREIGN KEY (`transfer_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_transfer_histories_transfer_user_id_foreign` FOREIGN KEY (`transfer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `create_directory_histories`
--
ALTER TABLE `create_directory_histories`
  ADD CONSTRAINT `create_directory_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `create_file_histories`
--
ALTER TABLE `create_file_histories`
  ADD CONSTRAINT `create_file_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deleted_histories`
--
ALTER TABLE `deleted_histories`
  ADD CONSTRAINT `deleted_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `department_transfer_histories`
--
ALTER TABLE `department_transfer_histories`
  ADD CONSTRAINT `department_transfer_histories_from_dept_id_foreign` FOREIGN KEY (`from_dept_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_transfer_histories_new_dept_id_foreign` FOREIGN KEY (`new_dept_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_transfer_histories_transfer_by_foreign` FOREIGN KEY (`transfer_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_transfer_histories_transfer_user_id_foreign` FOREIGN KEY (`transfer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `download_histories`
--
ALTER TABLE `download_histories`
  ADD CONSTRAINT `download_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_manager_permissions`
--
ALTER TABLE `file_manager_permissions`
  ADD CONSTRAINT `file_manager_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_uploading_histories`
--
ALTER TABLE `file_uploading_histories`
  ADD CONSTRAINT `file_uploading_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `parent_id` FOREIGN KEY (`parent_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_users`
--
ALTER TABLE `permission_users`
  ADD CONSTRAINT `permission_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_user_histories`
--
ALTER TABLE `permission_user_histories`
  ADD CONSTRAINT `permission_user_histories_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_histories_permission_child_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pest_histories`
--
ALTER TABLE `pest_histories`
  ADD CONSTRAINT `pest_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `priorities`
--
ALTER TABLE `priorities`
  ADD CONSTRAINT `priority_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `priority_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rename_histories`
--
ALTER TABLE `rename_histories`
  ADD CONSTRAINT `rename_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `voucher_documents`
--
ALTER TABLE `voucher_documents`
  ADD CONSTRAINT `voucher_documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_documents_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_documents_voucher_info_id_foreign` FOREIGN KEY (`voucher_info_id`) REFERENCES `account_voucher_infos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD CONSTRAINT `voucher_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_types_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
