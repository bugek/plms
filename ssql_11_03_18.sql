-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 20, 2011 at 04:51 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `sco`
-- 
CREATE DATABASE `sco` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sco`;

-- --------------------------------------------------------

-- 
-- Table structure for table `items`
-- 

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL auto_increment,
  `organizations_id` int(11) default NULL,
  `parent_id` int(11) default NULL,
  `title_bg` varchar(200) default NULL,
  `title_uk` varchar(200) default NULL,
  `invisible` varchar(45) default NULL,
  `parameters` varchar(200) default NULL,
  `metadata` varchar(200) default NULL,
  `metadata_link` varchar(200) default NULL,
  `time_limi_action` varchar(45) default NULL,
  `data_from_lms` text,
  `sequencing` varchar(45) default NULL,
  `presentation` varchar(45) default NULL,
  `author` varchar(100) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `items_resources`
-- 

CREATE TABLE `items_resources` (
  `items_resources_id` int(11) NOT NULL auto_increment,
  `resource_id` int(11) default NULL,
  `item_id` int(11) default NULL,
  `author` varchar(100) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`items_resources_id`),
  KEY `resource_id` (`resource_id`,`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `manifests`
-- 

CREATE TABLE `manifests` (
  `manifests_id` int(11) NOT NULL auto_increment,
  `metadata` varchar(255) default NULL,
  `metadata_link` varchar(255) default NULL,
  `author` varchar(100) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`manifests_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `organizations`
-- 

CREATE TABLE `organizations` (
  `organizations_id` int(11) NOT NULL auto_increment,
  `manifests_id` int(11) default NULL,
  `title_bg` varchar(200) default NULL,
  `title_uk` varchar(200) default NULL,
  `metadata` varchar(200) default NULL,
  `metadata_link` varchar(200) default NULL,
  `sequencing` varchar(200) default NULL,
  `author` varchar(100) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`organizations_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `organizations_class`
-- 

CREATE TABLE `organizations_class` (
  `organizations_id` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  KEY `organizations_id` (`organizations_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `resources`
-- 

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL auto_increment,
  `title_bg` varchar(200) default NULL,
  `title_uk` varchar(255) default NULL,
  `type` varchar(45) default NULL,
  `link_bg` varchar(100) default NULL,
  `link_uk` varchar(100) default NULL,
  `metadata` varchar(100) default NULL,
  `metadata_link` varchar(100) default NULL,
  `author` varchar(100) default NULL,
  `date` datetime default NULL,
  PRIMARY KEY  (`resource_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `sco_data`
-- 

CREATE TABLE `sco_data` (
  `sco_id` int(10) NOT NULL auto_increment,
  `organizations_id` int(10) NOT NULL,
  `entry` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `suspend_data` varchar(255) NOT NULL,
  `launch_data` varchar(255) NOT NULL,
  `student_id` int(10) NOT NULL,
  `raw_score` float NOT NULL,
  `min_scrore` float NOT NULL,
  `max_scrore` float NOT NULL,
  `mastery_score` float NOT NULL,
  `num_count` int(10) NOT NULL,
  `score` float NOT NULL,
  `total_time` varchar(20) NOT NULL,
  `total_time_sec` int(11) NOT NULL,
  `session_time` varchar(255) NOT NULL,
  `exit` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY  (`sco_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `scormvars`
-- 

CREATE TABLE `scormvars` (
  `SCOInstanceID` int(10) unsigned NOT NULL default '0',
  `varName` varchar(255) default NULL,
  `varValue` text,
  KEY `SCOInstanceID` (`SCOInstanceID`),
  KEY `varName` (`varName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `student`
-- 

CREATE TABLE `student` (
  `student_id` int(10) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_lastname` varchar(100) NOT NULL,
  `student_password` varchar(100) NOT NULL,
  `student_type` varchar(10) NOT NULL,
  `register_date` datetime NOT NULL,
  `lastlogin_date` datetime NOT NULL,
  PRIMARY KEY  (`student_id`),
  KEY `student_password` (`student_password`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `student`
-- 

INSERT INTO `student` VALUES (1, 'admin', 'admin', 'bba39244cb7eb4b76997b492a47d855b', 'admin', '2011-01-22 16:53:58', '2011-01-22 16:53:58');
INSERT INTO `student` VALUES (2, 'guest', 'guest', 'ee0454034972a8773fe2009f3dc444e2', 'guest', '2011-01-22 14:30:55', '2011-01-22 14:30:55');
