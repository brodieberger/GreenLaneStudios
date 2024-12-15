-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 15, 2024 at 04:39 PM
-- Server version: 10.5.25-MariaDB-cll-lve
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `berger_marina`
--

-- --------------------------------------------------------

--
-- Table structure for table `boats`
--

CREATE TABLE `boats` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `size` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `boats`
--

INSERT INTO `boats` (`id`, `name`, `size`, `user_id`) VALUES
(4, 'Boat of the seas', 'Class1', 1),
(5, 'Cool Boat', 'Class3', 3),
(6, 'Awesome Boat', 'Class2', 4),
(10, 'Other Boat', 'Class3', 3),
(11, 'super awesome boat', 'Class3', 3);

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `incident_date` date NOT NULL,
  `incident_type` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `incident_date`, `incident_type`, `description`) VALUES
(3, '2024-11-13', 'Other', 'Dog stole boat from Lot A.'),
(5, '2024-10-31', 'Damage to Boat', 'Staff member dinked a booey, minimal scratches on starboard side.'),
(6, '2024-12-10', 'Injury', 'Staff member slipped off dock and hurt ankle.');

-- --------------------------------------------------------

--
-- Table structure for table `inspection`
--

CREATE TABLE `inspection` (
  `inspection_id` int(11) NOT NULL,
  `inspection_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `boat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` varchar(50) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `quantity`, `price`) VALUES
('1', 'Bait', 24, 4.00);

-- --------------------------------------------------------

--
-- Table structure for table `lots`
--

CREATE TABLE `lots` (
  `id` int(11) NOT NULL,
  `space_number` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available` tinyint(1) DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lot_requested` varchar(11) NOT NULL,
  `date_requested` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `email`, `lot_requested`, `date_requested`) VALUES
(1, 'brodieberger@gmail.com', 'Lot A', '2024-11-19'),
(3, 'brodie@gmail.com', 'Lot B', '2024-11-19'),
(4, 'brodie2@gmail.com', 'Lot A', '2024-11-19'),
(5, 'brodie2@gmail.com', 'Lot D', '2024-11-19'),
(6, 'noahcook15@gmail.com', 'Lot D', '2024-11-22'),
(7, 'duce379@gmail.com', 'Lot B', '2024-11-22'),
(8, 'hirparah@kean.edu', 'Lot B', '2024-11-26'),
(9, 'brodie2@gmail.com', 'Lot B', '2024-11-27'),
(10, 'brodie2@gmail.com', 'Lot B', '2024-11-27'),
(11, 'joshfigs10@yahoo.com', 'Lot B', '2024-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `price`) VALUES
(1, 'Ramp Access', 200.00),
(2, 'Boat Cleaning', 55.00),
(3, 'Fuel Service', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `spots`
--

CREATE TABLE `spots` (
  `id` int(11) NOT NULL,
  `lot_name` varchar(50) NOT NULL,
  `boat_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `is_occupied` tinyint(1) DEFAULT 0,
  `occupied_until` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spots`
--

INSERT INTO `spots` (`id`, `lot_name`, `boat_id`, `owner_id`, `is_occupied`, `occupied_until`) VALUES
(1, 'Lot A', 5, 3, 1, '2024-11-30'),
(2, 'Lot A', 6, 4, 1, NULL),
(3, 'Lot A', 11, 3, 1, NULL),
(4, 'Lot A', 4, 1, 1, NULL),
(5, 'Lot A', NULL, NULL, 0, NULL),
(6, 'Lot A', NULL, NULL, 0, NULL),
(7, 'Lot A', NULL, NULL, 0, NULL),
(8, 'Lot A', NULL, NULL, 0, NULL),
(9, 'Lot A', NULL, NULL, 0, NULL),
(10, 'Lot A', 5, 3, 1, NULL),
(11, 'Lot B', NULL, NULL, 0, NULL),
(12, 'Lot B', NULL, NULL, 0, NULL),
(13, 'Lot B', NULL, NULL, 0, NULL),
(14, 'Lot B', NULL, NULL, 0, NULL),
(15, 'Lot B', NULL, NULL, 0, NULL),
(16, 'Lot B', NULL, NULL, 0, NULL),
(17, 'Lot B', NULL, NULL, 0, NULL),
(18, 'Lot B', NULL, NULL, 0, NULL),
(19, 'Lot B', NULL, NULL, 0, NULL),
(20, 'Lot B', NULL, NULL, 0, NULL),
(21, 'Lot C', NULL, NULL, 0, NULL),
(22, 'Lot C', NULL, NULL, 0, NULL),
(23, 'Lot C', NULL, NULL, 0, NULL),
(24, 'Lot C', NULL, NULL, 0, NULL),
(25, 'Lot C', NULL, NULL, 0, NULL),
(26, 'Lot C', NULL, NULL, 0, NULL),
(27, 'Lot C', NULL, NULL, 0, NULL),
(28, 'Lot C', NULL, NULL, 0, NULL),
(29, 'Lot C', NULL, NULL, 0, NULL),
(30, 'Lot C', NULL, NULL, 0, NULL),
(31, 'Lot D', NULL, NULL, 0, NULL),
(32, 'Lot D', NULL, NULL, 0, NULL),
(33, 'Lot D', NULL, NULL, 0, NULL),
(34, 'Lot D', NULL, NULL, 0, NULL),
(35, 'Lot D', NULL, NULL, 0, NULL),
(36, 'Lot D', NULL, NULL, 0, NULL),
(37, 'Lot D', NULL, NULL, 0, NULL),
(38, 'Lot D', NULL, NULL, 0, NULL),
(39, 'Lot D', NULL, NULL, 0, NULL),
(40, 'Lot D', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Whether the space is open or not',
  `occupiedby` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `open`, `occupiedby`) VALUES
(1, 0, 'Brodie'),
(3, 1, NULL),
(4, 1, NULL),
(8, 0, 'Some Dude');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_employee` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Not Employee\r\n1 = Employee',
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `is_employee`, `password`, `created_at`) VALUES
(1, 'brodie@gmail.com', 'Brodie', 1, '$2y$10$UQvyPUrbXKVsPo/Cvj6fZ.WznDS9ABa0l2UVkeaqw7COBQVI0x17a', '2024-11-17 00:47:09'),
(3, 'brodie2@gmail.com', 'Brodie2', 0, '$2y$10$m32V2PwzvyYLbcUnmqgSrOwuUaS5/M5SUoKVyoQv4Zw.F7AKVz.C.', '2024-11-19 20:22:21'),
(4, 'Karen@gmail.com', 'Karen', 0, '$2y$10$mqzzOuz8RHGfi/csGOMOv.Xi8vmYzjjrYUQlP7xsbcIMoPp9gRoJ.', '2024-11-19 20:59:10'),
(5, 'John@gmail.com', 'John', 0, '$2y$10$18rStQLPJm4KVViwVJd2DuKkJX89HaYH0IfPkk3qBRhHGUF.0espi', '2024-11-19 20:59:24'),
(6, 'brodie3@gmail.com', 'brodie3', 0, '$2y$10$s9vEqLPHvU/xOCLFP0ZZy.i4gzKxx8eXClpXNAnXYMDESLjnBXYPW', '2024-11-21 15:42:45'),
(7, 'noahcook15@gmail.com', 'Noah', 0, '$2y$10$pMK6DQPW/PXnwx9bgDmSHOfUPjteudvbh5G0asTP.FSkO4rKOX0GC', '2024-11-22 19:28:35'),
(8, 'duce379@gmail.com', 'Noah', 0, '$2y$10$JSGV67cTb.P6AEBec3PqsuJC/Sl1BB54qYMm5EOfxjsHupIrVIBIK', '2024-11-22 20:08:29'),
(9, 'hirparah@kean.edu', 'harsh', 0, '$2y$10$QO4CoRwDWvFiDOUULiDbh.qV079rE1ZlpJqP6gDn.baYbuELOheOW', '2024-11-26 15:36:21'),
(10, 'amatthew@kean.edu', 'Matthew', 0, '$2y$10$ywBoaH7.ucQd8jcyLXgLquOO5ZQzejTcTcaRxvn9uKji/JuwA4XYS', '2024-11-26 15:37:45'),
(11, 'testcase@e', 'testcase', 0, '$2y$10$VlQ4t38o3kBz9MtnZGOD.u0Ln7LtahHSI5iIkAMJeZR9J.w0pWZzu', '2024-11-27 00:42:22'),
(13, 'joshfigs10@yahoo.com', 'Joshua Figueroa', 0, '$2y$10$aZ7HFQzgGKw6cY6JDnn6leCGh/mA6wLu7Zd4K8VFrg2X2yWEg1eNq', '2024-12-04 20:08:14');

-- --------------------------------------------------------

--
-- Table structure for table `waitlist`
--

CREATE TABLE `waitlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `spot_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boats`
--
ALTER TABLE `boats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inspection`
--
ALTER TABLE `inspection`
  ADD PRIMARY KEY (`inspection_id`),
  ADD KEY `boat_id` (`boat_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `spots`
--
ALTER TABLE `spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boat_id` (`boat_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `spot_id` (`spot_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boats`
--
ALTER TABLE `boats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inspection`
--
ALTER TABLE `inspection`
  MODIFY `inspection_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lots`
--
ALTER TABLE `lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spots`
--
ALTER TABLE `spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `waitlist`
--
ALTER TABLE `waitlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boats`
--
ALTER TABLE `boats`
  ADD CONSTRAINT `boats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inspection`
--
ALTER TABLE `inspection`
  ADD CONSTRAINT `inspection_ibfk_1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`id`);

--
-- Constraints for table `spots`
--
ALTER TABLE `spots`
  ADD CONSTRAINT `spots_ibfk_1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `spots_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD CONSTRAINT `waitlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `waitlist_ibfk_2` FOREIGN KEY (`spot_id`) REFERENCES `spots` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
