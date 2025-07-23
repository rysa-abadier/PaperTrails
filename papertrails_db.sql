-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 23, 2025 at 03:15 PM
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
-- Database: `papertrails_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `name`) VALUES
(1, 'Cash'),
(2, 'E-Wallet'),
(3, 'Debit'),
(4, 'Crebit'),
(5, 'Investment');

-- --------------------------------------------------------

--
-- Table structure for table `budget_log`
--

CREATE TABLE `budget_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `budget_expense` varchar(255) NOT NULL,
  `expensetype_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `frequency_id` int(11) NOT NULL,
  `update_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_log`
--

INSERT INTO `budget_log` (`id`, `user_id`, `amount`, `budget_expense`, `expensetype_id`, `source_id`, `frequency_id`, `update_date`) VALUES
(1, 1, 179.00, 'Data', 7, 1, 3, '2025-07-23');

-- --------------------------------------------------------

--
-- Table structure for table `dailyexpense_log`
--

CREATE TABLE `dailyexpense_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense` varchar(255) DEFAULT NULL,
  `source_id` int(11) NOT NULL,
  `expensetype_id` int(11) NOT NULL,
  `expense_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dailyexpense_log`
--

INSERT INTO `dailyexpense_log` (`id`, `user_id`, `amount`, `expense`, `source_id`, `expensetype_id`, `expense_date`) VALUES
(1, 1, 179.00, 'Data', 1, 7, '2025-07-23');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`) VALUES
(1, 'Living Expenses'),
(2, 'Transportation'),
(3, 'Personal Care'),
(4, 'Family Care'),
(5, 'Debt Payments'),
(6, 'Healthcare'),
(7, 'Technology'),
(8, 'Savings and Investments');

-- --------------------------------------------------------

--
-- Table structure for table `frequency`
--

CREATE TABLE `frequency` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `frequency`
--

INSERT INTO `frequency` (`id`, `name`) VALUES
(1, 'Daily'),
(2, 'Weekly'),
(3, 'Biweekly'),
(4, 'Monthly'),
(5, 'Quarterly'),
(6, 'Annually');

-- --------------------------------------------------------

--
-- Table structure for table `income_log`
--

CREATE TABLE `income_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `modified_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income_log`
--

INSERT INTO `income_log` (`id`, `user_id`, `name`, `amount`, `asset_id`, `modified_date`) VALUES
(1, 1, 'GCash', 300.00, 2, '2025-07-23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 'Rysa', 'Abadier', 'rysa.abadier@ciit.edu.ph', '$2y$10$Wzr8z72Ff8cZJCDrgcvtSOPvfMze9Ucpk76kQLrvLKzCLvKvlDqGK'),
(2, 'Noah', 'Peñaranda', 'noah.penaranda@ciit.edu.ph', '$2y$10$NtEgYbyYDF1q4HEr8IelteMmA9nGdf5rsXLEZcFoT1svsyqn/CLOG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_log`
--
ALTER TABLE `budget_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expensetype_id` (`expensetype_id`),
  ADD KEY `frequency_id` (`frequency_id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `dailyexpense_log`
--
ALTER TABLE `dailyexpense_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expensetype_id` (`expensetype_id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frequency`
--
ALTER TABLE `frequency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_log`
--
ALTER TABLE `income_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `budget_log`
--
ALTER TABLE `budget_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dailyexpense_log`
--
ALTER TABLE `dailyexpense_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `frequency`
--
ALTER TABLE `frequency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `income_log`
--
ALTER TABLE `income_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget_log`
--
ALTER TABLE `budget_log`
  ADD CONSTRAINT `budget_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `budget_log_ibfk_2` FOREIGN KEY (`expensetype_id`) REFERENCES `expenses` (`id`),
  ADD CONSTRAINT `budget_log_ibfk_3` FOREIGN KEY (`source_id`) REFERENCES `assets` (`id`),
  ADD CONSTRAINT `budget_log_ibfk_4` FOREIGN KEY (`frequency_id`) REFERENCES `frequency` (`id`),
  ADD CONSTRAINT `budget_log_ibfk_5` FOREIGN KEY (`source_id`) REFERENCES `income_log` (`id`);

--
-- Constraints for table `dailyexpense_log`
--
ALTER TABLE `dailyexpense_log`
  ADD CONSTRAINT `dailyexpense_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dailyexpense_log_ibfk_2` FOREIGN KEY (`source_id`) REFERENCES `assets` (`id`),
  ADD CONSTRAINT `dailyexpense_log_ibfk_3` FOREIGN KEY (`expensetype_id`) REFERENCES `expenses` (`id`),
  ADD CONSTRAINT `dailyexpense_log_ibfk_4` FOREIGN KEY (`source_id`) REFERENCES `assets` (`id`),
  ADD CONSTRAINT `dailyexpense_log_ibfk_5` FOREIGN KEY (`source_id`) REFERENCES `income_log` (`id`);

--
-- Constraints for table `income_log`
--
ALTER TABLE `income_log`
  ADD CONSTRAINT `income_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `income_log_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
