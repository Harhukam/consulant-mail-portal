-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 05, 2018 at 12:34 AM
-- Server version: 10.1.31-MariaDB-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mywezvus_conDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `catID` int(11) NOT NULL,
  `catName` text NOT NULL,
  `catValue` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`catID`, `catName`, `catValue`) VALUES
(8, 'Work Visa', 'work_visa'),
(7, 'Study Visa', 'study_visa'),
(5, 'Tourist Visa', 'tourist_visa');

-- --------------------------------------------------------

--
-- Table structure for table `sidebar`
--

CREATE TABLE `sidebar` (
  `sidebar_id` int(11) NOT NULL,
  `sidebar` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sidebar`
--

INSERT INTO `sidebar` (`sidebar_id`, `sidebar`) VALUES
(200001, ' ');

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE `social` (
  `social_id` bigint(20) NOT NULL,
  `facebook` varchar(50) NOT NULL,
  `instagram` varchar(50) NOT NULL,
  `twitter` varchar(50) NOT NULL,
  `youtube` varchar(50) NOT NULL,
  `google` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `social`
--

INSERT INTO `social` (`social_id`, `facebook`, `instagram`, `twitter`, `youtube`, `google`) VALUES
(200001, '@harhukams', '@rebory.sgh', 'harhukam17', 'mywebdeal', '1235677547');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `adminID` int(11) NOT NULL,
  `adminName` varchar(100) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `adminPass` varchar(100) NOT NULL,
  `adminStatus` enum('Y','N') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL,
  `adminPhoto` varchar(200) DEFAULT NULL,
  `adminJoiningDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`adminID`, `adminName`, `adminEmail`, `adminPass`, `adminStatus`, `tokenCode`, `adminPhoto`, `adminJoiningDate`) VALUES
