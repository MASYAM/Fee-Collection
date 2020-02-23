-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 23, 2020 at 06:16 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdbdorg_macc_englishmedium`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication_for_setup`
--

CREATE TABLE `authentication_for_setup` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(150) NOT NULL,
  `loginattempt` int(11) NOT NULL,
  `welcome_message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authentication_for_setup`
--

INSERT INTO `authentication_for_setup` (`id`, `username`, `password`, `loginattempt`, `welcome_message`) VALUES
(2, 'admincontrol', 'd2e9291cbeb074e6457eca2bde735e0c0a3b447a79cd70e2226e889b0d6594a3a841fcf7f898b7bac25b9436953a33898326fbf615aec639b062b4568ac8eb44', 0, 'Sample Institute');

-- --------------------------------------------------------

--
-- Table structure for table `due`
--

CREATE TABLE `due` (
  `id` bigint(99) NOT NULL,
  `year` varchar(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `receipt_item` varchar(100) NOT NULL,
  `due` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `due`
--

INSERT INTO `due` (`id`, `year`, `month`, `date`, `time`, `student_id`, `receipt_item`, `due`) VALUES
(104, '2020', 'February', '2020-02-23', '03:08:50 PM', 'SJI19-3C-115', 'Stationery', 50);

-- --------------------------------------------------------

--
-- Table structure for table `extra_added_items`
--

CREATE TABLE `extra_added_items` (
  `id` bigint(99) NOT NULL,
  `item` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ipcheck_for_setup`
--

CREATE TABLE `ipcheck_for_setup` (
  `id` int(11) NOT NULL,
  `loggedip` varchar(20) NOT NULL,
  `failedattempts` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ipcheck_for_setup`
--

INSERT INTO `ipcheck_for_setup` (`id`, `loggedip`, `failedattempts`) VALUES
(36, '192.168.3.25', 0),
(35, '192.168.3.12', 0),
(37, '192.168.3.22', 0),
(38, '192.168.3.90', 0),
(39, '192.168.3.2', 0);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_transaction`
--

CREATE TABLE `monthly_transaction` (
  `id` bigint(99) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `date` text NOT NULL,
  `payable` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `due` int(11) NOT NULL,
  `exchange` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `monthly_transaction`
--

INSERT INTO `monthly_transaction` (`id`, `year`, `month`, `date`, `payable`, `paid`, `due`, `exchange`) VALUES
(1095, 2020, 'Feb', '2020-02-23', 10750, 10750, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

CREATE TABLE `months` (
  `id` bigint(99) NOT NULL,
  `month` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`id`, `month`) VALUES
(1, 'January'),
(2, 'February'),
(3, 'March'),
(4, 'April'),
(5, 'May'),
(6, 'June'),
(7, 'July'),
(8, 'August'),
(9, 'September'),
(10, 'October'),
(11, 'November'),
(12, 'December');

-- --------------------------------------------------------

--
-- Table structure for table `old_student_receipt_items`
--

CREATE TABLE `old_student_receipt_items` (
  `id` bigint(99) NOT NULL,
  `item` text NOT NULL,
  `amount` varchar(100) NOT NULL,
  `items_order` varchar(100) NOT NULL,
  `instant_change_permission` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `old_student_receipt_items`
--

INSERT INTO `old_student_receipt_items` (`id`, `item`, `amount`, `items_order`, `instant_change_permission`) VALUES
(1, 'Student certificate', '50', '1', 'No'),
(2, 'Testimonial', '100', '2', 'No'),
(3, 'Recommendation letter', '100', '3', 'No'),
(4, 'Transcript', '100', '4', 'No'),
(5, 'TC', '100', '5', 'No'),
(6, 'Due', '0', '6', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `payment_status`
--

CREATE TABLE `payment_status` (
  `id` bigint(99) NOT NULL,
  `year` varchar(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_status`
--

INSERT INTO `payment_status` (`id`, `year`, `month`, `date`, `time`, `student_id`, `status`) VALUES
(111603, '2020', 'January', '2020-02-23', '05:53:48 AM', 'SJI19-3C-115', 'Full paid'),
(111604, '2020', 'February', '2020-02-23', '03:08:50 PM', 'SJI19-3C-115', 'Not full paid'),
(111605, '2020', 'February', '2020-02-23', '05:08:59 PM', 'SJI19-3C-116', 'Full paid'),
(111606, '2020', 'January', '2020-02-23', '10:01:53 PM', 'SJI19-3C-118', 'Full paid');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `reload_permission` varchar(1000) NOT NULL,
  `calculate_permission` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `username`, `reload_permission`, `calculate_permission`) VALUES
(1, 'admincontrol', 'Cancel', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_id`
--

CREATE TABLE `receipt_id` (
  `id` bigint(99) NOT NULL,
  `year` varchar(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `date` varchar(1000) NOT NULL,
  `time` varchar(1000) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `receipt_id` mediumtext NOT NULL,
  `username` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt_id`
--

INSERT INTO `receipt_id` (`id`, `year`, `month`, `date`, `time`, `student_id`, `receipt_id`, `username`) VALUES
(71254, '2020', 'February', '2020-02-23', '05:53:48 AM', 'SJI19-3C-115', '202002-1', 'admincontrol'),
(71255, '2020', 'February', '2020-02-23', '03:08:50 PM', 'SJI19-3C-115', '202002-2', 'admincontrol'),
(71256, '2020', 'February', '2020-02-23', '05:08:59 PM', 'SJI19-3C-116', '202002-3', 'admincontrol'),
(71257, '2020', 'February', '2020-02-23', '10:01:53 PM', 'SJI19-3C-118', '202002-4', 'admincontrol');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items`
--

CREATE TABLE `receipt_items` (
  `id` int(11) NOT NULL,
  `item` varchar(1000) NOT NULL,
  `item_order` varchar(100) NOT NULL,
  `instant_change_permission` varchar(100) NOT NULL,
  `appear_permission_when_due_clear` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt_items`
--

INSERT INTO `receipt_items` (`id`, `item`, `item_order`, `instant_change_permission`, `appear_permission_when_due_clear`) VALUES
(1, 'Monthly fees', '1', 'No', 'No'),
(2, 'Admission fees', '3', 'No', 'No'),
(3, 'Magazine & Calander', '4', 'No', 'No'),
(4, 'Exercise book/Test Paper/ ID Card', '5', 'No', 'No'),
(5, 'Computer/IT Fees', '6', 'No', 'No'),
(6, 'Sports, Prize &Others', '7', 'No', 'No'),
(7, 'Utilities', '8', 'Yes', 'No'),
(8, 'Stationery', '9', 'No', 'No'),
(9, 'Library', '10', 'No', 'No'),
(13, 'M.D.D & Projector', '13', 'No', 'Yes'),
(12, 'Exam Fee', '12', 'No', 'Yes'),
(15, 'Scout ', '11', 'No', 'No'),
(16, 'Development', '14', 'No', 'No'),
(17, 'Contingencies', '15', 'No', 'No'),
(18, 'Gratuity', '16', 'No', 'No'),
(19, 'Fine', '17', 'Yes', 'Yes'),
(20, 'Cotti Dress', '18', 'No', 'Yes'),
(21, 'Others', '19', 'No', 'Yes'),
(22, 'Activities', '2', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items_amount`
--

CREATE TABLE `receipt_items_amount` (
  `id` int(11) NOT NULL,
  `section` varchar(100) NOT NULL,
  `item` varchar(1000) NOT NULL,
  `year` text NOT NULL,
  `month` varchar(100) NOT NULL,
  `academic_year` text NOT NULL,
  `amount` varchar(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt_items_amount`
--

INSERT INTO `receipt_items_amount` (`id`, `section`, `item`, `year`, `month`, `academic_year`, `amount`) VALUES
(25567, '3C', 'Others', '2020', 'March', '2020', '0'),
(25566, '3C', 'Cotti Dress', '2020', 'March', '2020', '0'),
(25565, '3C', 'Fine', '2020', 'March', '2020', '20'),
(25564, '3C', 'Gratuity', '2020', 'March', '2020', '0'),
(25563, '3C', 'Contingencies', '2020', 'March', '2020', '0'),
(25562, '3C', 'Development', '2020', 'March', '2020', '0'),
(25561, '3C', 'M.D.D & Projector', '2020', 'March', '2020', '0'),
(25560, '3C', 'Exam Fee', '2020', 'March', '2020', '0'),
(25559, '3C', 'Scout ', '2020', 'March', '2020', '0'),
(25558, '3C', 'Library', '2020', 'March', '2020', '150'),
(25557, '3C', 'Stationery', '2020', 'March', '2020', '0'),
(25556, '3C', 'Utilities', '2020', 'March', '2020', '0'),
(25555, '3C', 'Sports, Prize &Others', '2020', 'March', '2020', '0'),
(25554, '3C', 'Computer/IT Fees', '2020', 'March', '2020', '50'),
(25543, '3C', 'Development', '2020', 'February', '2020', '0'),
(25544, '3C', 'Contingencies', '2020', 'February', '2020', '0'),
(25545, '3C', 'Gratuity', '2020', 'February', '2020', '0'),
(25546, '3C', 'Fine', '2020', 'February', '2020', '0'),
(25547, '3C', 'Cotti Dress', '2020', 'February', '2020', '0'),
(25548, '3C', 'Others', '2020', 'February', '2020', '0'),
(25549, '3C', 'Monthly fees', '2020', 'March', '2020', '1000'),
(25550, '3C', 'Activities', '2020', 'March', '2020', '0'),
(25551, '3C', 'Admission fees', '2020', 'March', '2020', '0'),
(25552, '3C', 'Magazine & Calander', '2020', 'March', '2020', '0'),
(25553, '3C', 'Exercise book/Test Paper/ ID Card', '2020', 'March', '2020', '0'),
(25542, '3C', 'M.D.D & Projector', '2020', 'February', '2020', '0'),
(25541, '3C', 'Exam Fee', '2020', 'February', '2020', '200'),
(25540, '3C', 'Scout ', '2020', 'February', '2020', '0'),
(25539, '3C', 'Library', '2020', 'February', '2020', '0'),
(25538, '3C', 'Stationery', '2020', 'February', '2020', '50'),
(25537, '3C', 'Utilities', '2020', 'February', '2020', '0'),
(25536, '3C', 'Sports, Prize &Others', '2020', 'February', '2020', '0'),
(25535, '3C', 'Computer/IT Fees', '2020', 'February', '2020', '250'),
(25534, '3C', 'Exercise book/Test Paper/ ID Card', '2020', 'February', '2020', '0'),
(25533, '3C', 'Magazine & Calander', '2020', 'February', '2020', '0'),
(25532, '3C', 'Admission fees', '2020', 'February', '2020', '0'),
(25531, '3C', 'Activities', '2020', 'February', '2020', '0'),
(25530, '3C', 'Monthly fees', '2020', 'February', '2020', '3000'),
(25529, '3C', 'Others', '2020', 'January', '2020', '0'),
(25528, '3C', 'Cotti Dress', '2020', 'January', '2020', '0'),
(25527, '3C', 'Fine', '2020', 'January', '2020', '0'),
(25526, '3C', 'Gratuity', '2020', 'January', '2020', '0'),
(25525, '3C', 'Contingencies', '2020', 'January', '2020', '0'),
(25524, '3C', 'Development', '2020', 'January', '2020', '0'),
(25523, '3C', 'M.D.D & Projector', '2020', 'January', '2020', '0'),
(25522, '3C', 'Exam Fee', '2020', 'January', '2020', '0'),
(25521, '3C', 'Scout ', '2020', 'January', '2020', '400'),
(25520, '3C', 'Library', '2020', 'January', '2020', '100'),
(25519, '3C', 'Stationery', '2020', 'January', '2020', '50'),
(25518, '3C', 'Utilities', '2020', 'January', '2020', '0'),
(25517, '3C', 'Sports, Prize &Others', '2020', 'January', '2020', '0'),
(25516, '3C', 'Computer/IT Fees', '2020', 'January', '2020', '250'),
(25515, '3C', 'Exercise book/Test Paper/ ID Card', '2020', 'January', '2020', '0'),
(25514, '3C', 'Magazine & Calander', '2020', 'January', '2020', '0'),
(25513, '3C', 'Admission fees', '2020', 'January', '2020', '0'),
(25511, '3C', 'Monthly fees', '2020', 'January', '2020', '3000'),
(25512, '3C', 'Activities', '2020', 'January', '2020', '0');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items_amount_on_student`
--

CREATE TABLE `receipt_items_amount_on_student` (
  `id` bigint(99) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `section` varchar(50) NOT NULL,
  `year` text NOT NULL,
  `month` varchar(100) NOT NULL,
  `academic_year` text NOT NULL,
  `item` varchar(1000) NOT NULL,
  `amount` varchar(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt_items_amount_on_student`
--

INSERT INTO `receipt_items_amount_on_student` (`id`, `student_id`, `section`, `year`, `month`, `academic_year`, `item`, `amount`) VALUES
(606521, 'SJI19-3C-119', '3C', '2020', 'March', '2020', 'Fine', '20'),
(606520, 'SJI19-3C-119', '3C', '2020', 'March', '2020', 'Library', '150'),
(606519, 'SJI19-3C-119', '3C', '2020', 'March', '2020', 'Computer/IT Fees', '50'),
(606518, 'SJI19-3C-119', '3C', '2020', 'March', '2020', 'Monthly fees', '1000'),
(606517, 'SJI19-3C-118', '3C', '2020', 'March', '2020', 'Fine', '20'),
(606516, 'SJI19-3C-118', '3C', '2020', 'March', '2020', 'Library', '150'),
(606515, 'SJI19-3C-118', '3C', '2020', 'March', '2020', 'Computer/IT Fees', '50'),
(606514, 'SJI19-3C-118', '3C', '2020', 'March', '2020', 'Monthly fees', '1000'),
(606513, 'SJI19-3C-117', '3C', '2020', 'March', '2020', 'Fine', '20'),
(606512, 'SJI19-3C-117', '3C', '2020', 'March', '2020', 'Library', '150'),
(606511, 'SJI19-3C-117', '3C', '2020', 'March', '2020', 'Computer/IT Fees', '50'),
(606510, 'SJI19-3C-117', '3C', '2020', 'March', '2020', 'Monthly fees', '1000'),
(606509, 'SJI19-3C-116', '3C', '2020', 'March', '2020', 'Fine', '20'),
(606508, 'SJI19-3C-116', '3C', '2020', 'March', '2020', 'Library', '150'),
(606507, 'SJI19-3C-116', '3C', '2020', 'March', '2020', 'Computer/IT Fees', '50'),
(606506, 'SJI19-3C-116', '3C', '2020', 'March', '2020', 'Monthly fees', '1000'),
(606505, 'SJI19-3C-115', '3C', '2020', 'March', '2020', 'Fine', '20'),
(606504, 'SJI19-3C-115', '3C', '2020', 'March', '2020', 'Library', '150'),
(606503, 'SJI19-3C-115', '3C', '2020', 'March', '2020', 'Computer/IT Fees', '50'),
(606502, 'SJI19-3C-115', '3C', '2020', 'March', '2020', 'Monthly fees', '1000'),
(606501, 'SJI19-3C-119', '3C', '2020', 'February', '2020', 'Exam Fee', '200'),
(606500, 'SJI19-3C-119', '3C', '2020', 'February', '2020', 'Stationery', '50'),
(606499, 'SJI19-3C-119', '3C', '2020', 'February', '2020', 'Computer/IT Fees', '250'),
(606498, 'SJI19-3C-119', '3C', '2020', 'February', '2020', 'Monthly fees', '3000'),
(606497, 'SJI19-3C-118', '3C', '2020', 'February', '2020', 'Exam Fee', '200'),
(606496, 'SJI19-3C-118', '3C', '2020', 'February', '2020', 'Stationery', '50'),
(606495, 'SJI19-3C-118', '3C', '2020', 'February', '2020', 'Computer/IT Fees', '250'),
(606494, 'SJI19-3C-118', '3C', '2020', 'February', '2020', 'Monthly fees', '3000'),
(606493, 'SJI19-3C-117', '3C', '2020', 'February', '2020', 'Exam Fee', '200'),
(606492, 'SJI19-3C-117', '3C', '2020', 'February', '2020', 'Stationery', '50'),
(606491, 'SJI19-3C-117', '3C', '2020', 'February', '2020', 'Computer/IT Fees', '250'),
(606490, 'SJI19-3C-117', '3C', '2020', 'February', '2020', 'Monthly fees', '3000'),
(606489, 'SJI19-3C-116', '3C', '2020', 'February', '2020', 'Exam Fee', '200'),
(606488, 'SJI19-3C-116', '3C', '2020', 'February', '2020', 'Stationery', '50'),
(606461, 'SJI19-3C-115', '3C', '2020', 'January', '2020', 'Scout ', '400'),
(606480, 'SJI19-3C-119', '3C', '2020', 'January', '2020', 'Library', '100'),
(606479, 'SJI19-3C-119', '3C', '2020', 'January', '2020', 'Stationery', '50'),
(606478, 'SJI19-3C-119', '3C', '2020', 'January', '2020', 'Computer/IT Fees', '250'),
(606477, 'SJI19-3C-119', '3C', '2020', 'January', '2020', 'Monthly fees', '3000'),
(606475, 'SJI19-3C-118', '3C', '2020', 'January', '2020', 'Library', '100'),
(606474, 'SJI19-3C-118', '3C', '2020', 'January', '2020', 'Stationery', '50'),
(606473, 'SJI19-3C-118', '3C', '2020', 'January', '2020', 'Computer/IT Fees', '250'),
(606472, 'SJI19-3C-118', '3C', '2020', 'January', '2020', 'Monthly fees', '3000'),
(606470, 'SJI19-3C-117', '3C', '2020', 'January', '2020', 'Library', '100'),
(606469, 'SJI19-3C-117', '3C', '2020', 'January', '2020', 'Stationery', '50'),
(606468, 'SJI19-3C-117', '3C', '2020', 'January', '2020', 'Computer/IT Fees', '250'),
(606467, 'SJI19-3C-117', '3C', '2020', 'January', '2020', 'Monthly fees', '3000'),
(606465, 'SJI19-3C-116', '3C', '2020', 'January', '2020', 'Library', '100'),
(606464, 'SJI19-3C-116', '3C', '2020', 'January', '2020', 'Stationery', '50'),
(606457, 'SJI19-3C-115', '3C', '2020', 'January', '2020', 'Monthly fees', '3000'),
(606458, 'SJI19-3C-115', '3C', '2020', 'January', '2020', 'Computer/IT Fees', '250'),
(606459, 'SJI19-3C-115', '3C', '2020', 'January', '2020', 'Stationery', '50'),
(606460, 'SJI19-3C-115', '3C', '2020', 'January', '2020', 'Library', '100'),
(606462, 'SJI19-3C-116', '3C', '2020', 'January', '2020', 'Monthly fees', '3000'),
(606463, 'SJI19-3C-116', '3C', '2020', 'January', '2020', 'Computer/IT Fees', '250'),
(606466, 'SJI19-3C-116', '3C', '2020', 'January', '2020', 'Scout ', '400'),
(606471, 'SJI19-3C-117', '3C', '2020', 'January', '2020', 'Scout ', '400'),
(606476, 'SJI19-3C-118', '3C', '2020', 'January', '2020', 'Scout ', '400'),
(606481, 'SJI19-3C-119', '3C', '2020', 'January', '2020', 'Scout ', '400'),
(606482, 'SJI19-3C-115', '3C', '2020', 'February', '2020', 'Monthly fees', '3000'),
(606483, 'SJI19-3C-115', '3C', '2020', 'February', '2020', 'Computer/IT Fees', '250'),
(606484, 'SJI19-3C-115', '3C', '2020', 'February', '2020', 'Stationery', '50'),
(606485, 'SJI19-3C-115', '3C', '2020', 'February', '2020', 'Exam Fee', '200'),
(606486, 'SJI19-3C-116', '3C', '2020', 'February', '2020', 'Monthly fees', '3000'),
(606487, 'SJI19-3C-116', '3C', '2020', 'February', '2020', 'Computer/IT Fees', '250');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` bigint(99) NOT NULL,
  `section` varchar(50) NOT NULL,
  `order` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `section`, `order`) VALUES
(2, '3B', ''),
(3, '3C', ''),
(48, '3A', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `id` bigint(99) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `class_roll` varchar(100) NOT NULL,
  `student_name` varchar(50) NOT NULL,
  `father_name` varchar(50) NOT NULL,
  `mother_name` varchar(50) NOT NULL,
  `photo` text NOT NULL,
  `religion` varchar(100) NOT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`id`, `student_id`, `class_roll`, `student_name`, `father_name`, `mother_name`, `photo`, `religion`, `phone`, `email`) VALUES
(6616, 'SJI19-3C-115', '5A 07', 'Test Name Ayon', '', '', '', '', '', ''),
(6617, 'SJI19-3C-116', '5A 31', 'Test Name Jinku', '', '', '', '', '', ''),
(6618, 'SJI19-3C-117', '4C 40', 'Test Name Kabir', '', '', '', '', '', ''),
(6619, 'SJI19-3C-118', '5B 29', 'Test Name Jisan', '', '', '', '', '', ''),
(6620, 'SJI19-3C-119', '3C39', 'Test Name Ryson', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_on_section`
--

CREATE TABLE `student_on_section` (
  `id` bigint(99) NOT NULL,
  `section` varchar(100) NOT NULL,
  `student_id` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_on_section`
--

INSERT INTO `student_on_section` (`id`, `section`, `student_id`) VALUES
(15733, '3C', 'SJI19-3C-115'),
(15734, '3C', 'SJI19-3C-116'),
(15735, '3C', 'SJI19-3C-117'),
(15736, '3C', 'SJI19-3C-118'),
(15737, '3C', 'SJI19-3C-119');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_archive`
--

CREATE TABLE `transaction_archive` (
  `id` bigint(99) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `payable` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `due` int(11) NOT NULL,
  `exchange` int(11) NOT NULL,
  `payment_type` text NOT NULL,
  `username` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_archive`
--

INSERT INTO `transaction_archive` (`id`, `year`, `month`, `date`, `time`, `student_id`, `payable`, `paid`, `due`, `exchange`, `payment_type`, `username`) VALUES
(71305, 2020, 'Feb', '2020-02-23', '05:53:48 AM', 'SJI19-3C-115', 3800, 3800, 0, 0, 'Monthly Fee', 'admincontrol'),
(71306, 2020, 'Feb', '2020-02-23', '03:08:50 PM', 'SJI19-3C-115', 3450, 3450, 0, 0, 'Monthly Fee', 'admincontrol'),
(71307, 2020, 'Feb', '2020-02-23', '05:08:59 PM', 'SJI19-3C-116', 3500, 3500, 0, 0, 'Monthly Fee', 'admincontrol'),
(71308, 2020, 'Feb', '2020-02-23', '10:01:53 PM', 'SJI19-3C-118', 3800, 3800, 0, 0, 'Monthly Fee', 'admincontrol');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_notcurrentyear`
--

CREATE TABLE `transaction_notcurrentyear` (
  `id` bigint(99) NOT NULL,
  `paid_year` text NOT NULL,
  `paid_month` text NOT NULL,
  `current_year` text NOT NULL,
  `current_month` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `student_id` text NOT NULL,
  `receipt_item` text NOT NULL,
  `paid` int(99) NOT NULL,
  `username` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_receipt_item`
--

CREATE TABLE `transaction_receipt_item` (
  `id` bigint(99) NOT NULL,
  `year` varchar(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `receipt_item` varchar(100) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `paid` int(11) NOT NULL,
  `username` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_receipt_item`
--

INSERT INTO `transaction_receipt_item` (`id`, `year`, `month`, `date`, `time`, `receipt_item`, `student_id`, `paid`, `username`) VALUES
(368718, '2020', 'January', '2020-02-23', '05:53:48 AM', 'Monthly fees', 'SJI19-3C-115', 3000, 'admincontrol'),
(368719, '2020', 'January', '2020-02-23', '05:53:48 AM', 'Computer/IT Fees', 'SJI19-3C-115', 250, 'admincontrol'),
(368720, '2020', 'January', '2020-02-23', '05:53:48 AM', 'Stationery', 'SJI19-3C-115', 50, 'admincontrol'),
(368721, '2020', 'January', '2020-02-23', '05:53:48 AM', 'Library', 'SJI19-3C-115', 100, 'admincontrol'),
(368722, '2020', 'January', '2020-02-23', '05:53:48 AM', 'Scout ', 'SJI19-3C-115', 400, 'admincontrol'),
(368723, '2020', 'February', '2020-02-23', '03:08:50 PM', 'Monthly fees', 'SJI19-3C-115', 3000, 'admincontrol'),
(368724, '2020', 'February', '2020-02-23', '03:08:50 PM', 'Computer/IT Fees', 'SJI19-3C-115', 250, 'admincontrol'),
(368725, '2020', 'February', '2020-02-23', '03:08:50 PM', 'Exam Fee', 'SJI19-3C-115', 200, 'admincontrol'),
(368726, '2020', 'February', '2020-02-23', '05:08:59 PM', 'Monthly fees', 'SJI19-3C-116', 3000, 'admincontrol'),
(368727, '2020', 'February', '2020-02-23', '05:08:59 PM', 'Computer/IT Fees', 'SJI19-3C-116', 250, 'admincontrol'),
(368728, '2020', 'February', '2020-02-23', '05:08:59 PM', 'Stationery', 'SJI19-3C-116', 50, 'admincontrol'),
(368729, '2020', 'February', '2020-02-23', '05:08:59 PM', 'Exam Fee', 'SJI19-3C-116', 200, 'admincontrol'),
(368730, '2020', 'January', '2020-02-23', '10:01:53 PM', 'Monthly fees', 'SJI19-3C-118', 3000, 'admincontrol'),
(368731, '2020', 'January', '2020-02-23', '10:01:53 PM', 'Computer/IT Fees', 'SJI19-3C-118', 250, 'admincontrol'),
(368732, '2020', 'January', '2020-02-23', '10:01:53 PM', 'Stationery', 'SJI19-3C-118', 50, 'admincontrol'),
(368733, '2020', 'January', '2020-02-23', '10:01:53 PM', 'Library', 'SJI19-3C-118', 100, 'admincontrol'),
(368734, '2020', 'January', '2020-02-23', '10:01:53 PM', 'Scout ', 'SJI19-3C-118', 400, 'admincontrol');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`) VALUES
(1, 'admincontrol');

-- --------------------------------------------------------

--
-- Table structure for table `users_for_setup`
--

CREATE TABLE `users_for_setup` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `actual_pass` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `loginattempt` int(11) NOT NULL,
  `welcome_message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_for_setup`
--

INSERT INTO `users_for_setup` (`id`, `username`, `actual_pass`, `password`, `loginattempt`, `welcome_message`) VALUES
(2, 'admincontrol', 'josephplanet666', '6f0d935a893609c273fdba19ce9d63c0bcb93f6fa1ab94ae68c3458da94d5a73997304a74d3ac87c7cbd59b54229f48b6872c4755dcea1e723e4cfaf6f6af41a', 0, 'Sample Institute');

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`id`, `year`) VALUES
(7, 2019),
(8, 2020);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authentication_for_setup`
--
ALTER TABLE `authentication_for_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `due`
--
ALTER TABLE `due`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extra_added_items`
--
ALTER TABLE `extra_added_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ipcheck_for_setup`
--
ALTER TABLE `ipcheck_for_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_transaction`
--
ALTER TABLE `monthly_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old_student_receipt_items`
--
ALTER TABLE `old_student_receipt_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_status`
--
ALTER TABLE `payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_id`
--
ALTER TABLE `receipt_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_items_amount`
--
ALTER TABLE `receipt_items_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_items_amount_on_student`
--
ALTER TABLE `receipt_items_amount_on_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_on_section`
--
ALTER TABLE `student_on_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_archive`
--
ALTER TABLE `transaction_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_notcurrentyear`
--
ALTER TABLE `transaction_notcurrentyear`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_receipt_item`
--
ALTER TABLE `transaction_receipt_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_for_setup`
--
ALTER TABLE `users_for_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authentication_for_setup`
--
ALTER TABLE `authentication_for_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `due`
--
ALTER TABLE `due`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `extra_added_items`
--
ALTER TABLE `extra_added_items`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `ipcheck_for_setup`
--
ALTER TABLE `ipcheck_for_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `monthly_transaction`
--
ALTER TABLE `monthly_transaction`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1096;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `old_student_receipt_items`
--
ALTER TABLE `old_student_receipt_items`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_status`
--
ALTER TABLE `payment_status`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111607;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `receipt_id`
--
ALTER TABLE `receipt_id`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71258;

--
-- AUTO_INCREMENT for table `receipt_items`
--
ALTER TABLE `receipt_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `receipt_items_amount`
--
ALTER TABLE `receipt_items_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25568;

--
-- AUTO_INCREMENT for table `receipt_items_amount_on_student`
--
ALTER TABLE `receipt_items_amount_on_student`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=606522;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7014;

--
-- AUTO_INCREMENT for table `student_on_section`
--
ALTER TABLE `student_on_section`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16220;

--
-- AUTO_INCREMENT for table `transaction_archive`
--
ALTER TABLE `transaction_archive`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71309;

--
-- AUTO_INCREMENT for table `transaction_notcurrentyear`
--
ALTER TABLE `transaction_notcurrentyear`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30211;

--
-- AUTO_INCREMENT for table `transaction_receipt_item`
--
ALTER TABLE `transaction_receipt_item`
  MODIFY `id` bigint(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=368735;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_for_setup`
--
ALTER TABLE `users_for_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
