-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 10, 2026 at 01:34 PM
-- Server version: 8.0.45-0ubuntu0.22.04.1
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `training`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `acc_id` int NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `user_id` int NOT NULL,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`acc_id`, `balance`, `user_id`, `update_at`) VALUES
(48, '202.00', 9, '2026-03-10 12:21:40'),
(49, '235.00', 10, '2026-03-10 08:36:27');

--
-- Triggers `accounts`
--
DELIMITER $$
CREATE TRIGGER `user_balance_update` BEFORE UPDATE ON `accounts` FOR EACH ROW BEGIN
    SET NEW.update_at = CURRENT_TIMESTAMP;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `audit_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `action` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `user_id`) VALUES
(1, 1, 9),
(2, 2, 9);

-- --------------------------------------------------------

--
-- Stand-in structure for view `order_detail`
-- (See below for the actual view)
--
CREATE TABLE `order_detail` (
);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `product_detail` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_detail`, `product_price`, `product_image`, `create_at`, `user_id`) VALUES
(1, 'mobile', 'dddddddddd', '23.00', 'mobile.jpeg', '2026-03-10 10:54:20', 9),
(2, 'bag', '200 litre capacity', '500.00', 'bag.jpeg', '2026-03-10 10:56:42', 9);

-- --------------------------------------------------------

--
-- Table structure for table `product_order`
--

