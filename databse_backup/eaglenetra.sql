-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2022 at 08:03 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eaglenetra`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(40) NOT NULL DEFAULT '',
  `all_access` tinyint(1) NOT NULL DEFAULT 0,
  `controller` varchar(50) NOT NULL DEFAULT '',
  `date_created` datetime DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `uid`, `name`, `password`, `type`, `created_at`, `modified_at`) VALUES
(1, 'ADMIN_7391_22-12-28', 'admin', '123456', 'primary', '2022-12-28 13:03:39', '2022-12-28 13:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_numbers`
--

CREATE TABLE `emergency_numbers` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `smart_card_id` varchar(255) NOT NULL,
  `emergency_contact` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emergency_numbers`
--

INSERT INTO `emergency_numbers` (`id`, `uid`, `smart_card_id`, `emergency_contact`, `created_at`, `modified_at`) VALUES
(1, 'EMERGENCY_NUM_26181e4df25bdc53d669b07f9ab29372_1672137359', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', '7812345678', '2022-12-27 16:05:59', '2022-12-27 16:05:59'),
(2, 'EMERGENCY_NUM_0b55c133745744e4c0c98a4228ba6db3_1672137359', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', '9681391540', '2022-12-27 16:05:59', '2022-12-27 16:05:59'),
(3, 'EMERGENCY_NUM_4e8081a2cca9630aa64394a6e312109f_1672137359', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', '7812345678', '2022-12-27 16:05:59', '2022-12-27 16:05:59');

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 1, '9363ff37-22ab-4e64-bef1-9cd9a13b1ba2', 0, 0, 0, NULL, 2018);

-- --------------------------------------------------------

--
-- Table structure for table `limits`
--

CREATE TABLE `limits` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `smart_card_id` varchar(255) NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `uid`, `smart_card_id`, `tag`, `longitude`, `latitude`, `created_on`, `modified_at`) VALUES
(1, 'LOCATION_bb4889e96c53546d87fc2420cf78c8e9_1672137534', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', NULL, '19.560591017950745', '61.53711434642541', '2022-12-27 16:08:54', '2022-12-27 16:08:54'),
(2, 'LOCATION_12a04868529a9401a758c220280b084e_1672137556', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', NULL, '34.560591017', '91.537112345', '2022-12-27 16:09:16', '2022-12-27 16:09:16'),
(3, 'LOCATION_0c54899796bf0fb0ea3da3fa8345b48c_1672137627', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', NULL, '55.560591017', '67.537112345', '2022-12-27 16:10:27', '2022-12-27 16:10:27'),
(4, 'LOCATION_fe5ae5148c0db424a8cd81b979cb31cd_1672137646', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', NULL, '20.560591017', '30.537112345', '2022-12-27 16:10:46', '2022-12-27 16:10:46'),
(5, 'LOCATION_5fd85f83e06d243c9cc23ec799f41a57_1672137651', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', NULL, '20.560591017', '30.537112345', '2022-12-27 16:10:51', '2022-12-27 16:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text DEFAULT NULL,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(1, 'eagle/splashScreen', 'get', 'a:6:{s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"249a4ba3-c4ee-4b03-8d5c-4fa7c179d067\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '', '::1', 1672293607, 0.0757849, '0', 403),
(2, 'eagle/splashScreen', 'get', 'a:6:{s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"575795e1-a347-40d9-9629-3b5318caa540\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '', '::1', 1672293773, 0.0213008, '0', 403),
(3, 'eagle/splashScreen', 'get', 'a:7:{s:9:\"x-api-key\";s:36:\"9363ff37-22ab-4e64-bef1-9cd9a13b1ba2\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"4f339626-572f-41f5-bca5-306ccf27e31a\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '', '::1', 1672293849, 0.0217459, '0', 403),
(4, 'eagle/splashScreen', 'get', 'a:9:{s:9:\"x-api-key\";s:36:\"9363ff37-22ab-4e64-bef1-9cd9a13b1ba2\";s:8:\"platform\";s:7:\"android\";s:8:\"deviceid\";s:0:\"\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"68f268ae-461d-48cb-b4df-af82820ba091\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '9363ff37-22ab-4e64-bef1-9cd9a13b1ba2', '::1', 1672294169, 0.0505788, '1', 200),
(5, 'eagle/splashScreen', 'get', 'a:8:{s:9:\"x-api-key\";s:36:\"9363ff37-22ab-4e64-bef1-9cd9a13b1ba2\";s:8:\"platform\";s:7:\"android\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"21464f8d-9bf3-4c99-b138-1164847ef93c\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '9363ff37-22ab-4e64-bef1-9cd9a13b1ba2', '::1', 1672294181, 0.0219929, '1', 200),
(6, 'eagle/splashScreen', 'get', 'a:8:{s:9:\"x-api-key\";s:36:\"9363ff37-22ab-4e64-bef1-9cd9a13b1ba2\";s:8:\"platform\";s:7:\"android\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"c1b7f35a-f3ba-4575-8d65-9a88f44a996e\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '9363ff37-22ab-4e64-bef1-9cd9a13b1ba2', '::1', 1672294210, 0.0449181, '1', 200),
(7, 'eagle/getSafeArea/USER_05060f79f3c5be9b24cc5a7fd4f8a504_1672137283', 'get', 'a:7:{s:48:\"USER_05060f79f3c5be9b24cc5a7fd4f8a504_1672137283\";N;s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"04cab18c-5ceb-4603-ba70-e5311d22cc46\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '', '::1', 1672295143, 0.022562, '0', 403),
(8, 'eagle/splashScreen', 'get', 'a:8:{s:9:\"x-api-key\";s:36:\"9363ff37-22ab-4e64-bef1-9cd9a13b1ba2\";s:8:\"platform\";s:7:\"android\";s:10:\"User-Agent\";s:21:\"PostmanRuntime/7.30.0\";s:6:\"Accept\";s:3:\"*/*\";s:13:\"Postman-Token\";s:36:\"5fc0e5d9-3bc4-4cbe-a017-770e5000030f\";s:4:\"Host\";s:9:\"localhost\";s:15:\"Accept-Encoding\";s:17:\"gzip, deflate, br\";s:10:\"Connection\";s:10:\"keep-alive\";}', '9363ff37-22ab-4e64-bef1-9cd9a13b1ba2', '::1', 1672295613, 0.0249119, '1', 200);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `uid`, `user_id`, `otp`, `created_at`, `modified_at`) VALUES
(1, 'OTP_13985e53e6e6d5112c38a280f0561e9c_1672137283', 'USER_05060f79f3c5be9b24cc5a7fd4f8a504_1672137283', '9069', '2022-12-27 16:04:43', '2022-12-27 16:04:43'),
(2, 'OTP_16991e100e30fc8dadf2a862aab3216b_1672137688', 'USER_18e6a6bf5aa912269439db748f073e05_1672137688', '9597', '2022-12-27 16:11:28', '2022-12-27 16:11:28'),
(3, 'OTP_31d16ef35bf45d054b52cb5c91634906_1672230266', 'USER_5d62d5bfc42c7d29adf597bc65c7cb63_1672230266', '5689', '2022-12-28 17:54:26', '2022-12-28 17:54:26'),
(4, 'OTP_7c0bf7f6bb7156b6efa7ec4bae27d60c_1672230313', 'USER_d5dd795b59f445dd1585da448ca3bc61_1672230313', '6906', '2022-12-28 17:55:13', '2022-12-28 17:55:13'),
(5, 'OTP_e8d3cb9528968290b610c3c5aca33701_1672230384', 'USER_9b623cf305d505cfc085a31c3606c723_1672230384', '9546', '2022-12-28 17:56:24', '2022-12-28 17:56:24'),
(6, 'OTP_0a1da6924d75b7920fa83a1136bf506b_1672230392', 'USER_b8e46e740d7aba1ebfbfd2deed90adb0_1672230392', '4473', '2022-12-28 17:56:32', '2022-12-28 17:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `uid`, `price`, `validity`, `created_at`, `updated_at`) VALUES
(1, 'PACKAGE_7bb087b5f0f8d2c5a65113742815329c_1672137750', '399', '84 days', '2022-12-27 16:12:30', '2022-12-27 16:12:30'),
(2, 'PACKAGE_7797f20efb12c237afdfe37dc4305ec3_1672137770', '799', '180 days', '2022-12-27 16:12:50', '2022-12-27 16:12:50');

-- --------------------------------------------------------

--
-- Table structure for table `safe_area`
--

CREATE TABLE `safe_area` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `safe_area_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `Latitude` varchar(255) NOT NULL,
  `alert_on_exit` tinyint(1) NOT NULL,
  `alert_on_entry` tinyint(1) NOT NULL,
  `safe_area_radius` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `safe_area`
