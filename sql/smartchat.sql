
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `authformsforrepo`
--

CREATE TABLE IF NOT EXISTS `authformsforrepo` (
  `authFormsForRepoId` int(11) NOT NULL AUTO_INCREMENT,
  `authId` int(11) DEFAULT NULL,
  `repoId` int(11) DEFAULT NULL,
  PRIMARY KEY (`authFormsForRepoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `auth_forms`
--

CREATE TABLE IF NOT EXISTS `auth_forms` (
  `auth_forms_id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_forms_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`auth_forms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categoryservices`
--

CREATE TABLE IF NOT EXISTS `categoryservices` (
  `category_services_id` int(11) NOT NULL AUTO_INCREMENT,
  `repo_categories_id` int(11) DEFAULT NULL,
  `service_name` varchar(200) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`category_services_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `chatId` int(11) NOT NULL AUTO_INCREMENT,
  `onlineUserId` int(11) DEFAULT NULL,
  `repoId` int(11) DEFAULT NULL,
  `serviceId` int(11) DEFAULT NULL,
  PRIMARY KEY (`chatId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chatmessage`
--

CREATE TABLE IF NOT EXISTS `chatmessage` (
  `chatMessageId` int(11) NOT NULL AUTO_INCREMENT,
  `chatId` int(11) DEFAULT NULL,
  `personId` int(11) DEFAULT NULL,
  `onlineUserId` int(11) DEFAULT NULL,
  `chatMessageTxt` text,
  `chatDate` date DEFAULT NULL,
  PRIMARY KEY (`chatMessageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chatroom`
--

CREATE TABLE IF NOT EXISTS `chatroom` (
  `chatRoomId` int(11) NOT NULL AUTO_INCREMENT,
  `chatID` int(11) DEFAULT NULL,
  `personId` int(11) DEFAULT NULL,
  `personMode` int(11) DEFAULT NULL,
  `personJoinMssgId` int(11) DEFAULT NULL,
  `personJoinDate` date DEFAULT NULL,
  `onlineUserDate` date DEFAULT NULL,
  `onlineUserLeaveDate` date DEFAULT NULL,
  PRIMARY KEY (`chatRoomId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_his`
--

CREATE TABLE IF NOT EXISTS `login_his` (
  ` login_his_id` int(11) NOT NULL AUTO_INCREMENT,
  `his_person_id` int(11) NOT NULL,
  `login_his_date` date NOT NULL,
  `login_his_time` datetime NOT NULL,
  PRIMARY KEY (` login_his_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

--
-- Dumping data for table `login_his`
--

INSERT INTO `login_his` (` login_his_id`, `his_person_id`, `login_his_date`, `login_his_time`) VALUES
(17, 1, '2016-02-28', '2016-02-28 23:37:41'),
(18, 1, '2016-03-03', '2016-03-03 18:21:20'),
(19, 1, '2016-03-03', '2016-03-03 19:54:23'),
(20, 1, '2016-03-03', '2016-03-03 22:26:35'),
(21, 1, '2016-03-03', '2016-03-03 22:31:02'),
(22, 1, '2016-03-03', '2016-03-03 22:32:55'),
(23, 1, '2016-03-03', '2016-03-03 22:33:48'),
(24, 1, '2016-03-03', '2016-03-03 23:42:29'),
(25, 1, '2016-03-03', '2016-03-03 23:45:40'),
(26, 1, '2016-03-05', '2016-03-05 12:54:05'),
(27, 1, '2016-03-05', '2016-03-05 13:24:37'),
(28, 1, '2016-03-05', '2016-03-05 13:24:40'),
(29, 1, '2016-03-05', '2016-03-05 13:27:26'),
(30, 1, '2016-03-05', '2016-03-05 13:28:37'),
(31, 1, '2016-03-05', '2016-03-05 13:28:39'),
(32, 1, '2016-03-05', '2016-03-05 13:30:53'),
(33, 1, '2016-03-05', '2016-03-05 13:31:12'),
(34, 1, '2016-03-05', '2016-03-05 13:31:30'),
(35, 1, '2016-03-05', '2016-03-05 14:47:27'),
(36, 1, '2016-03-05', '2016-03-05 14:47:49'),
(37, 1, '2016-03-05', '2016-03-05 23:22:30'),
(38, 1, '2016-03-06', '2016-03-06 10:32:05'),
(39, 1, '2016-03-06', '2016-03-06 23:34:09'),
(40, 1, '2016-03-07', '2016-03-07 06:15:59'),
(41, 1, '2016-03-07', '2016-03-07 12:02:32'),
(42, 1, '2016-03-09', '2016-03-09 07:19:59'),
(43, 1, '2016-03-09', '2016-03-09 07:38:06'),
(44, 1, '2016-03-10', '2016-03-10 07:12:37'),
(45, 1, '2016-03-10', '2016-03-10 09:30:05'),
(46, 1, '2016-03-10', '2016-03-10 09:58:10'),
(47, 1, '2016-03-10', '2016-03-10 09:59:17'),
(48, 1, '2016-03-10', '2016-03-10 10:21:18'),
(49, 1, '2016-03-11', '2016-03-11 07:02:44'),
(50, 1, '2016-03-11', '2016-03-11 09:22:16'),
(51, 1, '2016-03-11', '2016-03-11 13:45:26'),
(52, 1, '2016-03-21', '2016-03-21 11:18:04'),
(53, 1, '2016-03-21', '2016-03-21 14:28:58'),
(54, 1, '2016-03-30', '2016-03-30 07:37:14'),
(55, 1, '2016-03-30', '2016-03-30 07:37:41'),
(56, 1, '2016-03-30', '2016-03-30 07:38:38'),
(57, 1, '2016-03-30', '2016-03-30 08:49:29'),
(58, 1, '2016-03-30', '2016-03-30 10:34:41'),
(59, 1, '2016-03-30', '2016-03-30 12:36:47'),
(60, 1, '2016-03-30', '2016-03-30 15:03:05'),
(61, 1, '2016-04-06', '2016-04-06 10:05:12'),
(62, 1, '2016-04-06', '2016-04-06 10:06:39'),
(63, 1, '2016-04-06', '2016-04-06 10:06:55'),
(64, 1, '2016-04-18', '2016-04-18 08:10:39'),
(65, 1, '2016-04-18', '2016-04-18 08:10:59'),
(66, 1, '2016-04-19', '2016-04-19 14:15:43'),
(67, 1, '2016-05-03', '2016-05-03 09:05:15'),
(68, 1, '2016-05-03', '2016-05-03 09:06:59'),
(69, 1, '2016-05-03', '2016-05-03 09:07:15'),
(70, 1, '2016-05-05', '2016-05-05 14:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `onlineusers`
--

CREATE TABLE IF NOT EXISTS `onlineusers` (
  `onlineUsersId` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `personalno` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `cardnumber` varchar(45) DEFAULT NULL,
  `regDate` date DEFAULT NULL,
  PRIMARY KEY (`onlineUsersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `personroles`
--

CREATE TABLE IF NOT EXISTS `personroles` (
  `personRolesId` int(11) NOT NULL AUTO_INCREMENT,
  `personId` int(11) DEFAULT NULL,
  `roleId` int(11) DEFAULT NULL,
  PRIMARY KEY (`personRolesId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE IF NOT EXISTS `persons` (
  `persons_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) DEFAULT NULL,
  `lastname` varchar(64) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `regDate` date DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT NULL,
  `lastVisit` date DEFAULT NULL,
  PRIMARY KEY (`persons_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`persons_id`, `firstname`, `lastname`, `birthday`, `phone`, `email`, `photo`, `password`, `regDate`, `isadmin`, `lastVisit`) VALUES
(1, 'ონისე', 'გაბისონია', '2016-02-05', '555 523 400', 'mail@mail.ge', 'image.jpg', '12345', '2016-02-05', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person_sessions`
--

CREATE TABLE IF NOT EXISTS `person_sessions` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=279;

--

--
-- Table structure for table `repocategories`
--

CREATE TABLE IF NOT EXISTS `repocategories` (
  `repo_categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `repository_id` int(11) DEFAULT NULL,
  `category_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`repo_categories_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `repocategories`
--

INSERT INTO `repocategories` (`repo_categories_id`, `repository_id`, `category_name`) VALUES
(25, 1, 'ეროვნული არქივი');

-- --------------------------------------------------------

--
-- Table structure for table `repository`
--

CREATE TABLE IF NOT EXISTS `repository` (
  `repository_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `otherName` varchar(255) DEFAULT NULL,
  `abr` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `rep_email` varchar(45) DEFAULT NULL,
  `regDate` date DEFAULT NULL,
  `expireDate` date DEFAULT NULL,
  PRIMARY KEY (`repository_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `repository`
--

INSERT INTO `repository` (`repository_id`, `name`, `otherName`, `abr`, `address`, `phone`, `fax`, `rep_email`, `regDate`, `expireDate`) VALUES
(1, 'Public Service Hall', 'PSH', 'PSH', 'tbilisi sanapiro 2', '54545335', '54654354', 'info@psh.gov.ge', '2016-03-05', '2017-03-05');

-- --------------------------------------------------------

--
-- Table structure for table `repositorypersons`
--

CREATE TABLE IF NOT EXISTS `repositorypersons` (
  `repository_persons_id` int(11) NOT NULL AUTO_INCREMENT,
  `repositorypersons_repository_id` int(11) DEFAULT NULL,
  `repositorypersons_person_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`repository_persons_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `repositorypersons`
--

INSERT INTO `repositorypersons` (`repository_persons_id`, `repositorypersons_repository_id`, `repositorypersons_person_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rolegroups`
--

CREATE TABLE IF NOT EXISTS `rolegroups` (
  `roleGroupsID` int(11) NOT NULL,
  `groupName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`roleGroupsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `rolesId` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) DEFAULT NULL,
  `roleName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`rolesId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
