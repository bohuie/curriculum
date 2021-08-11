-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 31, 2021 at 12:13 AM
-- Server version: 5.7.24
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `curriculum_daulton`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_methods`
--

DROP TABLE IF EXISTS `assessment_methods`;
CREATE TABLE IF NOT EXISTS `assessment_methods` (
  `a_method_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `a_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int(11) NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`a_method_id`),
  KEY `assessment_methods_course_id_foreign` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_methods`
--

INSERT INTO `assessment_methods` (`a_method_id`, `a_method`, `weight`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 'Tests', 40, 1, '2021-06-30 03:39:40', '2021-06-30 08:42:15'),
(2, 'Quizzes', 10, 1, '2021-06-30 03:39:40', '2021-06-30 08:42:15'),
(3, 'Assignments', 25, 1, '2021-06-30 08:39:56', '2021-06-30 08:42:15'),
(18, 'Tests', 34, 16, '2021-07-29 08:44:28', '2021-07-30 11:04:12'),
(19, 'Assignments', 23, 16, '2021-07-29 08:44:28', '2021-07-30 11:04:12'),
(20, 'Custom method', 11, 16, '2021-07-29 08:44:46', '2021-07-30 11:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_num` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_modality` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `semester` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '-1',
  `assigned` int(11) NOT NULL,
  `required` int(11) DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `standard_category_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `scale_category_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`course_id`),
  KEY `courses_standard_category_id_foreign` (`standard_category_id`),
  KEY `courses_scale_category_id_foreign` (`scale_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_num`, `delivery_modality`, `year`, `semester`, `section`, `course_title`, `status`, `assigned`, `required`, `type`, `created_at`, `updated_at`, `standard_category_id`, `scale_category_id`) VALUES
