
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
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

CREATE TABLE IF NOT EXISTS `information_object` (
  `information_object_id` int(11) NOT NULL AUTO_INCREMENT,
  `information_object_repo` int(11) NOT NULL,
  `information_object_table` varchar(64) NOT NULL,
  `information_object_rowid` int(11) NOT NULL,
  `information_object_person` int(11) NOT NULL,
  `information_object_event` varchar(64) NOT NULL,
  `information_object_date` datetime NOT NULL,
  PRIMARY KEY (`information_object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `information_object`
--

INSERT INTO `information_object` (`information_object_id`, `information_object_repo`, `information_object_table`, `information_object_rowid`, `information_object_person`, `information_object_event`, `information_object_date`) VALUES
(6, 1, 'repocategories', 34, 1, 'insert', '2016-06-02 08:53:57'),
(7, 1, 'repocategories', 35, 1, 'insert', '2016-06-02 08:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `languages_id` int(11) NOT NULL AUTO_INCREMENT,
  `languages_region_code` varchar(5) NOT NULL,
  `languages_name` varchar(64) NOT NULL,
  PRIMARY KEY (`languages_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languages_id`, `languages_region_code`, `languages_name`) VALUES
(1, 'ka', 'georgian'),
(2, 'ru', 'russian'),
(3, 'en', 'english'),
(4, 'aps', 'abkhazian');

-- --------------------------------------------------------

--
-- Table structure for table `language_settings`
--

CREATE TABLE IF NOT EXISTS `language_settings` (
  `language_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_settings_repository` int(11) NOT NULL COMMENT 'repository id ',
  `language_settings_lang_id` int(11) NOT NULL COMMENT 'repository languages',
  PRIMARY KEY (`language_settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `language_settings`
--

INSERT INTO `language_settings` (`language_settings_id`, `language_settings_repository`, `language_settings_lang_id`) VALUES
(1, 1, 4),
(2, 1, 1);

--
-- Table structure for table `chats`
--

CREATE TABLE IF NOT EXISTS `chats` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `online_user_id` int(11) DEFAULT NULL,
  `repo_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `chat_status_id` int(11) NOT NULL DEFAULT '0',
  `chat_uniq_id` varchar(200) NOT NULL,
  `add_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE IF NOT EXISTS  `chat_messages` (
  `chat_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `online_user_id` int(11) DEFAULT NULL,
  `chat_message` text,
  `message_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`chat_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_rooms`
--

CREATE TABLE IF NOT EXISTS `chat_rooms` (
  `chat_room_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `person_mode` int(11) DEFAULT NULL,
  `person_join_msg_id` int(11) DEFAULT NULL,
  `person_join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `online_user_date` date DEFAULT NULL,
  `online_user_leave_date` date DEFAULT NULL,
  `online_user_id` int(11) DEFAULT NULL,
PRIMARY KEY (`chat_room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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


-- --------------------------------------------------------

--
-- Table structure for table `online_users`
--

CREATE TABLE IF NOT EXISTS `online_users` (
  `online_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `personal_no` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `card_number` varchar(45) DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`online_user_id`)
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
(1, 'ონისე', 'გაბისონია', '2016-02-05', '555 523 400', 'mail@mail.ge', 'image.jpg', '8cb2237d0679ca88db6464eac60da96345513964', '2016-02-05', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person_sessions`
--

CREATE TABLE IF NOT EXISTS  `ci_sessions` (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(45) DEFAULT '0' NOT NULL,
	user_agent varchar(120) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY (session_id),
	KEY `last_activity_idx` (`last_activity`)
);

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

INSERT INTO `repocategories` (`repository_id`, `category_name`) VALUES (1, 'ეროვნული არქივი');

-- --------------------------------------------------------

--
-- Table structure for table `repository`
--

CREATE TABLE IF NOT EXISTS `repositories` (
  `repository_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `other_name` varchar(255) DEFAULT NULL,
  `abr` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `rep_email` varchar(45) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
PRIMARY KEY (`repository_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `repositories`
--

INSERT INTO `repositories`(`name`, `other_name`, `abr`, `address`, `phone`, `fax`, `rep_email`, `reg_date`, `expire_date`)
VALUES ('Public Service Hall', 'PSH', 'PSH', 'tbilisi sanapiro 2', '54545335', '54654354', 'info@psh.gov.ge', '2016-03-05', '2017-03-05');

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


CREATE TABLE `person_tokens` (
  `token` varchar(100) NOT NULL,
  `person_id` int(11) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expired` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
