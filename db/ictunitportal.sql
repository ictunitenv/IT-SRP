-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2024 at 10:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ictunitportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name_initials` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `emp_designation` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '*Designation undefined*',
  `emp_division` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `name_initials`, `emp_designation`, `emp_division`) VALUES
('EMP001', 'ABC Rajapakse', 'Cleark', 'Accounts'),
('EMP0010', 'ABC Samantha', 'Director', 'Investigation'),
('EMP002', 'DEF Wikramasinghe', 'Secretary', 'Additional Secretary Office'),
('EMP003', 'GHI Kumara', 'Director', 'Administration'),
('EMP004', 'JKL KARIYAWASAM', 'Manager', 'Air Resource Management and National Ozone Unit'),
('EMP005', 'MNO Perera', 'Manager', 'Bio Diversity'),
('EMP006', 'fgh', 'jkl', 'Climate Change'),
('EMP009', 'fgh', 'jkl', 'Climate Change'),
('EMP010', 'SMART Samarasinghe', 'Director', 'Environment Education Training and Special Project');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `request_item_id` int(11) NOT NULL,
  `emp_id` varchar(16) NOT NULL,
  `item_desc` varchar(256) NOT NULL,
  `current_amount` int(6) NOT NULL,
  `required_amount` int(6) NOT NULL,
  `item_remarks` varchar(1024) DEFAULT NULL,
  `dir_approved_amt` int(6) DEFAULT NULL,
  `store_approved_amt` int(6) DEFAULT NULL,
  `amount_released` int(6) DEFAULT NULL,
  `ledger_pageNo` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`request_item_id`, `emp_id`, `item_desc`, `current_amount`, `required_amount`, `item_remarks`, `dir_approved_amt`, `store_approved_amt`, `amount_released`, `ledger_pageNo`) VALUES
(2, 'EMP005', 'asdf', 123, 5, NULL, NULL, NULL, NULL, NULL),
(47, 'EMP006', 'PC', 3, 4, NULL, NULL, NULL, NULL, NULL),
(48, 'EMP006', 'Laptop', 12, 15, NULL, NULL, NULL, NULL, NULL),
(49, 'EMP006', 'A4 paper sets', 30, 45, NULL, NULL, NULL, NULL, NULL),
(50, 'EMP006', 'Pencils', 22, 25, NULL, NULL, NULL, NULL, NULL),
(51, 'EMP006', 'usb sticks', 10, 12, NULL, NULL, NULL, NULL, NULL),
(58, 'EMP005', 'a', 1, 2, NULL, NULL, NULL, NULL, NULL),
(59, 'EMP005', 'b', 2, 3, NULL, NULL, NULL, NULL, NULL),
(60, 'EMP005', 'c', 3, 4, NULL, NULL, NULL, NULL, NULL),
(61, 'EMP005', 'd', 4, 5, NULL, NULL, NULL, NULL, NULL),
(62, 'EMP005', 'e', 5, 6, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

CREATE TABLE `repair` (
  `record_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `device_type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Other',
  `serial_no` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `issue` varchar(4096) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  `emp_id` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `request_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `approval_div_dir` tinyint(1) DEFAULT NULL,
  `div_dir_approval_date` date DEFAULT NULL,
  `approval_ict_dir` tinyint(1) DEFAULT NULL,
  `approved_by` int(64) DEFAULT NULL,
  `ict_dir_approval_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `remarks` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `remark_date` date DEFAULT NULL,
  `is_finished` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `repair`
--

INSERT INTO `repair` (`record_id`, `device_type`, `serial_no`, `issue`, `date`, `emp_id`, `request_id`, `approval_div_dir`, `div_dir_approval_date`, `approval_ict_dir`, `approved_by`, `ict_dir_approval_date`, `status`, `completed_date`, `remarks`, `remark_date`, `is_finished`) VALUES
(0001, 'Laptop', '123456', 'Battery Issue', '2023-09-19', 'EMP001', 'REP21092023-7419', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0002, 'Printer', '789132', 'Toner not working', '2023-09-19', 'EMP003', 'REP21092023-4229', 1, '2023-10-11', 0, 18, '2023-10-11', NULL, NULL, NULL, NULL, NULL),
(0003, 'Scanner', '885369841', 'Won\'t scan', '2023-09-20', 'EMP005', 'REP22092023-5059', NULL, NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL),
(0004, 'Desktop', '456789', 'Power supply<br/>\nfried', '2023-09-22', 'EMP002', 'REP21092023-5392', 1, '2023-09-22', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0005, 'Laptop', '6969420iio2', 'Display damage', '2023-09-22', 'EMP004', 'REP22092023-1612', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0006, 'Laptop', '00214576523', 'Test issue', '2023-09-27', 'EMP003', 'REP27092023-1922', 1, '2024-02-12', 1, 17, '2024-02-12', 1, '2024-02-12', '', NULL, 1),
(0007, 'Scanner', '45678912', 'Test repair 2', '2023-10-02', 'EMP003', 'REP02102023-2262', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0008, 'Desktop', '00214576523', 'My HP Desktop PC won\'t turn on everytime I press power button<br/>\nNot Working<br/>\nNeed help<br/>\nNeed a fix\nNeed a fix', '2023-10-04', 'EMP001', 'REP04102023-1609', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0009, 'Desktop', '456789', 'Hello<br />\nMy Desktop<br />\nIs not working.<br />\nIt keeps beeping everytime', '2023-10-05', 'EMP001', 'REP05102023-3785', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0012, 'Printer', '249577823423', 'Computer won&#039;t recognize the network printer even when the computer is connected to the internet', '2024-01-10', 'EMP004', 'REP10012024-9092', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0013, 'Laptop', '01235648789', 'Laptop DIsplay Problem', '2024-01-18', 'EMP0010', 'REP18012024-2594', 1, '2024-01-18', 1, 17, '2024-01-18', 9, '2024-02-01', NULL, NULL, NULL),
(0017, 'ghj', 'fghjk', 'vbjkl', '2024-01-18', 'EMP009', 'REP18012024-7469', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0018, 'Desktop', '00214576523', 'asdfghjkl', '2024-02-12', 'EMP005', 'REP12022024-8820', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0019, 'Desktop', '6969420', 'New pc repair', '2024-03-05', 'EMP010', 'REP05032024-9861', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `record_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `service` varchar(4096) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` date NOT NULL,
  `emp_id` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `request_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `approval_div_dir` tinyint(1) DEFAULT NULL,
  `div_dir_approval_date` date DEFAULT NULL,
  `approval_ict_dir` tinyint(1) DEFAULT NULL,
  `approved_by` int(64) DEFAULT NULL,
  `ict_dir_approval_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `remarks` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `remark_date` date DEFAULT NULL,
  `is_finished` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`record_id`, `service`, `date`, `emp_id`, `request_id`, `approval_div_dir`, `div_dir_approval_date`, `approval_ict_dir`, `approved_by`, `ict_dir_approval_date`, `status`, `completed_date`, `remarks`, `remark_date`, `is_finished`) VALUES
(0001, 'Meeting @ 1:30 \r\n2nd floor', '2023-09-21', 'EMP003', 'SVC21092023-3343', 1, '2023-09-22', 1, 17, '2023-09-25', 2, '2023-11-17', '', NULL, NULL),
(0002, 'Test Service 1', '2023-09-25', 'EMP003', 'SVC25092023-4570', 1, '2023-09-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0003, 'Test service 2\nservice 2', '2023-09-27', 'EMP003', 'SVC27092023-4955', NULL, NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL),
(0004, 'We have a very urgent meeting today at 2.00pm on 2nd floor<br />\nIt is done through WebX<br />\n<br />\nWe need IT support<br />\nSend IT support<br />\nThanks.', '2023-10-05', 'EMP001', 'SVC05102023-0527', 1, '2023-09-22', 1, 20, '2023-09-25', NULL, NULL, 'SERVICE_Remark_Testing_Message', NULL, NULL),
(0005, 'We have a very urgent meeting today at 2.00pm on 2nd floor<br>It is done through WebX<br>We need IT support<br>Send IT support now<br>Thank you very much.', '2023-10-05', 'EMP002', 'SVC05102023-5363', NULL, NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, NULL),
(0007, 'Meeting 2nd floor<br />\n9am', '2023-10-19', 'EMP005', 'SVC19102023-4190', NULL, NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, NULL),
(0008, 'Need tech assistance for the meeting that will be held on the 8th floor tomorrow at 9 a.m.', '2024-01-10', 'EMP004', 'SVC10012024-3815', NULL, NULL, NULL, 20, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(8) NOT NULL,
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT 'User''s Name',
  `role` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `division` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `name`, `role`, `division`) VALUES
(1, 'Admin1', 'pw', 'Main Admin', 'admin', ''),
(4, 'Cage', 'cage', 'Admin 2', 'admin', ''),
(7, 'accounts.director', 'accounts@123', 'Mrs. D.A. Himali L. Senavirathne', 'director', 'Accounts'),
(8, 'dir2', 'abc', 'Director 2', 'director', 'Additional Secretary Office'),
(9, 'admin.director', 'admin@123', 'Mrs. S.M.G.K. Samarakoon', 'director', 'Administration'),
(10, 'ozone.director', 'ozone@123', 'Mrs. K.S. Apsara Mendis', 'director', 'Air Resource Management and National Ozone Unit'),
(11, 'bio.director', 'biodiversity@123', 'Ms. K.N.K. Vidyalankara', 'director', 'Biodiversity'),
(12, 'climate.director', 'climate@123', 'Mr. Leel Randeni', 'director', 'Climate Change'),
(13, 'EduSpecial.director', 'eduspecial@123', 'Mr. R.S.K. Doolwalage', 'director', 'Environment Education Training and Special Project'),
(14, 'EduTraining.director', 'edutraining@123', 'Ms. Piume Bentarage', 'director', 'Environment Education Training and Special Project'),
(15, 'PublicRelations.director', 'public@123', 'Mr. Ranjith Rajapakshe', 'director', 'Environment Education Training and Special Project'),
(17, 'planning.director', 'planning@123', 'Ms. K.N.K. Vidyalankara', 'director', 'Environmental Planning and Economic'),
(18, 'pollution.director', 'pollution@123', 'Mr. Senarath Mahinda Werahera', 'director', 'Environmental Pollution Control and Chemical Management'),
(19, 'hr.director', 'humanres@123', 'Mr. M.P. Bandara', 'director', 'Human Resource Development'),
(20, 'ictunit.director', 'ictunit@123', 'Mr. E.N.U.K. Rodrigo', 'director', 'ICT'),
(21, 'audit.director', 'audit@123', 'Mrs. D.A.I. Perera', 'director', 'Internal Audit'),
(22, 'ir.director', 'international@123', 'Mrs. Kulani H.W. Karunarathne', 'director', 'International Relation'),
(23, 'investigation.director', 'investigation@123', 'Mr. A.L. Basnayake', 'director', 'Investigation'),
(24, 'legal.director', 'legal@123', 'Mrs. Lumbini Kiriella', 'director', 'Legal'),
(25, 'dir16', 'abc', 'Director 16', 'director', 'Media'),
(26, 'dir17', 'abc', 'Director 17', 'director', 'Minister Office'),
(27, 'natural.director', 'natural@123', 'Mrs. Nilmini Wicramarachchi', 'director', 'Natural Resource'),
(28, 'policy.director', 'policy@123', 'Mr. Manju Sri Athurusinghe', 'director', 'Policy Planning'),
(29, 'dir20', 'abc', 'Director 20', 'director', 'Secretary Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`) USING BTREE;

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`request_item_id`),
  ADD KEY `item_FK_1` (`emp_id`);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `repair_emp_FK_1` (`emp_id`) USING BTREE,
  ADD KEY `repair_user_FK_1` (`approved_by`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `service_user_FK_1` (`approved_by`),
  ADD KEY `service_emp_FK_1` (`emp_id`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `request_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `repair`
--
ALTER TABLE `repair`
  MODIFY `record_id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `record_id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_FK_1` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`);

--
-- Constraints for table `repair`
--
ALTER TABLE `repair`
  ADD CONSTRAINT `repair_emp_FK_1` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`),
  ADD CONSTRAINT `repair_user_FK_1` FOREIGN KEY (`approved_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_emp_FK_1` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`),
  ADD CONSTRAINT `service_user_FK_1` FOREIGN KEY (`approved_by`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