(200001, 'MyVisa Consultants', 'harhukams@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Y', 'de17f1a87b151c6f572807f7a88481e1', '540267.jpg', '2018-01-28 00:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userID` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPass` varchar(100) NOT NULL,
  `userStatus` enum('Y','N') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL,
  `userPhoto` varchar(200) DEFAULT NULL,
  `userMobile` varchar(15) NOT NULL,
  `userAddress` text NOT NULL,
  `userCat` text NOT NULL,
  `userJoiningDateTime` varchar(25) NOT NULL,
  `userUpdateDateTime` varchar(25) NOT NULL,
  `userJoiningDate` varchar(12) NOT NULL,
  `userKey` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userID`, `userName`, `userEmail`, `userPass`, `userStatus`, `tokenCode`, `userPhoto`, `userMobile`, `userAddress`, `userCat`, `userJoiningDateTime`, `userUpdateDateTime`, `userJoiningDate`, `userKey`) VALUES
(20180842, 'Rebory', 'harhukams@gmail.com', '202cb962ac59075b964b07152d234b70', 'N', '157ce3b6a3941a55585f9d86821061a6', '000000.png', '9463710716', 'Rebory ,9463710716', 'study_visa', '02/09/2018 02:07:44 pm', '02/09/2018 02:49:15 pm', '02/09/2018 ', 200001),
(20180843, 'Jeevan', 'jeevan.sulhan55@gmail.com', '0f479969cac12c277a9a17fdbf4e131d', 'N', 'dc853a412001ddec97bcc39364b52e66', '000000.png', '9878614316', 'Jeevan ,9878614316', 'tourist_visa', '02/09/2018 02:55:44 pm', '', '02/09/2018 ', 200001),
(20180844, 'Ajay Singh', 'bakhshiram3@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'N', '9325be9d8d9c1b1ecc6e47bf3190ddc9', '321522.jpg', '9815391926', 'Ajay Singh ,9815391926', 'tourist_visa', '02/09/2018 03:49:30 pm', '03/09/2018 03:59:00 pm', '02/09/2018 ', 200001);

-- --------------------------------------------------------

--
-- Table structure for table `userdatafeed`
--

CREATE TABLE `userdatafeed` (
  `app_id` int(11) NOT NULL,
  `app_client_id` int(11) NOT NULL,
  `app_name` text NOT NULL,
  `app_mobile` text NOT NULL,
  `app_email` text NOT NULL,
  `app_cat` text NOT NULL,
  `app_subject` text NOT NULL,
  `app_message` longtext NOT NULL,
  `app_document` text NOT NULL,
  `app_status` text NOT NULL,
  `app_date` varchar(12) NOT NULL,
  `app_datetime` varchar(25) NOT NULL,
  `app_senderID` int(11) NOT NULL,
  `app_senderName` text NOT NULL,
  `app_senderEmail` text NOT NULL,
  `app_usertrash` enum('N','Y') NOT NULL,
  `app_admintrash` enum('N','Y') NOT NULL,
  `app_user_read_status` varchar(15) NOT NULL DEFAULT '0',
  `app_admin_read_status` varchar(15) NOT NULL DEFAULT '0',
  `app_timestemp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdatafeed`
--

INSERT INTO `userdatafeed` (`app_id`, `app_client_id`, `app_name`, `app_mobile`, `app_email`, `app_cat`, `app_subject`, `app_message`, `app_document`, `app_status`, `app_date`, `app_datetime`, `app_senderID`, `app_senderName`, `app_senderEmail`, `app_usertrash`, `app_admintrash`, `app_user_read_status`, `app_admin_read_status`, `app_timestemp`) VALUES
(201809091, 200001, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'tourist_visa file related document ', 'kr chk', '725900.jpg', 'Y', '02/09/2018 ', '02/09/2018 04:03:35 pm', 20180844, 'Ajay Singh', 'bakhshiram3@gmail.com', 'Y', 'Y', 'read', 'read', '2018-09-02 10:47:07'),
(201809088, 200001, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'tourist_visa file related document ', 'ok aa baba', '0.png', 'Y', '02/09/2018 ', '02/09/2018 03:57:32 pm', 20180844, 'Ajay Singh', 'bakhshiram3@gmail.com', 'Y', 'Y', 'read', 'read', '2018-09-02 10:47:07'),
(201809089, 20180844, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'Your Application tourist_visa Status under Complete ', 'Dear Ajay Singh,  with attachemnt', '20180844-401468.png', 'Complete', '02/09/2018 ', '02/09/2018 04:00:45 pm', 200001, 'MyVisa Consultants', 'harhukams@gmail.com', 'Y', 'Y', 'read', 'read', '2018-09-02 10:47:07'),
(201809090, 200001, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'tourist_visa file related document ', 'a gayi and download ho gayi file photo sketch send kiti aa\r\n', '0.png', 'Y', '02/09/2018 ', '02/09/2018 04:02:22 pm', 20180844, 'Ajay Singh', 'bakhshiram3@gmail.com', 'Y', 'Y', 'read', 'read', '2018-09-02 10:47:07'),
(201809086, 20180842, 'Rebory', '9463710716', 'harhukams@gmail.com', 'study_visa', 'Your Application study_visa Status under Process ', 'Dear Rebory,  hello', '0.png', 'Process', '02/09/2018 ', '02/09/2018 02:43:36 pm', 200001, 'MyVisa Consultants', 'harhukams@gmail.com', 'Y', 'Y', 'read', 'read', '2018-09-02 09:21:41'),
(201809087, 20180844, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'Your Application tourist_visa Status under Process ', 'Dear Ajay Singh,  how are you ? this is  email test msg.', '0.png', 'Process', '02/09/2018 ', '02/09/2018 03:54:52 pm', 200001, 'MyVisa Consultants', 'harhukams@gmail.com', 'Y', 'Y', 'read', 'read', '2018-09-02 10:47:07'),
(201809085, 200001, 'Rebory', '9463710716', 'harhukams@gmail.com', 'study_visa', 'study_visa file related document ', 'hello sir', '0.png', 'Y', '02/09/2018 ', '02/09/2018 02:43:19 pm', 20180842, 'Rebory', 'harhukams@gmail.com', 'Y', 'Y', '0', 'read', '2018-09-02 09:21:41'),
(201809084, 20180842, 'Rebory', '9463710716', 'harhukams@gmail.com', 'study_visa', 'Your Application study_visa Status under Process ', 'Dear Rebory, ', '0.png', 'Process', '02/09/2018 ', '02/09/2018 02:31:16 pm', 200001, 'MyVisa Consultants', 'harhukams@gmail.com', 'Y', 'Y', 'read', '0', '2018-09-02 09:21:41'),
(201809092, 20180844, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'Your Application tourist_visa Status under Complete ', 'Dear Ajay Singh, b', '0.png', 'Complete', '03/09/2018 ', '03/09/2018 04:00:20 pm', 200001, 'MyVisa Consultants', 'harhukams@gmail.com', 'Y', 'Y', 'read', '0', '2018-09-03 10:35:47'),
(201809093, 20180844, 'Ajay Singh', '9815391926', 'bakhshiram3@gmail.com', 'tourist_visa', 'Your Application tourist_visa Status under Process ', 'Dear Ajay Singh, ', '0.png', 'Process', '03/09/2018 ', '03/09/2018 04:06:30 pm', 200001, 'MyVisa Consultants', 'harhukams@gmail.com', 'Y', 'N', 'read', '0', '2018-09-03 10:36:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`catID`);

--
-- Indexes for table `sidebar`
--
ALTER TABLE `sidebar`
  ADD PRIMARY KEY (`sidebar_id`);

--
-- Indexes for table `social`
--
ALTER TABLE `social`
  ADD PRIMARY KEY (`social_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `adminEmail` (`adminEmail`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `userdatafeed`
--
ALTER TABLE `userdatafeed`
  ADD PRIMARY KEY (`app_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `social`
--
ALTER TABLE `social`
  MODIFY `social_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200002;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200002;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20180845;

--
-- AUTO_INCREMENT for table `userdatafeed`
--
ALTER TABLE `userdatafeed`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201809094;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