--

INSERT INTO `safe_area` (`id`, `uid`, `user_id`, `safe_area_name`, `address`, `longitude`, `Latitude`, `alert_on_exit`, `alert_on_entry`, `safe_area_radius`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SAFEAREA_8f086e2d16773db28c8ebce59213e38f_1672137420', 'USER_05060f79f3c5be9b24cc5a7fd4f8a504_1672137283', 'school', 'kolkata,', '345.453453', '432.2314123', 1, 1, '300', 'active', '2022-12-27 10:37:00', '2022-12-27 10:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `smart_card`
--

CREATE TABLE `smart_card` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `smart_card`
--

INSERT INTO `smart_card` (`id`, `uid`, `user_id`, `name`, `device_id`, `card_number`, `class`, `age`, `profile_image`, `created_at`, `modified_at`) VALUES
(1, 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', 'USER_05060f79f3c5be9b24cc5a7fd4f8a504_1672137283', 'Sk Rohan', '12345', '9681391540', '6', '10', 'assets/5848d7f8925e724e947b7b5e2acbb4a0.png', '2022-12-27 10:35:59', '2022-12-27 10:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `splash_screen`
--

CREATE TABLE `splash_screen` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `splash_screen`
--

INSERT INTO `splash_screen` (`id`, `uid`, `image`, `heading`, `description`, `status`, `created_at`, `modified_at`, `created_by`, `updated_by`) VALUES
(1, '467323', 'images/aqua.jpg', 'Smart id card', 'lorem ipsam dolar isat joreel kolmfn ', 'active', '2022-12-14 13:47:28', '2022-12-14 13:47:28', 'user', 'user'),
(2, '029335', 'images/mountain.jpg', 'Route Tracking', 'lorem ipsam dolar isat joreel kolmfn ', 'active', '2022-12-14 13:47:28', '2022-12-14 13:47:28', 'user', 'user'),
(3, '234567', 'images/pond.jpg', 'Geo fencing', 'lorem ipsam dolar isat joreel kolmfn ', 'active', '2022-12-14 13:47:28', '2022-12-14 13:47:28', 'user', 'user'),
(4, '345678', 'images/pond.jpg', 'Geo fencing', 'lorem ipsam dolar isat joreel kolmfn ', 'deactive', '2022-12-14 13:47:28', '2022-12-14 13:47:28', 'user', 'user'),
(5, '456234', 'images/pond.jpg', 'Geo fencing', 'lorem ipsam dolar isat joreel kolmfn ', 'deactive', '2022-12-14 13:47:28', '2022-12-14 13:47:28', 'user', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `smart_card_id` varchar(255) NOT NULL,
  `package_id` varchar(255) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `uid`, `smart_card_id`, `package_id`, `expiry_date`, `status`, `created_at`, `modified_at`) VALUES
(2, 'SUBSCRIPTION_359ec74e28cba4e6075db6aa583075df_1672138019', 'SMARTCARD_26b932f91f19b9bb5f82d9630b0febc2_1672137359', 'PACKAGE_7bb087b5f0f8d2c5a65113742815329c_1672137750', '2023-03-21 16:16:59', 'active', '2022-12-27 16:16:59', '2022-12-27 16:16:59');

-- --------------------------------------------------------

--
-- Table structure for table `tracking_for`
--

CREATE TABLE `tracking_for` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `tracking_for` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tracking_for`
--

INSERT INTO `tracking_for` (`id`, `uid`, `tracking_for`, `created_at`, `modified_at`) VALUES
(1, '1', 'Kids', '2022-12-16 10:47:35', '2022-12-16 10:47:35'),
(2, '2', 'Employees', '2022-12-16 10:49:07', '2022-12-16 10:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL DEFAULT 'parent',
  `tracking_for_id` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `uid`, `user_type`, `name`, `email`, `relationship`, `tracking_for_id`, `phone_number`, `image`, `created_at`, `modified_at`) VALUES
(1, 'USER_05060f79f3c5be9b24cc5a7fd4f8a504_1672137283', 'user_primary', 'sk rohan ali', 'rohan@gmail.com', 'parent', '1', '6290353314', 'assets/9324f468f096dc33845cba45f9997387.png', '2022-12-27 16:04:43', '2022-12-27 16:04:43'),
(2, 'USER_18e6a6bf5aa912269439db748f073e05_1672137688', 'user_secondary', 'rohan', '', 'uncle', '', '7890654321', '', '2022-12-27 16:11:28', '2022-12-27 16:11:28'),
(3, 'USER_d5dd795b59f445dd1585da448ca3bc61_1672230313', 'user_primary', 'sk rohan ali', 'rohan@gmail.com', 'parent', '1', '7654321099', 'assets/2c7c9d8b686e52324256bb03b274c454.png', '2022-12-28 17:55:13', '2022-12-28 17:55:13'),
(4, 'USER_9b623cf305d505cfc085a31c3606c723_1672230384', 'user_secondary', 'rohan', '', 'uncle', '', '6098452621', '', '2022-12-28 17:56:24', '2022-12-28 17:56:24'),
(5, 'USER_b8e46e740d7aba1ebfbfd2deed90adb0_1672230392', 'user_secondary', 'rohan', '', 'uncle', '', '8604637292', '', '2022-12-28 17:56:32', '2022-12-28 17:56:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_numbers`
--
ALTER TABLE `emergency_numbers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `limits`
--
ALTER TABLE `limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safe_area`
--
ALTER TABLE `safe_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smart_card`
--
ALTER TABLE `smart_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `splash_screen`
--
ALTER TABLE `splash_screen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracking_for`
--
ALTER TABLE `tracking_for`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `emergency_numbers`
--
ALTER TABLE `emergency_numbers`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `limits`
--
ALTER TABLE `limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `safe_area`
--
ALTER TABLE `safe_area`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `smart_card`
--
ALTER TABLE `smart_card`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `splash_screen`
--
ALTER TABLE `splash_screen`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracking_for`
--
ALTER TABLE `tracking_for`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
