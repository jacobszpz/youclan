-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2021 at 05:00 AM
-- Server version: 5.7.23-23-log
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `main_youclan_uk`
--
CREATE DATABASE IF NOT EXISTS `main_youclan_uk` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `main_youclan_uk`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int(10) UNSIGNED NOT NULL,
  `ParentID` int(10) UNSIGNED NOT NULL,
  `PosterID` int(10) UNSIGNED NOT NULL,
  `Content` text COLLATE utf8_unicode_ci,
  `Roses` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `PostTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`ID`, `Name`) VALUES
(1, 'Afghanistan'),
(2, 'Albania'),
(3, 'Algeria'),
(4, 'Andorra'),
(5, 'Angola'),
(6, 'Antigua and Barbuda'),
(7, 'Argentina'),
(8, 'Armenia'),
(9, 'Australia'),
(10, 'Austria'),
(11, 'Azerbaijan'),
(12, 'The Bahamas'),
(13, 'Bahrain'),
(14, 'Bangladesh'),
(15, 'Barbados'),
(16, 'Belarus'),
(17, 'Belgium'),
(18, 'Belize'),
(19, 'Benin'),
(20, 'Bhutan'),
(21, 'Bolivia'),
(22, 'Bosnia and Herzegovina'),
(23, 'Botswana'),
(24, 'Brazil'),
(25, 'Brunei'),
(26, 'Bulgaria'),
(27, 'Burkina Faso'),
(28, 'Burundi'),
(29, 'Cambodia'),
(30, 'Cameroon'),
(31, 'Canada'),
(32, 'Cape Verde'),
(33, 'Central African Republic'),
(34, 'Chad'),
(35, 'Chile'),
(36, 'China'),
(37, 'Colombia'),
(38, 'Comoros'),
(39, 'Congo, Republic of the'),
(40, 'Congo, Democratic Republic of the'),
(41, 'Costa Rica'),
(42, 'Cote dâ€™Ivoire'),
(43, 'Croatia'),
(44, 'Cuba'),
(45, 'Cyprus'),
(46, 'Czech Republic'),
(47, 'Denmark'),
(48, 'Djibouti'),
(49, 'Dominica'),
(50, 'Dominican Republic'),
(51, 'East Timor (Timor-Leste)'),
(52, 'Ecuador'),
(53, 'Egypt'),
(54, 'El Salvador'),
(55, 'Equatorial Guinea'),
(56, 'Eritrea'),
(57, 'Estonia'),
(58, 'Ethiopia'),
(59, 'Fiji'),
(60, 'Finland'),
(61, 'France'),
(62, 'Gabon'),
(63, 'The Gambia'),
(64, 'Georgia'),
(65, 'Germany'),
(66, 'Ghana'),
(67, 'Greece'),
(68, 'Grenada'),
(69, 'Guatemala'),
(70, 'Guinea'),
(71, 'Guinea-Bissau'),
(72, 'Guyana'),
(73, 'Haiti'),
(74, 'Honduras'),
(75, 'Hungary'),
(76, 'Iceland'),
(77, 'India'),
(78, 'Indonesia'),
(79, 'Iran'),
(80, 'Iraq'),
(81, 'Ireland'),
(82, 'Israel'),
(83, 'Italy'),
(84, 'Jamaica'),
(85, 'Japan'),
(86, 'Jordan'),
(87, 'Kazakhstan'),
(88, 'Kenya'),
(89, 'Kiribati'),
(90, 'Korea, North'),
(91, 'Korea, South'),
(92, 'Kosovo'),
(93, 'Kuwait'),
(94, 'Kyrgyzstan'),
(95, 'Laos'),
(96, 'Latvia'),
(97, 'Lebanon'),
(98, 'Lesotho'),
(99, 'Liberia'),
(100, 'Libya'),
(101, 'Liechtenstein'),
(102, 'Lithuania'),
(103, 'Luxembourg'),
(104, 'Macedonia'),
(105, 'Madagascar'),
(106, 'Malawi'),
(107, 'Malaysia'),
(108, 'Maldives'),
(109, 'Mali'),
(110, 'Malta'),
(111, 'Marshall Islands'),
(112, 'Mauritania'),
(113, 'Mauritius'),
(114, 'Mexico'),
(115, 'Micronesia, Federated States of'),
(116, 'Moldova'),
(117, 'Monaco'),
(118, 'Mongolia'),
(119, 'Montenegro'),
(120, 'Morocco'),
(121, 'Mozambique'),
(122, 'Myanmar (Burma)'),
(123, 'Namibia'),
(124, 'Nauru'),
(125, 'Nepal'),
(126, 'Netherlands'),
(127, 'New Zealand'),
(128, 'Nicaragua'),
(129, 'Niger'),
(130, 'Nigeria'),
(131, 'Norway'),
(132, 'Oman'),
(133, 'Pakistan'),
(134, 'Palau'),
(135, 'Panama'),
(136, 'Papua New Guinea'),
(137, 'Paraguay'),
(138, 'Peru'),
(139, 'Philippines'),
(140, 'Poland'),
(141, 'Portugal'),
(142, 'Qatar'),
(143, 'Romania'),
(144, 'Russia'),
(145, 'Rwanda'),
(146, 'Saint Kitts and Nevis'),
(147, 'Saint Lucia'),
(148, 'Saint Vincent and the Grenadines'),
(149, 'Samoa'),
(150, 'San Marino'),
(151, 'Sao Tome and Principe'),
(152, 'Saudi Arabia'),
(153, 'Senegal'),
(154, 'Serbia'),
(155, 'Seychelles'),
(156, 'Sierra Leone'),
(157, 'Singapore'),
(158, 'Slovakia'),
(159, 'Slovenia'),
(160, 'Solomon Islands'),
(161, 'Somalia'),
(162, 'South Africa'),
(163, 'South Sudan'),
(164, 'Spain'),
(165, 'Sri Lanka'),
(166, 'Sudan'),
(167, 'Suriname'),
(168, 'Swaziland'),
(169, 'Sweden'),
(170, 'Switzerland'),
(171, 'Syria'),
(172, 'Taiwan'),
(173, 'Tajikistan'),
(174, 'Tanzania'),
(175, 'Thailand'),
(176, 'Togo'),
(177, 'Tonga'),
(178, 'Trinidad and Tobago'),
(179, 'Tunisia'),
(180, 'Turkey'),
(181, 'Turkmenistan'),
(182, 'Tuvalu'),
(183, 'Uganda'),
(184, 'Ukraine'),
(185, 'United Arab Emirates'),
(186, 'United Kingdom'),
(187, 'United States of America'),
(188, 'Uruguay'),
(189, 'Uzbekistan'),
(190, 'Vanuatu'),
(191, 'Vatican City (Holy See)'),
(192, 'Venezuela'),
(193, 'Vietnam'),
(194, 'Yemen'),
(195, 'Zambia'),
(196, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `School` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`ID`, `Name`, `School`) VALUES
(1, 'Accounting (ACCA)', NULL),
(2, 'Accounting and Finance', NULL),
(3, 'Accounting and Financial Management (CIMA)', NULL),
(4, 'Accounting and Financial Studies', NULL),
(5, 'Acting', NULL),
(6, 'Additive Manufacturing', NULL),
(7, 'Advanced Community Justice', NULL),
(8, 'Advanced Pharmacy Practice', NULL),
(9, 'Advanced Restorative and Periodontal Practice', NULL),
(10, 'Aerospace Engineering', NULL),
(11, 'Aerospace Engineering with Pilot Studies', NULL),
(12, 'Agile Leadership', NULL),
(13, 'Animation', NULL),
(14, 'Antiques', NULL),
(15, 'Applied Clinical Psychology', NULL),
(16, 'Applied Data Science', NULL),
(17, 'Applied Public Health', NULL),
(18, 'Applied Science', NULL),
(19, 'Archaeology', NULL),
(20, 'Archaeology and Anthropology', NULL),
(21, 'Architectural Studies (Hong Kong)', NULL),
(22, 'Architectural Technology Advanced Entry', NULL),
(23, 'Architecture (ARB/RIBA Part 1)', NULL),
(24, 'Architecture (Part II)', NULL),
(25, 'Art and Design', NULL),
(26, 'Arts - Health', NULL),
(27, 'Asia Pacific Studies', NULL),
(28, 'Associate Year Out Programme', NULL),
(29, 'Astronomy', NULL),
(30, 'Astrophysics', NULL),
(31, 'Bachelor of Medicine and Bachelor of Surgery', NULL),
(32, 'Biology', NULL),
(33, 'Biomedical Science', NULL),
(34, 'British Sign Language / English Interpreting and Translation', NULL),
(35, 'British Sign Language and Deaf Studies', NULL),
(36, 'Broadcast Journalism', NULL),
(37, 'Building Conservation and Adaptation', NULL),
(38, 'Building Services and Sustainable Engineering', NULL),
(39, 'Building Surveying', NULL),
(40, 'Business Administration', NULL),
(41, 'Business and Management', NULL),
(42, 'Business and Marketing', NULL),
(43, 'Business Management', NULL),
(44, 'Business Management and Chinese', NULL),
(45, 'Cancer Biology', NULL),
(46, 'Cancer Biology and Therapy', NULL),
(47, 'Ceramics', NULL),
(48, 'Chemistry', NULL),
(49, 'Child and Adolescent Mental Health', NULL),
(50, 'Child Computer Interaction', NULL),
(51, 'Children Schools and Families', NULL),
(52, 'Children, Schools and Families', NULL),
(53, 'Civil Engineering', NULL),
(54, 'Civil Engineering and Construction Management', NULL),
(55, 'Clinical Dental Technology', NULL),
(56, 'Clinical Implantology', NULL),
(57, 'Clinical Periodontology', NULL),
(58, 'Clinical Practice, Management and Education', NULL),
(59, 'Clinical Psychology', NULL),
(60, 'Clinical Research', NULL),
(61, 'Clinical Studies', NULL),
(62, 'Cognitive Behavioural Psychotherapy', NULL),
(63, 'Community and Social Care: Policy and Practice', NULL),
(64, 'Community Health Practice', NULL),
(65, 'Community Leadership', NULL),
(66, 'Community Specialist Practitioner', NULL),
(67, 'Compassionate Leadership', NULL),
(68, 'Computer Aided Engineering', NULL),
(69, 'Computer Games Development', NULL),
(70, 'Computer Networks and Security', NULL),
(71, 'Computer Science', NULL),
(72, 'Computing', NULL),
(73, 'Conflict and Violence Minimisation', NULL),
(74, 'Construction Law & Dispute Resolution', NULL),
(75, 'Construction Project Management', NULL),
(76, 'Counselling and Psychotherapy Studies', NULL),
(77, 'Counter Terrorism', NULL),
(78, 'Creative Advertising', NULL),
(79, 'Creative Arts - Creative Practice', NULL),
(80, 'Criminal Investigation', NULL),
(81, 'Criminal Justice', NULL),
(82, 'Criminology', NULL),
(83, 'Criminology and Criminal Justice', NULL),
(84, 'Criminology and Sociology', NULL),
(85, 'Cyber Security', NULL),
(86, 'Cybercrime Investigation', NULL),
(87, 'Cyberpsychology', NULL),
(88, 'Dance and Somatic Wellbeing', NULL),
(89, 'Dance Performance and Teaching', NULL),
(90, 'Deaf Studies and Education', NULL),
(91, 'Dental Education', NULL),
(92, 'Dental Implantology', NULL),
(93, 'Dental Studies (Dental Care Professionals)', NULL),
(94, 'Dental Therapy', NULL),
(95, 'Dentistry', NULL),
(96, 'Design', NULL),
(97, 'Design Engineering', NULL),
(98, 'Digital Health', NULL),
(99, 'Digital Marketing', NULL),
(100, 'Digital Marketing Communications', NULL),
(101, 'Disaster Medicine', NULL),
(102, 'DNA Profiling', NULL),
(103, 'Doctor of Business Administration', NULL),
(104, 'Doctor of Professional Practice Community Social Care Policy and Practice', NULL),
(105, 'Doctorate in Education, Professional Doctorate', NULL),
(106, 'Drug Discovery and Development', NULL),
(107, 'Education and History', NULL),
(108, 'Education and Psychology', NULL),
(109, 'Education and Sociology', NULL),
(110, 'Education Studies', NULL),
(111, 'Electrical and Electronic Engineering', NULL),
(112, 'Electronic Engineering', NULL),
(113, 'Elite Coaching Practice', NULL),
(114, 'Elite Performance, Professional Doctorate', NULL),
(115, 'Emergency Management in High Hazard Industries', NULL),
(116, 'Endodontology', NULL),
(117, 'Energy Engineering', NULL),
(118, 'English for International Corporate Communication', NULL),
(119, 'English Language and Creative Writing', NULL),
(120, 'English Language and Linguistics', NULL),
(121, 'English Language and Literature', NULL),
(122, 'English Literature', NULL),
(123, 'English Literature and Creative Writing', NULL),
(124, 'English Literature and History', NULL),
(125, 'English with a Modern Language', NULL),
(126, 'Enhanced Paramedic Practice', NULL),
(127, 'Equality and Community Leadership', NULL),
(128, 'Event Management', NULL),
(129, 'Facilitating Learning in Healthcare Practice', NULL),
(130, 'Facilities Management', NULL),
(131, 'Fashion and Lifestyle Brand Studies', NULL),
(132, 'Fashion and Lifestyle Promotion', NULL),
(133, 'Fashion and Textiles', NULL),
(134, 'Fashion Design', NULL),
(135, 'Fashion Promotion', NULL),
(136, 'Film Production', NULL),
(137, 'Film, Media and Popular Culture', NULL),
(138, 'Financial and Commercial Law', NULL),
(139, 'Financial Investigation', NULL),
(140, 'Fine Art', NULL),
(141, 'Fire and Leadership Studies', NULL),
(142, 'Fire and Rescue Service Management', NULL),
(143, 'Fire Engineering', NULL),
(144, 'Fire Safety (Engineering)', NULL),
(145, 'Fire Safety (Management)', NULL),
(146, 'Fire Safety and Risk Management', NULL),
(147, 'Fire Safety Engineering', NULL),
(148, 'Fire Scene Investigation', NULL),
(149, 'Food Safety Management', NULL),
(150, 'Football Coaching and Development', NULL),
(151, 'Football Science and Rehabilitation', NULL),
(152, 'Football Studies', NULL),
(153, 'Forensic Computing and Security', NULL),
(154, 'Forensic Psychology', NULL),
(155, 'Forensic Science', NULL),
(156, 'Forensic Science & Criminal Investigation', NULL),
(157, 'Forensic Science and Chemical Analysis', NULL),
(158, 'Forensic Science and Molecular Biology', NULL),
(159, 'Games Design', NULL),
(160, 'Graduate Diploma in Law (GDL)', NULL),
(161, 'Graphic Design', NULL),
(162, 'Health', NULL),
(163, 'Health and Social Care', NULL),
(164, 'Health Informatics', NULL),
(165, 'Healthcare Practice', NULL),
(166, 'Healthcare Science', NULL),
(167, 'History', NULL),
(168, 'History and Politics', NULL),
(169, 'Human Resource Management', NULL),
(170, 'Human Resource Management / Development', NULL),
(171, 'Illustration', NULL),
(172, 'Industrial Pharmaceutics', NULL),
(173, 'Injection Therapy', NULL),
(174, 'Integrative Psychotherapy', NULL),
(175, 'Intelligent Maintenance Engineering', NULL),
(176, 'Intercultural Business Communication', NULL),
(177, 'Interior Design', NULL),
(178, 'International Business', NULL),
(179, 'International Business and Management', NULL),
(180, 'International Business Communication (IBC)', NULL),
(181, 'International Business Communication with a Modern Foreign Language', NULL),
(182, 'International Business Law', NULL),
(183, 'International Festivals and Event Management', NULL),
(184, 'International Festivals and Tourism Management', NULL),
(185, 'International Hospitality and Event Management', NULL),
(186, 'International Hospitality and Tourism Management', NULL),
(187, 'International Hospitality Management', NULL),
(188, 'International Journalism', NULL),
(189, 'International Medical Sciences', NULL),
(190, 'International Tourism Management', NULL),
(191, 'Internship in International Tourism, Hospitality and Event Management', NULL),
(192, 'Interpreting and Translation', NULL),
(193, 'Investigating Serious Incidents', NULL),
(194, 'IT Security', NULL),
(195, 'Journalism', NULL),
(196, 'Law', NULL),
(197, 'Law with Business', NULL),
(198, 'Law with Criminology', NULL),
(199, 'Law with International Studies', NULL),
(200, 'Leadership and Management in Social Work and Social Care', NULL),
(201, 'Liberal Arts', NULL),
(202, 'LLM in Law and International Security', NULL),
(203, 'LLM in Legal practice', NULL),
(204, 'Maintenance Engineering', NULL),
(205, 'Management', NULL),
(206, 'Management Coaching Skills', NULL),
(207, 'Management in Events', NULL),
(208, 'Management in Hospitality', NULL),
(209, 'Management in Tourism', NULL),
(210, 'Management Studies (DMS)', NULL),
(211, 'Manufacturing Engineering', NULL),
(212, 'Marketing', NULL),
(213, 'Master of Business Administration (MBA)', NULL),
(214, 'Mathematics', NULL),
(215, 'Mechanical Engineering', NULL),
(216, 'Mechanical Maintenance Engineering', NULL),
(217, 'Mechatronics and Intelligent Machines', NULL),
(218, 'Media Production', NULL),
(219, 'Medical Education', NULL),
(220, 'Medical Leadership', NULL),
(221, 'Medical Sciences', NULL),
(222, 'Medical Ultrasound', NULL),
(223, 'Mental Health Practice', NULL),
(224, 'Mentoring in Dental Practice', NULL),
(225, 'Midwifery', NULL),
(226, 'Midwifery: for Registered Nurses (Adult)', NULL),
(227, 'MMath Mathematics', NULL),
(228, 'Modern Languages', NULL),
(229, 'Modern Languages for International Business', NULL),
(230, 'Motorsports Engineering', NULL),
(231, 'Multidisciplinary Practice in Dysphagia Care', NULL),
(232, 'Multimedia Journalism', NULL),
(233, 'Musculoskeletal Management', NULL),
(234, 'Music', NULL),
(235, 'Music Industry Management and Promotion', NULL),
(236, 'Music Production and Performance', NULL),
(237, 'Music Theatre', NULL),
(238, 'Nanoscience and Nanotechnology', NULL),
(239, 'Neonatal Practice', NULL),
(240, 'Neuropsychology', NULL),
(241, 'Neuroscience', NULL),
(242, 'North Korean Studies', NULL),
(243, 'Nuclear Engineering Science', NULL),
(244, 'Nuclear Safety', NULL),
(245, 'Nuclear Safety, Security and Safeguards', NULL),
(246, 'Nuclear Science and Technology', NULL),
(247, 'Nuclear Security and Safeguards', NULL),
(248, 'Nursing', NULL),
(249, 'Nursing Associate', NULL),
(250, 'Nursing in General Practice', NULL),
(251, 'Nursing with registered Nurse', NULL),
(252, 'Nutrition and Exercise Sciences', NULL),
(253, 'Occupational Therapy', NULL),
(254, 'Oil and Gas Engineering', NULL),
(255, 'Oil and Gas Safety Engineering', NULL),
(256, 'Operating Department Practice', NULL),
(257, 'Ophthalmic Dispensing', NULL),
(258, 'Optometry', NULL),
(259, 'Oral Surgery', NULL),
(260, 'Outdoor Adventure Leadership', NULL),
(261, 'Outdoor Practice', NULL),
(262, 'Paramedic Science', NULL),
(263, 'Performance Medicine', NULL),
(264, 'Periodontology', NULL),
(265, 'Personality Disorder', NULL),
(266, 'Pharmaceutical Sciences', NULL),
(267, 'Pharmacology', NULL),
(268, 'Pharmacy', NULL),
(269, 'Philosophy', NULL),
(270, 'Philosophy and Mental Health', NULL),
(271, 'Photography', NULL),
(272, 'Physical Education and School Sport', NULL),
(273, 'Physician Associate Practice', NULL),
(274, 'Physician Associate Studies', NULL),
(275, 'Physics', NULL),
(276, 'Physics with Astrophysics', NULL),
(277, 'Physiology and Pharmacology', NULL),
(278, 'Physiotherapy', NULL),
(279, 'Policing and Criminal Investigation', NULL),
(280, 'Politics', NULL),
(281, 'Politics, Philosophy and Society', NULL),
(282, 'Postgraduate Research Degrees in Physics, Astrophysics, and Mathematics', NULL),
(283, 'Postgraduate Research Degrees in Psychology', NULL),
(284, 'Practice Teacher', NULL),
(285, 'Product Design', NULL),
(286, 'Professional Development and Practice', NULL),
(287, 'Professional Masters in Elite Performance', NULL),
(288, 'Professional Policing', NULL),
(289, 'Professional Practice', NULL),
(290, 'Professional Practice and Mental Health Law', NULL),
(291, 'Professional Practice in Education', NULL),
(292, 'Professional Practice with Children and Young People', NULL),
(293, 'Project Management', NULL),
(294, 'Promoting Psychological Wellbeing (IAPT)', NULL),
(295, 'Prosthodontics', NULL),
(296, 'Psychology', NULL),
(297, 'Psychology and Criminology', NULL),
(298, 'Psychology Conversion', NULL),
(299, 'Psychology of Child Development', NULL),
(300, 'Psychology with Psychotherapy and Counselling', NULL),
(301, 'Psychosexual Therapy', NULL),
(302, 'Psychosocial Mental Health Care', NULL),
(303, 'Psychotherapy Studies', NULL),
(304, 'Public Management', NULL),
(305, 'Public Services Final Year', NULL),
(306, 'Publishing', NULL),
(307, 'Quantity Surveying', NULL),
(308, 'Religion, Culture and Society', NULL),
(309, 'Renewable Energy Engineering', NULL),
(310, 'Researching Social Care', NULL),
(311, 'Resource Energy and Environmental Management', NULL),
(312, 'Robotics Engineering', NULL),
(313, 'Rural Medicine', NULL),
(314, 'Safeguarding Children', NULL),
(315, 'Safeguarding in an International Context', NULL),
(316, 'Screenwriting with Film, Television and Radio', NULL),
(317, 'Scriptwriting', NULL),
(318, 'Senior Status', NULL),
(319, 'Sexual Health Studies', NULL),
(320, 'Social Pedagogy Leadership', NULL),
(321, 'Social Policy', NULL),
(322, 'Social Work', NULL),
(323, 'Sociology', NULL),
(324, 'Software Engineering', NULL),
(325, 'Specialist Child Care Practice', NULL),
(326, 'Specialist Community Public Health Nurse', NULL),
(327, 'Specialist Practice with Adults', NULL),
(328, 'Sport and Exercise Science', NULL),
(329, 'Sport and Physical Education', NULL),
(330, 'Sport Business Management', NULL),
(331, 'Sport Leadership and Professional Development', NULL),
(332, 'Sports Business Management', NULL),
(333, 'Sports Business Marketing', NULL),
(334, 'Sports Coaching', NULL),
(335, 'Sports Coaching and Performance', NULL),
(336, 'Sports Coaching Development', NULL),
(337, 'Sports Journalism', NULL),
(338, 'Sports Medicine', NULL),
(339, 'Sports Therapy', NULL),
(340, 'Strength and Conditioning', NULL),
(341, 'Supervision of Counselling and Psychotherapy', NULL),
(342, 'Surface Pattern and Textiles', NULL),
(343, 'Sustainability, Health and Wellbeing', NULL),
(344, 'Synthetic Organic Chemistry', NULL),
(345, 'Teaching English to Speakers of Other Languages and Modern Languages', NULL),
(346, 'Television Production', NULL),
(347, 'TESOL', NULL),
(348, 'TESOL with Applied Linguistics', NULL),
(349, 'Textile Design', NULL),
(350, 'Theatre and Performance', NULL),
(351, 'Transforming Integrated Health and Social Care', NULL),
(352, 'User Experience (UX) Design', NULL),
(353, 'Web Design and Development', NULL),
(354, 'Youth Work and Community Practice', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_types`
--