(8, 'COSC', '400', 'O', 1, 'W1', '001', 'Projects 2', -1, 1, NULL, 'unassigned', '2021-07-23 14:19:43', '2021-07-23 14:19:43', 1, 1),
(9, 'COSC', '493', 'I', 2, 'W1', '001', 'Projects', -1, 1, NULL, 'assigned', '2021-07-23 23:42:11', '2021-07-23 23:42:11', 1, 1),
(4, 'MATH', '200', 'O', 2, 'W1', '001', 'Calculus 3', -1, 1, NULL, 'unassigned', '2021-07-20 02:55:12', '2021-07-20 02:55:26', 1, 1),
(5, 'MATH', '221', 'I', 2, 'W1', '001', 'Linear Algebra', -1, 1, NULL, 'unassigned', '2021-07-20 02:55:59', '2021-07-20 02:55:59', 1, 1),
(10, 'COSC', '499', 'I', 1, 'W1', '001', 'Projects', 1, -11, NULL, 'unassigned', '2021-07-23 23:56:50', '2021-07-23 23:56:50', 1, 1),
(7, 'PHYS', '100', 'O', 1, 'S1', '001', 'Introduction to Physics', -1, 1, NULL, 'assigned', '2021-07-20 02:57:34', '2021-07-27 00:02:46', 1, 1),
(11, 'COSC', '444', 'I', 1, 'W1', NULL, 'Machine Architecture', 1, -1, NULL, 'assigned', '2021-07-26 23:10:54', '2021-07-26 23:10:54', 1, 1),
(12, 'CHEM', '499', 'I', 1, 'W1', NULL, 'Projects2', 1, -1, NULL, 'assigned', '2021-07-26 23:11:14', '2021-07-26 23:11:14', 1, 1),
(13, 'MATH', '499', 'I', 1, 'W1', NULL, 'ttyutu', 1, -1, NULL, 'assigned', '2021-07-26 23:11:38', '2021-07-26 23:11:38', 1, 1),
(14, 'COSC', '333', 'I', 1, 'W1', NULL, 'Projects2', 1, -1, NULL, 'assigned', '2021-07-26 23:12:05', '2021-07-26 23:12:05', 1, 1),
(15, 'CHEM', '222', 'I', 1, 'W1', NULL, 'Machine Architecture', 1, -1, NULL, 'assigned', '2021-07-26 23:12:19', '2021-07-26 23:12:19', 1, 1),
(16, 'MATH', '111', 'I', 1, 'W1', NULL, 'really long title for a course', 1, -1, NULL, 'assigned', '2021-07-26 23:15:07', '2021-07-26 23:15:07', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course_optional_priorities`
--

DROP TABLE IF EXISTS `course_optional_priorities`;
CREATE TABLE IF NOT EXISTS `course_optional_priorities` (
  `op_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`op_id`,`course_id`),
  KEY `course_optional_priorities_course_id_foreign` (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_optional_priorities`
--

INSERT INTO `course_optional_priorities` (`op_id`, `course_id`, `created_at`, `updated_at`) VALUES
(2, 8, '2021-07-30 14:34:24', '2021-07-30 14:34:24'),
(1, 8, '2021-07-30 14:34:24', '2021-07-30 14:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `course_programs`
--

DROP TABLE IF EXISTS `course_programs`;
CREATE TABLE IF NOT EXISTS `course_programs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `course_required` int(11) DEFAULT NULL,
  `instructor_assigned` int(11) DEFAULT NULL,
  `map_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `course_programs_course_id_foreign` (`course_id`),
  KEY `course_programs_program_id_foreign` (`program_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_programs`
--

INSERT INTO `course_programs` (`id`, `course_id`, `program_id`, `created_at`, `updated_at`, `course_required`, `instructor_assigned`, `map_status`) VALUES
(38, 10, 4, '2021-07-30 11:23:12', '2021-07-30 11:23:12', 1, NULL, 0),
(37, 16, 4, NULL, NULL, NULL, NULL, 0),
(36, 11, 4, '2021-07-30 10:50:55', '2021-07-30 10:50:55', NULL, NULL, 0),
(35, 9, 4, '2021-07-30 10:50:55', '2021-07-30 10:50:55', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course_users`
--

DROP TABLE IF EXISTS `course_users`;
CREATE TABLE IF NOT EXISTS `course_users` (
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`course_id`,`user_id`),
  KEY `course_users_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_users`
--

INSERT INTO `course_users` (`course_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 1, '2021-06-30 08:32:05', '2021-06-30 08:32:05'),
(3, 1, NULL, NULL),
(4, 1, NULL, NULL),
(5, 1, NULL, NULL),
(6, 1, NULL, NULL),
(7, 1, NULL, NULL),
(8, 1, NULL, NULL),
(9, 1, NULL, NULL),
(10, 1, NULL, NULL),
(11, 1, NULL, NULL),
(12, 1, NULL, NULL),
(13, 1, NULL, NULL),
(14, 1, NULL, NULL),
(15, 1, NULL, NULL),
(16, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `custom_assessment_methods`
--

DROP TABLE IF EXISTS `custom_assessment_methods`;
CREATE TABLE IF NOT EXISTS `custom_assessment_methods` (
  `custom_method_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_methods` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`custom_method_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_assessment_methods`
--

INSERT INTO `custom_assessment_methods` (`custom_method_id`, `custom_methods`, `created_at`, `updated_at`) VALUES
(1, 'Tests', NULL, NULL),
(2, 'Assignments', NULL, NULL),
(3, 'Projects', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `custom_learning_activities`
--

DROP TABLE IF EXISTS `custom_learning_activities`;
CREATE TABLE IF NOT EXISTS `custom_learning_activities` (
  `custom_activity_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_activities` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`custom_activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_program_learning_outcomes`
--

DROP TABLE IF EXISTS `custom_program_learning_outcomes`;
CREATE TABLE IF NOT EXISTS `custom_program_learning_outcomes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_plo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_program_id` int(11) NOT NULL,
  `custom_program_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invitation_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invites_email_unique` (`email`),
  UNIQUE KEY `invites_invitation_token_unique` (`invitation_token`),
  KEY `invites_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_activities`
--

DROP TABLE IF EXISTS `learning_activities`;
CREATE TABLE IF NOT EXISTS `learning_activities` (
  `l_activity_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `l_activity` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`l_activity_id`),
  KEY `learning_activities_course_id_foreign` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `learning_activities`
--

INSERT INTO `learning_activities` (`l_activity_id`, `l_activity`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 'Field Trip', 1, '2021-06-30 03:39:40', '2021-06-30 08:42:15'),
(12, 'ssfsdfe', 16, '2021-07-30 11:03:01', '2021-07-30 11:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `learning_outcomes`
--

DROP TABLE IF EXISTS `learning_outcomes`;
CREATE TABLE IF NOT EXISTS `learning_outcomes` (
  `l_outcome_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `clo_shortphrase` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_outcome` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`l_outcome_id`),
  KEY `learning_outcomes_course_id_foreign` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `learning_outcomes`
--

INSERT INTO `learning_outcomes` (`l_outcome_id`, `clo_shortphrase`, `l_outcome`, `course_id`, `created_at`, `updated_at`) VALUES
(2, 'with books', 'Learning', 1, '2021-06-30 03:40:55', '2021-06-30 08:42:15'),
(5, 'tete', 'tests', 2, '2021-07-12 07:47:08', '2021-07-20 08:39:23'),
(6, 'Understanding compilation process', 'compilation of code', 3, '2021-07-20 02:44:23', '2021-07-20 02:52:38'),
(7, 'Understanding how machines communicate in binary', 'machine language concepts', 3, '2021-07-20 02:44:23', '2021-07-20 02:52:38'),
(8, 'combustion comprehension', 'explosions!', 6, '2021-07-21 08:36:22', '2021-07-21 08:37:33'),
(9, 'rttt', 'wrwrr', 7, '2021-07-23 01:42:43', '2021-07-27 00:04:33'),
(10, 'werrrw', 'werwr', 16, '2021-07-30 11:03:01', '2021-07-30 11:04:12'),
(11, 'truthiness', 'cass', 10, '2021-07-30 11:24:07', '2021-07-30 11:24:07');

-- --------------------------------------------------------

--
-- Table structure for table `mapping_scales`
--

DROP TABLE IF EXISTS `mapping_scales`;
CREATE TABLE IF NOT EXISTS `mapping_scales` (
  `map_scale_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colour` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mapping_scale_categories_id` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`map_scale_id`),
  KEY `mapping_scales_mapping_scale_categories_id_foreign` (`mapping_scale_categories_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mapping_scales`
--

INSERT INTO `mapping_scales` (`map_scale_id`, `title`, `abbreviation`, `description`, `colour`, `created_at`, `updated_at`, `mapping_scale_categories_id`) VALUES
(8, 'Principle', 'P', 'Makes a significant contribution to the degree', '#80bdff', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 2),
(9, 'Secondary', 'S', 'Contributes less significantly towards the degree requirements', '#1aa7ff', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 2),
(3, 'Advanced', 'A', 'Students demonstrate the learning outcomes with a high level of independence, expertise and sophistication expected upon graduation. Learning activities focus on and integrate the use of content or skills in multiple.', '#0065bd', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 1),
(2, 'Developing', 'D', 'Learning outcome is reinforced with feedback; students demonstrate the outcome at an increasing level of proficiency. Learning activities concentrate on enhancing and strengthening existing knowledge and skills as well as expanding complexity.', '#1aa7ff', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 1),
(1, 'Introduced', 'I', 'Key ideas, concepts or skills related to the learning outcome are demonstrated at an introductory level. Learning activities focus on basic knowledge, skills, and/or competencies and entry-level complexity.', '#80bdff', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 1),
(10, 'Major Contributor', 'Ma', 'Major contribution towards the degree requirement', '#0065bd', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 2),
(11, 'Minor Contributor', 'Mi', 'Minor contribution towards the degree requirement', '#843976', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 2),
(12, 'Yes', 'Y', 'The course outcome is aligned with the program-level learning outcome.', '#80bdff', '2021-07-21 01:14:39', '2021-07-21 01:14:39', 3),
(13, 'Foundations', 'F', 'Foundational knowledge is emphasized, including information, discrete facts, concepts, or basic skills. There may or may not be evidence of learning from participants.', '#80BDFF', '2021-07-21 01:14:39', '2021-07-30 11:10:15', 4),
(14, 'Extensions', 'E', 'Learning goes beyond the foundational level to make connections between facts or ideas, relating knowledge to personal experience, understanding multiple perspectives, and/or analyzing information. Participants evidence their learning in one or more ways.', '#1AA7FF', '2021-07-21 01:14:39', '2021-07-30 11:10:15', 4);

-- --------------------------------------------------------

--
-- Table structure for table `mapping_scale_categories`
--

DROP TABLE IF EXISTS `mapping_scale_categories`;
CREATE TABLE IF NOT EXISTS `mapping_scale_categories` (
  `mapping_scale_categories_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `msc_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`mapping_scale_categories_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mapping_scale_categories`
--

INSERT INTO `mapping_scale_categories` (`mapping_scale_categories_id`, `msc_title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Example 1', 'This is a standard scale that has been useful for a variety of different programs.', '2021-07-21 01:14:28', '2021-07-21 01:14:28'),
(2, 'Example 2', 'This scale might be useful for programs that focus on skills demonstration.', '2021-07-21 01:14:28', '2021-07-21 01:14:28'),
(3, 'Example 3', 'Use a one-point scale to indicate where an alignment exists but the level is not specified, such as in curriculum development.', '2021-07-21 01:14:28', '2021-07-21 01:14:28'),
(4, 'Example 4', 'Consider using a 2-point scale such as this one for non-credit learning opportunities.', '2021-07-21 01:14:28', '2021-07-21 01:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `mapping_scale_programs`
--

DROP TABLE IF EXISTS `mapping_scale_programs`;
CREATE TABLE IF NOT EXISTS `mapping_scale_programs` (
  `map_scale_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_scale_id`,`program_id`),
  KEY `mapping_scale_programs_program_id_foreign` (`program_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mapping_scale_programs`
--

INSERT INTO `mapping_scale_programs` (`map_scale_id`, `program_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(2, 1, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(3, 1, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(1, 2, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(2, 2, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(3, 2, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(1, 3, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(2, 3, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(3, 3, '2021-06-30 03:33:06', '2021-06-30 03:33:06'),
(11, 4, '2021-07-30 11:22:52', '2021-07-30 11:22:52'),
(10, 4, '2021-07-30 11:22:52', '2021-07-30 11:22:52'),
(9, 4, '2021-07-30 11:22:52', '2021-07-30 11:22:52'),
(8, 4, '2021-07-30 11:22:52', '2021-07-30 11:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_09_30_230930_create_programs_table', 1),
(5, '2020_10_01_015240_create_courses_table', 1),
(6, '2020_10_01_040348_create_learning_outcomes_table', 1),
(7, '2020_10_01_045146_create_learning_activities_table', 1),
(8, '2020_10_01_050955_create_assessment_methods_table', 1),
(9, '2020_10_02_044053_create_roles_table', 1),
(10, '2020_10_02_044814_create_role_user_table', 1),
(11, '2020_10_04_112114_create_course_users_table', 1),
(12, '2020_10_12_033720_create_program_users_table', 1),
(13, '2020_10_12_040046_create_p_l_o_categories_table', 1),
(14, '2020_10_12_063957_create_program_learning_outcomes_table', 1),
(15, '2020_10_16_030821_create_outcome_assessments_table', 1),
(16, '2020_10_16_030926_create_outcome_activities_table', 1),
(17, '2020_10_23_193349_create_outcome_maps_table', 1),
(18, '2020_11_06_213928_create_mapping_scales_table', 1),
(19, '2020_11_18_201559_create_mapping_scale_programs_table', 1),
(20, '2021_02_12_172212_update_courses_table', 1),
(21, '2021_02_24_185203_create_custom_learning_activities_table', 1),
(22, '2021_02_26_184047_create_custom_assessment_methods_table', 1),
(23, '2021_03_05_180845_create_invites_table', 1),
(24, '2021_03_25_204559_create_optional_program_learning_outcomes_table', 1),
(25, '2021_06_23_182042_create_optional_priority_categories_table', 1),
(26, '2021_06_24_181949_create_optional_priority_subcategories_table', 1),
(27, '2021_06_25_172715_create_optional_priorities_table', 1),
(28, '2021_06_26_181737_create_course_optional_priorities_table', 1),
(29, '2021_06_15_205336_create_course_programs_table', 2),
(30, '2021_06_16_224720_update_program_courses_table', 3),
(31, '2021_06_28_204736_create_standards_scale_categories_table', 4),
(32, '2021_06_28_205119_create_standard_scales_table', 5),
(33, '2021_06_28_205601_create_standard_categories_table', 6),
(34, '2021_06_28_205904_create_standards_table', 7),
(35, '2021_06_28_210155_create_standards_outcome_maps_table', 8),
(36, '2021_06_22_203247_update_m_s_courses', 9),
(37, '2021_06_04_174755_create_syllabi_table', 10),
(38, '2021_07_12_151933_update_syllabi_and_syllabi_users_tables', 11),
(39, '2021_07_12_154153_create_syllabus_tables', 12),
(40, '2021_07_02_220532_create_mapping_scale_categories_table', 13),
(41, '2021_07_02_221302_update_mapping_scale', 14);

-- --------------------------------------------------------

--
-- Table structure for table `okanagan_syllabi`
--

DROP TABLE IF EXISTS `okanagan_syllabi`;
CREATE TABLE IF NOT EXISTS `okanagan_syllabi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `course_format` text COLLATE utf8mb4_unicode_ci,
  `course_overview` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `okanagan_syllabi_syllabus_id_foreign` (`syllabus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `okanagan_syllabus_resources`
--

DROP TABLE IF EXISTS `okanagan_syllabus_resources`;
CREATE TABLE IF NOT EXISTS `okanagan_syllabus_resources` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `okanagan_syllabus_resources_id_name_unique` (`id_name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `okanagan_syllabus_resources`
--

INSERT INTO `okanagan_syllabus_resources` (`id`, `id_name`, `title`, `created_at`, `updated_at`) VALUES
(1, 'land', 'Land Acknowledgement', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(2, 'academic', 'Academic Integrity', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(3, 'finals', 'Final Examinations', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(4, 'grading', 'Grading Practices', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(5, 'health', 'Health and Wellness', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(6, 'safewalk', 'Safewalk', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(7, 'student', 'Student Learning Hub', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(8, 'disability', 'UBC Okanagan Disability Resource Centre', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(9, 'equity', 'UBC Okanagan Equity and Inclusion Office', '2021-07-20 09:49:10', '2021-07-20 09:49:10'),
(10, 'copyright', '© Copyright Statement', '2021-07-20 09:49:10', '2021-07-20 09:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `optional_priorities`
--

DROP TABLE IF EXISTS `optional_priorities`;
CREATE TABLE IF NOT EXISTS `optional_priorities` (
  `op_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subcat_id` bigint(20) UNSIGNED NOT NULL,
  `optional_priority` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`op_id`),
  KEY `optional_priorities_subcat_id_foreign` (`subcat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `optional_priorities`
--

INSERT INTO `optional_priorities` (`op_id`, `subcat_id`, `optional_priority`, `created_at`, `updated_at`) VALUES
(1, 1, 'Incorporation of the Declaration on the Rights of Indigenous Peoples Act and Calls to Action of the Truth and Reconciliation Commission\r\n                                  <a href=\"http://trc.ca/assets/pdf/Calls_to_Action_English2.pdf\" target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> More \r\n                                  Information can be found here)</a>', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(2, 1, 'Align with CleanBC\'s plan to a protect our communities towards a more sustainable future\r\n                                   <a href=\"https://cleanbc.gov.bc.ca/\" target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> \r\n                                   More Information can be found here)</a>', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(3, 1, 'Advancing and supporting open learning resources', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(4, 1, 'Offer programming aligned with high opportunity and priority occupations (such as trades, technology, early childhood educators and health)', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(5, 1, 'Embed more co-op and work-integrated learning opportunities', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(6, 1, 'Respond to the reskilling needs of British Columbians to support employment and career transitions', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(7, 1, 'Supporting students\' awareness of career planning resources (such as the Labour Market Outlook)<a href=\"https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_Labour_Market_Outlook_2019.pdf.aspx\" \r\n                                target=\"_blank\">( <i class=\"bi bi-box-arrow-up-right\"></i> More Information can be found here)</a>', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(8, 2, 'Active Listening', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(9, 2, 'Reading Comprehension', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(10, 2, 'Critical Thinking', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(11, 2, 'Social Perceptiveness', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(12, 2, 'Judgement and Decision Making', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(13, 2, 'Writing', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(14, 2, 'Monitoring', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(15, 2, 'Complex Problem Solving', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(16, 2, 'Coordination', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(17, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-1-great-people/\" target=\"_blank\">Strategy 1: </a>\r\n                                  Great People: Attract, engage and retain a diverse global community of outstanding students, faculty and staff.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(18, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-2-inspiring-spaces/\" target=\"_blank\">Strategy 2: </a>\r\n                                  Inspiring Spaces: Create welcoming physical and virtual spaces to advance collaboration, innovation and community development.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(19, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-3-thriving-communities/\" target=\"_blank\">Strategy 3: </a>Thriving Communities: \r\n                                   Support the ongoing development of sustainable, healthy and connected campuses and communities, consistent with the 20-Year Sustainability \r\n                                   Strategy and the developing Wellbeing Strategy.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(20, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-4-inclusive-excellence/\" target=\"_blank\">Strategy 4: </a>\r\n                                  Inclusive Excellence: Cultivate a diverse community that creates and sustains equitable and inclusive campuses.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(21, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-5-systems-renewal/\" target=\"_blank\">Strategy 5: </a>Systems Renewal:\r\n                                  Transform university-level systems and processes to facilitate collaboration, innovation and agility.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(22, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-6-collaborative-clusters/\" target=\"_blank\">Strategy 6: </a>\r\n                                                    Collaborative Clusters: Enable interdisciplinary clusters of research excellence in pursuit of societal impact.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(23, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-7-research-support/\" target=\"_blank\">Strategy 7: </a>\r\n                                  Research Support: Strengthen shared infrastructure and resources to support research excellence.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(24, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-8-student-research/\" target=\"_blank\">Strategy 8: </a>\r\n                                  Student Research: Broaden access to, and enhance, student research experiences.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(25, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-9-knowledge-exchange/\" target=\"_blank\">Strategy 9: </a>\r\n                                  Knowledge Exchange: Improve the ecosystem that supports the translation of research into action.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(26, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-10-research-culture/\" target=\"_blank\">Strategy 10: </a>\r\n                                  Research Culture: Foster a strong and diverse research culture that embraces the highest standards of integrity, collegiality and service.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(27, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-11-education-renewal/\" target=\"_blank\">Strategy 11: </a>\r\n                                  Education Renewal: Facilitate sustained program renewal and improvements in teaching effectiveness.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(28, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-12-program-redesign/\" target=\"_blank\">Strategy 12: </a>\r\n                                  Program Redesign: Reframe undergraduate academic program design in terms of learning outcomes and competencies.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(29, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-13-practical-learning/\" target=\"_blank\">Strategy 13: </a>\r\n                                  Practical Learning: Expand experiential, work-integrated and extended learning opportunities for students, faculty, staff and alumni.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(30, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-14-interdisciplinary-education/\" target=\"_blank\">Strategy 14: </a>\r\n                                  Interdisciplinary Education: Facilitate the development of integrative, problem-focused learning.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(31, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-15-student-experience/\" target=\"_blank\">Strategy 15: </a>\r\n                                  Student Experience: Strengthen undergraduate and graduate student communities and experience.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(32, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-16-public-relevance/\" target=\"_blank\">Strategy 16: </a>\r\n                                  Public Relevance: Deepen the relevance and public impact of UBC research and education.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(33, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-17-indigenous-engagement/\" target=\"_blank\">Strategy 17: </a>\r\n                                  Indigenous Engagement: Support the objectives and actions of the renewed Indigenous Strategic Plan.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(34, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-18-alumni-engagement/\" target=\"_blank\">Strategy 18: </a>\r\n                                  Alumni Engagement: Reach, inspire and engage alumni through lifelong enrichment, consistent with the alumniUBC strategic plan,\r\n                                  <a href=\"https://www.alumni.ubc.ca/about/strategic-plan/\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> Connecting Forward.</a>', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(35, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-19-global-networks/\" target=\"_blank\">Strategy 19: </a>\r\n                                  Global Networks: Build and sustain strategic global networks, notably around the Pacific Rim, that enhance impact.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(36, 3, '<a href=\"https://strategicplan.ubc.ca/strategy-20-co-ordinated-engagement/\" target=\"_blank\">Strategy 20: </a>\r\n                                  Co-ordinated Engagement: Co-create with communities the principles and effective practices of engagement, and establish supporting infrastructure.', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(37, 4, 'Continuing education programs', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(38, 4, 'Offer hybrid pedagogies', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(39, 4, 'Professional programs in health and technology', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(40, 4, 'Increase graduate student training', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(41, 4, 'Leverage new academic and/or research space', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(42, 4, 'Increased community engagement', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(43, 5, 'Integration of Indigenous histories, experiences, worldviews and knowledge systems', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(44, 5, 'Inclusion of substantive content that explores histories and identifies how Indigenous issues intersect with the field of study', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(45, 5, 'Inclusion of Indigenous people for the development and offering of the curriculum', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(46, 5, 'Continue to partner with Indigenous communities locally and globally', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(47, 6, 'Climate justice education', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(48, 6, 'Climate research', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(49, 6, 'Content on Indigenous rights, content, history, and culture', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(50, 6, 'Environmental and sustainability education', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(51, 6, 'Content from Indigenous scholars and communities and/or equity-seeking and marginalized groups', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(52, 6, 'Inclusion of de-colonial approaches to science through Indigenous and community traditional knowledge and \"authorship\"', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(53, 6, 'Knowledge, awareness and skills related to the relationship between climate change and food systems', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(54, 6, 'Climate-related mental health content', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(55, 6, 'Applied learning opportunities grounded in the personal, local and regional community (e.g. flood and wildfire impacted communities in BC)', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(56, 7, 'test p', '2021-07-30 11:27:47', '2021-07-30 11:27:47');

-- --------------------------------------------------------

--
-- Table structure for table `optional_priority_categories`
--

DROP TABLE IF EXISTS `optional_priority_categories`;
CREATE TABLE IF NOT EXISTS `optional_priority_categories` (
  `cat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `optional_priority_categories`
--

INSERT INTO `optional_priority_categories` (`cat_id`, `cat_name`, `created_at`, `updated_at`) VALUES
(1, 'Ministry of Advanced Education and Skills Training', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(2, 'UBC Strategic Priorities', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(3, 'test catr', '2021-07-30 11:27:30', '2021-07-30 11:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `optional_priority_subcategories`
--

DROP TABLE IF EXISTS `optional_priority_subcategories`;
CREATE TABLE IF NOT EXISTS `optional_priority_subcategories` (
  `subcat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subcat_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cat_id` bigint(20) UNSIGNED NOT NULL,
  `subcat_desc` text COLLATE utf8mb4_unicode_ci,
  `subcat_postamble` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subcat_id`),
  KEY `optional_priority_subcategories_cat_id_foreign` (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `optional_priority_subcategories`
--

INSERT INTO `optional_priority_subcategories` (`subcat_id`, `subcat_name`, `cat_id`, `subcat_desc`, `subcat_postamble`, `created_at`, `updated_at`) VALUES
(1, 'UBC\'s Mandate by the Ministry', 1, 'UBC\'s mandate letter (see <a href=\"https://www2.gov.bc.ca/gov/content/education-training/post-secondary-education/institution-resources-\r\n                            administration/mandate-letters\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> mandate letter here </a>)\r\n                            calls for the below, as they relate to curriculum:', '', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(2, 'BC\'s Labour Market: Top skills in Demand', 1, 'BC\'s tops skills in demand,as forecasted to the year 2029 by the <a href=\"https://www.workbc.ca/getmedia/18214b5d-b338-4bbd-80bf-b04e48a11386/BC_\r\n                            Labour_Market_Outlook_2019.pdf.aspx\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> BC Labour Market Outlook (page 46)</a>\r\n                            , are the following:', '<p>Additionally, BC expects <a href=\"https://www.workbc.ca/Labour-Market-Industry/Jobs-in-Demand/High-Demand-Occupations.aspx\"target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> these occupations to be of \"High Opportunity\"</a> in the province. \r\n                                Does your course/program align with a High Opportunity Occupation in BC ?</p>\r\n                                <select id=\"highOpportunity\" class=\"highOpportunity\"><option value=\"1\">Yes</option> <option value=\"0\">No</option></select>', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(3, '<a href=\"https://strategicplan.ubc.ca/\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> Shaping UBCs next Century</a>', 2, '', '', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(4, '<a href=\"https://okmain.cms.ok.ubc.ca/wp-content/uploads/sites/26/2019/02/UBCO-Outlook-2040.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i>\r\n                                            UBC Okanagan 2040 Outlook</a>', 2, '', '', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(5, '<a href=\"https://aboriginal-2018.sites.olt.ubc.ca/files/2020/09/UBC.ISP_C2V13.1_Spreads_Sept1.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i>\r\n                                        UBC\'s Indigenous Strategic Plan (2020)</a>', 2, '', '', '2021-07-30 04:43:10', '2021-07-30 04:43:10'),
(6, '<a href=\"https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> UBC\'s Climate Priorities</a>', 2, 'The <a href=\"https://bog3.sites.olt.ubc.ca/files/2021/01/4_2021.02_Climate-Emergency-Engagement.pdf\" target=\"_blank\"><i class=\"bi bi-box-arrow-up-right\"></i> UBC\'s Climate Emergency Engagement Report and Recommendations (2021)</a> set out the below curricular examples.\r\n                                            Programs are encouraged to take these and/or other initiatives that align with the report:', NULL, '2021-07-30 04:43:10', '2021-07-30 11:09:51'),
(7, 'test subcat', 2, 'tttt', 'vvvv', '2021-07-30 11:27:19', '2021-07-30 11:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `outcome_activities`
--

DROP TABLE IF EXISTS `outcome_activities`;
CREATE TABLE IF NOT EXISTS `outcome_activities` (
  `l_outcome_id` bigint(20) UNSIGNED NOT NULL,
  `l_activity_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`l_outcome_id`,`l_activity_id`),
  KEY `outcome_activities_l_activity_id_foreign` (`l_activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outcome_activities`
--

INSERT INTO `outcome_activities` (`l_outcome_id`, `l_activity_id`, `created_at`, `updated_at`) VALUES
(3, 4, NULL, NULL),
(3, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `outcome_assessments`
--

DROP TABLE IF EXISTS `outcome_assessments`;
CREATE TABLE IF NOT EXISTS `outcome_assessments` (
  `l_outcome_id` bigint(20) UNSIGNED NOT NULL,
  `a_method_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`l_outcome_id`,`a_method_id`),
  KEY `outcome_assessments_a_method_id_foreign` (`a_method_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outcome_assessments`
--

INSERT INTO `outcome_assessments` (`l_outcome_id`, `a_method_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 1, '2021-06-30 08:46:02', '2021-06-30 08:46:02'),
(2, 2, '2021-06-30 08:46:02', '2021-06-30 08:46:02'),
(2, 3, '2021-06-30 08:46:02', '2021-06-30 08:46:02'),
(10, 19, NULL, NULL),
(10, 18, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `outcome_maps`
--

DROP TABLE IF EXISTS `outcome_maps`;
CREATE TABLE IF NOT EXISTS `outcome_maps` (
  `l_outcome_id` bigint(20) UNSIGNED NOT NULL,
  `pl_outcome_id` bigint(20) UNSIGNED NOT NULL,
  `map_scale_value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`l_outcome_id`,`pl_outcome_id`),
  KEY `outcome_maps_pl_outcome_id_foreign` (`pl_outcome_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outcome_maps`
--

INSERT INTO `outcome_maps` (`l_outcome_id`, `pl_outcome_id`, `map_scale_value`, `created_at`, `updated_at`) VALUES
(1, 6, '1', NULL, NULL),
(1, 5, '2', NULL, NULL),
(1, 4, '1', NULL, NULL),
(1, 3, '2', NULL, NULL),
(1, 2, '1', NULL, NULL),
(1, 1, '1', NULL, NULL),
(2, 1, '3', NULL, '2021-06-30 08:42:15'),
(2, 2, '2', NULL, '2021-06-30 08:42:15'),
(2, 3, '0', NULL, '2021-06-30 08:42:15'),
(2, 4, '0', NULL, '2021-06-30 08:42:15'),
(2, 5, '0', NULL, '2021-06-30 08:42:15'),
(2, 6, '0', NULL, '2021-06-30 08:42:15'),
(3, 12, '2', NULL, NULL),
(3, 11, '0', NULL, NULL),
(3, 10, '2', NULL, NULL),
(3, 9, '3', NULL, NULL),
(3, 8, '3', NULL, NULL),
(3, 7, '3', NULL, NULL),
(3, 6, '2', NULL, NULL),
(3, 5, '1', NULL, NULL),
(3, 4, '2', NULL, NULL),
(3, 3, '1', NULL, NULL),
(3, 2, '0', NULL, NULL),
(3, 1, '3', NULL, NULL),
(9, 6, 'I', NULL, '2021-07-27 00:04:33'),
(9, 5, 'D', NULL, '2021-07-27 00:04:33'),
(9, 4, 'I', NULL, '2021-07-27 00:04:33'),
(9, 3, 'I', NULL, '2021-07-27 00:04:33'),
(9, 2, 'D', NULL, '2021-07-27 00:04:33'),
(9, 1, 'I', NULL, '2021-07-27 00:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
CREATE TABLE IF NOT EXISTS `programs` (
  `program_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`program_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program`, `faculty`, `department`, `level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ministry of Advanced Education Standards - Undergraduate degrees', 'Other', 'Other', 'Undergraduate', 1, '2021-06-30 03:33:05', '2021-06-30 03:33:05'),
(2, 'Ministry of Advanced Education Standards - Masters\'s degrees', 'Other', 'Other', 'Graduate', 1, '2021-06-30 03:33:05', '2021-06-30 03:33:05'),
(3, 'Ministry of Advanced Education Standards - Doctoral degrees', 'Other', 'Other', 'Graduate', 1, '2021-06-30 03:33:05', '2021-06-30 03:33:05'),
(4, 'Test', 'School of Engineering', 'Computer Science, Mathematics, Physics and Statistics', 'Undergraduate', -1, '2021-07-20 09:50:06', '2021-07-20 09:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `program_learning_outcomes`
--

DROP TABLE IF EXISTS `program_learning_outcomes`;
CREATE TABLE IF NOT EXISTS `program_learning_outcomes` (
  `pl_outcome_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plo_shortphrase` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pl_outcome` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `plo_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pl_outcome_id`),
  KEY `program_learning_outcomes_program_id_foreign` (`program_id`),
  KEY `program_learning_outcomes_plo_category_id_foreign` (`plo_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_learning_outcomes`
--

INSERT INTO `program_learning_outcomes` (`pl_outcome_id`, `plo_shortphrase`, `pl_outcome`, `program_id`, `plo_category_id`, `created_at`, `updated_at`) VALUES
(1, 'Depth and Breadth of Knowledge', 'Basic understanding of the range of fields within the discipline/field. \r\n            Ability to gather, review, evaluate and interpret information, including new information relevant to the discipline.\r\n            Capacity to engage in independent research or practice in a supervised context.\r\n            Critical thinking/analytical skills.\r\n            Ability to apply learning from one or more areas outside the discipline', 1, NULL, NULL, NULL),
(2, 'Knowledge of Methodologies and Research ', 'Evaluate the appropriateness of different approaches to solving problems using well established ideas and techniques. \r\n                Devise and sustain arguments or solve problems using these methods. \r\n                Describe and comment upon particular aspects of current research or equivalent advanced scholarship in the discipline', 1, NULL, NULL, NULL),
(3, 'Communications Skills ', 'Ability to communicate information, arguments, and analyses accurately and reliably, orally and in writing, to a range of audiences. \r\n                Use structured and coherent arguments', 1, NULL, NULL, NULL),
(4, 'Application of Knowledge  ', 'Ability to review, present and critically evaluate qualitative and quantitative information to develop an argument, make sound judgement, apply concept, or use this knowledge in the creative process', 1, NULL, NULL, NULL),
(5, 'Awareness of Limits of Knowledge ', 'Understanding of the limits to their own knowledge and ability. \r\n                Appreciation of the uncertainty, ambiguity and limits to knowledge and how this might influence analyses and interpretations', 1, NULL, NULL, NULL),
(6, 'Professional Capacity/Autonomy ', 'Initiative, personal responsibility and accountability in both personal and group contexts. Working effectively with others. Behavior consistent with academic integrity ', 1, NULL, NULL, NULL),
(7, 'Depth and Breadth of Knowledge', 'Systematic understanding of knowledge, and a critical awareness of current problems and/or new insights, much of which is at, or informed by, the forefront of their academic discipline, field of study, or area of professional practice e', 2, NULL, NULL, NULL),
(8, 'Knowledge of Methodologies and Research ', 'Working comprehension of how established techniques of research and inquiry are used to create and interpret knowledge in the discipline.\r\n                Capacity to evaluate critically current research and advanced research and scholarship in the discipline or area of professional competence.\r\n                Capacity to address complex issues and judgments based on established principles and techniques. \r\n                Demonstrated ability to develop and support of a sustained argument in written form. Originality in the application of knowledge  ', 2, NULL, NULL, NULL),
(9, 'Communications Skills ', 'Ability to communicate ideas, issues and conclusions clearly and effectively to specialist and non-specialist audiences.', 2, NULL, NULL, NULL),
(10, 'Application of Knowledge  ', 'Competency in the research process by applying an existing body of knowledge in the research and critical analysis of a new question or of a specific problem or issue in a new setting ', 2, NULL, NULL, NULL),
(11, 'Awareness of Limits of Knowledge ', 'Cognizance of the complexity of knowledge and of the potential contributions of other interpretations, methods, and disciplines. ', 2, NULL, NULL, NULL),
(12, 'Professional Capacity/Autonomy ', 'Exercise of initiative and of personal responsibility and accountability. Decision-making in complex situations, such as employment. \r\n                Intellectual independence required for continuing professional development. Ability to appreciate the broader implications of applying knowledge to particular contexts', 2, NULL, NULL, NULL),
(13, 'Depth and Breadth of Knowledge', 'Thorough understanding of a substantial body of knowledge that is at the forefront of their academic discipline or area of professional practice.', 3, NULL, NULL, NULL),
(14, 'Knowledge of Methodologies and Research ', 'Conceptualize, design, and implement research for the generation of new knowledge, applications, or understanding at the forefront of the discipline, and to adjust the research design or methodology in the light of unforeseen problems. \r\n                    Make informed judgments on complex issues in specialist fields, sometimes requiring new methods.Produce original research, or other advanced scholarship, of a quality to satisfy peer review, and to merit publication. ', 3, NULL, NULL, NULL),
(15, 'Communications Skills ', 'Ability to communicate complex and/or ambiguous ideas, issues and conclusions clearly and effectively.', 3, NULL, NULL, NULL),
(16, 'Application of Knowledge  ', 'Capacity to undertake pure and/or applied research at an advanced level. \r\n                    Capacity to contribute to the development of academic or professional skill, techniques, tools, practices, ideas, theories, approaches, and/or materials.  ', 3, NULL, NULL, NULL),
(17, 'Awareness of Limits of Knowledge ', 'Appreciation of the limitations of one’s own work and discipline, of the complexity of knowledge, and of the potential contributions of other interpretations, methods, and disciplines. ', 3, NULL, NULL, NULL),
(18, 'Professional Capacity/Autonomy ', 'Exercise of personal responsibility and largely autonomous initiative in complex situations. \r\n                    Intellectual independence to be academically and professionally engaged and current. \r\n                    Ability to evaluate the broader implications of applying knowledge to particular contexts. ', 3, NULL, NULL, NULL),
(56, 'rrrrrrrr', 'dfg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(45, '2rertet', '2ertet', 4, 9, '2021-07-28 09:35:38', '2021-07-31 00:26:49'),
(46, '3etet', '3ertet', 4, 9, '2021-07-28 09:35:38', '2021-07-31 00:26:49'),
(48, '3esrs', 'serrrsr', 4, 9, '2021-07-28 12:07:01', '2021-07-31 00:26:49'),
(49, '4rrt', 'etet', 4, 9, '2021-07-28 12:11:18', '2021-07-31 00:26:49'),
(50, '4eet', 'dtdt', 4, 9, '2021-07-28 12:11:18', '2021-07-31 00:26:49'),
(51, 'sfsffs', 'test', 4, 9, '2021-07-30 11:22:34', '2021-07-31 00:26:49'),
(52, 'trtert', 'ertet', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(53, 'eet', 'eretet', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(54, 'ttttttt', 'dg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(55, 'rrrrrrrrr', 'dfg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(37, '1test ', '1tt', 4, 9, '2021-07-28 00:46:00', '2021-07-31 00:26:49'),
(57, 'tte', 'dg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(58, 'rte', 'dg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(59, 'ee', 'dfg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(60, 'ert', 'fgg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(61, 'gdg', 'cfh', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(62, 'drgdg', 'dr', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(63, 'rtt', 'dg', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(64, 'dgg', 'dgr', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49'),
(65, 'wewrdg', 'werwr', 4, 9, '2021-07-31 00:26:49', '2021-07-31 00:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `program_users`
--

DROP TABLE IF EXISTS `program_users`;
CREATE TABLE IF NOT EXISTS `program_users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`program_id`),
  KEY `program_users_program_id_foreign` (`program_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_users`
--

INSERT INTO `program_users` (`user_id`, `program_id`, `created_at`, `updated_at`) VALUES
(1, 4, '2021-07-20 09:50:06', '2021-07-20 09:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `p_l_o_categories`
--

DROP TABLE IF EXISTS `p_l_o_categories`;
CREATE TABLE IF NOT EXISTS `p_l_o_categories` (
  `plo_category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plo_category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`plo_category_id`),
  KEY `p_l_o_categories_program_id_foreign` (`program_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `p_l_o_categories`
--

INSERT INTO `p_l_o_categories` (`plo_category_id`, `plo_category`, `program_id`, `created_at`, `updated_at`) VALUES
(10, 'testing 2', 4, '2021-07-27 07:11:10', '2021-07-31 00:26:49'),
(9, 'testing', 4, '2021-07-27 07:11:10', '2021-07-31 00:26:49'),
(15, '', 3, NULL, NULL),
(16, 'rwrwrr4', 4, NULL, '2021-07-31 00:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'administrator', '2021-06-30 03:33:05', '2021-06-30 03:33:05'),
(2, 'user', '2021-06-30 03:33:05', '2021-06-30 03:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 1, NULL, NULL),
(2, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `standards`
--

DROP TABLE IF EXISTS `standards`;
CREATE TABLE IF NOT EXISTS `standards` (
  `standard_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `standard_category_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `s_shortphrase` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `s_outcome` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`standard_id`),
  KEY `standards_standard_category_id_foreign` (`standard_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `standards`
--

INSERT INTO `standards` (`standard_id`, `standard_category_id`, `s_shortphrase`, `s_outcome`, `created_at`, `updated_at`) VALUES
(1, 1, 'Depth and Breadth of Knowledge', 'Basic understanding of the range of fields within the discipline/field. \r\n            Ability to gather, review, evaluate and interpret information, including new information relevant to the discipline.\r\n            Capacity to engage in independent research or practice in a supervised context.\r\n            Critical thinking/analytical skills.\r\n            Ability to apply learning from one or more areas outside the discipline.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(2, 1, 'Knowledge of Methodologies and Research', 'Evaluate the appropriateness of different approaches to solving problems using well established ideas and techniques. \r\n        Devise and sustain arguments or solve problems using these methods. \r\n        Describe and comment upon particular aspects of current research or equivalent advanced scholarship in the discipline.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(3, 1, 'Communications Skills', 'Ability to communicate information, arguments, and analyses accurately and reliably, orally and in writing, to a range of audiences. \r\n        Use structured and coherent arguments.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(4, 1, 'Application of Knowledge', 'Ability to review, present and critically evaluate qualitative and quantitative information to develop an argument, make sound judgement, apply concept, or use this knowledge in the creative process.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(5, 1, 'Awareness of Limits of Knowledge', 'Understanding of the limits to their own knowledge and ability. \r\n        Appreciation of the uncertainty, ambiguity and limits to knowledge and how this might influence analyses and interpretations.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(6, 1, 'Professional Capacity/Autonomy', 'Initiative, personal responsibility and accountability in both personal and group contexts. Working effectively with others. Behavior consistent with academic integrity.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(7, 2, 'Depth and Breadth of Knowledge', 'Systematic understanding of knowledge, and a critical awareness of current problems and/or new insights, much of which is at, or informed by, the forefront of their academic discipline, field of study, or area of professional practice.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(8, 2, 'Knowledge of Methodologies and Research', 'Working comprehension of how established techniques of research and inquiry are used to create and interpret knowledge in the discipline.\r\n        Capacity to evaluate critically current research and advanced research and scholarship in the discipline or area of professional competence.\r\n        Capacity to address complex issues and judgments based on established principles and techniques. \r\n        Demonstrated ability to develop and support of a sustained argument in written form. Originality in the application of knowledge.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(9, 2, 'Communications Skills', 'Ability to communicate ideas, issues and conclusions clearly and effectively to specialist and non-specialist audiences.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(10, 2, 'Application of Knowledge', 'Competency in the research process by applying an existing body of knowledge in the research and critical analysis of a new question or of a specific problem or issue in a new setting.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(11, 2, 'Awareness of Limits of Knowledge', 'Cognizance of the complexity of knowledge and of the potential contributions of other interpretations, methods, and disciplines.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(12, 2, 'Professional Capacity/Autonomy', 'Exercise of initiative and of personal responsibility and accountability. Decision-making in complex situations, such as employment. \r\n        Intellectual independence required for continuing professional development. Ability to appreciate the broader implications of applying knowledge to particular contexts.', '2021-07-01 07:38:59', '2021-07-01 07:38:59'),
(13, 3, 'Depth and Breadth of Knowledge', 'Thorough understanding of a substantial body of knowledge that is at the forefront of their academic discipline or area of professional practice. test', '2021-07-01 07:38:59', '2021-07-24 00:24:52'),
(14, 3, 'Knowledge of Methodologies and Research', 'Conceptualize, design, and implement research for the generation of new knowledge, applications, or understanding at the forefront of the discipline, and to adjust the research design or methodology in the light of unforeseen problems. \n        Make informed judgments on complex issues in specialist fields, sometimes requiring new methods.Produce original research, or other advanced scholarship, of a quality to satisfy peer review, and to merit publication.', '2021-07-01 07:38:59', '2021-07-24 00:24:52'),
(15, 3, 'Communications Skills', 'Ability to communicate complex and/or ambiguous ideas, issues and conclusions clearly and effectively.', '2021-07-01 07:38:59', '2021-07-24 00:24:52'),
(16, 3, 'Application of Knowledge', 'Capacity to undertake pure and/or applied research at an advanced level. \n        Capacity to contribute to the development of academic or professional skill, techniques, tools, practices, ideas, theories, approaches, and/or materials.', '2021-07-01 07:38:59', '2021-07-24 00:24:52'),
(17, 3, 'Awareness of Limits of Knowledge', 'Appreciation of the limitations of one’s own work and discipline, of the complexity of knowledge, and of the potential contributions of other interpretations, methods, and disciplines.', '2021-07-01 07:38:59', '2021-07-24 00:24:52'),
(18, 3, 'Professional Capacity/Autonomy', 'Exercise of personal responsibility and largely autonomous initiative in complex situations. \n        Intellectual independence to be academically and professionally engaged and current. \n        Ability to evaluate the broader implications of applying knowledge to particular contexts.', '2021-07-01 07:38:59', '2021-07-24 00:24:52'),
(22, 3, 'test', 'eeee', '2021-07-24 00:24:52', '2021-07-24 00:24:52'),
(21, 4, 'test', 'eeee', '2021-07-24 00:05:29', '2021-07-25 09:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `standards_outcome_maps`
--

DROP TABLE IF EXISTS `standards_outcome_maps`;
CREATE TABLE IF NOT EXISTS `standards_outcome_maps` (
  `standard_id` bigint(20) UNSIGNED NOT NULL,
  `l_outcome_id` bigint(20) UNSIGNED NOT NULL,
  `map_scale_value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`standard_id`,`l_outcome_id`),
  KEY `standards_outcome_maps_l_outcome_id_foreign` (`l_outcome_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `standards_scale_categories`
--

DROP TABLE IF EXISTS `standards_scale_categories`;
CREATE TABLE IF NOT EXISTS `standards_scale_categories` (
  `scale_category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`scale_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `standards_scale_categories`
--

INSERT INTO `standards_scale_categories` (`scale_category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Default Scale Category', NULL, '2021-07-01 07:34:04', '2021-07-01 07:34:04'),
(2, 'Secondary Scale Category', 'testing', '2021-07-01 07:34:04', '2021-07-12 07:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `standard_categories`
--

DROP TABLE IF EXISTS `standard_categories`;
CREATE TABLE IF NOT EXISTS `standard_categories` (
  `standard_category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sc_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`standard_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `standard_categories`
--

INSERT INTO `standard_categories` (`standard_category_id`, `sc_name`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor\'s degree level standards', '2021-07-01 07:37:41', '2021-07-01 07:37:41'),
(2, 'Master\'s degree level standards', '2021-07-01 07:37:41', '2021-07-01 07:37:41'),
(3, 'Doctoral degree level standards', '2021-07-01 07:37:41', '2021-07-01 07:37:41'),
(4, 'test', '2021-07-24 00:05:14', '2021-07-24 00:05:14');

-- --------------------------------------------------------

--
-- Table structure for table `standard_scales`
--

DROP TABLE IF EXISTS `standard_scales`;
CREATE TABLE IF NOT EXISTS `standard_scales` (
  `standard_scale_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scale_category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `colour` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`standard_scale_id`),
  KEY `standard_scales_scale_category_id_foreign` (`scale_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `standard_scales`
--

INSERT INTO `standard_scales` (`standard_scale_id`, `scale_category_id`, `title`, `abbreviation`, `description`, `colour`, `created_at`, `updated_at`) VALUES
(54, 1, 'Introduced', 'I', 'Key ideas, concepts or skills related to the learning outcome are demonstrated at an introductory level. Learning activities focus on basic knowledge, skills, and/or competencies and entry-level complexity.', '#80BDFF', '2021-07-30 11:12:13', '2021-07-30 11:19:13'),
(49, 2, 'Principle', 'P', 'Makes a significant contribution to the degree', '#80BDFF', '2021-07-30 11:10:46', '2021-07-30 11:18:51'),
(52, 1, 'Developing', 'D', 'Learning outcome is reinforced with feedback; students demonstrate the outcome at an increasing level of proficiency. Learning activities concentrate on enhancing and strengthening existing knowledge and skills as well as expanding complexity.', '#1AA7FF', '2021-07-30 11:12:13', '2021-07-30 11:19:13'),
(53, 1, 'Advanced', 'A', 'Students demonstrate the learning outcomes with a high level of independence, expertise and sophistication expected upon graduation. Learning activities focus on and integrate the use of content or skills in multiple.', '#0065BD', '2021-07-30 11:12:13', '2021-07-30 11:19:13'),
(46, 2, 'Minor Contributor', 'Mi', 'Minor contribution towards the degree requirement', '#843976', '2021-07-30 11:10:46', '2021-07-30 11:18:51'),
(43, 2, 'Major Contributor', 'Ma', 'Major contribution towards the degree requirement', '#0065BD', '2021-07-14 03:12:28', '2021-07-30 11:18:51'),
(42, 2, 'Secondary', 'S', 'Contributes less significantly towards the degree requirements', '#1AA7FF', '2021-07-14 03:12:28', '2021-07-30 11:18:51');

-- --------------------------------------------------------

--
-- Table structure for table `syllabi`
--

DROP TABLE IF EXISTS `syllabi`;
CREATE TABLE IF NOT EXISTS `syllabi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_num` bigint(20) UNSIGNED DEFAULT NULL,
  `course_term` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_year` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `campus` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_instructor` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_hours` text COLLATE utf8mb4_unicode_ci,
  `class_meeting_days` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_instructional_staff` text COLLATE utf8mb4_unicode_ci,
  `class_start_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_end_time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learning_outcomes` text COLLATE utf8mb4_unicode_ci,
  `learning_assessments` text COLLATE utf8mb4_unicode_ci,
  `learning_activities` text COLLATE utf8mb4_unicode_ci,
  `late_policy` text COLLATE utf8mb4_unicode_ci,
  `missed_exam_policy` text COLLATE utf8mb4_unicode_ci,
  `missed_activity_policy` text COLLATE utf8mb4_unicode_ci,
  `passing_criteria` text COLLATE utf8mb4_unicode_ci,
  `learning_materials` text COLLATE utf8mb4_unicode_ci,
  `learning_resources` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `syllabi_resources_okanagan`
--

DROP TABLE IF EXISTS `syllabi_resources_okanagan`;
CREATE TABLE IF NOT EXISTS `syllabi_resources_okanagan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `o_syllabus_resource_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `syllabi_resources_okanagan_syllabus_id_foreign` (`syllabus_id`),
  KEY `syllabi_resources_okanagan_o_syllabus_resource_id_foreign` (`o_syllabus_resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `syllabi_resources_vancouver`
--

DROP TABLE IF EXISTS `syllabi_resources_vancouver`;
CREATE TABLE IF NOT EXISTS `syllabi_resources_vancouver` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `v_syllabus_resource_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `syllabi_resources_vancouver_syllabus_id_foreign` (`syllabus_id`),
  KEY `syllabi_resources_vancouver_v_syllabus_resource_id_foreign` (`v_syllabus_resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `syllabi_users`
--

DROP TABLE IF EXISTS `syllabi_users`;
CREATE TABLE IF NOT EXISTS `syllabi_users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `syllabi_users_user_id_foreign` (`user_id`),
  KEY `syllabi_users_syllabus_id_foreign` (`syllabus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Matt Admin', 'duromatt@telus.net', '2021-06-30 03:33:05', '$2y$10$I1LzC9IvWSjFXFufABHUi..PxtkVsoluYWORDVTegowRMbKjnjooe', NULL, '2021-06-30 03:33:05', '2021-06-30 03:33:05'),
(2, 'Matt User', 'le.deliverator@gmail.com', '2021-06-30 03:33:05', '$2y$10$1Y2tcFmLvhLR302DZXL1/.p766UqvtU8etKCDzyJPdw95lX7TN9/O', NULL, '2021-06-30 03:33:05', '2021-06-30 03:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `vancouver_syllabi`
--

DROP TABLE IF EXISTS `vancouver_syllabi`;
CREATE TABLE IF NOT EXISTS `vancouver_syllabi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `course_credit` bigint(20) UNSIGNED NOT NULL,
  `office_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_description` text COLLATE utf8mb4_unicode_ci,
  `course_contacts` text COLLATE utf8mb4_unicode_ci,
  `instructor_bio` text COLLATE utf8mb4_unicode_ci,
  `course_prereqs` text COLLATE utf8mb4_unicode_ci,
  `course_coreqs` text COLLATE utf8mb4_unicode_ci,
  `course_structure` text COLLATE utf8mb4_unicode_ci,
  `course_schedule` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vancouver_syllabi_syllabus_id_foreign` (`syllabus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vancouver_syllabus_resources`
--

DROP TABLE IF EXISTS `vancouver_syllabus_resources`;
CREATE TABLE IF NOT EXISTS `vancouver_syllabus_resources` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vancouver_syllabus_resources_id_name_unique` (`id_name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vancouver_syllabus_resources`
--

INSERT INTO `vancouver_syllabus_resources` (`id`, `id_name`, `title`, `created_at`, `updated_at`) VALUES
(1, 'land', 'Land Acknowledgement', NULL, NULL),
(2, 'academic', 'Academic Integrity Statement', NULL, NULL),
(3, 'disability', 'Accomodations for students with disabilities', NULL, NULL),
(4, 'copyright', '© Copyright Statement', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
