-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- โฮสต์: localhost:8889
-- เวลาในการสร้าง: 
-- รุ่นของเซิร์ฟเวอร์: 5.1.41
-- รุ่นของ PHP: 5.2.6
SET NAMES UTF8;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE DATABASE IF NOT EXISTS `sco`;
use `sco`;

-- 
-- ฐานข้อมูล: `sco`
-- 

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `category`
-- 

CREATE TABLE `category` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_char` varchar(10) NOT NULL,
  `cate_name` varchar(500) NOT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- 
-- dump ตาราง `category`
-- 

INSERT INTO `category` VALUES (1, 'a', 'การงานอาชีพและเทคโนโลยี ');
INSERT INTO `category` VALUES (2, 'c', 'คณิตศาสตร์ ');
INSERT INTO `category` VALUES (3, 'f', 'ดนตรี - นาฏศิลป์ ');
INSERT INTO `category` VALUES (4, 'i', 'ทัศนศิลป์ ');
INSERT INTO `category` VALUES (5, 'b', 'ประวัติศาสตร์ ');
INSERT INTO `category` VALUES (6, 'd', 'พระพุทธศาสนา ');
INSERT INTO `category` VALUES (7, 'j', 'ภาษาไทย ');
INSERT INTO `category` VALUES (8, 'g', 'ภาษาอังกฤษ ');
INSERT INTO `category` VALUES (9, 'e', 'วิทยาศาสตร์ ');
INSERT INTO `category` VALUES (10, 'h', 'สังคมศึกษา ');
INSERT INTO `category` VALUES (11, 'k', 'สุขศึกษา ');

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `items`
-- 

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `organizations_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title_bg` varchar(200) DEFAULT NULL,
  `title_uk` varchar(200) DEFAULT NULL,
  `invisible` varchar(45) DEFAULT NULL,
  `parameters` varchar(200) DEFAULT NULL,
  `masteryscore` int(11) NOT NULL,
  `metadata` varchar(200) DEFAULT NULL,
  `metadata_link` varchar(200) DEFAULT NULL,
  `time_limi_action` varchar(45) DEFAULT NULL,
  `data_from_lms` text,
  `sequencing` varchar(45) DEFAULT NULL,
  `presentation` varchar(45) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- dump ตาราง `items`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `items_resources`
-- 

CREATE TABLE `items_resources` (
  `items_resources_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`items_resources_id`),
  KEY `resource_id` (`resource_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- dump ตาราง `items_resources`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `manifests`
-- 

CREATE TABLE `manifests` (
  `manifests_id` int(11) NOT NULL AUTO_INCREMENT,
  `metadata` varchar(255) DEFAULT NULL,
  `metadata_link` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`manifests_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- dump ตาราง `manifests`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `organizations`
-- 

CREATE TABLE `organizations` (
  `organizations_id` int(11) NOT NULL AUTO_INCREMENT,
  `manifests_id` int(11) DEFAULT NULL,
  `title_bg` varchar(200) DEFAULT NULL,
  `title_uk` varchar(200) DEFAULT NULL,
  `metadata` varchar(200) DEFAULT NULL,
  `metadata_link` varchar(200) DEFAULT NULL,
  `sequencing` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`organizations_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- dump ตาราง `organizations`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `organizations_class`
-- 

CREATE TABLE `organizations_class` (
  `organizations_id` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL,
  KEY `organizations_id` (`organizations_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- dump ตาราง `organizations_class`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `resources`
-- 

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `title_bg` varchar(200) DEFAULT NULL,
  `title_uk` varchar(255) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `link_bg` varchar(100) DEFAULT NULL,
  `link_uk` varchar(100) DEFAULT NULL,
  `metadata` varchar(100) DEFAULT NULL,
  `metadata_link` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- dump ตาราง `resources`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `scormvars`
-- 

CREATE TABLE `scormvars` (
  `SCOInstanceID` int(10) unsigned NOT NULL DEFAULT '0',
  `varName` varchar(255) DEFAULT NULL,
  `varValue` text,
  KEY `SCOInstanceID` (`SCOInstanceID`),
  KEY `varName` (`varName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- dump ตาราง `scormvars`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `sco_data`
-- 

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
  `sco_lastlogin_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sco_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- 
-- dump ตาราง `sco_data`
-- 


-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `student`
-- 

CREATE TABLE `student` (
  `student_id` int(10) NOT NULL,
  `prefix_name` varchar(50) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_lastname` varchar(100) NOT NULL,
  `student_password` varchar(100) NOT NULL,
  `student_type` varchar(10) NOT NULL,
  `register_date` datetime NOT NULL,
  `lastlogin_date` datetime NOT NULL,
  PRIMARY KEY (`student_id`),
  KEY `student_password` (`student_password`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- dump ตาราง `student`
-- 

INSERT INTO `student` VALUES (1, 'เด็กชาย', 'admin', 'admin', 'a72217dbc26f2d71824d00780523fffa', 'admin', '2011-01-22 16:53:58', '2011-01-22 16:53:58');
INSERT INTO `student` VALUES (2, '', 'guest', 'guest', 'ee0454034972a8773fe2009f3dc444e2', 'guest', '2011-01-22 14:30:55', '2011-01-22 14:30:55');
