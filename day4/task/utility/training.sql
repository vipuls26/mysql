-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 11, 2026 at 01:21 PM
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
(1, '-36000.00', 1, '2026-03-11 12:52:13'),
(2, '100001.00', 2, '2026-03-11 12:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `qty` int NOT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(1, 'ramsung', '5000 mah fast charging', '12000.00', 'mobile.jpeg', '2026-03-11 12:53:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_order`
--

CREATE TABLE `product_order` (
  `order_id` int NOT NULL,
  `order_qty` int DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_status` varchar(50) NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_order`
--

INSERT INTO `product_order` (`order_id`, `order_qty`, `order_date`, `total_amount`, `order_status`, `product_id`, `user_id`) VALUES
(1, 1, '2026-03-11 12:53:51', '12000.00', 'bank insufficent', 1, 2),
(2, 1, '2026-03-11 12:54:08', '12000.00', 'order placed', 1, 1),
(3, 1, '2026-03-11 13:03:46', '12000.00', 'order placed', 1, 1);

--
-- Triggers `product_order`
--
DELIMITER $$
CREATE TRIGGER `update_account_balance` BEFORE INSERT ON `product_order` FOR EACH ROW BEGIN
    UPDATE accounts
    SET balance = balance - NEW.total_amount
    WHERE accounts.user_id = NEW.user_id;
END
$$
DELIMITER ;

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
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `transaction_type`, `transaction_amount`, `acc_id`, `user_id`, `update_at`, `order_id`) VALUES
(1, 'credit', '100001', 2, 2, '2026-03-11 12:53:59', NULL);

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `add_order_id` BEFORE INSERT ON `transactions` FOR EACH ROW BEGIN
	SET NEW.order_id = product_order.order_id;
END
$$
DELIMITER ;
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
(1, 'jho', 'jhon@gmail.com', '$2y$10$KePFPLAjTejYzmAplOK/yOxfKreZeiWtrSOufXh.LWjXVjmN8Dn0y', '2026-03-11 12:52:13'),
(2, 'testuser', 'testuser@gmail.com', '$2y$10$DQsK9Z5pKdlJZHosa/maSe3yOAiat1XbllcnhtLkQFhVwNZwUJsg2', '2026-03-11 12:53:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `acc_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_order`
--
ALTER TABLE `product_order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
