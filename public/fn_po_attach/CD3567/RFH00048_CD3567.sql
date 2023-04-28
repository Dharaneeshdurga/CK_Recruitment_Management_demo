-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2022 at 09:57 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ck_recruitment_management_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidate_preonboarding`
--

CREATE TABLE `candidate_preonboarding` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `emp_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recruiter_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preonboarding_process` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidate_preonboarding`
--

INSERT INTO `candidate_preonboarding` (`id`, `emp_id`, `recruiter_id`, `preonboarding_process`, `type`, `date`, `created_at`, `updated_at`) VALUES
(1, 'CD1', '900057', 'Hr ops', 1, '2022-03-23', NULL, '2022-03-28 06:56:48'),
(2, 'CD1', '900057', 'Supervisor', 1, '2022-03-31', NULL, '2022-03-28 06:56:48'),
(3, 'CD1', '900057', 'Buddy', 1, '2022-03-19', NULL, '2022-03-28 06:56:48'),
(25, 'CD2', '900042', 'Hr ops', 0, '2022-03-26', '2022-03-26 05:23:14', '2022-03-28 04:13:31'),
(26, 'CD2', '900042', 'Supervisor', 1, '2022-03-26', '2022-03-26 05:23:14', '2022-03-28 04:13:31'),
(27, 'CD2', '900042', 'Buddy', 0, '2022-03-26', '2022-03-26 05:23:14', '2022-03-28 04:13:31'),
(28, 'CD3', '900042', 'Hr ops', 1, '2022-04-04', '2022-04-04 07:59:17', '2022-04-04 07:59:17'),
(29, 'CD3', '900042', 'Supervisor', 1, '2022-04-04', '2022-04-04 07:59:17', '2022-04-04 07:59:17'),
(30, 'CD3', '900042', 'Buddy', 0, NULL, '2022-04-04 07:59:17', '2022-04-04 07:59:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidate_preonboarding`
--
ALTER TABLE `candidate_preonboarding`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidate_preonboarding`
--
ALTER TABLE `candidate_preonboarding`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