CREATE TABLE `product_order` (
  `order_id` int NOT NULL,
  `order_qty` int DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int NOT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `transaction_amount` varchar(50) NOT NULL,
  `acc_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `transaction_type`, `transaction_amount`, `acc_id`, `user_id`, `update_at`) VALUES
(1, 'credit', '1200', 48, 9, '2026-03-10 04:11:32'),
(2, 'credit', '100', 48, 9, '2026-03-10 04:14:30'),
(3, 'credit', '200', 48, 9, '2026-03-10 04:14:35'),
(4, 'debit', '2000', 48, 9, '2026-03-10 04:14:47'),
(5, 'credit', '1800', 48, 9, '2026-03-10 04:14:58'),
(6, 'debit', '100', 48, 9, '2026-03-10 04:15:05'),
(7, 'debit', '1000', 48, 9, '2026-03-10 04:40:05'),
(8, 'debit', '1000', 48, 9, '2026-03-10 05:15:52'),
(9, 'credit', '1', 48, 9, '2026-03-10 05:16:20'),
(10, 'credit', '1', 48, 9, '2026-03-10 05:47:39'),
(11, 'credit', '3', 48, 9, '2026-03-10 05:47:45'),
(12, 'debit', '6', 48, 9, '2026-03-10 05:47:51'),
(13, 'credit', '5', 48, 9, '2026-03-10 05:49:31'),
(14, 'credit', '100', 49, 10, '2026-03-10 06:06:03'),
(15, 'credit', '101', 49, 10, '2026-03-10 06:06:15'),
(16, 'debit', '101', 49, 10, '2026-03-10 06:08:48'),
(17, 'credit', '100', 49, 10, '2026-03-10 06:23:26'),
(18, 'credit', '100', 49, 10, '2026-03-10 06:24:25'),
(19, 'credit', '100', 49, 10, '2026-03-10 06:25:36'),
(20, 'credit', '100', 49, 10, '2026-03-10 06:26:44'),
(21, 'credit', '1000', 49, 10, '2026-03-10 06:37:28'),
(22, 'credit', '200', 49, 10, '2026-03-10 06:46:35'),
(23, 'credit', '1', 49, 10, '2026-03-10 06:54:34'),
(24, 'debit', '200', 49, 10, '2026-03-10 06:55:25'),
(25, 'credit', '100', 49, 10, '2026-03-10 06:59:42'),
(26, 'credit', '1000', 49, 10, '2026-03-10 06:59:59'),
(27, 'debit', '1000', 49, 10, '2026-03-10 07:00:07'),
(28, 'credit', '1', 49, 10, '2026-03-10 07:01:15'),
(29, 'debit', '1', 49, 10, '2026-03-10 07:01:32'),
(30, 'credit', '1', 49, 10, '2026-03-10 07:02:16'),
(31, 'credit', '1', 49, 10, '2026-03-10 07:02:59'),
(32, 'debit', '100', 49, 10, '2026-03-10 07:03:05'),
(33, 'debit', '100', 49, 10, '2026-03-10 07:03:36'),
(34, 'credit', '100', 49, 10, '2026-03-10 07:03:39'),
(35, 'debit', '200', 49, 10, '2026-03-10 07:03:47'),
(36, 'credit', '2000', 49, 10, '2026-03-10 07:03:53'),
(37, 'debit', '200', 49, 10, '2026-03-10 07:04:01'),
(38, 'credit', '200', 49, 10, '2026-03-10 07:04:08'),
(39, 'debit', '1000', 49, 10, '2026-03-10 07:04:15'),
(40, 'debit', '1000', 49, 10, '2026-03-10 07:04:24'),
(41, 'debit', '1000', 49, 10, '2026-03-10 07:04:27'),
(42, 'debit', '200', 49, 10, '2026-03-10 07:05:53'),
(43, 'debit', '101', 49, 10, '2026-03-10 07:06:31'),
(44, 'credit', '1', 49, 10, '2026-03-10 07:06:48'),
(45, 'credit', '1', 49, 10, '2026-03-10 07:06:55'),
(46, 'credit', '1', 49, 10, '2026-03-10 07:06:58'),
(47, 'debit', '3', 49, 10, '2026-03-10 07:07:04'),
(48, 'debit', '1', 49, 10, '2026-03-10 07:08:15'),
(49, 'debit', '1', 49, 10, '2026-03-10 07:08:20'),
(50, 'credit', '111', 49, 10, '2026-03-10 07:10:53'),
(51, 'credit', '11', 49, 10, '2026-03-10 07:11:44'),
(52, 'credit', '100', 49, 10, '2026-03-10 07:12:01'),
(53, 'debit', '100', 49, 10, '2026-03-10 07:13:55'),
(54, 'credit', '111', 49, 10, '2026-03-10 07:24:51'),
(55, 'credit', '1', 49, 10, '2026-03-10 07:25:40'),
(56, 'credit', '1', 49, 10, '2026-03-10 07:26:04'),
(57, 'credit', '1', 49, 10, '2026-03-10 07:26:09'),
(58, 'credit', '1', 49, 10, '2026-03-10 07:33:42'),
(59, 'debit', '1', 49, 10, '2026-03-10 07:33:49'),
(60, 'debit', '1', 49, 10, '2026-03-10 07:33:57'),
(61, 'debit', '1', 49, 10, '2026-03-10 07:35:03'),
(62, 'debit', '1', 49, 10, '2026-03-10 07:35:43'),
(63, 'credit', '1', 49, 10, '2026-03-10 07:35:57'),
(64, 'credit', '1', 49, 10, '2026-03-10 08:36:27'),
(65, 'debit', '1', 48, 9, '2026-03-10 12:20:51'),
(66, 'debit', '1', 48, 9, '2026-03-10 12:21:00'),
(67, 'credit', '100', 48, 9, '2026-03-10 12:21:12'),
(68, 'credit', '100', 48, 9, '2026-03-10 12:21:40');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `transaction_balance_update` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN

    IF NEW.transaction_type = 'credit' THEN
        UPDATE accounts
        SET balance = balance + NEW.transaction_amount
        WHERE acc_id = NEW.acc_id;
    END IF;


    IF NEW.transaction_type = 'debit' THEN
        UPDATE accounts
        SET balance = balance - NEW.transaction_amount
        WHERE acc_id = NEW.acc_id;
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `register_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `register_at`) VALUES
(9, 'testuser', 'testuser@gmail.com', '$2y$10$excXhzpPrs8ut0o5BD//ru53SQSWvtgeSgGQ9raarCAZRC1q/T4kK', '2026-03-10 04:11:17'),
(10, 'jhon', 'jhon@gmail.com', '$2y$10$bjZVpV0mxYOneDCSww/UFOtKPdoB6VxE5JJpeCqFqbu5oI7.L4phm', '2026-03-10 06:00:42');

-- --------------------------------------------------------

--
-- Structure for view `order_detail`
--
DROP TABLE IF EXISTS `order_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `order_detail`  AS SELECT `product_order`.`order_id` AS `order_id`, `product_order`.`quantity` AS `quantity`, `product_order`.`product_id` AS `product_id`, `product_order`.`total` AS `total`, `product`.`product_name` AS `product_name`, `product`.`product_price` AS `product_price` FROM (`product_order` left join `product` on((`product_order`.`order_id` = `product`.`product_id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`acc_id`),
  ADD KEY `user_id_index` (`user_id`) USING BTREE;

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `acc_id` (`acc_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `acc_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_order`
--
ALTER TABLE `product_order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `audit_trail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `product_order_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`acc_id`) REFERENCES `accounts` (`acc_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
