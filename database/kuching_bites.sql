-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 04:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kuching_bites`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_table`
--

CREATE TABLE `account_table` (
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_table`
--

INSERT INTO `account_table` (`email`, `password`, `type`) VALUES
('admin@swin.edu.my', '$2y$10$zLgp4IV/dK5W7xy6HUuaje4cDTXSSInlQYqqAaAgrrUs6s1eU.5f.', 'admin'),
('ainsley@example.com', '$2y$10$ALasCa93Q.f99CRNwUUmX.HM2lT33VXRcXreBeHciAEhr.kyPXPQO', 'user'),
('daniel@example.com', '$2y$10$kZPbQkLhNc4o/pz3GkHyc.xpvcI6awvMsRttjcNYdqfumQ2nkennW', 'user'),
('james@example.com', '$2y$10$oqLEcB4pgN/Wq/ofkMGu8OS8zh6s9YAjYI9cFs6zlm0fv4LoECOFq', 'user'),
('maikuru@example.com', '$2y$10$zfR61IFPwvvZCyacGzCN6uQCEd8gpwMYoZGRSF2A.EpLwKWn2WwoK', 'user'),
('sarah@example.com', '$2y$10$nhr3szbDCkaPbbwRXLCNXOwWaqUrmN7Jd4kBX0P.j6sKJFPvQawWG', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `activity_table`
--

CREATE TABLE `activity_table` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_table`
--

INSERT INTO `activity_table` (`id`, `email`, `first_name`, `last_name`, `date`, `time`, `contact_number`, `status`) VALUES
(1, 'james@example.com', 'James', 'Tan', '2026-11-15', '10:00:00', '0123456789', 'approved'),
(2, 'sarah@example.com', 'Sarah', 'Lim', '2026-11-20', '14:00:00', '0123456788', 'pending'),
(3, 'daniel@example.com', 'Daniel', 'Lee', '2026-11-25', '09:00:00', '0123456787', 'approved'),
(4, 'ainsley@example.com', 'Ainsley', 'Sim', '2026-12-01', '15:00:00', '0123456786', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `community_table`
--

CREATE TABLE `community_table` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `contribution_image` varchar(100) DEFAULT NULL,
  `related_activity` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_table`
--

INSERT INTO `community_table` (`id`, `first_name`, `last_name`, `contribution_image`, `related_activity`, `status`) VALUES
(1, 'James', 'Tan', 'image/food1.jpg', 'Sarawak Food Festival', 'approved'),
(2, 'Sarah', 'Lim', 'image/food2.jpg', 'Traditional Cooking Workshop', 'approved'),
(3, 'Daniel', 'Lee', 'image/food3.jpg', 'Kuching Food Photography', 'pending'),
(4, 'Ainsley', 'Sim', 'image/food4.jpg', 'Local Food Sharing', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `smart_feature_table`
--

CREATE TABLE `smart_feature_table` (
  `id` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_category` varchar(50) NOT NULL,
  `item_image` varchar(100) DEFAULT NULL,
  `description_file` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smart_feature_table`
--

INSERT INTO `smart_feature_table` (`id`, `item_name`, `item_category`, `item_image`, `description_file`, `status`) VALUES
(1, 'Sarawak Laksa', 'Food', NULL, NULL, 'approved'),
(2, 'Kolo Mee', 'Food', NULL, NULL, 'approved'),
(3, 'Kek Lapis', 'Dessert', NULL, NULL, 'pending'),
(4, 'Umai', 'Food', NULL, NULL, 'approved'),
(5, 'Sarawak Laksa', 'Food', 'smart_feature_images/ad2fd9265ed5bc63f3898f61345ffd2e.jpg', 'smart_feature_description/8f70bb2b4862a08b646cc8fe53ee3d25.pdf', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `email` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(6) NOT NULL,
  `hometown` varchar(50) NOT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `supporting_document` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`email`, `first_name`, `last_name`, `dob`, `gender`, `hometown`, `profile_image`, `supporting_document`) VALUES
('admin@swin.edu.my', 'Admin', 'Kuching', NULL, 'Male', 'Kuching', NULL, NULL),
('ainsley@example.com', 'Ainsley', 'Sim', '2002-11-30', 'Female', 'Kuching', NULL, NULL),
('daniel@example.com', 'Daniel', 'Lee', '2003-01-15', 'Male', 'Sibu', NULL, NULL),
('james@example.com', 'Jessica', 'Pangestika', '2003-05-12', 'Female', 'Kuching', 'profile_images/90e6297b531ead0d682019d9b2ca3336.jpg', 'resume/2b4570917ffde64b3e7bed67e805c9dc.pdf'),
('maikuru@example.com', 'Michael', 'Tan', '2003-01-14', 'Male', 'Fuzhou', 'profile_images/c89046dab6db7da464cda7ff83a9c84c.jpg', 'resume/b96d4e7a12ca09cc915eec8e1b1b73ec.pdf'),
('sarah@example.com', 'Sarah', 'Lim', '2002-08-20', 'Female', 'Miri', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_table`
--
ALTER TABLE `account_table`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `activity_table`
--
ALTER TABLE `activity_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_table`
--
ALTER TABLE `community_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smart_feature_table`
--
ALTER TABLE `smart_feature_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_table`
--
ALTER TABLE `activity_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `community_table`
--
ALTER TABLE `community_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `smart_feature_table`
--
ALTER TABLE `smart_feature_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_table`
--
ALTER TABLE `account_table`
  ADD CONSTRAINT `account_table_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user_table` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
