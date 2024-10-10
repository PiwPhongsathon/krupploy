-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 06:50 PM
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
-- Database: `learningwebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `cover_image` varchar(255) NOT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_content` text NOT NULL,
  `subject` enum('ภาษาอังกฤษ','ภาษาไทย','คณิตศาสตร์') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `cover_image`, `video_link`, `course_name`, `course_content`, `subject`, `price`, `user_id`, `rating`) VALUES
(66, '66e2f33eb1f50.jpg', '66e2f33eb1f54.mp4', 'สอนการจัดการ', 'ตรวจสอบ $course_id: เราได้เพิ่มการตรวจสอบและกำหนดค่า $course_id ตั้งแต่ต้น และส่งค่า $course_id ไปในฟอร์มเพื่อให้สามารถทำการ Redirect กลับไปยังหน้ารายละเอียดคอร์ส (course_detail.php) ได้ถูกต้อง', 'คณิตศาสตร์', 500.00, 18, 0),
(67, '66e3c1e16a213.jpg', '66e3c1e16a217.mp4', 'Horizontal Dividers วงเวียนแนวนอน', 'เพิ่มคุณสมบัติ [border-bottom: ……..;] เส้นด้านล่างหรือ [border-top: ……..;] เส้นด้านบน ให้  และ  จาก Horizontal Dividers', 'ภาษาไทย', 1000.00, 18, 0),
(68, '66e3c720d71b7.png', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/preview', 'สอนคณิตศาสตร์ เครื่องหมาย มากก่ว่าน้อยกว่า', 'Where does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &#34;de Finibus Bonorum et Malorum&#34; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &#34;Lorem ipsum dolor sit amet..&#34;, comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &#34;de Finibus Bonorum et Malorum&#34; by Cicero are also reproduced in their exact or', 'คณิตศาสตร์', 350.00, 18, 0),
(69, '66e3cfe72f22d.jpg', '66e3cfe72f230.mp4', 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic', 'ภาษาอังกฤษ', 200.00, 6, 0),
(76, '66ea3ecd6da30.png', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/preview', 'คณิตศาสตร์ เรื่อง เครื่องหมายมากกว่าน้อยกว่าหรือเท่ากับ', 'จำนวน 2 จำนวน อาจมีค่าเท่ากันหรือไม่เท่ากัน สามารถเปรียบเทียบจำนวนได้โดยใช้เครื่องหมายสัญลักษณ์ ดังนี้\r\n\r\n    =    เครื่องหมายเท่ากับ        ≠    เครื่องหมายไม่เท่ากับ\r\n\r\n    >    เครื่องหมายมากกว่า            89\r\n\r\n    2. กรณีที่จำนวนหลักเท่ากัน ให้ดูตัวเลขตัวแรกทางซ้ายมือเปรียบเทียบกัน เช่น\r\n\r\n        545    ', 'คณิตศาสตร์', 500.00, 6, 0),
(77, '66ea453835bb1.png', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/preview', 'เรียนรู้พยัญชนะไทย', 'เรียนรู้พยัญชนะไทย\r\n\r\nสาระสำคัญ/ความคิดรวมยอด\r\n           พยัญชนะไทย ก-ฮ ประกอบไปด้วย 44 ตัว ที่มีรูปแบบตัวอักษร คล้ายกัน และเสียงของตัวอักษร\r\nที่เหมือนกันและแตกต่างกัน\r\n\r\nตัวชี้วัด/จุดประสงค์การเรียนรู้\r\n  \r\n\r\n \r\n\r\n         1 ด้านความรู้ ความเข้าใจ (K)\r\n\r\n                    - บอกชื่อพยัญชนะไทยได้\r\n\r\n           2 ด้านทักษะ/กระบวนการ (P)\r\n\r\n                    - อ่านและเขียนพยัญชนะไทยได้\r\n\r\n            3 ด้านคุณลักษณะ เจตคติ ค่านิยม (A)\r\n\r\n                   - เห็นความสำคัญของการอ่านและเขียนพยัญชนะไทย\r\n', 'ภาษาไทย', 250.00, 6, 0),
(78, '66ea4626d35b5.png', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/preview', 'Nice to Meet You', 'การทักทาย การแนะนำตัวและการทำความรู้จักเพื่อนใหม่ มีความสำคัญที่ผู้พูดและผู้ฟังจำเป็นต้องสื่อสารให้ถูกต้อง เพื่อความเข้าใจที่ตรงกันและถูกต้องเหมาะสมตามวัฒนธรรมของเจ้าของภาษา', 'ภาษาอังกฤษ', 500.00, 6, 0),
(79, '66ea55caae95e.png', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/preview', '123123', '13123123', 'ภาษาอังกฤษ', 123.00, 6, 0),
(87, '4GoOrqYlPLPG6u1852Wp.png', NULL, 'Hello! How Are You?', 'Where does it come from?\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &#34;de Finibus Bonorum et Malorum&#34; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &#34;Lorem ipsum dolor sit amet..&#34;, comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &#34;de Finibus Bonorum et Malorum&#34; by Cicero are also reproduced in their exact or', 'ภาษาอังกฤษ', 150.00, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course_purchases`
--

CREATE TABLE `course_purchases` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `slip_image` varchar(255) DEFAULT NULL,
  `status` enum('รอตรวจสอบ','ยืนยันแล้ว') DEFAULT 'รอตรวจสอบ',
  `confirmation_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `course_purchases`
--

INSERT INTO `course_purchases` (`id`, `student_id`, `course_id`, `purchase_date`, `slip_image`, `status`, `confirmation_date`, `expiry_date`, `price`) VALUES
(50, 4, 66, '2024-09-12 20:59:37', '66e2f3c960f50.jpg', 'ยืนยันแล้ว', '2024-09-12 21:00:01', '2024-09-12 21:10:01', 500),
(57, 14, 68, '2024-09-13 12:04:53', '66e3c7f5c9da6.jpg', 'ยืนยันแล้ว', '2024-09-13 12:05:25', '2024-09-13 12:15:25', 350),
(65, 4, 68, '2024-09-16 15:11:53', '66e7e8493805f.png', 'ยืนยันแล้ว', '2024-09-16 16:14:50', '2024-09-16 16:24:50', 350),
(75, 4, 76, '2024-09-18 11:08:48', '66ea5250cc2ea.png', 'ยืนยันแล้ว', '2024-09-18 11:09:04', '2024-09-18 11:19:04', 500),
(76, 4, 78, '2024-09-18 11:20:31', '66ea550fd6afc.png', 'ยืนยันแล้ว', '2024-09-18 11:21:08', '2024-09-18 11:31:08', 500),
(80, 14, 77, '2024-10-08 19:11:56', '6705218cb170a.png', 'ยืนยันแล้ว', '2024-10-08 19:12:20', '2024-10-08 19:22:20', 250),
(81, 4, 77, '2024-10-08 19:27:33', '67052535e1e8c.png', 'ยืนยันแล้ว', '2024-10-08 19:27:52', '2024-10-08 19:37:52', 250),
(83, 4, 87, '2024-10-10 22:45:56', '6707f6b456670.png', 'ยืนยันแล้ว', '2024-10-10 22:46:07', '2024-10-10 22:56:07', 123);

-- --------------------------------------------------------

--
-- Table structure for table `course_ratings`
--

CREATE TABLE `course_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `course_ratings`
--

INSERT INTO `course_ratings` (`id`, `user_id`, `course_id`, `rating`, `created_at`) VALUES
(1, 4, 62, 5, '2024-09-12 14:14:48'),
(2, 14, 62, 3, '2024-09-12 14:26:16'),
(3, 14, 68, 5, '2024-09-13 05:07:07'),
(4, 14, 56, 3, '2024-09-13 14:02:39'),
(5, 4, 71, 5, '2024-09-16 02:55:21'),
(6, 4, 68, 5, '2024-09-16 09:26:34'),
(7, 4, 16, 5, '2024-09-16 12:40:20'),
(8, 4, 10, 5, '2024-09-17 13:41:51'),
(9, 4, 77, 5, '2024-09-18 03:44:01'),
(10, 4, 78, 5, '2024-09-18 04:22:20'),
(11, 14, 77, 5, '2024-10-08 12:13:12'),
(12, 4, 76, 1, '2024-10-10 16:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `exercise_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `course_id`, `exercise_name`, `created_at`) VALUES
(4, 66, 'ตอบถูกรับไปเลย', '2024-09-12 13:58:46'),
(26, 6, 'ตอบถูกรับไปเลย', '2024-09-16 07:42:55'),
(27, 16, 'Hello WORld', '2024-09-16 12:38:57'),
(28, 76, 'ตอบถูกรับไปเลย', '2024-09-18 04:08:03'),
(29, 79, 'ตอบถูกรับไปเลย', '2024-09-18 04:24:41'),
(32, 78, 'ตอบถูกรับไปเลย', '2024-09-18 04:30:03'),
(33, 69, 'หกฟไกฟไกฟไกฟกไก', '2024-09-30 10:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_answers`
--

CREATE TABLE `exercise_answers` (
  `id` int(11) NOT NULL,
  `result_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `choice_id` int(11) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_choices`
--

CREATE TABLE `exercise_choices` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `choice_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `exercise_choices`
--

INSERT INTO `exercise_choices` (`id`, `question_id`, `choice_text`, `is_correct`) VALUES
(1, 1, 'ๅ/ๅภๅภ', 0),
(2, 1, 'ถูก', 1),
(3, 1, 'ภๅ/ภๅภ', 0),
(4, 1, 'ๅ/ภๅภ/', 0),
(5, 2, 'ภูก', 1),
(6, 2, 'ก', 0),
(7, 2, 'ด', 0),
(8, 2, 'ด', 0),
(9, 3, 'ๅ/ๅภๅภ', 0),
(10, 3, 'ถูก', 1),
(11, 3, 'ภๅ/ภๅภ', 0),
(12, 3, 'ๅ/ภๅภ/', 0),
(13, 4, 'ภูก', 1),
(14, 4, 'ก', 0),
(15, 4, 'ด', 0),
(16, 4, 'ด', 0),
(17, 5, 'ๅ/ๅภๅภ', 0),
(18, 5, 'ถูก', 1),
(19, 5, 'ภๅ/ภๅภ', 0),
(20, 5, 'ๅ/ภๅภ/', 0),
(21, 6, 'ภูก', 1),
(22, 6, 'ก', 0),
(23, 6, 'ด', 0),
(24, 6, 'ด', 0),
(25, 7, 'ถูก', 1),
(26, 7, 'ผิด', 0),
(27, 7, 'ผิด', 0),
(28, 7, 'ผิด', 0),
(29, 8, 'นอน', 1),
(30, 8, 'เดิน', 0),
(31, 8, 'วิ่ง', 0),
(32, 8, 'นั่ง', 0),
(33, 9, 'วิ่ง', 0),
(34, 9, 'นอน', 0),
(35, 9, 'เดิน', 1),
(36, 9, 'วิ้ง', 0),
(37, 10, 'ถูก', 1),
(38, 10, 'ๅ/ๅภๅภ', 0),
(39, 10, 'ๅ/ๅภๅภ', 0),
(40, 10, 'ๅ/ๅภๅภ', 0),
(41, 11, 'นอน', 0),
(42, 11, 'นอน', 0),
(43, 11, 'นอน', 0),
(44, 11, 'ภูก', 1),
(45, 15, '', 1),
(46, 15, '', 0),
(47, 15, '', 0),
(48, 15, '', 0),
(49, 16, '', 1),
(50, 16, '', 0),
(51, 16, '', 0),
(52, 16, '', 0),
(53, 17, '', 1),
(54, 17, '', 0),
(55, 17, '', 0),
(56, 17, '', 0),
(57, 18, 'ถูก', 1),
(58, 18, 'ถูก', 0),
(59, 18, 'ถูก', 0),
(60, 18, 'ถูก', 0),
(61, 19, 'ถูก', 1),
(62, 19, 'ถูก', 0),
(63, 19, 'ถูก', 0),
(64, 19, 'ถูก', 0),
(65, 20, 'ถูก', 1),
(66, 20, 'ๅ/ๅภๅภ', 0),
(67, 20, 'ถูก', 0),
(68, 20, 'ๅ/ๅภๅภ', 0),
(69, 21, 'ถูก', 1),
(70, 21, 'ๅ/ๅภๅภ', 0),
(71, 21, 'ถูก', 0),
(72, 21, 'ๅ/ๅภๅภ', 0),
(73, 22, 'ถูก', 1),
(74, 22, 'ถูก', 0),
(75, 22, 'ถูก', 0),
(76, 22, 'ๅ/ๅภๅภ', 0),
(77, 23, 'ๅ', 0),
(78, 23, 'ๅ', 1),
(79, 23, 'ๅ', 0),
(80, 23, 'ๅ', 0),
(81, 24, 'ๅ', 1),
(82, 24, 'ๅ', 0),
(83, 24, 'ๅ', 0),
(84, 24, 'ๅ', 0),
(85, 25, 'ๅ-', 1),
(86, 25, '-ๅ-', 0),
(87, 25, '-', 0),
(88, 25, '-ๅ', 0),
(89, 26, 'ๅ', 1),
(90, 26, 'ๅ', 0),
(91, 26, 'ๅ', 0),
(92, 26, 'ๅ', 0),
(93, 27, 'ๅ', 1),
(94, 27, 'ๅ', 0),
(95, 27, 'ๅ', 0),
(96, 27, 'ๅ', 0),
(97, 28, '1', 1),
(98, 28, '1', 0),
(99, 28, '1', 0),
(100, 28, '1', 0),
(101, 29, 'ๅ', 1),
(102, 29, 'ๅๅ', 0),
(103, 29, 'ๅ', 0),
(104, 29, 'ๅ', 0),
(105, 30, 'ๅ', 0),
(106, 30, 'ๅ', 1),
(107, 30, 'ๅ', 0),
(108, 30, 'ๅ', 0),
(109, 31, '3123', 1),
(110, 31, '12', 0),
(111, 31, '1', 0),
(112, 31, '1', 0),
(113, 32, '13', 0),
(114, 32, '1231313', 0),
(115, 32, '131', 0),
(116, 32, '1', 1),
(117, 33, '123', 0),
(118, 33, '123', 0),
(119, 33, '123', 1),
(120, 33, '321', 0),
(121, 34, '123', 0),
(122, 34, '231', 0),
(123, 34, '231', 1),
(124, 34, '231', 0),
(125, 35, '1231', 0),
(126, 35, '3131', 0),
(127, 35, '1', 1),
(128, 35, '313', 0),
(129, 36, '123', 0),
(130, 36, '32', 0),
(131, 36, '32', 0),
(132, 36, '2', 1),
(133, 37, '23', 0),
(134, 37, '22', 1),
(135, 37, '1', 0),
(136, 37, '2', 0),
(137, 38, 'ถูก', 1),
(138, 38, 'ๅ', 0),
(139, 38, 'ๅ/ๅภๅภ', 0),
(140, 38, '3123', 0),
(141, 39, 'นอน', 0),
(142, 39, 'ภูก', 1),
(143, 39, '123', 0),
(144, 39, '123', 0),
(145, 40, '312', 1),
(146, 40, '312', 0),
(147, 40, '32', 0),
(148, 40, '3', 0),
(149, 42, '13', 1),
(150, 42, '23', 0),
(151, 42, '2', 0),
(152, 42, '3', 0),
(153, 43, '123', 0),
(154, 43, '132', 0),
(155, 43, '132', 1),
(156, 43, '321', 0),
(157, 44, 'ๅ/-', 0),
(158, 44, 'ๅ/-', 0),
(159, 44, 'ๅ-/', 1),
(160, 44, '/ๅ-', 0),
(161, 45, 'ๅ/-', 1),
(162, 45, 'ๅ/-', 0),
(163, 45, 'ๅ-/', 0),
(164, 45, '-ๅ/', 0),
(165, 46, 'ฟไกฟไกฟ', 0),
(166, 46, 'กฟไกฟไกฟไกฟ', 0),
(167, 46, 'กฟกฟกฟกกก', 0),
(168, 46, 'กกกกกก', 1),
(169, 47, 'ฟไกฟไกฟก', 0),
(170, 47, 'ฟไกฟกฟก', 0),
(171, 47, 'ฟกฟกฟก', 0),
(172, 47, 'ฟกฟไก', 1),
(173, 48, 'กไฟกฟ', 1),
(174, 48, 'ไกก', 0),
(175, 48, 'ก', 0),
(176, 48, 'ก', 0),
(177, 49, '1', 0),
(178, 49, '1', 1),
(179, 49, '1', 0),
(180, 49, '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `exercise_questions`
--

CREATE TABLE `exercise_questions` (
  `id` int(11) NOT NULL,
  `exercise_id` int(11) DEFAULT NULL,
  `question_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `exercise_questions`
--

INSERT INTO `exercise_questions` (`id`, `exercise_id`, `question_text`, `created_at`) VALUES
(3, 2, 'ทำไง', '2024-09-12 09:48:14'),
(4, 2, 'ทำยังไง', '2024-09-12 09:48:14'),
(5, 3, 'ทำไง', '2024-09-12 09:48:28'),
(6, 3, 'ทำยังไง', '2024-09-12 09:48:29'),
(7, 4, 'การหาตัวแปรของ', '2024-09-12 13:58:46'),
(8, 4, 'การนอนคือ', '2024-09-12 13:58:46'),
(9, 4, 'การเดินคือ', '2024-09-12 13:58:46'),
(10, 5, 'การหาตัวแปรของ', '2024-09-13 05:04:17'),
(11, 5, 'ทำไง', '2024-09-13 05:04:17'),
(12, 6, '', '2024-09-13 06:09:57'),
(13, 7, '', '2024-09-13 06:10:18'),
(14, 8, '', '2024-09-13 06:10:19'),
(15, 9, '', '2024-09-13 06:14:50'),
(16, 10, '', '2024-09-13 06:15:14'),
(17, 11, '', '2024-09-13 06:15:18'),
(18, 12, 'การหาตัวแปรของ', '2024-09-13 06:18:18'),
(19, 13, 'การหาตัวแปรของ', '2024-09-13 06:20:25'),
(20, 14, 'ทำไง', '2024-09-13 06:26:05'),
(32, 26, 'การหาตัวแปรของ', '2024-09-16 07:42:55'),
(33, 27, '213123123', '2024-09-16 12:38:57'),
(34, 27, '123', '2024-09-16 12:38:57'),
(35, 27, '1231231', '2024-09-16 12:38:57'),
(36, 27, '3123', '2024-09-16 12:38:57'),
(37, 27, '123', '2024-09-16 12:38:57'),
(38, 28, 'การหาตัวแปรของ', '2024-09-18 04:08:03'),
(39, 28, 'ทำไง', '2024-09-18 04:08:03'),
(40, 29, 'การหาตัวแปรของ', '2024-09-18 04:24:41'),
(41, 29, 'ทำไง', '2024-09-18 04:24:41'),
(44, 32, '-ๅ/-ๅ', '2024-09-18 04:30:03'),
(45, 32, 'การหาตัวแปรของ', '2024-09-18 04:30:03'),
(46, 33, 'ฟไกฟไกฟไก', '2024-09-30 10:20:28'),
(47, 33, 'กฟไกฟกฟก', '2024-09-30 10:20:28'),
(48, 33, 'เพกเพำเ', '2024-09-30 10:20:28');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_results`
--

CREATE TABLE `exercise_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `exercise_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `exercise_results`
--

INSERT INTO `exercise_results` (`id`, `user_id`, `exercise_id`, `score`, `completed_at`, `created_at`) VALUES
(1, 4, 1, 1, '2024-09-12 10:19:32', '2024-09-12 10:28:23'),
(2, 4, 1, 2, '2024-09-12 10:19:37', '2024-09-12 10:28:23'),
(3, 4, 1, 0, '2024-09-12 10:28:56', '2024-09-12 10:28:56'),
(4, 4, 1, 0, '2024-09-12 10:30:52', '2024-09-12 10:30:52'),
(5, 4, 1, 1, '2024-09-12 10:40:47', '2024-09-12 10:40:47'),
(6, 4, 1, 1, '2024-09-12 10:41:22', '2024-09-12 10:41:22'),
(7, 4, 1, 1, '2024-09-12 10:41:57', '2024-09-12 10:41:57'),
(8, 4, 1, 1, '2024-09-12 10:42:03', '2024-09-12 10:42:03'),
(9, 4, 1, 2, '2024-09-12 10:49:56', '2024-09-12 10:49:56'),
(10, 4, 1, 2, '2024-09-12 10:59:19', '2024-09-12 10:59:19'),
(11, 4, 1, 1, '2024-09-12 11:12:31', '2024-09-12 11:12:31'),
(12, 4, 1, 1, '2024-09-12 11:13:44', '2024-09-12 11:13:44'),
(13, 4, 1, 1, '2024-09-12 11:13:45', '2024-09-12 11:13:45'),
(14, 4, 1, 1, '2024-09-12 11:14:02', '2024-09-12 11:14:02'),
(15, 4, 1, 1, '2024-09-12 11:14:03', '2024-09-12 11:14:03'),
(16, 4, 1, 2, '2024-09-12 11:14:11', '2024-09-12 11:14:11'),
(17, 4, 1, 2, '2024-09-12 11:14:30', '2024-09-12 11:14:30'),
(18, 4, 1, 2, '2024-09-12 11:20:28', '2024-09-12 11:20:28'),
(19, 4, 1, 2, '2024-09-12 11:21:21', '2024-09-12 11:21:21'),
(20, 4, 1, 2, '2024-09-12 11:21:40', '2024-09-12 11:21:40'),
(21, 4, 1, 2, '2024-09-12 11:27:25', '2024-09-12 11:27:25'),
(22, 4, 1, 2, '2024-09-12 11:28:45', '2024-09-12 11:28:45'),
(23, 4, 1, 2, '2024-09-12 11:28:48', '2024-09-12 11:28:48'),
(24, 4, 1, 2, '2024-09-12 11:28:51', '2024-09-12 11:28:51'),
(25, 4, 1, 1, '2024-09-12 11:29:02', '2024-09-12 11:29:02'),
(26, 4, 1, 2, '2024-09-12 11:29:34', '2024-09-12 11:29:34'),
(27, 4, 1, 1, '2024-09-12 11:35:20', '2024-09-12 11:35:20'),
(28, 4, 1, 2, '2024-09-12 11:35:29', '2024-09-12 11:35:29'),
(29, 4, 1, 0, '2024-09-12 11:35:38', '2024-09-12 11:35:38'),
(30, 4, 1, 2, '2024-09-12 11:36:14', '2024-09-12 11:36:14'),
(31, 4, 1, 2, '2024-09-12 11:45:56', '2024-09-12 11:45:56'),
(32, 4, 1, 2, '2024-09-12 11:46:06', '2024-09-12 11:46:06'),
(33, 4, 1, 1, '2024-09-12 11:46:12', '2024-09-12 11:46:12'),
(34, 4, 4, 3, '2024-09-12 14:01:15', '2024-09-12 14:01:15'),
(35, 14, 5, 2, '2024-09-13 05:06:33', '2024-09-13 05:06:33'),
(36, 14, 5, 0, '2024-09-13 05:06:46', '2024-09-13 05:06:46'),
(37, 4, 1, 1, '2024-09-13 06:35:25', '2024-09-13 06:35:25'),
(38, 4, 5, 1, '2024-09-16 10:19:27', '2024-09-16 10:19:27'),
(39, 4, 5, 2, '2024-09-16 10:19:51', '2024-09-16 10:19:51'),
(40, 4, 5, 2, '2024-09-16 12:16:11', '2024-09-16 12:16:11'),
(41, 4, 27, 0, '2024-09-16 12:40:02', '2024-09-16 12:40:02'),
(42, 4, 27, 1, '2024-09-16 12:40:12', '2024-09-16 12:40:12'),
(43, 4, 5, 0, '2024-09-17 13:39:49', '2024-09-17 13:39:49'),
(44, 4, 5, 0, '2024-09-17 17:56:36', '2024-09-17 17:56:36'),
(45, 4, 28, 1, '2024-09-18 04:09:36', '2024-09-18 04:09:36'),
(46, 4, 28, 2, '2024-09-18 04:22:49', '2024-09-18 04:22:49'),
(47, 4, 30, 0, '2024-09-18 04:27:16', '2024-09-18 04:27:16'),
(60, 4, 28, 1, '2024-10-10 16:18:14', '2024-10-10 16:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_content` text DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `lesson_title`, `lesson_content`, `video_link`, `created_at`) VALUES
(4, 87, '(1) My Clothes ', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/view?usp=sharing', '2024-10-10 14:05:54'),
(5, 87, '(2) What Does She Look Like', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'https://drive.google.com/file/d/1atzxLh9g5GHClD_Z3or43GoTtmcUdSnJ/view?usp=sharing', '2024-10-10 14:05:54');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profiles`
--

CREATE TABLE `teacher_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `university` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('รอตรวจสอบ','ยืนยันแล้ว') NOT NULL DEFAULT 'รอตรวจสอบ',
  `confirmed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_profiles`
--

INSERT INTO `teacher_profiles` (`id`, `user_id`, `nickname`, `full_name`, `faculty`, `university`, `created_at`, `status`, `confirmed_at`) VALUES
(1, 6, 'พิว', 'นาย พงศธร จันทร์ทิพย์', 'เทคโนโลยีสารสนเทศ', 'มหาวิทยาลัยเทคโนโลยีราชมงคลรัตนโกสินทร์', '2024-09-29 13:37:45', 'ยืนยันแล้ว', '2024-09-30 17:18:21'),
(2, 18, 'Peter', 'ปีเตอร์ เดนแมน', 'แพทยศาสตร์', 'จุฬาลงกรณ์มหาวิทยาลัย', '2024-09-29 13:48:59', 'ยืนยันแล้ว', '2024-10-10 21:49:55'),
(39, 21, 'ชื่อเล่นพิว', 'พงศธร จันทร์ทิพย์', 'วิศวกรรมศาสตร์', 'จุฬาลงกรณ์มหาวิทยาลัย', '2024-10-08 14:05:29', 'ยืนยันแล้ว', '2024-10-08 21:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `profile_pic` varchar(100) NOT NULL,
  `bank_slip_image` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `fname`, `lname`, `role`, `profile_pic`, `bank_slip_image`, `reset_token`, `token_expiry`) VALUES
(4, 'test1@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'เด็กชายสมหมาย', 'มีเงิน', 'student', '66d88bad71084.jpg', '', '2d58d01e6e9d77c848a37c41dda2c8d3', '2024-09-30 14:45:42'),
(6, 'admin1@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'พงศธร', 'จันทร์ทิพย์', 'teacher', '66f95b402f2ea.JPG', '66d2c8be34f4d.jpg', NULL, NULL),
(9, 'ploysaiis13@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'ครู', 'พลอย', 'teacher', '66d0a353ef416.JPG', '', 'f107f778bc5f7d1646fc37c6e00847ec', '2024-09-30 14:49:55'),
(11, 'test2@gmail.com', '761c7920f470038d4c8a619c79eddd62', '12', '12', 'student', '66d09fe8489b4.png', '', NULL, NULL),
(12, 'test3@gmail.com', 'c81e728d9d4c2f636f067f89cc14862c', 'test', '1', 'student', '66d8a75d7b4eb.jpg', '', NULL, NULL),
(14, 'test4@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'เด็กหญิง ไลลา', 'สว่างเสนา', 'student', '66d8aa6ac85cb.jpg', '', NULL, NULL),
(15, 'test5@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'ชอบเรียนครับ', '1233123123', 'student', '', '', NULL, NULL),
(16, 'test6@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', 'student', '66d8aaf202e3c.jpg', '', NULL, NULL),
(17, 'test12@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '1', '1', 'student', '', '', NULL, NULL),
(18, 'admin2@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'Admin', 'Test', 'teacher', '66e2f2b1bbc0a.jpg', '66e2f2fa4d69b.jpg', NULL, NULL),
(19, 'phongsathonpiw@gmail.com', '', 'Phongsathon Chanthip', '', 'student', '', '', 'a2f62df7a3223b766a7e63fcfb15e444', '2024-09-30 15:00:05'),
(20, 'kulwadee45@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'นิว', 'นิว', 'student', '', '', '3edee343d303b6cfeb63d1fe03c159e7', '2024-09-30 14:49:24'),
(21, '1641051641128@rmutr.ac.th', 'c81e728d9d4c2f636f067f89cc14862c', 'พิว', 'พงศธร', 'teacher', '67053cc3dd97e.jfif', '67053cf1c4b39.jpg', '0d3c252cc6497093b375e4d2a1bc4a5a', '2024-09-30 15:18:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `course_purchases`
--
ALTER TABLE `course_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_ratings`
--
ALTER TABLE `course_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercise_answers`
--
ALTER TABLE `exercise_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `choice_id` (`choice_id`);

--
-- Indexes for table `exercise_choices`
--
ALTER TABLE `exercise_choices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercise_questions`
--
ALTER TABLE `exercise_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercise_results`
--
ALTER TABLE `exercise_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `course_purchases`
--
ALTER TABLE `course_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `course_ratings`
--
ALTER TABLE `course_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `exercise_answers`
--
ALTER TABLE `exercise_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exercise_choices`
--
ALTER TABLE `exercise_choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `exercise_questions`
--
ALTER TABLE `exercise_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `exercise_results`
--
ALTER TABLE `exercise_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `course_purchases`
--
ALTER TABLE `course_purchases`
  ADD CONSTRAINT `course_purchases_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_purchases_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exercise_answers`
--
ALTER TABLE `exercise_answers`
  ADD CONSTRAINT `exercise_answers_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `exercise_results` (`id`),
  ADD CONSTRAINT `exercise_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `exercise_questions` (`id`),
  ADD CONSTRAINT `exercise_answers_ibfk_3` FOREIGN KEY (`choice_id`) REFERENCES `exercise_choices` (`id`);

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD CONSTRAINT `fk_teacher_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
