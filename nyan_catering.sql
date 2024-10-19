-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2024 at 12:21 PM
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
-- Database: `nyan_catering`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `order_id` int(11) NOT NULL,
  `app_experience_rating` tinyint(4) NOT NULL CHECK (`app_experience_rating` between 1 and 5),
  `wait_time_rating` tinyint(4) NOT NULL CHECK (`wait_time_rating` between 1 and 5),
  `food_quality_rating` tinyint(4) NOT NULL CHECK (`food_quality_rating` between 1 and 5),
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imagetest`
--

CREATE TABLE `imagetest` (
  `user_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_data` mediumblob NOT NULL,
  `image_type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `menu_item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(7) NOT NULL,
  `is_in_stock` tinyint(1) NOT NULL DEFAULT 1,
  `is_vegetarian` tinyint(1) NOT NULL,
  `is_halal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`menu_item_id`, `item_name`, `description`, `price`, `category`, `is_in_stock`, `is_vegetarian`, `is_halal`) VALUES
(5, 'bob\'s liver', 'fresh organ harvested', 69.00, 'main', 1, 0, 0),
(6, 'bob\'s liver', 'its new and uppriced, now a side', 96.00, 'side', 1, 0, 1),
(7, 'shaun\'s liver', 'why the liver obsession man?', 69.00, 'main', 1, 1, 1),
(8, 'shaun\'s liver', 'i guess this is how it is now.', 96.00, 'dessert', 1, 0, 1),
(9, 'ya needa loan?', 'this is a drink, 555.55 dollabuckaroos', 555.55, 'drink', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_images`
--

CREATE TABLE `menu_item_images` (
  `menu_item_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_data` mediumblob NOT NULL,
  `image_type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_datetime` datetime DEFAULT current_timestamp(),
  `delivery_datetime` datetime DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `total_spending` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `packages_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(32) NOT NULL,
  `is_available` int(11) NOT NULL,
  `main` int(11) NOT NULL,
  `side` int(11) NOT NULL,
  `dessert` int(11) NOT NULL,
  `drink` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_images`
--

CREATE TABLE `package_images` (
  `package_image_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_data` mediumblob NOT NULL,
  `image_type` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_auths`
--

CREATE TABLE `user_auths` (
  `user_id` int(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` int(15) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `account_creation_time` datetime NOT NULL,
  `last_login_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_auths`
--

INSERT INTO `user_auths` (`user_id`, `email`, `phone_number`, `hashed_password`, `account_creation_time`, `last_login_time`) VALUES
(0, 'bob@sg.sg', 88888888, '$argon2id$v=19$m=65536,t=4,p=1$L3pReDdVTWsxZmJ2WnZHRg$kludBRfB7OGEEQIn3PE9QZW6BlBM4LV41EchrCPWJ8s', '2024-10-13 01:54:48', '2024-10-13 21:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `user_id` int(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `preferred_payment_method` varchar(63) DEFAULT NULL,
  `is_notify_by_sms` tinyint(1) NOT NULL,
  `is_notify_by_email` tinyint(1) NOT NULL,
  `is_notify_by_whatsapp` tinyint(1) NOT NULL,
  `is_notify_by_telegram` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `imagetest`
--
ALTER TABLE `imagetest`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`menu_item_id`);

--
-- Indexes for table `menu_item_images`
--
ALTER TABLE `menu_item_images`
  ADD PRIMARY KEY (`menu_item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`,`package_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `package_images`
--
ALTER TABLE `package_images`
  ADD PRIMARY KEY (`package_image_id`);

--
-- Indexes for table `user_auths`
--
ALTER TABLE `user_auths`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `imagetest`
--
ALTER TABLE `imagetest`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `menu_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_auths`
--
ALTER TABLE `user_auths`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
