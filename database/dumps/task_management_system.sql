-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2026 at 07:00 PM
-- Server version: 8.0.46-0ubuntu0.24.04.3
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_31_204313_create_personal_access_tokens_table', 1),
(5, '2026_08_01_125245_create_projects_table', 1),
(6, '2026_08_01_133733_create_tasks_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Minima dicta dicta.', 'Deleniti maiores voluptatem voluptatibus suscipit delectus at. Ut porro dolorum et totam. Quia recusandae sint qui dolores fugit et eligendi. Ut cumque aspernatur sit non rerum.', 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(2, 1, 'Amet quas ratione earum.', 'Incidunt dolor iure ea rerum error iure. Dolor amet est enim totam consequatur ducimus temporibus. Consequuntur ullam et consequatur minima.', 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(3, 1, 'Molestias reiciendis voluptatem.', 'Explicabo voluptatem neque sapiente impedit ipsam. Eligendi minus voluptas ut. Nihil optio nihil ipsum voluptatum sunt et.', 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(4, 2, 'Dolorem adipisci iusto.', NULL, 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(5, 2, 'Sapiente debitis dicta blanditiis.', 'Ex sed cum eos et possimus sequi. Eum incidunt in dolorem natus ut consequatur. Vitae ullam harum et eos praesentium quia. Adipisci consectetur sint totam excepturi veritatis ullam itaque.', 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(6, 2, 'Molestias et et et.', NULL, 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(7, 3, 'Amet veniam.', NULL, 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(8, 3, 'Aut amet officiis voluptas.', NULL, 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(9, 3, 'Magnam ea doloribus.', 'Eos porro ea dolore rerum voluptatum consequatur molestiae. Repellat natus corrupti minus.', 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(10, 4, 'Dolor assumenda deleniti.', NULL, 'active', '2026-08-01 15:45:29', '2026-08-01 15:45:29', NULL),
(11, 4, 'Cum aut facere est omnis.', 'Non dolorem ad numquam. Rerum sint voluptates et iusto quas sint dolores. Perferendis a id quo quia.', 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(12, 4, 'Ad ea rem.', NULL, 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(13, 5, 'Sint magni optio.', 'At possimus quis in harum. Doloribus provident omnis et sit consectetur.', 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(14, 5, 'Delectus consequatur rerum.', NULL, 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(15, 5, 'Vel nam ducimus.', NULL, 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(16, 6, 'Consequuntur eos ipsum labore.', NULL, 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(17, 6, 'Qui enim.', NULL, 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(18, 6, 'Tenetur eos vel.', 'Non nisi ut voluptatibus voluptatibus dolor ut hic. Commodi culpa dicta sint. Id et praesentium itaque voluptatem dignissimos voluptatem.', 'active', '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'todo',
  `due_date` date DEFAULT NULL,
  `overdue_notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `title`, `description`, `priority`, `status`, `due_date`, `overdue_notified_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Facere facere pariatur sit.', NULL, 'medium', 'todo', '1983-07-08', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(2, 1, 'Minima sint ratione repudiandae.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(3, 1, 'Consequuntur iure dolores culpa fugit.', 'Aliquid aut consectetur non voluptas. Rerum et voluptatem laudantium.', 'medium', 'todo', '2012-12-15', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(4, 1, 'Rerum sit delectus architecto similique.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(5, 1, 'Provident at ullam assumenda.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(6, 2, 'Delectus distinctio veniam voluptatem.', NULL, 'medium', 'todo', '1981-06-01', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(7, 2, 'Harum cum aut dolores neque.', 'Ut illo pariatur aut tempora reiciendis asperiores natus ut. Qui veritatis ut consequatur corporis voluptatum sapiente commodi. Voluptas eum minima saepe in tenetur qui non.', 'medium', 'todo', '2018-10-03', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(8, 2, 'Voluptas nihil qui facilis.', NULL, 'medium', 'todo', '2013-04-26', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(9, 2, 'Facere et illum nostrum dolor eos.', 'Quod eum quo facilis quaerat iure. Distinctio beatae praesentium rerum sequi distinctio. Dolorem corrupti sequi consequatur non. Saepe voluptas sit blanditiis minus iste ea.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(10, 2, 'Repudiandae et velit animi.', NULL, 'medium', 'todo', '1996-05-08', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(11, 3, 'Autem sint officiis et.', NULL, 'medium', 'todo', '2004-12-01', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(12, 3, 'Aliquam est dolorem molestiae enim ut.', 'Officiis quo sed reiciendis aperiam ut quam ullam. Ut omnis qui totam. Laudantium deleniti recusandae ut voluptatem eius. Aut voluptates magnam veritatis molestiae velit.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(13, 3, 'Nobis quidem ea eos.', 'Dolor sit eos unde consequatur. Et quis hic nam odio ad vel tempore accusantium. Delectus sed ducimus sed officiis et odit reiciendis. Ad animi illum iste qui eligendi illo.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(14, 3, 'Vel earum accusamus aut sed.', NULL, 'medium', 'todo', '1985-10-26', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(15, 3, 'Tempora laborum reprehenderit ullam soluta.', 'Vero libero autem ex aperiam nostrum qui iusto. Et quam magni labore eveniet similique vitae voluptate optio. Et molestias animi non saepe nisi. Eius nulla qui excepturi dolore autem.', 'medium', 'todo', '1983-11-12', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(16, 4, 'Ut facilis enim.', 'Tenetur aspernatur aliquam in adipisci id debitis. Ipsum velit omnis ullam consequatur nostrum sapiente consequatur. Est harum distinctio et eum aut voluptatem quaerat.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(17, 4, 'Officiis natus qui eius sunt.', 'Quam dicta molestiae atque. Omnis id velit et modi qui mollitia. Harum ea natus aliquid et sed.', 'medium', 'todo', '1971-02-18', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(18, 4, 'Quidem rerum voluptas et.', 'Eos eos omnis accusamus hic magnam. Est incidunt at ut ea consequatur et eius. Perspiciatis qui rerum voluptates libero beatae labore in. Reiciendis placeat qui mollitia eos.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(19, 4, 'Nihil ullam deserunt tempora reiciendis.', 'Exercitationem ut nihil quis id maiores. Nihil voluptas dolor aut et deleniti ratione accusamus veritatis. Repellat reiciendis assumenda soluta animi velit. Enim debitis voluptates est est.', 'medium', 'todo', '2017-12-30', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(20, 4, 'Molestias a est laborum reprehenderit ut.', NULL, 'medium', 'todo', '2021-09-16', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(21, 5, 'Laborum molestiae reiciendis non ut.', NULL, 'medium', 'todo', '1972-07-23', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(22, 5, 'Assumenda aut fugiat est numquam quia.', 'Autem cum rerum et adipisci commodi odio. Vel sed enim reprehenderit quia. Ipsam eveniet accusamus ullam praesentium. Est aut a ipsa delectus placeat. Voluptatem ducimus recusandae in omnis commodi in voluptas.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(23, 5, 'Rerum odio error enim numquam.', 'Sequi inventore optio non amet hic voluptatem voluptatem at. Excepturi vitae ut magnam qui laudantium expedita. Molestias mollitia vero repellendus in et veritatis delectus. Ut aut natus rerum unde.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(24, 5, 'Temporibus unde tenetur exercitationem quia recusandae.', NULL, 'medium', 'todo', '2016-11-12', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(25, 5, 'Eius distinctio ea qui.', 'Quia aspernatur sed nihil debitis. Accusamus soluta incidunt porro dolorem vel laudantium at. Pariatur ut asperiores quam voluptatem est quos quis culpa.', 'medium', 'todo', '1977-05-17', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(26, 6, 'Error eos consequuntur mollitia.', NULL, 'medium', 'todo', '1985-08-30', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(27, 6, 'Animi eveniet facilis.', NULL, 'medium', 'todo', '1973-03-28', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(28, 6, 'Et est eius debitis totam est.', 'Hic maxime nihil praesentium perferendis unde nulla laborum. Consequatur voluptatem enim natus et delectus qui et. Sint autem voluptatem deserunt repellat ex ea. Ut et et facilis nostrum beatae recusandae impedit ipsa.', 'medium', 'todo', '2003-08-06', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(29, 6, 'Sunt consequatur asperiores.', NULL, 'medium', 'todo', '1977-06-09', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(30, 6, 'Minima similique animi vitae.', 'Inventore amet minus eum exercitationem labore autem accusantium. Et est enim eligendi aliquid dolorum aut. Aspernatur tempora minima ad sequi. Dolores magni quidem et aut.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(31, 7, 'In sunt natus officia inventore sit.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(32, 7, 'Ut nemo dolorem ad quam.', 'Perferendis nihil molestiae quos nulla adipisci. Sit saepe praesentium neque quaerat. Doloremque cumque libero non in odio.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(33, 7, 'Sit beatae rem exercitationem.', 'Voluptate voluptas accusamus animi molestiae vel quo excepturi. Repudiandae corporis soluta recusandae ut libero est et. Eum ut vel quibusdam nobis. Sapiente et dolorem iusto ut ratione esse voluptatum.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(34, 7, 'Dolores amet sequi culpa.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(35, 7, 'Distinctio eveniet non totam vel sint.', NULL, 'medium', 'todo', '2015-05-24', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(36, 8, 'Rerum quaerat velit asperiores aliquam.', 'Nisi natus blanditiis eum ut. Ut et aspernatur quia exercitationem perspiciatis magnam nihil. Eligendi voluptates optio excepturi quis voluptas explicabo et.', 'medium', 'todo', '2006-10-12', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(37, 8, 'Consectetur voluptates placeat sed.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(38, 8, 'Est nihil fugiat est exercitationem ullam.', NULL, 'medium', 'todo', '1986-01-17', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(39, 8, 'Sunt libero praesentium ad.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(40, 8, 'Vel laboriosam maxime.', 'Et ut molestiae aut. Dolore necessitatibus sunt aut blanditiis maxime qui aliquam. Quam ut voluptatem quidem ut enim. Numquam odit qui sit impedit.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(41, 9, 'Aut corrupti rerum sed ut.', 'Ut rerum exercitationem vel minus eum sunt. Est dolorem dolorem cumque facere deleniti. Earum tenetur animi error repellendus doloribus.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(42, 9, 'Ex reprehenderit ea ex totam iusto.', NULL, 'medium', 'todo', '2018-03-09', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(43, 9, 'Incidunt quis voluptatibus veritatis.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(44, 9, 'Corporis totam nobis ex aut.', NULL, 'medium', 'todo', '1987-04-15', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(45, 9, 'Ea unde necessitatibus rerum architecto porro.', 'Ut molestias in inventore repellat vero. Voluptatum eligendi est quasi veritatis deleniti. Cum eos dolor est non id. Dignissimos aut unde tempora rerum impedit impedit et.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(46, 10, 'A facilis alias maxime.', 'Quasi voluptate debitis quo dolor nam est modi expedita. Et ut voluptas sunt rerum ex eum praesentium. Provident earum omnis doloribus dolores mollitia sint eum. Aliquid neque tempora laboriosam quas quidem.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(47, 10, 'Est voluptate qui est non.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(48, 10, 'Cumque id voluptatem neque voluptas.', 'Natus voluptas quam consequatur at. Corporis error at velit rerum voluptatem hic. Doloribus aliquam dolores voluptatum nemo reprehenderit quia.', 'medium', 'todo', '2012-11-16', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(49, 10, 'Vel quis praesentium et dolorem.', 'Est temporibus voluptas vero ratione. Blanditiis dolores voluptate perspiciatis temporibus et. Quo ut fugit quos. Amet error quo iste.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(50, 10, 'Iure laborum aut ex eum molestiae.', 'Omnis exercitationem quidem dolor id molestiae quia. Soluta corporis sapiente exercitationem deleniti architecto. Sequi voluptas autem laborum occaecati numquam natus ea.', 'medium', 'todo', '1985-01-03', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(51, 11, 'Praesentium eum voluptatem numquam voluptatibus.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(52, 11, 'Sed aut nihil dolorem amet vel.', 'Quia ut omnis nostrum quisquam sed soluta. Qui est at sint rerum libero vel velit. A aspernatur quibusdam consequatur repellendus. Tempore earum adipisci numquam inventore. Consequuntur adipisci necessitatibus rerum voluptatem ratione reprehenderit ut.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(53, 11, 'Enim maxime in ratione ab.', NULL, 'medium', 'todo', '1987-07-22', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(54, 11, 'Odit optio omnis repellendus accusantium.', 'Dolor quidem id et consequuntur facilis. Aliquid est eum unde sequi nisi nesciunt ut sed. Sed corporis modi accusantium nisi nobis asperiores. Atque earum possimus quis aliquid rem.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(55, 11, 'Aut velit voluptas earum consequatur.', 'Quos aperiam qui ullam voluptatem eum aspernatur. Mollitia nostrum omnis vel quae et. Nulla consequatur inventore cumque dolorem molestiae ut.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(56, 12, 'Maiores est est rerum.', NULL, 'medium', 'todo', '2000-09-22', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(57, 12, 'Beatae modi unde eius.', 'Rerum et sunt accusantium pariatur sint. Reprehenderit officia voluptatem eius voluptas consequatur doloribus. Velit corrupti sit aspernatur quis perspiciatis sint. Culpa quia et et officia occaecati minima consequatur.', 'medium', 'todo', '1994-02-24', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(58, 12, 'Quidem minus et est consequatur.', 'Enim et molestiae atque iste. Corporis quis quisquam velit. Corporis corporis nisi facilis vel ratione nemo. Error quia sequi maiores et vel nihil numquam.', 'medium', 'todo', '2022-07-22', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(59, 12, 'Sed inventore rerum.', 'Possimus iste incidunt porro consectetur voluptates et mollitia. Cumque neque molestiae corporis assumenda magnam dicta alias soluta. Est ut labore quam non.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(60, 12, 'Dolor fuga nesciunt eveniet dolorem.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(61, 13, 'Perferendis rerum nulla magnam.', NULL, 'medium', 'todo', '1971-09-18', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(62, 13, 'Nulla quidem voluptas velit provident.', NULL, 'medium', 'todo', '2003-07-01', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(63, 13, 'Blanditiis adipisci magnam debitis quia.', 'Aut sunt quia qui error suscipit non. Perspiciatis totam laboriosam ut repellendus impedit explicabo quia et. Velit consectetur ratione recusandae commodi non rerum ea. Aperiam ipsa delectus omnis mollitia qui dolorem.', 'medium', 'todo', '2018-09-14', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(64, 13, 'Reiciendis sit sapiente necessitatibus.', NULL, 'medium', 'todo', '2005-01-23', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(65, 13, 'Sequi esse alias.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(66, 14, 'Ipsum magni architecto exercitationem.', 'Nisi quis cupiditate sunt voluptatibus temporibus laudantium incidunt. Odit tempora consectetur odit facilis vitae eum. Architecto atque incidunt velit perspiciatis ipsum. Saepe vero quia voluptate ab error.', 'medium', 'todo', '2003-01-01', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(67, 14, 'Ipsum incidunt facilis.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(68, 14, 'Earum non qui laboriosam aut.', NULL, 'medium', 'todo', '1994-03-27', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(69, 14, 'Rem nemo quae soluta facilis quis.', 'Eligendi tempore rerum corporis earum. Eum labore aut ipsam amet harum et qui. Minus magni voluptatem illo et voluptas.', 'medium', 'todo', '1972-03-21', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(70, 14, 'Eos aliquid deleniti sapiente.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(71, 15, 'Eum et ad pariatur sit.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(72, 15, 'Id molestiae est dolore libero.', NULL, 'medium', 'todo', '2003-10-22', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(73, 15, 'Iusto enim laboriosam dignissimos.', 'Qui unde adipisci et. Quia velit cumque exercitationem iusto.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(74, 15, 'A tempora libero commodi quo cupiditate.', NULL, 'medium', 'todo', '2021-04-16', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(75, 15, 'Fugiat ratione hic.', 'Sint illum earum culpa labore consectetur rerum sit. In voluptatibus distinctio distinctio voluptatem eos doloremque. Suscipit minus est consectetur rerum perferendis voluptatibus officia libero. Eius voluptatem corrupti ut neque eos.', 'medium', 'todo', '1979-03-23', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(76, 16, 'Quisquam repudiandae perferendis autem est.', 'Officiis repellat omnis saepe dolorem. Qui quae illo molestias sed. Et quia qui deserunt eaque excepturi provident quisquam. Explicabo non quis consequuntur tenetur sed sed. Est possimus voluptatibus totam est debitis sed.', 'medium', 'todo', '1984-01-18', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(77, 16, 'Quam veritatis aut voluptas.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(78, 16, 'Nulla doloribus qui molestiae.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(79, 16, 'Quia molestiae rerum officia pariatur.', 'Repellat eveniet iste asperiores rerum. Et illo explicabo at tenetur consequatur. Voluptatem soluta tenetur eius quidem enim rerum dicta. Quod laudantium ipsam repellendus.', 'medium', 'todo', '2015-09-15', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(80, 16, 'Rerum et odio harum.', 'Quis vel et expedita aliquid sit. Quis est voluptatem ut minus quia. Exercitationem autem excepturi aspernatur impedit sunt.', 'medium', 'todo', '1971-08-20', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(81, 17, 'Facilis voluptatibus quae eos.', 'Ut suscipit et qui eos sapiente voluptas qui. Adipisci maiores enim illum commodi autem. Odio reprehenderit beatae praesentium voluptatem in. Sapiente enim unde esse.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(82, 17, 'Eveniet eligendi iusto sit voluptatem rem.', NULL, 'medium', 'todo', '1990-06-10', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(83, 17, 'Ut tempore et.', 'Nihil dolores error eius deserunt repudiandae. Ipsam rerum in facilis qui enim quia ipsum. Ipsum illo ad nobis dolorem quo ullam. Suscipit unde laudantium eum ex.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(84, 17, 'Aliquam doloribus impedit quis.', NULL, 'medium', 'todo', '2024-01-31', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(85, 17, 'Soluta dicta nam.', NULL, 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(86, 18, 'Molestias in maxime dolor dignissimos porro.', NULL, 'medium', 'todo', '2002-09-23', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(87, 18, 'Officia voluptates quis.', 'Accusantium dignissimos vero assumenda deleniti laudantium ea ipsum. Minus quam corporis praesentium modi et rerum fugit.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(88, 18, 'Libero voluptatem accusantium odio.', 'Velit inventore vitae est similique hic ex optio. Explicabo inventore occaecati voluptate. Cumque neque beatae ratione dolorum tempore sunt eaque. Fugit aut voluptatum ut dicta praesentium saepe aut delectus.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(89, 18, 'Blanditiis minima deleniti.', 'Nobis vel iste inventore vel. Dolore et fugiat ut cumque illum vel. Et neque dolor facilis eos et hic. Iste corrupti eum laboriosam qui quo dolor.', 'medium', 'todo', '2025-10-26', NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL),
(90, 18, 'Consequuntur et repellat autem sed.', 'Adipisci consequuntur officiis molestiae inventore ullam. Autem inventore voluptatum tempora quae nihil aut. Aut et magnam dolore animi eos quo. Fugiat laborum nulla nostrum quaerat et earum. Dolore a culpa hic sequi ad dolores tempora.', 'medium', 'todo', NULL, NULL, '2026-08-01 15:45:30', '2026-08-01 15:45:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-08-01 15:45:29', '$2y$12$btFY5bsd6OBpN9/ODuED8eVRDATBpenV7YCP8Q4Bl07MRsOYZxH2K', 'LZN5oYfGRt', '2026-08-01 15:45:29', '2026-08-01 15:45:29'),
(2, 'Neoma Jaskolski', 'gilberto.dooley@example.net', '2026-08-01 15:45:29', '$2y$12$btFY5bsd6OBpN9/ODuED8eVRDATBpenV7YCP8Q4Bl07MRsOYZxH2K', 'x7m2b2A431', '2026-08-01 15:45:29', '2026-08-01 15:45:29'),
(3, 'Joanny Cummings', 'elyssa57@example.com', '2026-08-01 15:45:29', '$2y$12$btFY5bsd6OBpN9/ODuED8eVRDATBpenV7YCP8Q4Bl07MRsOYZxH2K', 'ewBvyGxRhs', '2026-08-01 15:45:29', '2026-08-01 15:45:29'),
(4, 'Marguerite Waelchi Sr.', 'demmerich@example.com', '2026-08-01 15:45:29', '$2y$12$btFY5bsd6OBpN9/ODuED8eVRDATBpenV7YCP8Q4Bl07MRsOYZxH2K', '793TsiuOwa', '2026-08-01 15:45:29', '2026-08-01 15:45:29'),
(5, 'Favian Funk', 'oschimmel@example.org', '2026-08-01 15:45:29', '$2y$12$btFY5bsd6OBpN9/ODuED8eVRDATBpenV7YCP8Q4Bl07MRsOYZxH2K', 'sHbpOOPlSk', '2026-08-01 15:45:29', '2026-08-01 15:45:29'),
(6, 'Roberto Pouros', 'holly68@example.com', '2026-08-01 15:45:29', '$2y$12$btFY5bsd6OBpN9/ODuED8eVRDATBpenV7YCP8Q4Bl07MRsOYZxH2K', 'xUHEtJT7ig', '2026-08-01 15:45:29', '2026-08-01 15:45:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_user_id_foreign` (`user_id`),
  ADD KEY `projects_status_index` (`status`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_priority_index` (`priority`),
  ADD KEY `tasks_status_index` (`status`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