CREATE TABLE `course_types` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `course_types`
--

INSERT INTO `course_types` (`ID`, `Name`) VALUES
(1, 'Bachelor'),
(2, 'Masters'),
(3, 'Doctorate'),
(4, 'Apprenticeship'),
(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `c_roses`
--

CREATE TABLE `c_roses` (
  `RoseID` int(10) UNSIGNED NOT NULL,
  `CommentID` int(10) UNSIGNED NOT NULL,
  `UserID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `ID` int(10) UNSIGNED NOT NULL,
  `PosterID` int(10) UNSIGNED NOT NULL,
  `Content` text COLLATE utf8_unicode_ci,
  `ImageID` int(10) UNSIGNED DEFAULT NULL,
  `Roses` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `PostTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roses`
--

CREATE TABLE `roses` (
  `RoseID` int(10) UNSIGNED NOT NULL,
  `PostID` int(10) UNSIGNED NOT NULL,
  `UserID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `ID` int(10) UNSIGNED NOT NULL,
  `OriginalFilename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Filename` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `UploadedBy` int(10) UNSIGNED NOT NULL,
  `FileChecksum` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `Filesize` int(10) UNSIGNED NOT NULL,
  `UploadTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) UNSIGNED NOT NULL,
  `Username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `Surnames` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Birthdate` date DEFAULT NULL,
  `Gender` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `Lecturer` tinyint(1) NOT NULL DEFAULT '0',
  `Course` int(10) UNSIGNED DEFAULT NULL,
  `CourseType` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `StartYear` year(4) NOT NULL DEFAULT '2020',
  `Country` int(10) UNSIGNED NOT NULL DEFAULT '186',
  `ProfilePicture` int(10) UNSIGNED DEFAULT NULL,
  `VerifiedAccount` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `VerifyToken` char(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `SetupComplete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `LostAccount` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `LostToken` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreationTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `course_types`
--
ALTER TABLE `course_types`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `c_roses`
--
ALTER TABLE `c_roses`
  ADD PRIMARY KEY (`RoseID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `roses`
--
ALTER TABLE `roses`
  ADD PRIMARY KEY (`RoseID`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT for table `course_types`
--
ALTER TABLE `course_types`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `c_roses`
--
ALTER TABLE `c_roses`
  MODIFY `RoseID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roses`
--
ALTER TABLE `roses`
  MODIFY `RoseID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
