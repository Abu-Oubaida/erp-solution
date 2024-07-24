-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 12:15 PM
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
-- Database: `credence_erp_solution`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_voucher_infos`
--

CREATE TABLE `account_voucher_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_type_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_date` date NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `file_count` int(11) DEFAULT NULL COMMENT 'if multiple file in a request',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_voucher_infos`
--

INSERT INTO `account_voucher_infos` (`id`, `voucher_type_id`, `voucher_date`, `voucher_number`, `file_count`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 3, '2023-11-26', 'JV-1234', 4, NULL, 1, NULL, '2023-11-26 07:54:14', NULL),
(2, 3, '2023-11-26', '12345', 1, 'test', 1, NULL, '2023-11-26 08:13:48', NULL),
(3, 3, '2023-11-26', 'JV208585', 1, NULL, 1, NULL, '2023-11-26 10:27:12', NULL),
(4, 2, '2023-11-25', '4521', 1, 'ffsfasfas', 116, NULL, '2023-11-26 11:06:56', NULL),
(5, 3, '2023-11-01', '584654', 1, '444', 200, NULL, '2023-11-26 11:59:48', NULL),
(6, 3, '2024-03-11', '12', 1, 'C', 316, NULL, '2024-03-11 04:53:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blood_groups`
--

CREATE TABLE `blood_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blood_type` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_groups`
--

INSERT INTO `blood_groups` (`id`, `blood_type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'B+', 1, NULL, '2023-11-08 03:51:54', '2023-11-08 03:51:54'),
(4, 'A-', 1, NULL, '2023-11-09 05:43:37', '2023-11-09 05:43:37'),
(5, 'A+', 1, NULL, '2023-11-09 05:43:57', '2023-11-09 05:43:57'),
(6, 'AB-', 1, NULL, '2023-11-09 05:44:05', '2023-11-09 05:44:05'),
(7, 'AB+', 1, NULL, '2023-11-09 05:44:10', '2023-11-09 05:44:10'),
(8, 'B-', 1, NULL, '2023-11-09 05:44:30', '2023-11-09 05:44:30'),
(9, 'O-', 1, NULL, '2023-11-09 05:44:45', '2023-11-09 05:44:45'),
(10, 'O+', 1, NULL, '2023-11-09 05:44:49', '2023-11-09 05:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_type` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_type`, `status`, `remarks`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Corporate Office', 1, '1', 'corporate office', NULL, NULL, 1, NULL),
(2, 'Head Office', 1, '1', 'Head Office', NULL, '2023-11-08 02:32:57', 1, 1),
(3, 'Head Office-2 (Anandodhara)', 1, '1', NULL, '2023-11-04 06:27:41', '2023-11-04 06:27:41', 1, NULL),
(4, 'Sales Office 27', 1, '1', 'Sales Office 27', '2023-11-25 07:23:53', '2023-11-25 07:23:53', 1, NULL),
(5, 'Head Office-3 (666)', 1, '1', 'Head Office-3 (666)', '2023-11-25 07:52:35', '2023-11-25 07:52:35', 1, NULL),
(6, '2/1, Lalmatia', 3, '1', '2/1, Lalmatia', '2023-11-25 10:36:14', '2023-11-25 10:36:14', 1, NULL),
(7, '21/21, Tubanir', 3, '1', '21/21, Tubanir', '2023-11-25 10:37:59', '2023-11-25 10:37:59', 1, NULL),
(8, '3/13, Iqbal road, Mohammadpur', 3, '1', '3/13, Iqbal road, Mohammadpur', '2023-11-25 10:38:31', '2023-11-25 10:38:31', 1, NULL),
(9, '3/13, Ruman Legercy', 3, '1', '3/13, Ruman Legercy', '2023-11-25 10:53:48', '2023-11-25 10:53:48', 1, NULL),
(10, '3/3, BlockB, Humayun Road', 3, '1', '3/3, BlockB, Humayun Road', '2023-11-25 10:55:47', '2023-11-25 10:55:47', 1, NULL),
(11, '6/9, Lalmatia', 3, '1', '6/9, Lalmatia', '2023-11-25 10:56:14', '2023-11-25 10:56:14', 1, NULL),
(12, 'AK Paradise', 3, '1', 'AK Paradise', '2023-11-25 10:56:39', '2023-11-25 10:56:39', 1, NULL),
(13, 'Akparadise', 3, '1', 'Akparadise', '2023-11-25 10:57:09', '2023-11-25 10:57:09', 1, NULL),
(14, 'Amat Heaven', 3, '1', 'Amat Heaven', '2023-11-25 10:57:23', '2023-11-25 10:57:23', 1, NULL),
(15, 'Austral', 3, '1', 'Austral', '2023-11-25 10:57:40', '2023-11-25 10:57:40', 1, NULL),
(16, 'Bella Vista', 2, '1', 'Bella Vista', '2023-11-25 11:02:01', '2023-11-25 11:02:01', 1, NULL),
(17, 'Bluebell', 3, '1', 'Bluebell', '2023-11-25 11:02:46', '2023-11-25 11:02:46', 1, NULL),
(18, 'Branch', 3, '1', 'Branch', '2023-11-25 11:02:58', '2023-11-25 11:02:58', 1, NULL),
(19, 'Breezeway', 3, '1', 'Breezeway', '2023-11-25 11:04:14', '2023-11-25 11:04:14', 1, NULL),
(20, 'Carnation', 3, '1', 'Carnation', '2023-11-25 11:37:20', '2023-11-25 11:37:20', 1, NULL),
(21, 'Cherry Blossom', 3, '1', 'Cherry Blossom', '2023-11-25 11:37:54', '2023-11-25 11:37:54', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_transfer_histories`
--

CREATE TABLE `branch_transfer_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_user_id` bigint(20) UNSIGNED NOT NULL,
  `new_branch_id` bigint(20) UNSIGNED NOT NULL,
  `from_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_types`
--

CREATE TABLE `branch_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active',
  `title` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_types`
--

INSERT INTO `branch_types` (`id`, `status`, `title`, `code`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Head Office', '1', 'Head Office', 1, NULL, '2023-11-04 02:33:15', '2023-11-04 02:33:15'),
(2, 1, 'None', '0', NULL, 1, 1, '2023-11-04 05:27:44', '2023-11-08 03:50:55'),
(3, 1, 'Project', '20', 'Project', 1, NULL, '2023-11-25 05:38:48', '2023-11-25 05:38:48');

-- --------------------------------------------------------

--
-- Table structure for table `company_infos`
--

CREATE TABLE `company_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive, 2=suspended, 3=deleted',
  `company_name` varchar(255) NOT NULL,
  `company_type_id` bigint(20) UNSIGNED NOT NULL,
  `contract_person_name` varchar(255) DEFAULT NULL,
  `company_code` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `contract_person_phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `logo_sm` text DEFAULT NULL,
  `logo_icon` text DEFAULT NULL,
  `cover` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_infos`
--

INSERT INTO `company_infos` (`id`, `status`, `company_name`, `company_type_id`, `contract_person_name`, `company_code`, `phone`, `contract_person_phone`, `email`, `location`, `remarks`, `logo`, `logo_sm`, `logo_icon`, `cover`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1', 'Credence', 1, 'Abu', 'CHL', '01877772225', '01778138129', 'abu@gmail.com', 'Dhanmondi, Dhaka', NULL, 'image/logo/CHL_logo_chl_logo.png', 'image/logo/CHL_logo_sm_chl_logo.png', 'image/logo/CHL_logo_icon_chl_logo.png', 'image/logo/CHL_cover_chl_logo.png', 1, NULL, '2024-06-09 17:30:57', '2024-06-09 17:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `company_types`
--

CREATE TABLE `company_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_type_title` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive, 3=deleted',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_types`
--

INSERT INTO `company_types` (`id`, `company_type_title`, `status`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Real Estate', 1, 'Real Estate Company', 1, NULL, '2024-06-08 06:26:32', '2024-06-08 06:26:32');

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
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active,2=processing,3=solved,4=pending,5=reject,6=delete',
  `progress` int(11) DEFAULT NULL,
  `forward_to` bigint(20) UNSIGNED DEFAULT NULL,
  `forward_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `document_img` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'success', 'dirCreated', 'file-manager', NULL, 'test', 1, '2023-11-22 12:16:47', '2023-11-22 12:16:47'),
(2, 'success', 'dirCreated', 'file-manager', NULL, 'test', 1, '2024-06-07 16:05:40', '2024-06-07 16:05:40');

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
(1, 'success', 'fileCreated', 'file-manager', 'guest', 'kdfsadf.tex', '', 200, '2023-11-26 12:04:48', '2023-11-26 12:04:48');

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
(1, 'file-manager', 'guest/BU-2606JPG300x300.jpg', 'file', 200, '2023-11-26 12:06:17', '2023-11-26 12:06:17'),
(2, 'file-manager', 'guest/kdfsadf.tex', 'file', 200, '2023-11-26 12:06:23', '2023-11-26 12:06:23'),
(3, 'file-manager', 'guest/sdjfvjsf.zip', 'file', 200, '2023-11-26 12:06:26', '2023-11-26 12:06:26'),
(4, 'file-manager', 'IT/1GB.bin', 'file', 1, '2024-03-10 12:16:47', '2024-03-10 12:16:47'),
(5, 'file-manager', 'IT/Hridoy Khan best song _Hridoy khan top 10_bangla music video_Hridoy khan album _bangla new song.mp4', 'file', 1, '2024-06-02 03:18:27', '2024-06-02 03:18:27');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dept_code` varchar(255) DEFAULT NULL,
  `dept_name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `dept_code`, `dept_name`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, '01', 'Management', 1, 'Management', '2023-11-09 03:32:39', '2023-11-09 03:32:39'),
(2, '02', 'HR & Administration', 1, 'HR & Administration', '2023-11-09 03:35:29', '2023-11-09 03:35:29'),
(3, '03', 'Planning & Development', 1, 'Planning & Development', '2023-11-09 03:36:06', '2023-11-09 03:36:06'),
(4, '04', 'Planning & Investment', 1, 'Planning & Investment', '2023-11-09 03:36:28', '2023-11-09 03:36:28'),
(5, '05', 'Planning & Coordination', 1, 'Planning & Coordination', '2023-11-09 03:39:11', '2023-11-09 03:39:11'),
(7, '06', 'Planning & Design', 1, 'Planning & Design', '2023-11-09 03:39:53', '2023-11-09 03:39:53'),
(8, '07', 'Accounts & Finance', 1, 'Accounts & Finance', '2023-11-09 03:40:12', '2023-11-09 03:40:12'),
(9, '08', 'Construction', 1, 'Construction', '2023-11-09 03:41:28', '2023-11-09 03:41:28'),
(10, '09', 'Purchases', 1, 'Material Quality Assurance And Purchases', '2023-11-09 03:42:26', '2023-11-09 03:42:26'),
(11, '10', 'Inventory Management', 1, 'Inventory Management', '2023-11-09 03:43:28', '2023-11-09 03:43:28'),
(12, '11', 'Quality Assurance', 1, 'Quality Assurance', '2023-11-09 03:43:55', '2023-11-09 03:43:55'),
(13, '12', 'BoQ', 1, 'BoQ', '2023-11-09 03:44:29', '2023-11-09 03:44:29'),
(14, '13', 'Electro-Mechanical', 1, 'Electro-Mechanical', '2023-11-09 03:44:57', '2023-11-09 03:44:57'),
(15, '14', 'Revenue Collection', 1, 'Revenue Collection', '2023-11-09 03:45:22', '2023-11-09 03:45:22'),
(16, '15', 'Law', 1, 'Law', '2023-11-09 03:46:05', '2023-11-09 03:46:05'),
(17, '16', 'Logistics', 1, 'Logistics', '2023-11-09 03:46:26', '2023-11-09 03:46:26'),
(18, '17', 'Brand Management', 1, 'Brand Management', '2023-11-09 03:46:49', '2023-11-09 03:46:49'),
(19, '18', 'Customer Services', 1, 'Customer Services', '2023-11-09 03:47:04', '2023-11-09 03:47:04'),
(20, '19', 'Internal Audit', 1, 'Internal Audit', '2023-11-09 03:47:31', '2023-11-09 03:47:31'),
(21, '20', 'IT', 1, 'Information Technology', '2023-11-09 03:48:38', '2023-11-09 03:48:38'),
(22, '21', 'MIS', 1, 'MIS', '2023-11-09 03:49:02', '2023-11-09 03:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `department_transfer_histories`
--

CREATE TABLE `department_transfer_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `new_employee_id` varchar(255) DEFAULT NULL,
  `old_employee_id` varchar(255) DEFAULT NULL,
  `transfer_user_id` bigint(20) UNSIGNED NOT NULL,
  `new_dept_id` bigint(20) UNSIGNED NOT NULL,
  `from_dept_id` bigint(20) UNSIGNED NOT NULL,
  `transfer_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `title`, `status`, `priority`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Chairman', 1, 1, 'Chairman of company', 1, NULL, '2023-10-30 04:30:10', '2023-10-30 04:30:10'),
(2, 'Senior Executive', 1, 51, 'Senior Executive', 1, NULL, '2023-10-30 04:32:15', '2023-10-30 04:32:15'),
(3, 'Executive', 1, 52, 'Executive', 1, NULL, '2023-10-30 04:32:44', '2023-10-30 04:32:44'),
(4, 'Junior Executive', 1, 53, 'Junior Executive', 1, NULL, '2023-10-30 04:33:22', '2023-10-30 04:33:22'),
(6, 'Managing Director', 1, 5, 'Managing Director', 1, NULL, '2023-11-23 08:54:04', '2023-11-23 08:54:04'),
(7, 'Director', 1, 6, 'Director', 1, NULL, '2023-11-23 08:54:35', '2023-11-23 08:54:35'),
(8, 'Deputy Managing Director', 1, 7, 'Deputy Managing Director', 1, NULL, '2023-11-23 08:55:05', '2023-11-23 08:55:05'),
(9, 'Senior General Manager', 1, 11, 'Senior General Manager', 1, NULL, '2023-11-23 11:06:06', '2023-11-23 11:06:06'),
(10, 'General Manager', 1, 12, 'General Manager', 1, NULL, '2023-11-23 11:06:44', '2023-11-23 11:06:44'),
(11, 'Assistant General Manager', 1, 14, 'Assistant General Manager', 1, NULL, '2023-11-23 11:37:00', '2023-11-23 11:37:00'),
(12, 'Deputy General Manager', 1, 13, 'Deputy General Manager', 1, NULL, '2023-11-23 11:37:22', '2023-11-23 11:37:22'),
(13, 'Consultant', 1, 9, 'Consultant', 1, NULL, '2023-11-23 11:39:07', '2023-11-23 11:39:07'),
(14, 'Assistant Managing Director', 1, 8, 'Assistant Managing Director', 1, NULL, '2023-11-23 11:39:43', '2023-11-23 11:39:43'),
(15, 'Senior Manager', 1, 31, 'Senior Manager', 1, NULL, '2023-11-23 11:41:10', '2023-11-23 11:41:10'),
(16, 'Manager', 1, 32, 'Manager', 1, NULL, '2023-11-23 11:41:40', '2023-11-23 11:41:40'),
(17, 'Deputy Manager', 1, 33, 'Deputy Manager', 1, NULL, '2023-11-23 11:42:02', '2023-11-23 11:42:02'),
(18, 'Assistant Manager', 1, 34, 'Assistant Manager', 1, NULL, '2023-11-23 11:42:27', '2023-11-23 11:42:27'),
(19, 'Manager (Security)', 1, 32, 'Manager (Security)', 1, NULL, '2023-11-23 11:49:44', '2023-11-23 11:49:44'),
(20, 'Vice Chairman', 1, 2, 'Vice Chairman', 1, NULL, '2023-11-23 11:51:55', '2023-11-23 11:51:55'),
(21, 'Admin (Supervisor)', 1, 41, 'Admin (Supervisor)', 1, NULL, '2023-11-23 11:57:22', '2023-11-23 11:57:22'),
(22, 'Security Supervisor', 1, 42, 'Security Supervisor', 1, NULL, '2023-11-23 12:00:14', '2023-11-23 12:00:14'),
(23, 'CAD Detailer', 1, 50, 'CAD Detailer', 1, NULL, '2023-11-23 12:02:57', '2023-11-23 12:02:57'),
(24, 'Architect', 1, 51, 'Architect', 1, NULL, '2023-11-23 12:05:25', '2023-11-23 12:05:25'),
(25, 'Executive (Asst. Architect)', 1, 52, 'Executive (Asst. Architect)', 1, NULL, '2023-11-23 12:06:00', '2023-11-23 12:06:00'),
(26, 'Junior Architect', 1, 53, 'Junior Architect', 1, NULL, '2023-11-23 12:06:34', '2023-11-23 12:06:34'),
(27, 'Project Engineer', 1, 51, 'Project Engineer', 1, NULL, '2023-11-23 12:22:07', '2023-11-23 12:22:07'),
(28, 'Senior Project Engineer', 1, 50, 'Senior Project Engineer', 1, NULL, '2023-11-23 12:22:42', '2023-11-23 12:22:42'),
(29, 'Deputy Project Engineer', 1, 52, 'Deputy Project Engineer', 1, NULL, '2023-11-23 12:23:27', '2023-11-23 12:23:27'),
(30, 'Deputy Project Manager', 1, 49, 'Deputy Project Manager', 1, NULL, '2023-11-23 12:23:48', '2023-11-23 12:23:48'),
(31, 'Asst. Project Manager', 1, 50, 'Asst. Project Manager', 1, NULL, '2023-11-23 12:24:27', '2023-11-23 12:24:27'),
(32, 'Assistant Project Engineer', 1, 53, 'Assistant Project Engineer', 1, NULL, '2023-11-23 12:25:32', '2023-11-23 12:25:32'),
(33, 'Jr. Asst. Project Engineer', 1, 54, 'Jr. Asst. Project Engineer', 1, NULL, '2023-11-23 12:25:53', '2023-11-23 12:25:53'),
(34, 'Content Writer', 1, 53, 'Content Writer', 1, NULL, '2023-11-23 12:26:23', '2023-11-23 12:26:23'),
(35, 'Sr. Project Engineer (Civil)', 1, 50, 'Sr. Project Engineer (Civil)', 1, NULL, '2023-11-23 12:27:44', '2023-11-23 12:27:44'),
(36, 'Senior Site Accountant', 1, 55, 'Senior Site Accountant', 1, NULL, '2023-11-23 12:28:18', '2023-11-23 12:28:18'),
(37, 'Site Accountant', 1, 56, 'Site Accountant', 1, NULL, '2023-11-23 12:28:34', '2023-11-23 12:28:34'),
(38, 'Assistant Project Accountant', 1, 57, 'Assistant Project Accountant', 1, NULL, '2023-11-23 12:29:07', '2023-11-23 12:29:07'),
(39, 'Intern', 1, 61, 'Intern', 1, NULL, '2023-11-23 12:29:41', '2023-11-23 12:29:41'),
(40, 'Material Engineer', 1, 56, 'Material Engineer', 1, NULL, '2023-11-23 12:30:08', '2023-11-23 12:30:08'),
(41, 'Trainee Site Engineer', 1, 61, 'Trainee Site Engineer', 1, NULL, '2023-11-23 12:30:47', '2023-11-23 12:30:47'),
(42, 'Supervisor', 1, 70, 'Supervisor', 1, NULL, '2023-11-23 12:31:14', '2023-11-23 12:31:14'),
(43, 'Supervisor (Catering Service)', 1, 70, 'Supervisor (Catering Service)', 1, NULL, '2023-11-23 12:31:29', '2023-11-23 12:31:29'),
(44, 'Supervisor (MD Sir Desk)', 1, 70, 'Supervisor (MD Sir Desk)', 1, NULL, '2023-11-23 12:31:43', '2023-11-23 12:31:43'),
(45, 'Senior Office Assistant', 1, 71, 'Senior Office Assistant', 1, NULL, '2023-11-23 12:31:58', '2023-11-23 12:31:58'),
(46, 'Electrician', 1, 65, 'Electrician', 1, NULL, '2023-11-23 12:32:44', '2023-11-23 12:32:44'),
(47, 'Mechanical Operator', 1, 66, 'Mechanical Operator', 1, NULL, '2023-11-23 12:33:04', '2023-11-23 12:33:04'),
(48, 'Welder', 1, 67, 'Welder', 1, NULL, '2023-11-23 12:33:46', '2023-11-23 12:33:46'),
(49, 'Work Assistant', 1, 75, 'Work Assistant', 1, NULL, '2023-11-23 12:34:03', '2023-11-23 12:34:03'),
(50, 'Security Guard', 1, 72, 'Security Guard', 1, NULL, '2023-11-23 12:34:26', '2023-11-23 12:34:26'),
(51, 'Office Assistant', 1, 72, 'Office Assistant', 1, NULL, '2023-11-23 12:34:54', '2023-11-23 12:34:54'),
(52, 'Office Messenger', 1, 72, 'Office Messenger', 1, NULL, '2023-11-23 12:35:13', '2023-11-23 12:35:13'),
(53, 'Photocopy Machine Operator', 1, 72, 'Photocopy Machine Operator', 1, NULL, '2023-11-23 12:35:32', '2023-11-23 12:35:32'),
(54, 'Driver', 1, 72, 'Driver', 1, NULL, '2023-11-23 12:35:58', '2023-11-23 12:35:58'),
(55, 'Cook', 1, 72, 'Cook', 1, NULL, '2023-11-23 12:36:10', '2023-11-23 12:36:10'),
(56, 'Kitchen Assistant', 1, 73, 'Kitchen Assistant', 1, NULL, '2023-11-23 12:36:33', '2023-11-23 12:36:33'),
(57, 'Gardener', 1, 73, 'Gardener', 1, NULL, '2023-11-23 12:36:54', '2023-11-23 12:36:54'),
(58, 'Cleaner', 1, 75, 'Cleaner', 1, NULL, '2023-11-23 12:37:13', '2023-11-23 12:37:13'),
(59, 'Mechanic', 1, 76, 'Mechanic', 1, NULL, '2023-11-23 12:37:34', '2023-11-23 12:37:34'),
(60, 'AGM', 1, 14, 'AGM', 1, NULL, '2023-11-23 12:38:03', '2023-11-23 12:38:03');

-- --------------------------------------------------------

--
-- Table structure for table `designation_change_histories`
--

CREATE TABLE `designation_change_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_user_id` bigint(20) UNSIGNED NOT NULL,
  `new_designation_id` bigint(20) UNSIGNED NOT NULL,
  `old_designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designation_change_histories`
--

INSERT INTO `designation_change_histories` (`id`, `transfer_user_id`, `new_designation_id`, `old_designation_id`, `transfer_by`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, 1, '2023-11-22 11:58:29', '2023-11-22 11:58:29');

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
(1, 'file-manager', 'guest/sdjfvjsf.zip', 'sdjfvjsf.zip', 200, '2023-11-26 12:05:35', '2023-11-26 12:05:35'),
(2, 'file-manager', 'IT/Hridoy Khan best song _Hridoy khan top 10_bangla music video_Hridoy khan album _bangla new song.mp4', 'Hridoy Khan best song _Hridoy khan top 10_bangla music video_Hridoy khan album _bangla new song.mp4', 1, '2024-01-11 08:39:14', '2024-01-11 08:39:14'),
(3, 'file-manager', 'Account Document/chl_complain (1).sql', 'chl_complain (1).sql', 1, '2024-01-11 10:07:13', '2024-01-11 10:07:13'),
(4, 'file-manager', 'Account Document/12345_Test_chl_complain (1).sql', '12345_Test_chl_complain (1).sql', 1, '2024-01-11 10:07:42', '2024-01-11 10:07:42'),
(5, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2024-03-21 05:03:51', '2024-03-21 05:03:51'),
(6, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2024-03-21 05:23:06', '2024-03-21 05:23:06'),
(7, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2024-03-31 09:24:05', '2024-03-31 09:24:05'),
(8, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2024-04-01 05:25:04', '2024-04-01 05:25:04'),
(9, 'file-manager', 'IT/Classroom.rar', 'Classroom.rar', 1, '2024-06-02 03:18:14', '2024-06-02 03:18:14');

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
(1, 1, 200, 'guest', 3, '2023-11-26 11:52:56', '2023-11-26 12:06:02'),
(2, 1, 316, 'Account_Finance', 2, '2024-03-11 04:51:41', '2024-03-11 04:51:41'),
(3, 1, 316, 'Account Document', 2, '2024-03-11 04:51:48', '2024-03-11 04:51:48');

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
(1, 'success', 'uploaded', 'file-manager', 'guest', 'BU-2606JPG300x300.jpg', '0', 200, '2023-11-26 12:05:14', '2023-11-26 12:05:14'),
(2, 'success', 'uploaded', 'file-manager', 'IT', 'Hridoy Khan best song _Hridoy khan top 10_bangla music video_Hridoy khan album _bangla new song.mp4', '0', 1, '2024-01-11 08:38:07', '2024-01-11 08:38:07'),
(3, 'success', 'uploaded', 'file-manager', 'IT', 'ZKT TIME ATTENDANCE SOFTWARE.rar', '0', 1, '2024-02-05 06:55:41', '2024-02-05 06:55:41'),
(4, 'success', 'uploaded', 'file-manager', 'IT', 'Classroom.rar', '0', 1, '2024-02-05 06:55:43', '2024-02-05 06:55:43'),
(5, 'success', 'uploaded', 'file-manager', 'IT', '1GB.bin', '0', 1, '2024-03-10 12:16:31', '2024-03-10 12:16:31'),
(6, 'success', 'uploaded', 'file-manager', 'IT', 'winrar-x64-701.exe', '0', 1, '2024-06-02 03:19:45', '2024-06-02 03:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `fixed_assets`
--

CREATE TABLE `fixed_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recourse_code` varchar(255) NOT NULL,
  `materials_name` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `depreciation` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive, 3=deleted',
  `remarks` text DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fixed_assets`
--

INSERT INTO `fixed_assets` (`id`, `recourse_code`, `materials_name`, `rate`, `unit`, `depreciation`, `status`, `remarks`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '123', 'Abc', '120', 'Nos', NULL, 1, NULL, 1, 1, NULL, '2024-07-18 11:23:55', '2024-07-18 11:23:55');

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
(5, '2023_04_15_051654_laratrust_setup_tables', 1),
(6, '2023_04_15_074849_create_departments_table', 1),
(7, '2023_04_15_093951_create_branches_table', 1),
(8, '2023_04_17_035846_create_complains_table', 1),
(9, '2023_04_18_075737_create_priorities _table', 1),
(10, '2023_06_15_103011_create_file_manager_permissions_table', 1),
(11, '2023_06_24_095214_create_create_file_histories_table', 1),
(12, '2023_06_24_112959_create_create_directory_histories_table', 1),
(13, '2023_06_24_114612_create_download_histories_table', 1),
(14, '2023_06_25_092517_create_file_uploading_histories_table', 1),
(15, '2023_06_25_105048_create_deleted_histories_table', 1),
(16, '2023_07_04_054212_create_pest_histories_table', 1),
(17, '2023_07_06_102013_create_rename_histories_table', 1),
(18, '2023_07_06_165020_create_department_transfer_histories_table', 1),
(19, '2023_07_08_100222_create_branch_transfer_histories_table', 1),
(20, '2023_09_28_160330_create_account_voucher_infos_table', 1),
(21, '2023_09_30_154651_create_voucher_types_table', 1),
(22, '2023_10_02_145031_create_voucher_documents_table', 1),
(23, '2023_10_04_124150_create_user_permissions_table', 1),
(24, '2023_10_05_152241_create_permission_users_table', 1),
(25, '2023_10_05_161342_create_permission_user_histories_table', 1),
(26, '2023_10_17_155258_create_voucher_document_share_email_links_table', 1),
(27, '2023_10_17_155458_create_voucher_document_share_email_lists_table', 1),
(28, '2023_10_21_140827_create_user_salary_certificate_data_table', 1),
(29, '2023_10_28_144138_create_designations_table', 1),
(30, '2023_10_30_105254_create_designation_change_histories_table', 1),
(31, '2023_11_02_093436_create_role_wise_default_permissions_table', 1),
(32, '2023_11_02_094922_create_blood_groups_table', 1),
(33, '2023_11_02_100032_update_users_table', 1),
(34, '2023_11_04_132025_create_branch_types_table', 1),
(35, '2023_11_04_132841_modify_branch_type_column_in_branches_table', 1),
(36, '2023_11_04_181052_update_branches_table', 1),
(37, '2023_11_11_155345_update_department_transfer_histories_table', 1),
(38, '2023_11_11_174126_update_users_table', 1),
(39, '2023_11_13_102458_create_salary_certificate_transections_table', 1),
(40, '2023_11_27_142603_create_voucher_document_individual_deleted_histories_table', 2),
(41, '2023_11_27_143538_update_voucher_document_individual_deleted_histories_table', 3),
(42, '2023_11_27_171522_create_voucher_document_share_links_table', 4),
(43, '2024_06_07_211440_create_company_infos_table', 5),
(44, '2024_06_07_224319_update_company_infos_table', 6),
(45, '2024_06_08_100925_create_company_type_table', 7),
(46, '2024_07_18_123923_update_user_table_company_id', 8);

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
('oubaida.credence@gmail.com', '$2y$10$mbhm3VSQbA9wnfijf9zeAepQU7cQztOadQeHl/RNJxfYdxkGxeSzS', '2023-12-24 06:44:57');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `is_parent` int(11) DEFAULT NULL COMMENT '1=yes 0=no',
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `parent_id`, `name`, `is_parent`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, 'none', 1, 'None', 'None', NULL, NULL),
(2, NULL, 'accounts_file', 1, 'Accounts File', 'Accounts File Upload', '2023-10-04 03:41:29', '2023-10-04 03:41:29'),
(3, 2, 'voucher_document_upload', NULL, 'Voucher Document Upload', 'Accounts Voucher Document Upload', '2023-10-04 03:41:29', '2023-10-04 03:41:29'),
(4, 2, 'voucher_document_edit', NULL, 'Voucher Document Edit', 'Accounts Voucher Document Edit', '2023-10-04 03:41:29', '2023-10-04 03:41:29'),
(5, 2, 'voucher_document_delete', NULL, 'Voucher Document Delete', 'Accounts Voucher Document Edit', '2023-10-04 03:41:29', '2023-10-04 03:41:29'),
(6, 2, 'voucher_document_list', NULL, 'Voucher Document List', 'Accounts Voucher Document List', '2023-10-04 03:41:29', '2023-10-04 03:41:29'),
(7, 2, 'voucher_document_view', NULL, 'Voucher Document View', 'Accounts Voucher Document View', '2023-10-04 03:41:29', '2023-10-04 03:41:29'),
(8, 2, 'voucher_type_add', NULL, 'Voucher Type Add', 'Voucher Type Add', '2023-10-07 01:29:21', '2023-10-07 01:29:21'),
(11, 2, 'voucher_type_list', NULL, 'Voucher Type List', 'Voucher Type List', '2023-10-07 01:29:21', '2023-10-07 01:29:21'),
(12, 2, 'voucher_type_view', NULL, 'Voucher Type View', 'Voucher Type View', '2023-10-07 01:29:21', '2023-10-07 01:29:21'),
(21, NULL, 'user_managemet', 1, 'User Management', 'User Management', '2023-10-30 04:10:36', '2023-10-30 04:10:36'),
(22, 21, 'add_user', NULL, 'Add New User', 'Add New User', '2023-10-30 04:11:49', '2023-10-30 04:11:49'),
(23, 21, 'list_user', NULL, 'User List', 'User List', '2023-10-30 04:12:55', '2023-10-30 04:12:55'),
(24, 21, 'view_user', NULL, 'User Profile View', 'User Profile Single View', '2023-10-30 04:13:55', '2023-10-30 04:13:55'),
(25, 21, 'edit_user', NULL, 'Edit User Profile', 'Edit User Profile', '2023-10-30 04:14:32', '2023-10-30 04:14:32'),
(26, 21, 'delete_user', NULL, 'Delete User Profile', 'Delete User Profile', '2023-10-30 04:15:06', '2023-10-30 04:15:06'),
(27, 21, 'department', 1, 'Department', 'Department', '2023-10-30 04:16:44', '2023-10-30 04:16:44'),
(28, 27, 'add_department', NULL, 'Add Department ( Add + List + Edit )', 'Add Department ( Add + List + Edit )', '2023-10-30 04:19:43', '2023-10-30 04:19:43'),
(29, 2, 'salary_certificate_input', NULL, 'Add Salary Certificate', 'Salary Certificate Input', '2023-11-14 23:47:56', '2023-11-14 23:47:56'),
(30, 21, 'mobile_sim', 1, 'Mobile SIM', 'Mobile SIM', '2023-11-14 23:51:18', '2023-11-14 23:51:18'),
(31, 30, 'add_sim_number', NULL, 'Add SIM', 'Add Mobile SIM Number', '2023-11-14 23:52:43', '2023-11-14 23:52:43'),
(32, NULL, 'file_manager', 1, 'File Manager', 'File Manager', '2023-11-14 23:54:00', '2023-11-14 23:54:00'),
(33, 2, 'add_voucher_type', NULL, 'Add voucher Type', 'Add voucher Type', '2023-11-14 23:57:28', '2023-11-14 23:57:28'),
(34, 2, 'edit_voucher_type', NULL, 'Edit Voucher Type', 'Edit Voucher Type', '2023-11-14 23:58:25', '2023-11-14 23:58:25'),
(35, 2, 'delete_voucher_type', NULL, 'Delete Voucher Type', 'Delete Voucher Type', '2023-11-18 22:00:01', '2023-11-18 22:00:01'),
(36, 2, 'add_voucher_document', NULL, 'Add Voucher', 'Add Voucher Document', '2023-11-18 22:01:58', '2023-11-18 22:01:58'),
(37, 2, 'add_bill_document', NULL, 'Add Bill', 'Add Bill Document', '2023-11-18 22:02:25', '2023-11-18 22:02:25'),
(38, 2, 'add_fr_document', NULL, 'Add FR', 'Add FR Document', '2023-11-18 22:03:34', '2023-11-18 22:03:34'),
(39, 2, 'list_voucher_document', NULL, 'Voucher List', 'Voucher Document List', '2023-11-18 22:04:10', '2023-11-18 22:04:10'),
(40, 2, 'view_voucher_document', NULL, 'Voucher View', 'Voucher View Document', '2023-11-18 22:10:56', '2023-11-18 22:10:56'),
(41, 2, 'salary_certificate_list', NULL, 'Salary Certificate List', 'Salary Certificate List', '2023-11-18 22:12:00', '2023-11-18 22:12:00'),
(42, 2, 'salary_certificate_view', NULL, 'Salary Certificate View', 'Salary Certificate View', '2023-11-18 22:13:18', '2023-11-18 22:13:18'),
(43, NULL, 'complains', 1, 'Complains', 'Complains', '2023-11-18 22:14:20', '2023-11-18 22:14:20'),
(44, 43, 'add_complain', NULL, 'Add Complain', 'Add Complain', '2023-11-18 22:14:53', '2023-11-18 22:14:53'),
(45, 43, 'list_complain_all', NULL, 'Complain List', 'Complain List', '2023-11-18 22:15:36', '2023-11-18 22:15:36'),
(46, 43, 'list_department_complain_all', NULL, 'Department complain List', 'Department complain List', '2023-11-18 22:16:06', '2023-11-18 22:16:06'),
(47, 43, 'list_my_complain', NULL, 'My Complain List', 'My Complain List', '2023-11-18 22:16:29', '2023-11-18 22:16:29'),
(48, 43, 'list_my_complain_trash', NULL, 'My Complain Trash List', 'My Complain Trash List', '2023-11-18 22:19:39', '2023-11-18 22:19:39'),
(49, 43, 'view_complain_single', NULL, 'View Complain', 'View Complain', '2023-11-18 22:20:08', '2023-11-18 22:20:08'),
(50, 43, 'edit_complain', NULL, 'Edit Complain', 'Edit Complain', '2023-11-18 22:20:31', '2023-11-18 22:20:31'),
(51, 43, 'delete_complain', NULL, 'Delete Complain', 'Delete Complain', '2023-11-18 22:20:58', '2023-11-18 22:20:58'),
(52, 21, 'designation', 1, 'Designation', 'Designation', '2023-11-18 22:21:45', '2023-11-18 22:21:45'),
(53, 52, 'add_designation', NULL, 'Add Designation', 'Add Designation', '2023-11-18 22:22:15', '2023-11-18 22:22:15'),
(54, 21, 'branch', 1, 'Branch', 'Branch', '2023-11-18 22:22:56', '2023-11-18 22:22:56'),
(55, 54, 'list_branch_type', NULL, 'Branch Type List', 'Branch Type List', '2023-11-18 22:23:42', '2023-11-18 22:23:42'),
(56, 54, 'add_branch_type', NULL, 'Add Branch Type', 'Add Branch Type', '2023-11-18 22:26:05', '2023-11-18 22:26:05'),
(57, 54, 'edit_branch_type', NULL, 'Edit Branch Type', 'Edit Branch Type', '2023-11-18 22:26:26', '2023-11-18 22:26:26'),
(58, 54, 'delete_branch_type', NULL, 'Delete Branch Type', 'Delete Branch Type', '2023-11-18 22:26:57', '2023-11-18 22:26:57'),
(59, 54, 'list_branch', NULL, 'Branch List', 'Branch List', '2023-11-18 22:27:23', '2023-11-18 22:27:23'),
(60, 54, 'add_branch', NULL, 'Add Branch', 'Add Branch', '2023-11-18 22:27:50', '2023-11-18 22:27:50'),
(61, 54, 'edit_branch', NULL, 'Edit Branch', 'Edit Branch', '2023-11-18 22:28:20', '2023-11-18 22:28:20'),
(62, 21, 'blood_group', 1, 'Blood Group', 'Blood Group', '2023-11-18 22:28:59', '2023-11-18 22:28:59'),
(63, 62, 'list_blood_group', NULL, 'Blood Group List', 'Blood Group List', '2023-11-18 22:29:22', '2023-11-18 22:29:22'),
(64, 62, 'add_blood_group', NULL, 'Add Blood Group', 'Add Blood Group', '2023-11-18 22:29:43', '2023-11-18 22:29:43'),
(65, 62, 'delete_blood_group', NULL, 'Delete Blood Group', 'Delete Blood Group', '2023-11-18 22:33:26', '2023-11-18 22:33:26'),
(66, 2, 'add_voucher_document_individual', NULL, 'Add Voucher Document Individual', 'Add Voucher Document Individual', '2023-11-27 04:46:39', '2023-11-27 04:46:39'),
(67, 2, 'delete_voucher_document_individual', NULL, 'Delete Voucher Document Individual', 'Delete Voucher Document Individual', '2023-11-27 04:47:32', '2023-11-27 04:47:32'),
(68, 2, 'share_voucher_document_individual', NULL, 'Share Voucher Document Individual', 'Share Voucher Document Individual', '2023-11-27 07:37:49', '2023-11-27 07:37:49'),
(69, 2, 'multiple_voucher_operation', NULL, 'Multiple Voucher Operation', 'Selected data delete, download zip, download list etc.', '2023-11-28 11:54:56', '2023-11-28 11:54:56'),
(72, NULL, 'control_panel', 1, 'Control Panel', 'Control Panel', '2024-06-07 12:48:01', '2024-06-07 12:48:01'),
(73, NULL, 'sales_interface', 1, 'Sales Interface', 'Sales Interface', '2024-06-07 12:49:44', '2024-06-07 12:49:44'),
(74, 73, 'sales_dashboard_interface', NULL, 'Sales Interface Dashboard', 'Sales Interface Dashboard', '2024-06-07 12:50:50', '2024-06-07 12:50:50'),
(75, 73, 'add_sales_lead', NULL, 'Add Sales Lead', 'Add Sales Lead', '2024-06-07 12:51:50', '2024-06-07 12:51:50'),
(76, 73, 'sales_lead_list', NULL, 'Sales Lead List', 'Sales Lead List', '2024-06-07 12:52:49', '2024-06-07 12:52:49'),
(77, 72, 'create_sales_team', NULL, 'Create Sales Team', 'Create Sales Team', '2024-06-07 14:44:12', '2024-06-07 14:44:12'),
(78, 72, 'assign_sales_marketing_users', NULL, 'Assign Sales Marketing Users', 'Assign Sales Marketing Users', '2024-06-07 14:45:22', '2024-06-07 14:45:22');

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
(1, 116, 'accounts_file', 2, '2023-11-26 11:03:25', '2023-11-26 11:03:25'),
(2, 116, 'voucher_document_upload', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(5, 116, 'voucher_document_list', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(6, 116, 'voucher_document_view', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(7, 116, 'voucher_type_add', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(10, 116, 'voucher_type_list', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(11, 116, 'voucher_type_view', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(12, 116, 'salary_certificate_input', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(13, 116, 'add_voucher_type', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(16, 116, 'add_voucher_document', 2, '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(17, 116, 'add_bill_document', 2, '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(18, 116, 'add_fr_document', 2, '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(19, 116, 'list_voucher_document', 2, '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(20, 116, 'view_voucher_document', 2, '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(21, 116, 'salary_certificate_list', 2, '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(22, 116, 'salary_certificate_view', 2, '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(45, 200, 'file_manager', 32, '2023-11-26 12:04:08', '2023-11-26 12:04:08'),
(46, 116, 'add_voucher_document_individual', 2, '2023-11-27 07:20:32', '2023-11-27 07:20:32'),
(47, 116, 'share_voucher_document_individual', 2, '2023-11-27 07:38:09', '2023-11-27 07:38:09'),
(49, 316, 'accounts_file', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(50, 316, 'voucher_document_upload', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(51, 316, 'voucher_document_edit', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(52, 316, 'voucher_document_delete', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(53, 316, 'voucher_document_list', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(54, 316, 'voucher_document_view', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(55, 316, 'voucher_type_add', 2, '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(56, 316, 'voucher_type_list', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(57, 316, 'voucher_type_view', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(58, 316, 'salary_certificate_input', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(59, 316, 'add_voucher_type', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(60, 316, 'edit_voucher_type', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(61, 316, 'delete_voucher_type', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(62, 316, 'add_voucher_document', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(63, 316, 'add_bill_document', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(64, 316, 'add_fr_document', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(65, 316, 'list_voucher_document', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(66, 316, 'view_voucher_document', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(67, 316, 'salary_certificate_list', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(68, 316, 'salary_certificate_view', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(69, 316, 'add_voucher_document_individual', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(70, 316, 'delete_voucher_document_individual', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(71, 316, 'share_voucher_document_individual', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(72, 316, 'multiple_voucher_operation', 2, '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(73, 402, 'list_user', 21, '2024-06-10 04:08:21', '2024-06-10 04:08:21'),
(74, 402, 'view_user', 21, '2024-06-10 04:08:21', '2024-06-10 04:08:21'),
(75, 402, 'blood_group', 21, '2024-06-10 04:08:21', '2024-06-10 04:08:21'),
(76, 402, 'voucher_document_list', 2, '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(77, 402, 'voucher_document_view', 2, '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(78, 402, 'list_voucher_document', 2, '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(79, 402, 'view_voucher_document', 2, '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(80, 402, 'salary_certificate_list', 2, '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(81, 402, 'salary_certificate_view', 2, '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(82, 402, 'sales_dashboard_interface', 73, '2024-06-10 04:13:03', '2024-06-10 04:13:03'),
(83, 402, 'add_sales_lead', 73, '2024-06-10 04:13:03', '2024-06-10 04:13:03'),
(84, 402, 'sales_lead_list', 73, '2024-06-10 04:13:03', '2024-06-10 04:13:03'),
(85, 402, 'sales_interface', 73, '2024-06-10 04:50:59', '2024-06-10 04:50:59');

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
(1, 1, 116, 1, 'added', '2023-11-26 11:03:25', '2023-11-26 11:03:25'),
(2, 1, 116, 2, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(3, 1, 116, 3, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(4, 1, 116, 4, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(5, 1, 116, 5, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(6, 1, 116, 6, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(7, 1, 116, 7, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(8, 1, 116, 8, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(9, 1, 116, 9, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(10, 1, 116, 10, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(11, 1, 116, 11, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(12, 1, 116, 12, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(13, 1, 116, 13, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(14, 1, 116, 14, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(15, 1, 116, 15, 'added', '2023-11-26 11:03:26', '2023-11-26 11:03:26'),
(16, 1, 116, 16, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(17, 1, 116, 17, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(18, 1, 116, 18, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(19, 1, 116, 19, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(20, 1, 116, 20, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(21, 1, 116, 21, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(22, 1, 116, 22, 'added', '2023-11-26 11:03:27', '2023-11-26 11:03:27'),
(23, 1, 200, 23, 'added', '2023-11-26 11:53:30', '2023-11-26 11:53:30'),
(24, 1, 200, 24, 'added', '2023-11-26 11:54:04', '2023-11-26 11:54:04'),
(25, 1, 200, 25, 'added', '2023-11-26 11:55:39', '2023-11-26 11:55:39'),
(26, 1, 200, 26, 'added', '2023-11-26 11:56:28', '2023-11-26 11:56:28'),
(27, 1, 200, 27, 'added', '2023-11-26 11:56:28', '2023-11-26 11:56:28'),
(28, 1, 200, 28, 'added', '2023-11-26 11:56:28', '2023-11-26 11:56:28'),
(29, 1, 200, 29, 'added', '2023-11-26 11:56:28', '2023-11-26 11:56:28'),
(30, 1, 200, 30, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(31, 1, 200, 31, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(32, 1, 200, 32, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(33, 1, 200, 33, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(34, 1, 200, 34, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(35, 1, 200, 35, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(36, 1, 200, 36, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(37, 1, 200, 37, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(38, 1, 200, 38, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(39, 1, 200, 39, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(40, 1, 200, 40, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(41, 1, 200, 41, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(42, 1, 200, 42, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(43, 1, 200, 43, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(44, 1, 200, 44, 'added', '2023-11-26 11:56:29', '2023-11-26 11:56:29'),
(45, 1, 200, 43, 'deleted', '2023-11-26 11:57:46', '2023-11-26 11:57:46'),
(46, 1, 200, 41, 'deleted', '2023-11-26 11:58:29', '2023-11-26 11:58:29'),
(47, 1, 200, 25, 'deleted', '2023-11-26 11:59:05', '2023-11-26 11:59:05'),
(48, 1, 200, 38, 'deleted', '2023-11-26 12:00:19', '2023-11-26 12:00:19'),
(49, 1, 200, 35, 'deleted', '2023-11-26 12:01:21', '2023-11-26 12:01:21'),
(50, 1, 200, 23, 'deleted', '2023-11-26 12:01:40', '2023-11-26 12:01:40'),
(51, 1, 200, 24, 'deleted', '2023-11-26 12:02:56', '2023-11-26 12:02:56'),
(52, 1, 200, 39, 'deleted', '2023-11-26 12:02:59', '2023-11-26 12:02:59'),
(53, 1, 200, 40, 'deleted', '2023-11-26 12:03:02', '2023-11-26 12:03:02'),
(54, 1, 200, 37, 'deleted', '2023-11-26 12:03:06', '2023-11-26 12:03:06'),
(55, 1, 200, 36, 'deleted', '2023-11-26 12:03:09', '2023-11-26 12:03:09'),
(56, 1, 200, 44, 'deleted', '2023-11-26 12:03:12', '2023-11-26 12:03:12'),
(57, 1, 200, 42, 'deleted', '2023-11-26 12:03:15', '2023-11-26 12:03:15'),
(58, 1, 200, 27, 'deleted', '2023-11-26 12:03:18', '2023-11-26 12:03:18'),
(59, 1, 200, 26, 'deleted', '2023-11-26 12:03:20', '2023-11-26 12:03:20'),
(60, 1, 200, 28, 'deleted', '2023-11-26 12:03:23', '2023-11-26 12:03:23'),
(61, 1, 200, 29, 'deleted', '2023-11-26 12:03:26', '2023-11-26 12:03:26'),
(62, 1, 200, 30, 'deleted', '2023-11-26 12:03:29', '2023-11-26 12:03:29'),
(63, 1, 200, 32, 'deleted', '2023-11-26 12:03:32', '2023-11-26 12:03:32'),
(64, 1, 200, 31, 'deleted', '2023-11-26 12:03:34', '2023-11-26 12:03:34'),
(65, 1, 200, 33, 'deleted', '2023-11-26 12:03:37', '2023-11-26 12:03:37'),
(66, 1, 200, 34, 'deleted', '2023-11-26 12:03:58', '2023-11-26 12:03:58'),
(67, 1, 200, 45, 'added', '2023-11-26 12:04:08', '2023-11-26 12:04:08'),
(68, 1, 116, 15, 'deleted', '2023-11-27 06:56:21', '2023-11-27 06:56:21'),
(69, 1, 116, 4, 'deleted', '2023-11-27 06:56:31', '2023-11-27 06:56:31'),
(70, 1, 116, 9, 'deleted', '2023-11-27 06:58:08', '2023-11-27 06:58:08'),
(71, 1, 116, 8, 'deleted', '2023-11-27 07:03:03', '2023-11-27 07:03:03'),
(72, 1, 116, 3, 'deleted', '2023-11-27 07:04:16', '2023-11-27 07:04:16'),
(73, 1, 116, 14, 'deleted', '2023-11-27 07:04:23', '2023-11-27 07:04:23'),
(74, 1, 116, 46, 'added', '2023-11-27 07:20:32', '2023-11-27 07:20:32'),
(75, 1, 116, 47, 'added', '2023-11-27 07:38:09', '2023-11-27 07:38:09'),
(76, 1, 116, 48, 'added', '2023-11-27 08:48:33', '2023-11-27 08:48:33'),
(77, 1, 116, 48, 'deleted', '2023-11-27 08:49:40', '2023-11-27 08:49:40'),
(78, 1, 316, 49, 'added', '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(79, 1, 316, 50, 'added', '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(80, 1, 316, 51, 'added', '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(81, 1, 316, 52, 'added', '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(82, 1, 316, 53, 'added', '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(83, 1, 316, 54, 'added', '2024-03-11 04:50:28', '2024-03-11 04:50:28'),
(84, 1, 316, 55, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(85, 1, 316, 56, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(86, 1, 316, 57, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(87, 1, 316, 58, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(88, 1, 316, 59, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(89, 1, 316, 60, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(90, 1, 316, 61, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(91, 1, 316, 62, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(92, 1, 316, 63, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(93, 1, 316, 64, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(94, 1, 316, 65, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(95, 1, 316, 66, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(96, 1, 316, 67, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(97, 1, 316, 68, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(98, 1, 316, 69, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(99, 1, 316, 70, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(100, 1, 316, 71, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(101, 1, 316, 72, 'added', '2024-03-11 04:50:29', '2024-03-11 04:50:29'),
(102, 1, 402, 73, 'added', '2024-06-10 04:08:21', '2024-06-10 04:08:21'),
(103, 1, 402, 74, 'added', '2024-06-10 04:08:21', '2024-06-10 04:08:21'),
(104, 1, 402, 75, 'added', '2024-06-10 04:08:21', '2024-06-10 04:08:21'),
(105, 1, 402, 76, 'added', '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(106, 1, 402, 77, 'added', '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(107, 1, 402, 78, 'added', '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(108, 1, 402, 79, 'added', '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(109, 1, 402, 80, 'added', '2024-06-10 04:11:08', '2024-06-10 04:11:08'),
(110, 1, 402, 81, 'added', '2024-06-10 04:11:09', '2024-06-10 04:11:09'),
(111, 1, 402, 82, 'added', '2024-06-10 04:13:03', '2024-06-10 04:13:03'),
(112, 1, 402, 83, 'added', '2024-06-10 04:13:03', '2024-06-10 04:13:03'),
(113, 1, 402, 84, 'added', '2024-06-10 04:13:03', '2024-06-10 04:13:03'),
(114, 1, 402, 85, 'added', '2024-06-10 04:50:59', '2024-06-10 04:50:59');

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

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--

CREATE TABLE `priorities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  `title` varchar(255) DEFAULT NULL,
  `priority_number` bigint(20) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'success', 'renamed', 'file-manager', 'test', 'guest', 1, '2023-11-22 12:17:33', '2023-11-22 12:17:33');

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
(1, 'superadmin', 'Superadmin', 'Superadmin', '2023-04-14 17:21:45', '2023-04-14 17:21:45'),
(2, 'admin', 'Admin', 'Admin', '2023-04-14 17:21:45', '2023-04-14 17:21:45'),
(3, 'it', 'It', 'It', '2023-04-14 17:21:46', '2023-04-14 17:21:46'),
(4, 'hr', 'Hr', 'Hr', '2023-04-14 17:21:46', '2023-04-14 17:21:46'),
(5, 'user', 'User', 'User', '2023-04-14 17:21:46', '2023-04-14 17:21:46'),
(6, 'role_name', 'Role Name', 'Role Name', '2023-04-14 17:21:46', '2023-04-14 17:21:46');

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
(5, 2, 'App\\Models\\User'),
(5, 3, 'App\\Models\\User'),
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
(5, 15, 'App\\Models\\User'),
(5, 16, 'App\\Models\\User'),
(5, 17, 'App\\Models\\User'),
(5, 18, 'App\\Models\\User'),
(5, 19, 'App\\Models\\User'),
(5, 20, 'App\\Models\\User'),
(5, 21, 'App\\Models\\User'),
(5, 22, 'App\\Models\\User'),
(5, 23, 'App\\Models\\User'),
(5, 24, 'App\\Models\\User'),
(5, 25, 'App\\Models\\User'),
(5, 26, 'App\\Models\\User'),
(5, 27, 'App\\Models\\User'),
(5, 28, 'App\\Models\\User'),
(5, 29, 'App\\Models\\User'),
(5, 30, 'App\\Models\\User'),
(5, 31, 'App\\Models\\User'),
(5, 32, 'App\\Models\\User'),
(5, 33, 'App\\Models\\User'),
(5, 34, 'App\\Models\\User'),
(5, 35, 'App\\Models\\User'),
(5, 36, 'App\\Models\\User'),
(5, 37, 'App\\Models\\User'),
(5, 38, 'App\\Models\\User'),
(5, 39, 'App\\Models\\User'),
(5, 40, 'App\\Models\\User'),
(5, 41, 'App\\Models\\User'),
(5, 42, 'App\\Models\\User'),
(5, 43, 'App\\Models\\User'),
(5, 44, 'App\\Models\\User'),
(5, 45, 'App\\Models\\User'),
(5, 46, 'App\\Models\\User'),
(5, 47, 'App\\Models\\User'),
(5, 48, 'App\\Models\\User'),
(5, 49, 'App\\Models\\User'),
(5, 50, 'App\\Models\\User'),
(5, 51, 'App\\Models\\User'),
(5, 52, 'App\\Models\\User'),
(5, 53, 'App\\Models\\User'),
(5, 54, 'App\\Models\\User'),
(5, 55, 'App\\Models\\User'),
(5, 56, 'App\\Models\\User'),
(5, 57, 'App\\Models\\User'),
(5, 58, 'App\\Models\\User'),
(5, 59, 'App\\Models\\User'),
(5, 60, 'App\\Models\\User'),
(5, 61, 'App\\Models\\User'),
(5, 62, 'App\\Models\\User'),
(5, 63, 'App\\Models\\User'),
(5, 64, 'App\\Models\\User'),
(5, 65, 'App\\Models\\User'),
(5, 66, 'App\\Models\\User'),
(5, 67, 'App\\Models\\User'),
(5, 68, 'App\\Models\\User'),
(5, 69, 'App\\Models\\User'),
(5, 70, 'App\\Models\\User'),
(5, 71, 'App\\Models\\User'),
(5, 72, 'App\\Models\\User'),
(5, 73, 'App\\Models\\User'),
(5, 74, 'App\\Models\\User'),
(5, 75, 'App\\Models\\User'),
(5, 76, 'App\\Models\\User'),
(5, 77, 'App\\Models\\User'),
(5, 78, 'App\\Models\\User'),
(5, 79, 'App\\Models\\User'),
(5, 80, 'App\\Models\\User'),
(5, 81, 'App\\Models\\User'),
(5, 82, 'App\\Models\\User'),
(5, 83, 'App\\Models\\User'),
(5, 84, 'App\\Models\\User'),
(5, 85, 'App\\Models\\User'),
(5, 86, 'App\\Models\\User'),
(5, 87, 'App\\Models\\User'),
(5, 88, 'App\\Models\\User'),
(5, 89, 'App\\Models\\User'),
(5, 90, 'App\\Models\\User'),
(5, 91, 'App\\Models\\User'),
(5, 92, 'App\\Models\\User'),
(5, 93, 'App\\Models\\User'),
(5, 94, 'App\\Models\\User'),
(5, 95, 'App\\Models\\User'),
(5, 96, 'App\\Models\\User'),
(5, 97, 'App\\Models\\User'),
(5, 98, 'App\\Models\\User'),
(5, 99, 'App\\Models\\User'),
(5, 100, 'App\\Models\\User'),
(5, 101, 'App\\Models\\User'),
(5, 102, 'App\\Models\\User'),
(5, 103, 'App\\Models\\User'),
(5, 104, 'App\\Models\\User'),
(5, 105, 'App\\Models\\User'),
(5, 106, 'App\\Models\\User'),
(5, 107, 'App\\Models\\User'),
(5, 108, 'App\\Models\\User'),
(5, 109, 'App\\Models\\User'),
(5, 110, 'App\\Models\\User'),
(5, 111, 'App\\Models\\User'),
(5, 112, 'App\\Models\\User'),
(5, 113, 'App\\Models\\User'),
(5, 114, 'App\\Models\\User'),
(5, 115, 'App\\Models\\User'),
(5, 116, 'App\\Models\\User'),
(5, 117, 'App\\Models\\User'),
(5, 118, 'App\\Models\\User'),
(5, 119, 'App\\Models\\User'),
(5, 120, 'App\\Models\\User'),
(5, 121, 'App\\Models\\User'),
(5, 122, 'App\\Models\\User'),
(5, 123, 'App\\Models\\User'),
(5, 124, 'App\\Models\\User'),
(5, 125, 'App\\Models\\User'),
(5, 126, 'App\\Models\\User'),
(5, 127, 'App\\Models\\User'),
(5, 128, 'App\\Models\\User'),
(5, 129, 'App\\Models\\User'),
(5, 130, 'App\\Models\\User'),
(5, 131, 'App\\Models\\User'),
(5, 132, 'App\\Models\\User'),
(5, 133, 'App\\Models\\User'),
(5, 134, 'App\\Models\\User'),
(5, 135, 'App\\Models\\User'),
(5, 136, 'App\\Models\\User'),
(5, 137, 'App\\Models\\User'),
(5, 138, 'App\\Models\\User'),
(5, 139, 'App\\Models\\User'),
(5, 140, 'App\\Models\\User'),
(5, 141, 'App\\Models\\User'),
(5, 142, 'App\\Models\\User'),
(5, 143, 'App\\Models\\User'),
(5, 144, 'App\\Models\\User'),
(5, 145, 'App\\Models\\User'),
(5, 146, 'App\\Models\\User'),
(5, 147, 'App\\Models\\User'),
(5, 148, 'App\\Models\\User'),
(5, 149, 'App\\Models\\User'),
(5, 150, 'App\\Models\\User'),
(5, 151, 'App\\Models\\User'),
(5, 152, 'App\\Models\\User'),
(5, 153, 'App\\Models\\User'),
(5, 154, 'App\\Models\\User'),
(5, 155, 'App\\Models\\User'),
(5, 156, 'App\\Models\\User'),
(5, 157, 'App\\Models\\User'),
(5, 158, 'App\\Models\\User'),
(5, 159, 'App\\Models\\User'),
(5, 160, 'App\\Models\\User'),
(5, 161, 'App\\Models\\User'),
(5, 162, 'App\\Models\\User'),
(5, 163, 'App\\Models\\User'),
(5, 164, 'App\\Models\\User'),
(5, 165, 'App\\Models\\User'),
(5, 166, 'App\\Models\\User'),
(5, 167, 'App\\Models\\User'),
(5, 168, 'App\\Models\\User'),
(5, 169, 'App\\Models\\User'),
(5, 170, 'App\\Models\\User'),
(5, 171, 'App\\Models\\User'),
(5, 172, 'App\\Models\\User'),
(5, 173, 'App\\Models\\User'),
(5, 174, 'App\\Models\\User'),
(5, 175, 'App\\Models\\User'),
(5, 176, 'App\\Models\\User'),
(5, 177, 'App\\Models\\User'),
(5, 178, 'App\\Models\\User'),
(5, 179, 'App\\Models\\User'),
(5, 180, 'App\\Models\\User'),
(5, 181, 'App\\Models\\User'),
(5, 182, 'App\\Models\\User'),
(5, 183, 'App\\Models\\User'),
(5, 184, 'App\\Models\\User'),
(5, 185, 'App\\Models\\User'),
(5, 186, 'App\\Models\\User'),
(5, 187, 'App\\Models\\User'),
(5, 188, 'App\\Models\\User'),
(5, 189, 'App\\Models\\User'),
(5, 190, 'App\\Models\\User'),
(5, 191, 'App\\Models\\User'),
(5, 192, 'App\\Models\\User'),
(5, 193, 'App\\Models\\User'),
(3, 194, 'App\\Models\\User'),
(5, 195, 'App\\Models\\User'),
(5, 196, 'App\\Models\\User'),
(5, 197, 'App\\Models\\User'),
(5, 198, 'App\\Models\\User'),
(1, 199, 'App\\Models\\User'),
(3, 200, 'App\\Models\\User'),
(5, 201, 'App\\Models\\User'),
(5, 202, 'App\\Models\\User'),
(5, 203, 'App\\Models\\User'),
(5, 204, 'App\\Models\\User'),
(5, 205, 'App\\Models\\User'),
(5, 206, 'App\\Models\\User'),
(5, 207, 'App\\Models\\User'),
(5, 208, 'App\\Models\\User'),
(5, 209, 'App\\Models\\User'),
(5, 210, 'App\\Models\\User'),
(5, 211, 'App\\Models\\User'),
(5, 212, 'App\\Models\\User'),
(5, 213, 'App\\Models\\User'),
(5, 214, 'App\\Models\\User'),
(5, 215, 'App\\Models\\User'),
(5, 216, 'App\\Models\\User'),
(5, 217, 'App\\Models\\User'),
(5, 218, 'App\\Models\\User'),
(5, 219, 'App\\Models\\User'),
(5, 220, 'App\\Models\\User'),
(5, 221, 'App\\Models\\User'),
(5, 222, 'App\\Models\\User'),
(5, 223, 'App\\Models\\User'),
(5, 224, 'App\\Models\\User'),
(5, 225, 'App\\Models\\User'),
(5, 226, 'App\\Models\\User'),
(5, 227, 'App\\Models\\User'),
(5, 228, 'App\\Models\\User'),
(5, 229, 'App\\Models\\User'),
(5, 230, 'App\\Models\\User'),
(5, 231, 'App\\Models\\User'),
(5, 232, 'App\\Models\\User'),
(5, 233, 'App\\Models\\User'),
(5, 234, 'App\\Models\\User'),
(5, 235, 'App\\Models\\User'),
(5, 236, 'App\\Models\\User'),
(5, 237, 'App\\Models\\User'),
(5, 238, 'App\\Models\\User'),
(5, 239, 'App\\Models\\User'),
(5, 240, 'App\\Models\\User'),
(5, 241, 'App\\Models\\User'),
(5, 242, 'App\\Models\\User'),
(5, 243, 'App\\Models\\User'),
(5, 244, 'App\\Models\\User'),
(5, 245, 'App\\Models\\User'),
(5, 246, 'App\\Models\\User'),
(5, 247, 'App\\Models\\User'),
(5, 248, 'App\\Models\\User'),
(5, 249, 'App\\Models\\User'),
(5, 250, 'App\\Models\\User'),
(5, 251, 'App\\Models\\User'),
(5, 252, 'App\\Models\\User'),
(5, 253, 'App\\Models\\User'),
(5, 254, 'App\\Models\\User'),
(5, 255, 'App\\Models\\User'),
(5, 256, 'App\\Models\\User'),
(5, 257, 'App\\Models\\User'),
(5, 258, 'App\\Models\\User'),
(5, 259, 'App\\Models\\User'),
(5, 260, 'App\\Models\\User'),
(5, 261, 'App\\Models\\User'),
(5, 262, 'App\\Models\\User'),
(5, 263, 'App\\Models\\User'),
(5, 264, 'App\\Models\\User'),
(5, 265, 'App\\Models\\User'),
(5, 266, 'App\\Models\\User'),
(5, 267, 'App\\Models\\User'),
(5, 268, 'App\\Models\\User'),
(5, 269, 'App\\Models\\User'),
(5, 270, 'App\\Models\\User'),
(5, 271, 'App\\Models\\User'),
(5, 272, 'App\\Models\\User'),
(5, 273, 'App\\Models\\User'),
(5, 274, 'App\\Models\\User'),
(5, 275, 'App\\Models\\User'),
(5, 276, 'App\\Models\\User'),
(5, 277, 'App\\Models\\User'),
(5, 278, 'App\\Models\\User'),
(5, 279, 'App\\Models\\User'),
(5, 280, 'App\\Models\\User'),
(5, 281, 'App\\Models\\User'),
(5, 282, 'App\\Models\\User'),
(5, 283, 'App\\Models\\User'),
(5, 284, 'App\\Models\\User'),
(5, 285, 'App\\Models\\User'),
(5, 286, 'App\\Models\\User'),
(5, 287, 'App\\Models\\User'),
(5, 288, 'App\\Models\\User'),
(5, 289, 'App\\Models\\User'),
(5, 290, 'App\\Models\\User'),
(5, 291, 'App\\Models\\User'),
(5, 292, 'App\\Models\\User'),
(5, 293, 'App\\Models\\User'),
(5, 294, 'App\\Models\\User'),
(5, 295, 'App\\Models\\User'),
(5, 296, 'App\\Models\\User'),
(5, 297, 'App\\Models\\User'),
(5, 298, 'App\\Models\\User'),
(5, 299, 'App\\Models\\User'),
(5, 300, 'App\\Models\\User'),
(5, 301, 'App\\Models\\User'),
(5, 302, 'App\\Models\\User'),
(5, 303, 'App\\Models\\User'),
(5, 304, 'App\\Models\\User'),
(5, 305, 'App\\Models\\User'),
(5, 306, 'App\\Models\\User'),
(5, 307, 'App\\Models\\User'),
(5, 308, 'App\\Models\\User'),
(5, 309, 'App\\Models\\User'),
(5, 310, 'App\\Models\\User'),
(5, 311, 'App\\Models\\User'),
(5, 312, 'App\\Models\\User'),
(5, 313, 'App\\Models\\User'),
(5, 314, 'App\\Models\\User'),
(5, 315, 'App\\Models\\User'),
(5, 316, 'App\\Models\\User'),
(5, 317, 'App\\Models\\User'),
(5, 318, 'App\\Models\\User'),
(5, 319, 'App\\Models\\User'),
(5, 320, 'App\\Models\\User'),
(5, 321, 'App\\Models\\User'),
(5, 322, 'App\\Models\\User'),
(5, 323, 'App\\Models\\User'),
(5, 324, 'App\\Models\\User'),
(5, 325, 'App\\Models\\User'),
(5, 326, 'App\\Models\\User'),
(5, 327, 'App\\Models\\User'),
(5, 328, 'App\\Models\\User'),
(5, 329, 'App\\Models\\User'),
(5, 330, 'App\\Models\\User'),
(5, 331, 'App\\Models\\User'),
(5, 332, 'App\\Models\\User'),
(5, 333, 'App\\Models\\User'),
(5, 334, 'App\\Models\\User'),
(5, 335, 'App\\Models\\User'),
(5, 336, 'App\\Models\\User'),
(5, 337, 'App\\Models\\User'),
(5, 338, 'App\\Models\\User'),
(5, 339, 'App\\Models\\User'),
(5, 340, 'App\\Models\\User'),
(5, 341, 'App\\Models\\User'),
(5, 342, 'App\\Models\\User'),
(5, 343, 'App\\Models\\User'),
(5, 344, 'App\\Models\\User'),
(5, 345, 'App\\Models\\User'),
(5, 346, 'App\\Models\\User'),
(5, 347, 'App\\Models\\User'),
(5, 348, 'App\\Models\\User'),
(5, 349, 'App\\Models\\User'),
(5, 350, 'App\\Models\\User'),
(5, 351, 'App\\Models\\User'),
(5, 352, 'App\\Models\\User'),
(5, 353, 'App\\Models\\User'),
(5, 354, 'App\\Models\\User'),
(5, 355, 'App\\Models\\User'),
(5, 356, 'App\\Models\\User'),
(5, 357, 'App\\Models\\User'),
(5, 358, 'App\\Models\\User'),
(5, 359, 'App\\Models\\User'),
(5, 360, 'App\\Models\\User'),
(5, 361, 'App\\Models\\User'),
(5, 362, 'App\\Models\\User'),
(5, 363, 'App\\Models\\User'),
(5, 364, 'App\\Models\\User'),
(5, 365, 'App\\Models\\User'),
(5, 366, 'App\\Models\\User'),
(5, 367, 'App\\Models\\User'),
(5, 368, 'App\\Models\\User'),
(5, 369, 'App\\Models\\User'),
(5, 370, 'App\\Models\\User'),
(5, 371, 'App\\Models\\User'),
(5, 372, 'App\\Models\\User'),
(5, 373, 'App\\Models\\User'),
(5, 374, 'App\\Models\\User'),
(5, 375, 'App\\Models\\User'),
(5, 376, 'App\\Models\\User'),
(5, 377, 'App\\Models\\User'),
(5, 378, 'App\\Models\\User'),
(5, 379, 'App\\Models\\User'),
(5, 380, 'App\\Models\\User'),
(5, 381, 'App\\Models\\User'),
(5, 382, 'App\\Models\\User'),
(5, 383, 'App\\Models\\User'),
(5, 384, 'App\\Models\\User'),
(5, 385, 'App\\Models\\User'),
(5, 386, 'App\\Models\\User'),
(5, 387, 'App\\Models\\User'),
(5, 388, 'App\\Models\\User'),
(5, 389, 'App\\Models\\User'),
(5, 390, 'App\\Models\\User'),
(5, 391, 'App\\Models\\User'),
(5, 392, 'App\\Models\\User'),
(5, 393, 'App\\Models\\User'),
(1, 394, 'App\\Models\\User'),
(5, 395, 'App\\Models\\User'),
(5, 396, 'App\\Models\\User'),
(5, 397, 'App\\Models\\User'),
(1, 398, 'App\\Models\\User'),
(5, 399, 'App\\Models\\User'),
(5, 400, 'App\\Models\\User'),
(5, 401, 'App\\Models\\User'),
(2, 402, 'App\\Models\\User');

-- --------------------------------------------------------

--
-- Table structure for table `role_wise_default_permissions`
--

CREATE TABLE `role_wise_default_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active',
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_certificate_transections`
--

CREATE TABLE `salary_certificate_transections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_salary_certificate_data_id` bigint(20) UNSIGNED NOT NULL,
  `dated` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `challan_no` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'bank or cash',
  `bank_name` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_certificate_transections`
--

INSERT INTO `salary_certificate_transections` (`id`, `user_salary_certificate_data_id`, `dated`, `amount`, `challan_no`, `type`, `bank_name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-11-26', '500', 'oijlkk2513', 'cash', 'Bank aSIA', 116, NULL, '2023-11-26 11:16:40', '2023-11-26 11:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `employee_id_hidden` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive, 4=cool',
  `dept_id` bigint(20) DEFAULT NULL,
  `designation_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `joining_date` varchar(255) DEFAULT NULL,
  `birthdate` varchar(255) DEFAULT NULL,
  `profile_pic` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `blood_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone_2` varchar(255) DEFAULT NULL,
  `email_2` varchar(255) DEFAULT NULL,
  `father_name` text DEFAULT NULL,
  `mother_name` text DEFAULT NULL,
  `home_no` text DEFAULT NULL,
  `village` text DEFAULT NULL,
  `word_no` text DEFAULT NULL,
  `union` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `sub-district` text DEFAULT NULL,
  `district` text DEFAULT NULL,
  `division` text DEFAULT NULL,
  `capital` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `employee_id_hidden`, `name`, `phone`, `email`, `status`, `dept_id`, `designation_id`, `branch_id`, `joining_date`, `birthdate`, `profile_pic`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `blood_id`, `phone_2`, `email_2`, `father_name`, `mother_name`, `home_no`, `village`, `word_no`, `union`, `city`, `sub-district`, `district`, `division`, `capital`, `country`, `company_id`) VALUES
(1, '220821002', '21002', 'Abu Oubaida', '01877772225', 'oubaida.credence@gmail.com', 1, 22, 4, 2, '22-08-27', NULL, NULL, NULL, '$2y$10$bHHNcnHlOjfq.kNPNBfusOgu1TZ4vNrRMMkXKyVXzurO9c1DwQL1y', NULL, '2023-11-22 11:08:40', '2024-06-06 08:14:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(202, '130101001', '01001', 'Zillul Karim', '01234456789', NULL, 1, 1, 6, 2, '01-01-2013', NULL, NULL, NULL, '$2y$10$5ReYsENw3S.bk9oWKNPLGe30cvGCK5p1eOYRXC21/qd3FM4kjmSxe', NULL, '2023-12-04 07:34:54', '2023-12-04 07:34:54', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(203, '130101002', '01002', 'TamannaARahman', '01235456789', NULL, 1, 1, 7, 2, '01-01-2013', NULL, NULL, NULL, '$2y$10$H/S.rvkGxf6w2/iob4zRNuH0lpy7WBX0cgAqNtpLxkVy4NuTOSq8.', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(204, '130301003', '01003', 'Al Mamun Azad', '01819262644', NULL, 0, 1, 8, 2, '01-03-2013', NULL, NULL, NULL, '$2y$10$csJJsXKhBWrnKigepWPLKOBV.l2lAOWsgnTV4nEiIZYK7/pYwo88i', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(205, '200201004', '01004', 'Engr. Belayet Hossain', '01843482188', NULL, 1, 1, 7, 2, '02-02-2020', NULL, NULL, NULL, '$2y$10$/nedF36PjnWs7IE3QKmuqORyM/RgNvJaUJ8I1nPRjryRRpmRnYKJS', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(206, '211001005', '01005', 'S.A Asgar Mohiuddin', '01970341003', NULL, 1, 1, 7, 2, '01-10-2021', NULL, NULL, NULL, '$2y$10$q5tsGdCTnAYImX14m4CSN.9enE7eicyLlkQtMmhE4ujJH.FCcbtTa', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(207, '140202001', '02001', 'Rajib Munshi', '01955065148', NULL, 1, 2, 51, 2, '05-02-2014', NULL, NULL, NULL, '$2y$10$Q35QFyQIUPDIDCUUM.B.eu0Px0NNQNF3ytmbnInTW8oSb1c4YhPce', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(208, '150102002', '02002', 'Helal Uddin (Driver)', '01965272373', NULL, 1, 2, 54, 2, '01-01-2015', NULL, NULL, NULL, '$2y$10$76iMRm4lgp9QyGg9djS8he/iv0c/JTdjK0WkQGPOII7/ndXPl14.K', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(209, '160302003', '02003', 'Shariful Alom', '01724418443', NULL, 1, 2, 42, 5, '01-03-2016', NULL, NULL, NULL, '$2y$10$6g.fXuy8Q5DOHz7SU47i1OQNhw1H0ebQQnV0vvFcEowGu/jgacq6O', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(210, '160502004', '02004', 'Omor Ali', '01959702692', NULL, 1, 2, 58, 2, '11-05-2016', NULL, NULL, NULL, '$2y$10$F2WY17jFN/arlaFESO6/k.kfEPAWjj5AnMLet/.1yYxiX/qS6S9Xu', NULL, '2023-12-04 07:34:55', '2023-12-04 07:34:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(211, '160802005', '02005', 'Sanug Pure Marma', '01862267439', NULL, 1, 2, 58, 2, '17-08-2016', NULL, NULL, NULL, '$2y$10$qb9HnDXR.o3HmrPY/RGTOOzs/3EmKalsiCS826E5anWxnb.TUy9X6', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(212, '161102006', '02006', 'Repon Gazi', '01749996660', NULL, 1, 2, 21, 2, '27-11-2016', NULL, NULL, NULL, '$2y$10$Gy5e89u7jpSxEP7NZpK3VeASl.Cp5/COTyrYl1.MJgbUbdR0GIDs6', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(213, '170902007', '02007', 'Md. Abdul Aziz', '01923012427', NULL, 1, 2, 42, 2, '23-09-2017', NULL, NULL, NULL, '$2y$10$09GzlrvPVUBer695NZe0uebQYr/8/StHGb4m1jf8wOTg0EyNjjuam', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(214, '171102008', '02008', 'Md Ayub Ali', '01723733520', NULL, 1, 2, 54, 2, '04-11-2017', NULL, NULL, NULL, '$2y$10$Qj5GEwg6Xe6G7nBgkAU1MexONgsvTU1LHkq5SuAuGpcyiHIuvqCvG', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(215, '181002009', '02009', 'Md. Mokshed Sarker', '01731163442', NULL, 1, 2, 51, 2, '15-10-2018', NULL, NULL, NULL, '$2y$10$g6UWUamAGQwMUkSh1t2yhut4dsdDfZUyffHM2nqm4nF6zUL2eO.Gi', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(216, '181102010', '02010', 'Shakil Ahmed Khan', '01724825474', NULL, 1, 2, 16, 2, '10-11-2018', NULL, NULL, NULL, '$2y$10$5etgECPXyfB7w05ScwUS9urmgxF0wT1Owy9jFN61YokyhTR9mJQMK', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(217, '190202011', '02011', 'Mukul Hossain', '01782578412', NULL, 1, 2, 54, 2, '13-02-2019', NULL, NULL, NULL, '$2y$10$JmgRTIgFCXIFqxv2uDGN0OsadmRH9NUYZSMyxTUtZpnkxqTiwYi.u', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(218, '190902012', '02012', 'Al Amin', '01790283623', NULL, 1, 2, 44, 2, '01-09-2019', NULL, NULL, NULL, '$2y$10$WEw8WAt5qi4oeINrrcB88eJJ.rY89vD5LsEcXwm0Vugd1qaKSoNFm', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(219, '200102013', '02013', 'Ripon Mondol', '01983668969', NULL, 1, 2, 43, 2, '01-01-2020', NULL, NULL, NULL, '$2y$10$CJoGkQvBDF.x3Hf8a0noVezdkSCkmzQAaDzFj0duhkiW.4HiEmvHy', NULL, '2023-12-04 07:34:56', '2023-12-04 07:34:56', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(220, '200202014', '02014', 'Md. Faruk Hosen (OA)', '01744216789', NULL, 1, 2, 51, 2, '04-02-2020', NULL, NULL, NULL, '$2y$10$IyCmVATx5ZOVA5usz1Y9DectoBSOMB.njCCqf/bJrpnUQHHh1nO0.', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(221, '200802015', '02015', 'Md. Abul Hossain', '01920838421', NULL, 1, 2, 54, 2, '10-08-2020', NULL, NULL, NULL, '$2y$10$mi.LCFVEqN7nPUDLW1SUD.fXC2maPs/89Tm/iPPJNNuDIQEEUx86O', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(222, '201102016', '02016', 'Md. Al Amin Hosain Motiur', '01775320361', NULL, 1, 2, 51, 2, '21-11-2020', NULL, NULL, NULL, '$2y$10$VTTJC8D4Hhb.Ath6aUlLcOS/ZITzHAmTGt1CW37k5Bi8neh0sl.U2', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(223, '201202017', '02017', 'Mir Monwar Hossain', '01712739505', NULL, 1, 2, 16, 3, '14-12-2020', NULL, NULL, NULL, '$2y$10$tKEnZGYhnuY2w36mpsf7GevvAIvbyNzxVBY/nsCSbnr7ZMGSbAbSe', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(224, '210102018', '02018', 'Md. Ekramul Haque Sazu', '01717528789', NULL, 1, 2, 52, 2, '10-01-2021', NULL, NULL, NULL, '$2y$10$gHcqW2e03hFvxXeqXDAmP.Pp9b935i3mBs32m5NLdUukdoVhOXo42', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(225, '210102019', '02019', 'Md. Salim Hossen', '01736930841', NULL, 1, 2, 52, 3, '19-01-2021', NULL, NULL, NULL, '$2y$10$.12k4wuGD4bg/xczhiHqEe8.ZeYE/pcl5R0pUjQRw/5decWTpDeEe', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(226, '210202020', '02020', 'Md. Faruk', '01910910532', NULL, 1, 2, 57, 2, '03-02-2021', NULL, NULL, NULL, '$2y$10$Vf0ZggpMTb14hdgcBolbW.LDvIsXYcgufWlUD8il1nJlWJo0L25sO', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(227, '210302021', '02021', 'Md. Raihan Kabir (Office Assistant)', '01568320879', NULL, 1, 2, 51, 2, '05-03-2021', NULL, NULL, NULL, '$2y$10$N0Mtsl3ioBfdNXU77/Mft.wPJy.nP0IJqhqO5S2y5zFvk/wwK92iy', NULL, '2023-12-04 07:34:57', '2023-12-04 07:34:57', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(228, '210802022', '02022', 'Md. Sumon Mollik', '01757088841', NULL, 1, 2, 54, 2, '25-08-2021', NULL, NULL, NULL, '$2y$10$aWCqAb5SFDWmXi1.6ES/Me.JWqg4rxiMgTM3lODcJGXhe4m.7pJXu', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(229, '210902023', '02023', 'Md.Abu Bakkar', '01992299667', NULL, 1, 2, 52, 3, '26-09-2021', NULL, NULL, NULL, '$2y$10$d4B00YEvieSx0c23Dsr9VOyuoXdVz4yPaUpPVBpLEDOzEzLXY03eK', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(230, '211202024', '02024', 'Md.Zishan Khan', '01611886737', NULL, 1, 2, 54, 2, '01-12-2021', NULL, NULL, NULL, '$2y$10$JXP0vHkiiPgFHmPbS8Oev.QXRUPfXOg4bxtTHgwN/e/Q51QFuMZAy', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(231, '220102025', '02025', 'Md. Oli Ullah', '01738356175', NULL, 1, 2, 54, 2, '10-01-2022', NULL, NULL, NULL, '$2y$10$mEUsguNyBd7vx6Hyoed6qOh0l3o4gFcxoQGEaZ48iJUt.fBshlOaK', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(232, '220102026', '02026', 'Md. Hasan', '01620116822', NULL, 1, 2, 54, 2, '26-01-2022', NULL, NULL, NULL, '$2y$10$PG1ES9iKU.0MlchgDeHJ.u7lkg38/gUEerqDkHoFdeWlEPWGaqCHm', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(233, '220202027', '02027', 'Md. Rafiqul Islam (Driver)', '01749310317', NULL, 1, 2, 54, 2, '23-02-2022', NULL, NULL, NULL, '$2y$10$GN9klPGk5dQTCbuTUF0aaO1v5Zi9Vj4nFHuFMLHCFQvnznVKzcvV6', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(234, '220602028', '02028', 'Surashi Chakma', '01829277453', NULL, 1, 2, 3, 2, '08-06-2022', NULL, NULL, NULL, '$2y$10$DDQLZtb1YCTLZFNn3IS/iuAeV4Wmyl1n2uM2Oz07zFyKLiUMmzL.S', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(235, '220602029', '02029', 'Santunu Sarker', '01730696686', NULL, 1, 2, 53, 2, '27-06-2022', NULL, NULL, NULL, '$2y$10$G4uLDSUOlzK.7RFoqvmtFeF/mYOMyjGnveuAoV4ZkJerxW8L2fAi2', NULL, '2023-12-04 07:34:58', '2023-12-04 07:34:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(236, '220702030', '02030', 'Atik Hasnat', '01732482880', NULL, 1, 2, 3, 2, '26-07-2022', NULL, NULL, NULL, '$2y$10$7zMTTBm67C9NiHlZJAOhLOcjUh2Gcs5iojEYysC2gQ93v.GgGWq1i', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(237, '220702031', '02031', 'Ibrahim Hossain (OA)', '01793041387', NULL, 1, 2, 45, 2, '30-07-2022', NULL, NULL, NULL, '$2y$10$JHyqLoqdse27kLKCUJw0ouVE4M0/WQgU9MHiyzTpX8zOSnAYDzhTy', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(238, '220702032', '02032', 'Rubel Kazi (OA)', '01911292862', NULL, 1, 2, 51, 2, '31-07-2022', NULL, NULL, NULL, '$2y$10$wip4VUnKEXj2jMXMwFRXX.4BgMWBd4geVkxhHIsx30GgIR2IKPErm', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(239, '220702033', '02033', 'Rasel Mia', '01739658837', NULL, 1, 2, 51, 2, '31-07-2022', NULL, NULL, NULL, '$2y$10$gOMTtg7yhS/cVOMcni6BrO9nPKIcXFuPtYEVBrD.NCZyZFF9He1yK', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(240, '220802034', '02034', 'Amzad Hossen', '01746301872', NULL, 1, 2, 54, 2, '08-08-2022', NULL, NULL, NULL, '$2y$10$FzwGP0GKi0/mxQJ1tdndVespjz8D6ks0Pp7CkmLLtElB7kX75Gnm6', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(241, '220902035', '02035', 'Md. Sojib (Driver)', '01758432908', NULL, 1, 2, 54, 2, '13-09-2022', NULL, NULL, NULL, '$2y$10$8jORiG3U/aMfdykrIPd4iuZ7zVifBq01Z1J/OkfVRGedjfCkikD5i', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(242, '221002036', '02036', 'Md. Abdus Samad', '01716114478', NULL, 1, 2, 19, 2, '03-10-2022', NULL, NULL, NULL, '$2y$10$.1YAICZD1XNLBEcqPuufaeBW2ONO4E7uikcU3YRdORqH1X9K0gVe.', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(243, '221002037', '02037', 'Bazlur  Rahman', '01754704266', NULL, 1, 2, 22, 5, '10-10-2022', NULL, NULL, NULL, '$2y$10$yJMSrSBi5EQh8DZS0Sq.eO0B0cEq3031DLSwGxeIV5Mhh8/QzqyDu', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(244, '221002038', '02038', 'Sohan Sheikh', '01743810707', NULL, 1, 2, 51, 2, '15-10-2022', NULL, NULL, NULL, '$2y$10$wNOxSU6Jutg7aW10x1Knh.vHFWXqVOBFOjjQSPOEoCgmOV7vaEVXy', NULL, '2023-12-04 07:34:59', '2023-12-04 07:34:59', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(245, '221102039', '02039', 'Md. Shawan Babu', '01892905663', NULL, 1, 2, 51, 2, '09-11-2022', NULL, NULL, NULL, '$2y$10$qhyP3Fb6OuLyK.VO6Fzf3eUkMan6vjJBnjjBkB3cCvVzP0eCSYDR6', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(246, '221102040', '02040', 'Sheikh Raihan Hashem', '01817158227', NULL, 1, 2, 3, 2, '12-11-2022', NULL, NULL, NULL, '$2y$10$taspfjqj1icI/gMRZhb1MedY5CzqV05UUP0SZiHbpUptsOHfE1eeu', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(247, '221102041', '02041', 'Sergeant ( Rhtd.) Abu Bakar Siddique', '01710063090', NULL, 1, 2, 22, 5, '12-11-2022', NULL, NULL, NULL, '$2y$10$/2I0tRsjF7QLPx59c4UtBeReHXp3EIkHm6a46xmmB55fy5npGqSKG', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(248, '221102042', '02042', 'Mohammad Nasir Uddin', '01729725720', NULL, 1, 2, 22, 5, '26-11-2022', NULL, NULL, NULL, '$2y$10$nG696/AmkFOqzw75gDj06ONeh.KxJlRuHEt4of9vEt4hsORRu8eQO', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(249, '221102043', '02043', 'Mofigul Islam', '01714647151', NULL, 1, 2, 22, 5, '28-11-2022', NULL, NULL, NULL, '$2y$10$QTolTsTvLj3b5Sq6SA7FLOZ8MLnXHHUqtscbfI5CZlpbRt4tdNecq', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(250, '221202044', '02044', 'Md.Al Amin(OA)', '013126450093', NULL, 1, 2, 51, 2, '20-12-2022', NULL, NULL, NULL, '$2y$10$hyj/U9nlomo5OL/a6Cit7uugJSDgq8qJkBLsSVTntnb6ZeUQonc6u', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(251, '221202045', '02045', 'Rudba Hossain', '01967963768', NULL, 1, 2, 3, 2, '21-12-2022', NULL, NULL, NULL, '$2y$10$puBKITxDSaD5udt6tfdka.pokvq5dEjEqgYeRuXHhwX8NfTQiZ2my', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(252, '221202046', '02046', 'Md.Kayum', '01797043800', NULL, 1, 2, 51, 2, '26-12-2022', NULL, NULL, NULL, '$2y$10$7IZel7DSxbbOwK7xOXoZFuucPP4TAMVVlbqw6662RsITOiPXNKMtK', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(253, '230102047', '02047', 'Ijhar Ali', '01728844885', NULL, 1, 2, 54, 2, '07-01-2023', NULL, NULL, NULL, '$2y$10$MX9S9qR9eGWs5MtH.Lf1oObk3xxPaaJmz.eJY8ZUwTiCi9cS4iZ22', NULL, '2023-12-04 07:35:00', '2023-12-04 07:35:00', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(254, '230302048', '02048', 'Rony', '01714619425', NULL, 1, 2, 51, 2, '08-03-2023', NULL, NULL, NULL, '$2y$10$8NL4pJ9LZYzo34OlwuIch.CPSiZGj3lnuZSLKXapx1fu.NSJX9uNq', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(255, '230302049', '02049', 'Jobaydul Islam', '01740459627', NULL, 1, 2, 51, 2, '30-03-2023', NULL, NULL, NULL, '$2y$10$lNStuh8ZA596ce5dtn0PseA6zwdhfuo1mYO4ev2zzXpXSaVd7pSKu', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(256, '230302050', '02050', 'Sobuj Munshi', '01906825297', NULL, 1, 2, 51, 2, '30-03-2023', NULL, NULL, NULL, '$2y$10$ziwY1UPVLSJZpH4Sbt14qu5Ui2Ak8DPBdAjqVjx0qBh6FGag3ockK', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(257, '230402051', '02051', 'Harun or Rashid', '01724919777', NULL, 1, 2, 22, 5, '01-04-2023', NULL, NULL, NULL, '$2y$10$jXh1qbMOQMS.4du0oco7YuPthIiKVRdLK6dvWTdz30tIbUlNMObQe', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(258, '230402052', '02052', 'Ayub Ali', '01725271613', NULL, 1, 2, 22, 5, '01-04-2023', NULL, NULL, NULL, '$2y$10$OZRSVSueE8QFdTwcTxNQb.ucxHHVYLgry61Vfb7a00iSKXEK9bMs6', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(259, '230502053', '02053', 'Murad Hossain', '01761654685', NULL, 1, 2, 51, 2, '02-05-2023', NULL, NULL, NULL, '$2y$10$AUezxOkFk0gRxk67ibv9ZOk21KRvkPySgn9sNg9J5mav8zj.cmWNy', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(260, '230502054', '02054', 'Shiblu Islam', '01886993907', NULL, 1, 2, 58, 2, '03-05-2023', NULL, NULL, NULL, '$2y$10$3HlzPfBPPXznlZnnT7APZeEpSaRJYcCKht3tNPSVLEZBVUv9KYquS', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(261, '230502055', '02055', 'A N M Asaduzzaman', '01730341001', NULL, 1, 2, 9, 3, '27-05-2023', NULL, NULL, NULL, '$2y$10$iY4T66JL3nZD4zDSbsxM8O0MadE0zY8pp0biQYMLnsbyBIo7tq/.a', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(262, '230702056', '02056', 'Md. Sarwar Hossen', '01952979427', NULL, 1, 2, 54, 2, '08-07-2023', NULL, NULL, NULL, '$2y$10$RuVCe2jewVQ5ntd1fOVz0uedEIAeEzl98hVFzFCu10DS7uoEYBHhe', NULL, '2023-12-04 07:35:01', '2023-12-04 07:35:01', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(263, '230702057', '02057', 'Johirul Islam', '01643255227', NULL, 1, 2, 54, 2, '15-07-2023', NULL, NULL, NULL, '$2y$10$pbDb6AX9iYgzYnNRwDZCmuudSeT5GSn9mgjZbVoyTmRA2yMYRyHxy', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(264, '230802058', '02058', 'Taslima Begum', '01776156264', NULL, 1, 2, 56, 2, '10-08-2023', NULL, NULL, NULL, '$2y$10$oov5.qQWYVqci5J7QxXrAuS1Ol.bYRGQ1lj5nAO2tgWTAWYCwB81O', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(265, '230902059', '02059', 'Md. Shipon Mondol', '01996258640', NULL, 1, 2, 55, 2, '16-09-2023', NULL, NULL, NULL, '$2y$10$Mc7Jtpr6hb8UMlJEdvHMaO.jN.gbcasLPzKPGFfUn5lfGNz0bvO.i', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(266, '230902060', '02060', 'Md. Manik Mia', '01834166663', NULL, 1, 2, 54, 2, '30-09-2023', NULL, NULL, NULL, '$2y$10$CUnfCr1lRg.J//FSM7sXz./hkxDWYOpb48/qouYGiv.m2BMOK/yIO', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(267, '231002061', '02061', 'Akmam Kabir', '01853743499', NULL, 1, 2, 4, 2, '01-10-2023', NULL, NULL, NULL, '$2y$10$9Agn8nnCuB8xnEnMpEwDxuLUtzkhuvxYDqfedlxtj5GoAYDdI/h8S', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(268, '231002062', '02062', 'Md. Abul Bashar', '01725098396', NULL, 1, 2, 22, 5, '01-10-2023', NULL, NULL, NULL, '$2y$10$r6wf/7eSmH3lHMAz8nyt4OdeK.cmm/P0QLLOt8kx340ObKgmoAG4K', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(269, '231002063', '02063', 'Md. Rukon Shek', '01796895526', NULL, 1, 2, 51, 2, '01-10-2023', NULL, NULL, NULL, '$2y$10$6xrLysF7BoSVo3.nKwZ7Q.f0AA2O/iPs.zDllfZv9YBSGDiJ9KLgC', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(270, '231002064', '02064', 'Rasel Haider', '01930065118', NULL, 1, 2, 22, 5, '10-10-2023', NULL, NULL, NULL, '$2y$10$MVMOgMIn1JKA5CcRtnxiCOZ14MG3kC2cxrxJvP6xGf882doh1wbaq', NULL, '2023-12-04 07:35:02', '2023-12-04 07:35:02', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(271, '231002065', '02065', 'Md. Hasanur Rahman', '01721734448', NULL, 1, 2, 22, 5, '11-10-2023', NULL, NULL, NULL, '$2y$10$1vyMK6FBIrUQt2hdEgw9c.IUu7iRvoADz6QTAebrQkjwJvohqswNy', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(272, '130603001', '03001', 'M Nazim Uddin', '01716917704', NULL, 1, 3, 11, 2, '15-06-2013', NULL, NULL, NULL, '$2y$10$Wqs.i22nb70T0oiWMtX.Oe.cixY9ecHgUVlmERWx5Ok8FR5oBU3s2', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(273, '130603002', '03002', 'Shakidul Islam', '01784885511', NULL, 1, 3, 18, 2, '15-06-2013', NULL, NULL, NULL, '$2y$10$I7KqDZXD5jbh5FEdx4St5uTnR4..M8lWwPmmqBJyXq5F1H/eRDhJ.', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(274, '140603003', '03003', 'Anwar Hossain', '01745613193', NULL, 1, 3, 18, 2, '03-06-2014', NULL, NULL, NULL, '$2y$10$6XBht6KILRA/PHdEx2m3BeZNEBbpZvrAVia2IUYWDH70PsP9PG8o6', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(275, '160803004', '03004', 'Sakhawat Hossain Zubair', '01784885522', NULL, 1, 3, 10, 2, '06-08-2016', NULL, NULL, NULL, '$2y$10$pnc/XMyFkGe1dNtDBzbzquN5y2qHKJPJ.wdkInNZ.OnsjV5DJpFjK', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(276, '180303005', '03005', 'Foysal Dewan', '01675685878', NULL, 1, 3, 18, 2, '04-03-2018', NULL, NULL, NULL, '$2y$10$3k8DdKjxFb8Y.JrHeE5n/.Kpu.L.CklolwvxTRI4UoADZXZ8PFYJG', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(277, '190203006', '03006', 'Jahirul Islam', '01761467248', NULL, 1, 3, 4, 2, '12-02-2019', NULL, NULL, NULL, '$2y$10$H9Vv1FBF9vPJvoAvOStoCuhkQWIlmAWR4XFr89zxKRn6s1L6yAvYS', NULL, '2023-12-04 07:35:03', '2023-12-04 07:35:03', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(278, '220903007', '03007', 'Muhamad Abdul Momen', '01610044267', NULL, 1, 3, 17, 2, '01-09-2022', NULL, NULL, NULL, '$2y$10$KM8mccgS45h9Qyq2ZFIapO7M1MDVWJUiXvCWbC32RGJR0q0mO6xui', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(279, '231003008', '03008', 'Md. Saief Ahmed Naktro', '01974734564', NULL, 1, 3, 4, 2, '01-10-2023', NULL, NULL, NULL, '$2y$10$VrcFvvBqEkGsu0IUoOhu8eHiewQPKECShkrt3sZBc3ONMzXI/Loae', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(280, '160804001', '04001', 'Mirza Romel Faruki', '01734717095', NULL, 1, 4, 15, 4, '13-08-2016', NULL, NULL, NULL, '$2y$10$6AJx8nz9.V9SBOec8FBfS.q3q1DIa.5D.8QAzia85qfP7vBM3ewGi', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(281, '161004002', '04002', 'Ripon Kumer Datta', '01797300800', NULL, 1, 4, 17, 4, '18-10-2016', NULL, NULL, NULL, '$2y$10$8MJhPt2pkcA4/uHvyso4iuppOSV4cMLecs8A3XMhnYQhAq1e6t9fq', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(282, '161004003', '04003', 'Md. Rezaul Karim (P&I)', '01781776633', NULL, 1, 4, 15, 4, '24-10-2016', NULL, NULL, NULL, '$2y$10$ic97HXndFtuExk6TRoTk.Okw0T4yyVGoP8NUQ0cXpkVUlbjv3CPoK', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(283, '170304004', '04004', 'Mahamudul Hasan', '01717077000', NULL, 1, 4, 18, 4, '01-03-2017', NULL, NULL, NULL, '$2y$10$FEVQx7Pt2RpKARJIhDSOtOi0aVy2vj2hsvXiZGG5g3lQjf2epEiwm', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(284, '170304005', '04005', 'Nazmul Huda Nahid', '01913341466', NULL, 1, 4, 18, 4, '01-03-2017', NULL, NULL, NULL, '$2y$10$U7F1xyu5vbjegeJlDujrVOkn2r/0yvHBgFRxNi3UZvXvMagLXq8q.', NULL, '2023-12-04 07:35:04', '2023-12-04 07:35:04', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(285, '170904006', '04006', 'Md. Sazzad Hossain', '01672551637', NULL, 1, 4, 18, 4, '16-09-2017', NULL, NULL, NULL, '$2y$10$kG5mGM2r/m2uobaLq88nw.zcD3qWLxKLALXDDZfYWRjD8VvzrJ2IS', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(286, '180304007', '04007', 'Md. Saifullah Rochee', '01701019377', NULL, 1, 4, 2, 4, '01-03-2018', NULL, NULL, NULL, '$2y$10$EPhoGTXMhM1615FUA6SAOeblPg67BKT1rfGoiieZv6Y0rMDrSb22.', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(287, '190204008', '04008', 'Md. Ahbabur Rahman Khan', '01712634283', NULL, 1, 4, 18, 4, '11-02-2019', NULL, NULL, NULL, '$2y$10$VAxLZ0Dsl6tVMPpcePaZP.yU2xU4t.s6Hj3OlqH8qajeIsZVESa4m', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(288, '200904009', '04009', 'Md. Saymon Chowdhury', '01671790678', NULL, 1, 4, 18, 4, '19-09-2020', NULL, NULL, NULL, '$2y$10$JklSNAszafsWPeAJKlMA5.2jkUhUkkOdK9jd5cunCQxNoVyl/pHjO', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(289, '201004010', '04010', 'Md. Jafor Iqbal (P&I)', '01787147157', NULL, 1, 4, 18, 4, '01-10-2020', NULL, NULL, NULL, '$2y$10$Zudf/LJuAUc3vYQBA05X8ebw4rtVTaixm8UlJWO6oWoE4A9DC97rG', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(290, '201004011', '04011', 'Md. Rezaul Hasan', '01798714155', NULL, 1, 4, 18, 4, '05-10-2020', NULL, NULL, NULL, '$2y$10$nC800DSvvlu6yAZmFkIW0OVL6ESemdg6G6qRm0JBlAjHfvske4tzW', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(291, '201004012', '04012', 'Mr. Abu Raihan Al Mustafiz (P&I)', '01797120380', NULL, 1, 4, 18, 4, '06-10-2020', NULL, NULL, NULL, '$2y$10$3Pge/cdyE8JxJ7GoG.ah/OWUOs1.1MhJzU23hT5uyUifWwPcAWRwK', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(292, '201104013', '04013', 'Md. Tarikul Islam Tarek (P&I)', '01738578443', NULL, 1, 4, 18, 4, '01-11-2020', NULL, NULL, NULL, '$2y$10$IMQV3rDXVr2GqDH/Ga0LsesGU2quqj2JSxicB4PFWmTsBhXf3xa.W', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(293, '220104014', '04014', 'Abu Sadik Md. Nafi', '01859290048', NULL, 1, 4, 3, 4, '19-01-2022', NULL, NULL, NULL, '$2y$10$mkGxgZpjZ27MbI8ZAIJqEOl590e1FttrFipbT6CQmyjZC2i0LxYMi', NULL, '2023-12-04 07:35:05', '2023-12-04 07:35:05', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(294, '220604015', '04015', 'Md. Sabbir Ahmed', '01950145772', NULL, 1, 4, 18, 4, '15-06-2022', NULL, NULL, NULL, '$2y$10$J9NTLAHUuamZYTk.7im8hu2QHswhVPkDWquBPvitZePBW.ihSFkBG', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(295, '230204016', '04016', 'Riaz Sarker', '01914759595', NULL, 1, 4, 2, 4, '01-02-2023', NULL, NULL, NULL, '$2y$10$KwA3L64txd7MDQVCR7tDSOI93Uk4JpK6aEWpnuy5xSPq9b9HJRIwi', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(296, '230204017', '04017', 'Khondokar Asaduzzaman Parves', '01728974647', NULL, 1, 4, 2, 4, '15-02-2023', NULL, NULL, NULL, '$2y$10$M91zSCAW0WPRwAssT2tlyOdCXhTPiqySWVY//NHEwxNOJ2F5TB5Dy', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(297, '230304018', '04018', 'Mohammad Shakil Hossain', '01642252287', NULL, 1, 4, 4, 4, '15-03-2023', NULL, NULL, NULL, '$2y$10$bvALAhktiVU1hlFR3PgXuOLZfamZkiDjnKW.P3BoKsQ/tDTaSQQIC', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(298, '230304019', '04019', 'Rakibul Hasan', '01767364858', NULL, 1, 4, 4, 4, '15-03-2023', NULL, NULL, NULL, '$2y$10$J5Ewo2lhRX6RSa0feQqgY.nhimDWuNBK396QCNTa7abnQ7kdKCvjy', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(299, '230504020', '04020', 'Ridoy Kumar Roy', '01723917762', NULL, 1, 4, 3, 4, '10-05-2023', NULL, NULL, NULL, '$2y$10$v4vt5MIzvcwkGaf8gF48AuCiRQ3UFcF/Vg53mr.tParjCTb1tD6ea', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(300, '230504021', '04021', 'Mamun Bhuiyan', '01911022527', NULL, 1, 4, 3, 4, '10-05-2023', NULL, NULL, NULL, '$2y$10$hS8kptK8tZ5N0Dul8M3VFuIw.QDzJWF6KI3M5EAIPmK64tdaGnXZy', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(301, '230504022', '04022', 'Dulal Hosen', '01926474585', NULL, 1, 4, 2, 4, '10-05-2023', NULL, NULL, NULL, '$2y$10$W5yiNHJimynZwSOs5nNHpequ9PZsR2uBJ1pcDKA5xpW6OvWEmSowS', NULL, '2023-12-04 07:35:06', '2023-12-04 07:35:06', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(302, '230504023', '04023', 'Rubel Rana', '01307171100', NULL, 1, 4, 2, 4, '10-05-2023', NULL, NULL, NULL, '$2y$10$nWIER1bKokrYJ.TggnOlqehF4rsUY/aEv/MJd9kpsFioCHsJroZ9.', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(303, '230504024', '04024', 'Nazmus Islam', '0182835031', NULL, 1, 4, 2, 4, '15-05-2023', NULL, NULL, NULL, '$2y$10$BG45x4qW3jpreiKVdnjI8.5rTnIQ4wX3GWnGl58Qe9Qdl2VoreIqC', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(304, '230504025', '04025', 'Mosiur Rahman Siam', '01768735979', NULL, 1, 4, 4, 4, '27-05-2023', NULL, NULL, NULL, '$2y$10$LQXyPLotmA1th0H9WjZA..lujCgginUrIIGGO8LFaWE6O2D/yVaTu', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(305, '230604026', '04026', 'Shakhawait Hossain Shojol', '01684002466', NULL, 1, 4, 2, 4, '14-06-2023', NULL, NULL, NULL, '$2y$10$GMyKmhWHG4m/7Flqxd4a5uEtJ9KG.wooVUS6VGsHhHQr34e33o5Wy', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(306, '170806001', '06001', 'Md. Shofiqul Islam (CAD)', '01718306014', NULL, 1, 7, 23, 3, '01-08-2017', NULL, NULL, NULL, '$2y$10$YOM9Y1kwJtzKfiZuzziWg.wgNRWk1iYqVLfTEIJpXLQYZvYINemTy', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(307, '210806002', '06002', 'Monna Debnath', '01965934984', NULL, 1, 7, 26, 2, '01-08-2021', NULL, NULL, NULL, '$2y$10$04RRBbclLSS6l8.0ZwLIVeedhTm.kmoWoejlh9rWkWsR1U9D9nUoi', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(308, '220206003', '06003', 'S.M. Abdullah Kashemi', '01872606123', NULL, 1, 7, 25, 2, '17-02-2022', NULL, NULL, NULL, '$2y$10$8EP12p/eht11TaCKNDQGTuuqhpHPBzb3SqLATXXPH5b8lCPu8yo7m', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(309, '220506004', '06004', 'Md. Abu Iqbal Khan', '01712671980', NULL, 1, 7, 12, 3, '16-05-2022', NULL, NULL, NULL, '$2y$10$knoZZQURjgJfYOO7EHc02OQFB2PDIr9upAV/LoLTrCmHkPncVqfkK', NULL, '2023-12-04 07:35:07', '2023-12-04 07:35:07', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(310, '220706005', '06005', 'Zihad Hasan Shovon', '01710414554', NULL, 0, 7, 26, 2, '23-07-2022', NULL, NULL, NULL, '$2y$10$5lGIYNVfjHGCBRf6a9uBje59j/MCR8e6lJgLZ3tC0GLuA2nteWRFS', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(311, '220906006', '06006', 'Md. Marufur Rahman', '01715368844', NULL, 1, 7, 26, 2, '26-09-2022', NULL, NULL, NULL, '$2y$10$YxtYRBAQ5ocoa6uWKeXTT.LnQoA9K2wM7e0W.MG6KmH2YD3Ehed9m', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(312, '230106007', '06007', 'G.M Zahid Hassan', '01516784902', NULL, 1, 7, 4, 3, '26-01-2023', NULL, NULL, NULL, '$2y$10$Nsbh1PhXhmXRHnAssEiGVOS91MXEJuHhg8YzBx071yhUnjo4cXOWO', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(313, '170707001', '07001', 'Mahabub Alam (Accounts)', '01719470819', NULL, 1, 8, 18, 2, '02-07-2017', NULL, NULL, NULL, '$2y$10$czqBpQYes/BNlcEiTVsBqOy71FXcyAB6FzIO88vqnPm1088J45G1e', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(314, '190107002', '07002', 'Khadijatul Jannat', '01877772223', NULL, 1, 8, 2, 2, '01-01-2019', NULL, NULL, NULL, '$2y$10$9PqhaCkRf1/RbjsJJSi3kOpER2pyidC0PQgbruftBdH4cYiZegoka', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(315, '200207003', '07003', 'Md. Shariful Islam', '01749595786', NULL, 1, 8, 2, 2, '02-02-2020', NULL, NULL, NULL, '$2y$10$0t5tfzqLIZfCbqFPzydG4e/ZYI6pm61ny2eDhTFTI2eotf0jyr19m', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(316, '220907004', '07004', 'Md. Tajbidul Islam', '01914586524', 'tajbidul@credencehousinglimited.com', 1, 8, 3, 2, '12-09-2022', NULL, NULL, NULL, '$2y$10$OonL4GMk25BKWNlQIgrjiuZ0i/PQUDs6izDq/zF2MXiPllFAsXA7G', NULL, '2023-12-04 07:35:08', '2024-03-11 05:03:32', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(317, '221207005', '07005', 'Sakib Siraj Sabbir', '01991080182', NULL, 1, 8, 4, 2, '13-12-2022', NULL, NULL, NULL, '$2y$10$rYwmSmXtq.RWG.UJPIBhbuMl7jDaTEuOppq7lHKw6AoI4CHaAZpZq', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(318, '230307006', '07006', 'Sakif Hasan Shopno', '01949207308', NULL, 1, 8, 4, 2, '09-03-2023', NULL, NULL, NULL, '$2y$10$yqawZ7cp5oLZA09T2R0Ine1yfyAeZpnvZyz27SSA.JVxgNTfBlRva', NULL, '2023-12-04 07:35:08', '2023-12-04 07:35:08', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(319, '230507007', '07007', 'Towhid Al Islam', '01875957301', NULL, 1, 8, 11, 2, '02-05-2023', NULL, NULL, NULL, '$2y$10$IdyQdwJfszMstrsZAJyghu0Y8zyrl.7YDcYHvka0Wl6AUuIWlZSGO', NULL, '2023-12-04 07:35:09', '2023-12-04 07:35:09', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(320, '180108001', '08001', 'Engr. Mohiuddin Sarker', '01671439992', NULL, 1, 9, 17, 3, '18-01-2018', NULL, NULL, NULL, '$2y$10$8VKsFWWsvpLFATulDBIiq.ugPNoRlzJJX.wJs4ZUS.s.mB7AE1IPm', NULL, '2023-12-04 07:35:09', '2023-12-04 07:35:09', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(321, '181208002', '08002', 'Manik Chandra Bishwas (S/A)', '01894940520', NULL, 1, 9, 32, 3, '20-12-2018', NULL, NULL, NULL, '$2y$10$kg.qZygPNLbC7.5xX08YQO/cReWlF8D7IHLkyF1akDMvfLL1BSgmG', NULL, '2023-12-04 07:35:09', '2023-12-04 07:35:09', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(322, '191208003', '08003', 'Md. Shahporan', '01756570850', NULL, 1, 9, 3, 3, '07-12-2019', NULL, NULL, NULL, '$2y$10$o7Q0jGBNOVS02l10ytphIeZPzYxj8zZaSHhYw1k.qsD.bIDMsHTuG', NULL, '2023-12-04 07:35:09', '2023-12-04 07:35:09', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(323, '200308004', '08004', 'Ariful Haque', '01943100560', NULL, 1, 9, 4, 3, '02-03-2020', NULL, NULL, NULL, '$2y$10$vHh7Ft0FAHSD8rFWJFWtWOXrLz2a5QcC.8RNsPJXwkkae3bgbPTzm', NULL, '2023-12-04 07:35:09', '2023-12-04 07:35:09', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(324, '200708005', '08005', 'Md. Jahur Ahmed', '01973366112', NULL, 1, 9, 15, 3, '02-07-2020', NULL, NULL, NULL, '$2y$10$K1hmGVcPypywHsWw9kvh5.8YhPYd6Mc0aGimdjkn3445kD.X9WWhi', NULL, '2023-12-04 07:35:09', '2023-12-04 07:35:09', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(325, '200808006', '08006', 'Saydul Alam', '01718656810', NULL, 1, 9, 17, 3, '20-08-2020', NULL, NULL, NULL, '$2y$10$t9xxl7PKytlR56hwKuEzTeJgHb2pLOkGLso2XNWjEb4v5iNkgXp3.', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(326, '201008007', '08007', 'Ratan Chandra das', '01911096346', NULL, 1, 9, 18, 3, '17-10-2020', NULL, NULL, NULL, '$2y$10$foHAh.nk68rq30JjL1DZb.pj7CYi7c06azTeOCg3xaNUxKFnDkOFW', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(327, '220308008', '08008', 'Kamruzzaman  (Plumbing)', '01913597802', NULL, 1, 9, 17, 3, '10-03-2022', NULL, NULL, NULL, '$2y$10$yO/DQw9lzXxdnGzwjDzwSu84NQaQ35iWjKZjqTF5isWgidmxOcGIm', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(328, '220308009', '08009', 'Abul Bashar', '01749155111', NULL, 1, 9, 29, 3, '12-03-2022', NULL, NULL, NULL, '$2y$10$b01b6DE3pnwv.larPBrF..2wOirxS4dduMhuTmySCt6O/XF4HUPxm', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(329, '220608010', '08010', 'Goutam Kumar Paul', '01716514407', NULL, 1, 9, 16, 3, '26-06-2022', NULL, NULL, NULL, '$2y$10$0WUvbDF5qTjkWFxv5we4i.N1CYPPvgZw8aQMUiXYO3lDMdO0/evoO', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(330, '230208011', '08011', 'Durul Hoda', '01716865730', NULL, 1, 9, 60, 3, '01-02-2023', NULL, NULL, NULL, '$2y$10$GrBrYkJAsjBDjIYiu61gheqtvAxZoWjhED3MLyPhjM0TWeubM7LhO', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(331, '230208012', '08012', 'Md.Gajibor Rahman', '01729920543', NULL, 1, 9, 11, 3, '15-02-2023', NULL, NULL, NULL, '$2y$10$CAid9Vk1II5C4Nz5bFbrqurcIjVcdjNU3BQDhP6FKtb1Gl0RgcDEK', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(332, '230508013', '08013', 'Ahsanur Rahman Adel', '01711974994', NULL, 1, 9, 2, 3, '06-05-2023', NULL, NULL, NULL, '$2y$10$No7fSlxxLewJLrpZXjSAnOn1uYPEYYsj4Qzut.szKOS88EiB/OR9e', NULL, '2023-12-04 07:35:10', '2023-12-04 07:35:10', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(333, '230808014', '08014', 'Md. Nafis Iqbal', '01327808121', NULL, 1, 9, 49, 2, '30-08-2023', NULL, NULL, NULL, '$2y$10$2e.k0J54KAHaUaZsc6huG.YpqQyBd31zzNdsiQgTBsV0w3n5I1tpW', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(334, '231008015', '08015', 'Riazul Islam', '01568535947', NULL, 1, 9, 29, 3, '10-10-2023', NULL, NULL, NULL, '$2y$10$vtNhfpiD8qcqBDYNKoSHz.IRjgbe6/EYlBP2dTFOvzTHaRPWNHBYO', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(335, '110109001', '09001', 'Md Sizan Rahman', '01717355927', NULL, 1, 10, 2, 3, '05-01-2011', NULL, NULL, NULL, '$2y$10$MGM3ANQ4g991UDnRJ/7GCepHPlxJ5W.Jb6RLmxWREhxj83hbL2bwa', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(336, '160809002', '09002', 'Raihan Kobir Rony', '01737741658', NULL, 1, 10, 2, 3, '17-08-2016', NULL, NULL, NULL, '$2y$10$LU8gZZn.e8g/Hen3VYenMO02IfjKA4CL1oJEmOY9HJ/GUMRUpvnAS', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(337, '180209003', '09003', 'Mirja Rafiul Rafsan Faruki', '01777255054', NULL, 1, 10, 2, 3, '01-02-2018', NULL, NULL, NULL, '$2y$10$WkHqer1rSH0k/LogLdIuUO68Nd6ZA5wSdn0WlyvrVPl/reaUkoJDi', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(338, '210909004', '09004', 'Naser Imran', '01787691183', NULL, 1, 10, 15, 3, '18-09-2021', NULL, NULL, NULL, '$2y$10$b0NAODyNuVpDqCs7GUuFD.C8/EbWHHyGSka9WJbj2mIsUT5kSVG4S', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(339, '211009005', '09005', 'Md. Shojib Hassan', '01735601000', NULL, 1, 10, 51, 3, '26-10-2021', NULL, NULL, NULL, '$2y$10$r31zjonn7q23ILKOa8lu5eJUKy4TiOiDZV2gSWwW7M1Tg3nGst5C2', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(340, '211109006', '09006', 'Md. Masum Billa', '01795692012', NULL, 1, 10, 4, 3, '01-11-2021', NULL, NULL, NULL, '$2y$10$r70ETcFmRZTF6NVWdKpZwOzXfFNaZwmIcAqmtbK5JHc4OFNP16vvu', NULL, '2023-12-04 07:35:11', '2023-12-04 07:35:11', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(341, '220909007', '09007', 'Md. Jakir Hossain', '01711143157', NULL, 1, 10, 18, 3, '17-09-2022', NULL, NULL, NULL, '$2y$10$kAQZil1fRgadqVhYOgP/7e0vVwyLQa92VOm/0xhPK5KePvhcpA3MS', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(342, '230109008', '09008', 'Hasibuzzaman', '01678712642', NULL, 1, 10, 3, 3, '15-01-2023', NULL, NULL, NULL, '$2y$10$e0z2GeaiOcG6WaTwLYclUu1zfyxWyotDQIPqw2Q73kWhzDyDw8cgq', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(343, '230109009', '09009', 'Zahir Rayhan(Asst.Manager)', '01962401516', NULL, 1, 10, 18, 3, '21-01-2023', NULL, NULL, NULL, '$2y$10$.nE/Rh7Y9lVfINEEOlLlD.HwtPE2v6tj1hc33U0j.xTKoAXWi/Jua', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(344, '180110001', '10001', 'Md. Sogib', '01758379407', NULL, 1, 11, 2, 3, '26-01-2018', NULL, NULL, NULL, '$2y$10$RjfIVbIOZycYfPkdohAwzeVsk3eFmLVwZHe5NZzNwzs4cqMzDdo8O', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(345, '181110002', '10002', 'Md. Ahsan Kabir (Inv.)', '01745850902', NULL, 1, 11, 2, 3, '24-11-2018', NULL, NULL, NULL, '$2y$10$8ESNSNzJO86yiSnr0ea2meM/I.uvqMpyYJNWiNCZKigM9ksUwOPH.', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(346, '211110003', '10003', 'Md. Helal Uddin Sharif', '01711991404', NULL, 1, 11, 4, 2, '15-11-2021', NULL, NULL, NULL, '$2y$10$nQPY3ZRTlQoR28qRy3AIketi2i9R.rwAUWLC7Tpl9.n3KpErkmg3e', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(347, '220410004', '10004', 'Md. Masud Rana  (Inv.)', '01716172382', NULL, 1, 11, 16, 3, '15-04-2022', NULL, NULL, NULL, '$2y$10$9Nn2B3UF2d77SJzdh9kRuup8rJu/IkDnU3KV6mYsYhMF2sEqKq8rW', NULL, '2023-12-04 07:35:12', '2023-12-04 07:35:12', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(348, '230710005', '10005', 'Akther Mohammed Ashif', '016707516022', NULL, 1, 11, 2, 3, '26-07-2023', NULL, NULL, NULL, '$2y$10$x36KeIn6IeQH4AWB6hV5eOKKXGO9UT6PUCKVJXW4q4ZoYB0TNyos2', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(349, '200911001', '11001', 'Md. Salehin Shayed Shishir', '01997424324', NULL, 1, 12, 17, 2, '26-09-2020', NULL, NULL, NULL, '$2y$10$yorMqthOjFXF4gGnt61c3OriwSY2bE.4MlzXAhVvv3YqpZ7xGJ8sa', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(350, '211211002', '11002', 'Mohammad Saidur Rahman', '01913136232', NULL, 1, 12, 2, 2, '20-12-2021', NULL, NULL, NULL, '$2y$10$ZDT8ADrNkc321Q.rjsEtUuT09twrH1W6MwLlaHSlWBGXIA8JH5JJa', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(351, '181212001', '12001', 'Md. Joynal Abedin', '01731989504', NULL, 1, 13, 16, 2, '15-12-2018', NULL, NULL, NULL, '$2y$10$C9JnaDx5LQrmyagv7LuVX.Ruzkm/sF.tJQ8NJUNXrPLkDyHyKSv76', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(352, '190212002', '12002', 'Md Rezanuzzaman', '01911584057', NULL, 1, 13, 2, 2, '02-02-2019', NULL, NULL, NULL, '$2y$10$i4ztsjYasCkYE20y69Tk7uQoGHo81UvSfOROr90v1ehDw1RS3k.Ri', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(353, '210812003', '12003', 'Md. Shahjia Rahman', '01779943111', NULL, 1, 13, 4, 2, '26-08-2021', NULL, NULL, NULL, '$2y$10$cGkhX8pXh0oZ4P3rPErx5.aE8v1QbuDSH5GaWYC0ga.gBJNDU9Dce', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(354, '220112004', '12004', 'Md. AlAmin', '01611222122', NULL, 1, 13, 3, 2, '01-01-2022', NULL, NULL, NULL, '$2y$10$Tq19ZwZdZnL8KV6s095sYuzoGOrLlfRmKgZcoq9FRIRFLbFmeJyEC', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO `users` (`id`, `employee_id`, `employee_id_hidden`, `name`, `phone`, `email`, `status`, `dept_id`, `designation_id`, `branch_id`, `joining_date`, `birthdate`, `profile_pic`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `blood_id`, `phone_2`, `email_2`, `father_name`, `mother_name`, `home_no`, `village`, `word_no`, `union`, `city`, `sub-district`, `district`, `division`, `capital`, `country`, `company_id`) VALUES
(355, '170913001', '13001', 'Md. Imtiaz Alam', '01711431317', NULL, 1, 14, 46, 3, '16-09-2017', NULL, NULL, NULL, '$2y$10$X7ipQgmD9rNLiHNZND2j/.hybTAAcGPWsFj7RWAg/iaB5D1AJ9GDG', NULL, '2023-12-04 07:35:13', '2023-12-04 07:35:13', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(356, '180713002', '13002', 'Razu Ahmed', '01768001670', NULL, 1, 14, 15, 3, '01-07-2018', NULL, NULL, NULL, '$2y$10$pse.cXiFOdwPgujQ6UTvvehjo51GuBMYG89KXbu2HLKi2TqhXZMKG', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(357, '200313003', '13003', 'Md. Ariful Islam (EM)', '01745166475', NULL, 1, 14, 18, 3, '16-03-2020', NULL, NULL, NULL, '$2y$10$TEKknOgfNggU/nZ17ME1bOd1s5A9MNeDiKUHJxNd6in.WdBSHLHtC', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(358, '210713004', '13004', 'Diyasrul Talukder (EM)', '01799372722', NULL, 1, 14, 46, 3, '26-07-2021', NULL, NULL, NULL, '$2y$10$PTzFrvtKJSQheJxFAQibYuTu3N.OjndV6DFKHDbFGakU1ZzdI.rPy', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(359, '211013005', '13005', 'Md. Sumon Hossain (EM)', '01782626643', NULL, 1, 14, 3, 3, '10-10-2021', NULL, NULL, NULL, '$2y$10$VdruQFIznFKkpyivgl2eoun7WrcSbsDWRyxUiX18E24qFCU1BiZIi', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(360, '220813006', '13006', 'Antor Hossain', '01708832102', NULL, 1, 14, 46, 3, '27-08-2022', NULL, NULL, NULL, '$2y$10$06/KWAnSgc0tE2bf3g4JvOnIPOud9TSkkNOD.PnV6TFmSZlD5rwBC', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(361, '221013007', '13007', 'Md. Masum Islam', '01617799672', NULL, 1, 14, 46, 3, '08-10-2022', NULL, NULL, NULL, '$2y$10$T2vOuobImxuLvmyiA45pJ.zuUVcOmVcuNnmPZYGuIcLI1m74c.Ts6', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(362, '180214001', '14001', 'Ms. Mahmuda Rahman Simi', '01794112574', NULL, 1, 15, 17, 2, '01-02-2018', NULL, NULL, NULL, '$2y$10$ykbt0fXEpqv21W745gRU0.MORjskIxE6..9A4RtFR0mEm0sya69/m', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(363, '200714002', '14002', 'Konika Kabir', '01977624735', NULL, 1, 15, 11, 2, '01-07-2020', NULL, NULL, NULL, '$2y$10$9Q4.tOULU22oe9lnQ1DwG.COUvXVDWoNbKtCk8auf03JQg2q0BMkO', NULL, '2023-12-04 07:35:14', '2023-12-04 07:35:14', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(364, '230114003', '14003', 'Md.Al Shariyar Jarjij', '01717321741', NULL, 1, 15, 4, 2, '26-01-2023', NULL, NULL, NULL, '$2y$10$fL5nuzoj.tNoRyIrGAiu8u7B5IRdS.C3bBL.ust2DE/gvELeJSWZS', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(365, '230914004', '14004', 'Rubayet Habib', '01777199117', NULL, 1, 15, 4, 2, '26-09-2023', NULL, NULL, NULL, '$2y$10$FaiQ8PnrGlwfSj3pILcSfeWoGlhUzB4o3i9qnWHDmhyHqBW9zl4/S', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(366, '180215001', '15001', 'Md. Mainul Islam', '01634706640', NULL, 1, 16, 16, 2, '01-02-2018', NULL, NULL, NULL, '$2y$10$NefAKZMCza2n8C3RGIABAeNR9GOF3PZwW.Crz9Ofedelyp9r3xAqW', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(367, '200215002', '15002', 'Md. Abdullah (Law)', '01722060032', NULL, 1, 16, 2, 2, '01-02-2020', NULL, NULL, NULL, '$2y$10$2lC5m6FluN79OJ6v.oFjCe44tesmynkiqGGpgvSdP93L15ffETQnm', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(368, '161216001', '16001', 'Md Juel Shake', '01728095503', NULL, 1, 17, 18, 2, '01-12-2016', NULL, NULL, NULL, '$2y$10$Wqh14uRWja8iT5N80XEQgOS8zfEtpd3zoq6NiMK1vFVEzhLCbQLj2', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(369, '171016002', '16002', 'Abul Kalam Azad (Logistics)', '01715835896', NULL, 1, 17, 12, 2, '01-10-2017', NULL, NULL, NULL, '$2y$10$p5WA6DacKFeMtn9rcEIAIO0./CqZfoCCvSIUJQRjAcQCqUtATzY0S', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(370, '180416003', '16003', 'Md. Arshadul Haque', '01988493671', NULL, 1, 17, 51, 2, '01-04-2018', NULL, NULL, NULL, '$2y$10$4FY2StkhPYAHHg6lKUoHmuKRnOED/9PWmz7OnQsDDizYCau.1aGMa', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(371, '191116004', '16004', 'Md. Abul Kalam Maruf', '01719440642', NULL, 1, 17, 17, 2, '09-11-2019', NULL, NULL, NULL, '$2y$10$8wYaYtg3H95X7B.q9SbN.ustrH4lU3koa7ZlDdmsP1aQURZROA/au', NULL, '2023-12-04 07:35:15', '2023-12-04 07:35:15', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(372, '151017001', '17001', 'Md. Mehedi Hasan (Brand)', '01985709311', NULL, 1, 18, 17, 2, '05-10-2015', NULL, NULL, NULL, '$2y$10$JvzP2E.fiGxYhrvqYJXphOVaTnHPIINcY5FSIXD2OkL1h6gAdmEVa', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(373, '210617002', '17002', 'Mohammad Mobaruk Hosen Apu', '01303528332', NULL, 1, 18, 3, 2, '01-06-2021', NULL, NULL, NULL, '$2y$10$T8NcAjJ8S19rODrcELgw4.AtMNKdyhUWD52os8iY5epOiEwPQfWUi', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(374, '221117003', '17003', 'Shahnaz Ameer', '01854944306', NULL, 1, 18, 34, 2, '05-11-2022', NULL, NULL, NULL, '$2y$10$0V3Zeyp3orV2CU1PgIJpm.JDDHOaN/8zK.Bk5pMDPt4ATUgDqiSmO', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(375, '221217004', '17004', 'Mohammad Ali', '01782122238', NULL, 1, 18, 4, 2, '17-12-2022', NULL, NULL, NULL, '$2y$10$kV8Y5UBYADsHWMXtRcwRgOuXxYbGtE.WRpsqgQM2WxFhqcROtBpii', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(376, '221217005', '17005', 'Abid Abdullah', '01521341076', NULL, 1, 18, 4, 2, '17-12-2022', NULL, NULL, NULL, '$2y$10$lnzTNvSBVcqcfCeg2pcZVeMMwZAO0/I71UJ59E/d06VTXdbz2AIf6', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(377, '230517006', '17006', 'Mirza Fahim Kabir', '01815424492', NULL, 1, 18, 39, 2, '03-05-2023', NULL, NULL, NULL, '$2y$10$utARGxVGLWCmZYy3at.XPuTDg.ryM44xEy8mO0gixaKhsxXduXgnG', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(378, '170418001', '18001', 'Mahadi Hasan', '01710603031', NULL, 1, 19, 11, 3, '01-04-2017', NULL, NULL, NULL, '$2y$10$JVs9poeZQ2rL5OcfmPff/uFuYr4F5jIO6nJTAZjSyEll5ElMRe6g2', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(379, '180218002', '18002', 'Md. Rubel Hossain', '01722568378', NULL, 1, 19, 18, 3, '15-02-2018', NULL, NULL, NULL, '$2y$10$VWyVZRhSuuYn8RH.nBAofuf95.mrKODhBKhWnAut/JqiQntg2AEVa', NULL, '2023-12-04 07:35:16', '2023-12-04 07:35:16', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(380, '181018003', '18003', 'Md. Swapan Sarker', '01718979593', NULL, 1, 19, 4, 3, '01-10-2018', NULL, NULL, NULL, '$2y$10$l1rTiWcTCkWSUhOcwepPs.c0J96wiJ3xx0oT.etidK02g5Qz52NrW', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(381, '190418004', '18004', 'Fazle Rabbi', '01762830301', NULL, 1, 19, 3, 3, '02-04-2019', NULL, NULL, NULL, '$2y$10$OsQG2WuwAjOjqH7fkx95mORMn/rYV7l67oksN4FmO.ZQb4lRJK4Xi', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(382, '190618005', '18005', 'Altaf Hossain', '01632580789', NULL, 1, 19, 3, 3, '12-06-2019', NULL, NULL, NULL, '$2y$10$ZMG6.blAPA7jlTm5LAwtwO20lHIRWpETSDBxfZNykyzxfgayXJn4y', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(383, '190918006', '18006', 'Shamim Seikh', '01703743902', NULL, 1, 19, 37, 3, '18-09-2019', NULL, NULL, NULL, '$2y$10$isEjZu3RnXk/9.cmvfYEI.6Als5hpndJCupVc4z36OmeXGjmzrudq', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(384, '191218007', '18007', 'Mizanur Rahman (CSD)', '01740321346', NULL, 1, 19, 2, 3, '01-12-2019', NULL, NULL, NULL, '$2y$10$Al61GvGX7MflRtZo7x/5Auw2VNzSTagBZx9xzUtcTmiE1OnACcX9G', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(385, '220318008', '18008', 'Md. Almas Uddin', '01753152504', NULL, 1, 19, 3, 3, '03-03-2022', NULL, NULL, NULL, '$2y$10$umX6UsoOg9bGttsTmRv1o.P7aH0i9D.tgKKsd5f5UQwLA5hWlE8pu', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(386, '230118009', '18009', 'Sinthia Nazia Nishat', '01728934722', NULL, 1, 19, 24, 3, '17-01-2023', NULL, NULL, NULL, '$2y$10$w2VWOBLMXrZV5M4SvB8po.R/sjA.dMZeDvZXKUjgffWHx63lzjHTi', NULL, '2023-12-04 07:35:17', '2023-12-04 07:35:17', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(387, '210819001', '19001', 'Bipul Sarker', '01740313579', NULL, 1, 20, 17, 2, '16-08-2021', NULL, NULL, NULL, '$2y$10$HlotpZguQ3H.Q7ING8KdneXGNFHsymNBXTwuQU1Rp74F7yJkAwsSG', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(388, '220219002', '19002', 'Md. Toufikul Islam', '01824636421', NULL, 1, 20, 18, 2, '05-02-2022', NULL, NULL, NULL, '$2y$10$61GDbvJFVOXDNvo02H5JzuyA2esPKEgC10WjrHD3a7odHwQxi0vA6', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(389, '221119003', '19003', 'Joy Das', '01551601126', NULL, 1, 20, 4, 2, '26-11-2022', NULL, NULL, NULL, '$2y$10$iia1vPwCuON./8OYBMntZ.gNUItY8oyyfrvQ0xdltgl5ySM1WW2JC', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(390, '221219004', '19004', 'Yeasin Arafat', '01521428700', NULL, 1, 20, 4, 2, '04-12-2022', NULL, NULL, NULL, '$2y$10$O1wHwEgu2jBIm3FsJc.Ub.NRLxh5nPKpisfM8iQBtOSuhjMl9RqVi', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(391, '230619005', '19005', 'Md. Sajib Mahmud', '01776012061', NULL, 1, 20, 39, 5, '14-06-2023', NULL, NULL, NULL, '$2y$10$0lrT82S9toxvrx4m4FUn.eT3F4ZvmbATpEN7tKAZvuJkYHT8nl06y', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(392, '230619006', '19006', 'Mohamudul Islam Foysal', '01754727156', NULL, 1, 20, 39, 5, '14-06-2023', NULL, NULL, NULL, '$2y$10$ffwmzYSKHC1k5bOoOF8UgOIYrlke5pSQLZGzjt1xheK0GnAWbVGxK', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(393, '230919007', '19007', 'Taifur Rahman', '01777344738', NULL, 1, 20, 39, 5, '16-09-2023', NULL, NULL, NULL, '$2y$10$CZ8CVeNs5OU4GypJ5/tc8OrgY7oFYBuQv2.UFPeQadBjokIDIqs/m', NULL, '2023-12-04 07:35:18', '2023-12-04 07:35:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(394, '210420001', '20001', 'Md. Ahnaf Morshed', '01783816108', NULL, 1, 21, 3, 3, '03-04-2021', NULL, NULL, NULL, '$2y$10$93rU7TWV2lGevfDXuTISiez2e9T44pxjzDyFL66NGg79k.T9t68qy', NULL, '2023-12-04 07:35:18', '2024-02-03 04:29:19', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(395, '220320002', '20002', 'Md. Ariful Hasan (Consultant)', '01711529878', NULL, 1, 21, 13, 3, '01-03-2022', NULL, NULL, NULL, '$2y$10$XI3iZp2gBr8PDHrTxiOzC.kCYguNCrNYfSgYW6aBFbXzkt2KRaq9G', NULL, '2023-12-04 07:35:19', '2023-12-04 07:35:19', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(396, '230220003', '20003', 'Nayeem Ahmed', '01715011640', NULL, 1, 21, 20, 5, '01-02-2023', NULL, NULL, NULL, '$2y$10$coJKO5BE/RvJz9MJQ.9tueJQgSBnmxN4v/EPMy2pH7pHqaaLCV9MG', NULL, '2023-12-04 07:35:19', '2023-12-04 07:35:19', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(397, '230720004', '20004', 'Sahadat Hosen', '01896055444', NULL, 1, 21, 46, 3, '30-07-2023', NULL, NULL, NULL, '$2y$10$k5dROB6z8AwqfhuYe0xLe.7OUyDOHd28QAjBASQEdMzvU0kWUkqaG', NULL, '2023-12-04 07:35:19', '2023-12-04 07:35:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(398, '201121001', '21001', 'Md. Rafiuzzaman', '01884337718', 'credence.mis@gmail.com', 1, 22, 18, 2, '01-11-2020', NULL, NULL, NULL, '$2y$10$77rMibedW2j7wRZaQIF9pe7fJB/bAUJmU30/g6IQYt2R0MaVoD2Kq', NULL, '2023-12-04 07:35:19', '2024-07-18 11:52:40', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(399, '230921003', '21003', 'Kushon Chandra Das', '01674389309', NULL, 1, 22, 39, 2, '10-09-2023', NULL, NULL, NULL, '$2y$10$KZPFgr0Oa2PvZ1yixc20e.rrTa8fhCwS8mNYmsl5.1CzhjSzQczw2', NULL, '2023-12-04 07:35:19', '2023-12-04 07:35:19', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(400, '230921004', '21004', 'Keshob Chondro Mohonto', '01742535527', NULL, 1, 22, 39, 2, '16-09-2023', NULL, NULL, NULL, '$2y$10$Vl3.V5EAqWbgyJHk8sZ4uOMusmX/w3pTKIONQgxES1n4Uy13hANR2', NULL, '2023-12-04 07:35:19', '2023-12-04 07:35:19', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(401, '230720005', '20005', 'Sahadat Hosen', '01721758802', 'sahada@abc.ffs', 5, 21, 46, 3, '23-07-31', NULL, NULL, NULL, '$2y$10$RczTGh1LyA9d6ykDdmnAs.s5a7R/dCfbMxT24hWtcTLmBQCOC03UW', NULL, '2023-12-06 08:03:42', '2023-12-06 08:05:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(402, '240121005', '21005', 'Gungun', '01778138129', 'testuser@test.com', 1, 22, 2, 1, '24-01-01', NULL, NULL, NULL, '$2y$10$x8lSjdgXAseKq6NOaPSDhudjmsgiKJtBMoVlU0FmGHcTHazF48ouG', NULL, '2024-06-10 03:50:23', '2024-06-10 03:50:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_salary_certificate_data`
--

CREATE TABLE `user_salary_certificate_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=active',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `financial_yer_from` varchar(255) DEFAULT NULL,
  `financial_yer_to` varchar(255) DEFAULT NULL,
  `basic` varchar(255) DEFAULT NULL,
  `house_rent` varchar(255) DEFAULT NULL,
  `conveyance` varchar(255) DEFAULT NULL,
  `medical_allowance` varchar(255) DEFAULT NULL,
  `festival_bonus` varchar(255) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_salary_certificate_data`
--

INSERT INTO `user_salary_certificate_data` (`id`, `status`, `user_id`, `financial_yer_from`, `financial_yer_to`, `basic`, `house_rent`, `conveyance`, `medical_allowance`, `festival_bonus`, `others`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-01', '2023-11', '42000', '21000', '3500', '3500', '12332', NULL, NULL, 116, NULL, '2023-11-26 11:14:07', '2023-11-26 11:14:07'),
(2, 1, 316, '2023-07', '2024-02', '22800', '9500', '3800', '1900', '38000', NULL, NULL, 316, NULL, '2024-03-11 05:13:09', '2024-03-11 05:13:09');

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
(1, 1, 'employee (2).xlsx', 'file-manager/Account Document/', 1, NULL, '2023-11-26 07:54:14', NULL),
(5, 2, '12345_Test_Claim+Details+Report.pdf', 'file-manager/Account Document/', 1, NULL, '2023-11-26 08:13:48', NULL),
(6, 3, 'JV208585_Test_Test.pdf', 'file-manager/Account Document/', 1, NULL, '2023-11-26 10:27:12', NULL),
(7, 4, '4521_Cash_Test.pdf', 'file-manager/Account Document/', 116, NULL, '2023-11-26 11:06:56', NULL),
(16, 1, 'JV-1234_Test_Proposal for Corporate Web Hosting System.pdf', 'file-manager/Account Document/', 1, NULL, '2023-11-30 10:53:02', NULL),
(17, 5, '584654_Test_MRF-180883, Export_Data_04-Dec-2023.pdf', 'file-manager/Account Document/', 1, NULL, '2023-12-04 07:44:49', NULL),
(18, 6, '12_Test_Meeting Minutes (Feb\'24).pdf', 'file-manager/Account Document/', 316, NULL, '2024-03-11 04:53:30', NULL),
(19, 6, '12_Test_Storage Documents Database.xlsx', 'file-manager/Account Document/', 316, NULL, '2024-03-11 04:54:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_document_individual_deleted_histories`
--

CREATE TABLE `voucher_document_individual_deleted_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_info_id` bigint(20) UNSIGNED NOT NULL,
  `document` text DEFAULT NULL,
  `filepath` text DEFAULT NULL,
  `restored_status` varchar(255) NOT NULL DEFAULT '0',
  `restored_by` varchar(255) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_document_individual_deleted_histories`
--

INSERT INTO `voucher_document_individual_deleted_histories` (`id`, `voucher_info_id`, `document`, `filepath`, `restored_status`, `restored_by`, `deleted_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'JV-1234_Test_Screenshot 2023-09-13 130127.png', 'file-manager/Account Document/', '0', NULL, '1', 116, NULL, '2023-11-27 08:43:40', '2023-11-27 08:43:40'),
(2, 5, '584654_Test_XPIBFL.pdf', 'file-manager/Account Document/', '0', NULL, '1', 200, NULL, '2023-11-27 08:44:47', '2023-11-27 08:44:47'),
(3, 5, '584654_Test_screen-capture (5).mp4', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-11-27 08:46:23', '2023-11-27 08:46:23'),
(4, 1, 'JV-1234_Test_screen-capture (5).mp4', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-11-27 08:46:42', '2023-11-27 08:46:42'),
(5, 1, 'chl_complain (1).sql', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-11-27 08:46:49', '2023-11-27 08:46:49'),
(6, 2, '12345_Test_chl_complain (1).sql', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-11-27 08:46:59', '2023-11-27 08:46:59'),
(7, 2, '12345_Test_DataTable_17-Oct-2023 (1).xls', 'file-manager/Account Document/', '0', NULL, '116', 1, NULL, '2023-11-27 08:48:47', '2023-11-27 08:48:47'),
(8, 2, '12345_Test_DataTable_17-Oct-2023 (2).xls', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-11-29 04:19:45', '2023-11-29 04:19:45'),
(9, 2, '12345_Test_DataTable_17-Oct-2023 (3).xls', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-11-29 04:19:58', '2023-11-29 04:19:58'),
(10, 1, 'salary_certificate_for_Abu Oubaida (1).pdf', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-12-21 04:45:50', '2023-12-21 04:45:50'),
(11, 1, 'salary_certificate_for_Abu Oubaida (2).pdf', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2023-12-21 04:46:06', '2023-12-21 04:46:06'),
(12, 6, '12_Test_Profile.pdf', 'file-manager/Account Document/', '0', NULL, '1', 1, NULL, '2024-05-12 04:18:07', '2024-05-12 04:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_document_share_email_links`
--

CREATE TABLE `voucher_document_share_email_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `share_id` varchar(255) NOT NULL,
  `share_document_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=active, 2=inactive/delete',
  `shared_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_document_share_email_links`
--

INSERT INTO `voucher_document_share_email_links` (`id`, `share_id`, `share_document_id`, `status`, `shared_by`, `created_at`, `updated_at`) VALUES
(1, 'SGlb17010704', 1, 1, 116, NULL, NULL),
(2, 'n4OG17013398', 1, 1, 1, NULL, NULL),
(3, 'mNon17013399', 4, 1, 1, NULL, NULL),
(4, 'iW2s17013402', 4, 1, 1, NULL, NULL),
(5, '4jxJ17013427', 16, 0, 1, NULL, '2023-12-21 04:47:06'),
(6, '5u8j17013431', 16, 1, 1, NULL, NULL),
(7, 'C9fx17013433', 16, 1, 1, NULL, NULL),
(8, 'REqe17015752', 3, 1, 1, NULL, NULL),
(9, 'Pakn17015820', 1, 1, 116, NULL, NULL),
(10, '388H17034003', 16, 1, 1, NULL, NULL),
(11, 'BL8p17034004', 16, 1, 1, NULL, NULL),
(12, '8Nbp17034008', 16, 1, 1, NULL, NULL),
(13, 'h9WV17101339', 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_document_share_email_lists`
--

CREATE TABLE `voucher_document_share_email_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `share_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_document_share_email_lists`
--

INSERT INTO `voucher_document_share_email_lists` (`id`, `share_id`, `email`, `created_at`, `updated_at`) VALUES
(1, '1', 'oubaida.credence@gmail.com', NULL, NULL),
(2, '2', 'mdrafiuzzaman@credencehousinglimited.com', NULL, NULL),
(3, '3', 'mdrafiuzzaman@credencehousinglimited.com', NULL, NULL),
(4, '4', 'mdrafiuzzaman@credencehousinglimited.com', NULL, NULL),
(5, '5', 'oubaida@credencehousinglimited.com', NULL, NULL),
(6, '6', 'oubaida@credencehousinglimited.com', NULL, NULL),
(7, '7', 'oubaida@credencehousinglimited.com', NULL, NULL),
(8, '8', 'oubaida@credencehousinglimited.com', NULL, NULL),
(9, '9', 'tajbidul@credencehousinglimited.com', NULL, NULL),
(10, '10', 'mis.credence@gmail.com', NULL, NULL),
(11, '11', 'oubaida.credence@gmail.com', NULL, NULL),
(12, '12', 'credence.mis@gmail.com', NULL, NULL),
(13, '13', 'abuoubaida36@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_document_share_links`
--

CREATE TABLE `voucher_document_share_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `share_id` varchar(255) DEFAULT NULL,
  `share_type` int(11) NOT NULL DEFAULT 1 COMMENT '1=only view, 2=view and download',
  `share_document_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1=active, 2=inactive/delete',
  `shared_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_document_share_links`
--

INSERT INTO `voucher_document_share_links` (`id`, `share_id`, `share_type`, `share_document_id`, `status`, `shared_by`, `created_at`, `updated_at`) VALUES
(1, 'LLzO17010856', 1, 1, 1, 1, '2023-11-27 11:47:25', '2023-11-27 11:47:25'),
(2, '8GRl17010857', 1, 4, 1, 116, '2023-11-27 11:49:19', '2023-11-27 11:49:19'),
(3, 'cFA617010880', 2, 4, 1, 1, '2023-11-27 12:26:56', '2023-11-27 12:26:56'),
(4, 'QP8Q17010897', 1, 3, 1, 1, '2023-11-27 12:55:55', '2023-11-27 12:55:55'),
(5, 'lksj17013416', 1, 16, 1, 1, '2023-11-30 10:53:20', '2023-11-30 10:53:20'),
(6, 'p66x17015752', 2, 3, 1, 1, '2023-12-03 03:48:05', '2023-12-03 03:48:05'),
(7, 'BvrP17034010', 2, 16, 1, 1, '2023-12-24 06:57:56', '2023-12-24 06:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_types`
--

CREATE TABLE `voucher_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL,
  `voucher_type_title` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_types`
--

INSERT INTO `voucher_types` (`id`, `status`, `voucher_type_title`, `code`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bank', '01', 'Bank Voucher', 1, 1, '2023-11-26 07:51:31', '2023-11-26 07:51:31'),
(2, 1, 'Cash', '02', 'Cash', 1, 1, '2023-11-26 07:51:56', '2023-11-26 07:51:56'),
(3, 1, 'Test', '00', 'Test', 1, 1, '2023-11-26 07:53:31', '2023-11-26 07:53:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_voucher_infos`
--
ALTER TABLE `account_voucher_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_groups`
--
ALTER TABLE `blood_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_transfer_histories`
--
ALTER TABLE `branch_transfer_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_types`
--
ALTER TABLE `branch_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_infos`
--
ALTER TABLE `company_infos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_infos_company_name_unique` (`company_name`),
  ADD UNIQUE KEY `company_infos_company_code_unique` (`company_code`),
  ADD UNIQUE KEY `company_infos_phone_unique` (`phone`),
  ADD UNIQUE KEY `company_infos_email_unique` (`email`);

--
-- Indexes for table `company_types`
--
ALTER TABLE `company_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_types_company_type_title_unique` (`company_type_title`);

--
-- Indexes for table `complains`
--
ALTER TABLE `complains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complains_user_id_foreign` (`user_id`),
  ADD KEY `complains_to_dept_foreign` (`to_dept`),
  ADD KEY `complains_forward_to_foreign` (`forward_to`),
  ADD KEY `complains_forward_by_foreign` (`forward_by`),
  ADD KEY `complains_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `create_directory_histories`
--
ALTER TABLE `create_directory_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `create_file_histories`
--
ALTER TABLE `create_file_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleted_histories`
--
ALTER TABLE `deleted_histories`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation_change_histories`
--
ALTER TABLE `designation_change_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `download_histories`
--
ALTER TABLE `download_histories`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_uploading_histories`
--
ALTER TABLE `file_uploading_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fixed_assets`
--
ALTER TABLE `fixed_assets`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `permissions_parent_id_foreign` (`parent_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_user_histories`
--
ALTER TABLE `permission_user_histories`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `priorities_priority_number_unique` (`priority_number`);

--
-- Indexes for table `rename_histories`
--
ALTER TABLE `rename_histories`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `role_wise_default_permissions`
--
ALTER TABLE `role_wise_default_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_certificate_transections`
--
ALTER TABLE `salary_certificate_transections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_salary_certificate_data`
--
ALTER TABLE `user_salary_certificate_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_documents`
--
ALTER TABLE `voucher_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_document_individual_deleted_histories`
--
ALTER TABLE `voucher_document_individual_deleted_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_document_share_email_links`
--
ALTER TABLE `voucher_document_share_email_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_document_share_email_lists`
--
ALTER TABLE `voucher_document_share_email_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_document_share_links`
--
ALTER TABLE `voucher_document_share_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_voucher_infos`
--
ALTER TABLE `account_voucher_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blood_groups`
--
ALTER TABLE `blood_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `branch_transfer_histories`
--
ALTER TABLE `branch_transfer_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_types`
--
ALTER TABLE `branch_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_infos`
--
ALTER TABLE `company_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_types`
--
ALTER TABLE `company_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complains`
--
ALTER TABLE `complains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `create_directory_histories`
--
ALTER TABLE `create_directory_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `create_file_histories`
--
ALTER TABLE `create_file_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deleted_histories`
--
ALTER TABLE `deleted_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `department_transfer_histories`
--
ALTER TABLE `department_transfer_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `designation_change_histories`
--
ALTER TABLE `designation_change_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `download_histories`
--
ALTER TABLE `download_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_manager_permissions`
--
ALTER TABLE `file_manager_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_uploading_histories`
--
ALTER TABLE `file_uploading_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fixed_assets`
--
ALTER TABLE `fixed_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `permission_users`
--
ALTER TABLE `permission_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `permission_user_histories`
--
ALTER TABLE `permission_user_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pest_histories`
--
ALTER TABLE `pest_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rename_histories`
--
ALTER TABLE `rename_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_wise_default_permissions`
--
ALTER TABLE `role_wise_default_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_certificate_transections`
--
ALTER TABLE `salary_certificate_transections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_salary_certificate_data`
--
ALTER TABLE `user_salary_certificate_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voucher_documents`
--
ALTER TABLE `voucher_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `voucher_document_individual_deleted_histories`
--
ALTER TABLE `voucher_document_individual_deleted_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `voucher_document_share_email_links`
--
ALTER TABLE `voucher_document_share_email_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `voucher_document_share_email_lists`
--
ALTER TABLE `voucher_document_share_email_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `voucher_document_share_links`
--
ALTER TABLE `voucher_document_share_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `voucher_types`
--
ALTER TABLE `voucher_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complains`
--
ALTER TABLE `complains`
  ADD CONSTRAINT `complains_forward_by_foreign` FOREIGN KEY (`forward_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complains_forward_to_foreign` FOREIGN KEY (`forward_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complains_to_dept_foreign` FOREIGN KEY (`to_dept`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complains_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complains_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
