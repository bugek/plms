# MySQL-Front 3.2  (Build 6.11)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES 'latin1' */;

# Host: localhost:8889    Database: sco
# ------------------------------------------------------
# Server version 5.1.41
/*!40101 SET NAMES utf8 */;


#
# Table structure for table sco_data
#

CREATE TABLE `sco_data` (
  `sco_id` int(10) NOT NULL AUTO_INCREMENT,
  `organizations_id` int(10) NOT NULL,
  `entry` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `last_location` varchar(255) NOT NULL,
  `suspend_data` varchar(255) NOT NULL,
  `launch_data` varchar(255) NOT NULL,
  `student_id` int(10) NOT NULL,
  `raw_score` float NOT NULL,
  `min_scrore` float NOT NULL,
  `max_scrore` float NOT NULL,
  `mastery_score` float NOT NULL,
  `num_count` int(10) NOT NULL,
  `score` float NOT NULL,
  `score_item` text,
  `total_time` varchar(20) NOT NULL,
  `total_time_sec` int(11) NOT NULL,
  `session_time` varchar(255) NOT NULL,
  `exit` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `last_update` datetime NOT NULL,
  `lesson_all` text NOT NULL,
  `lesson_graduate` text NOT NULL,
  `lesson_graduate_ps` int(3) NOT NULL,
  PRIMARY KEY (`sco_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Dumping data for table sco_data
#


/*!40101 SET NAMES latin1 */;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
