-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 09, 2005 at 12:25 AM
-- Server version: 4.0.22
-- PHP Version: 4.3.9
-- 
-- Database: `utstats`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_games`
-- 

CREATE TABLE `uts_games` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `gamename` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_gamestype`
-- 

CREATE TABLE `uts_gamestype` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `serverip` varchar(21) NOT NULL default '',
  `gamename` varchar(100) NOT NULL default '',
  `mutator` varchar(100) NOT NULL default '',
  `gid` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_ip2country`
-- 

CREATE TABLE IF NOT EXISTS `uts_ip2country` (
  `ip_from` int(10) unsigned NOT NULL default '0',
  `ip_to` int(10) unsigned NOT NULL default '0',
  `country` char(2) NOT NULL default ''
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_killsmatrix`
-- 

CREATE TABLE `uts_killsmatrix` (
  `matchid` mediumint(8) unsigned NOT NULL default '0',
  `killer` tinyint(4) NOT NULL default '0',
  `victim` tinyint(4) NOT NULL default '0',
  `kills` tinyint(3) unsigned NOT NULL default '0',
  KEY `matchid` (`matchid`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_match`
-- 

CREATE TABLE `uts_match` (
  `id` mediumint(10) NOT NULL auto_increment,
  `time` varchar(14) default NULL,
  `servername` varchar(100) NOT NULL default '',
  `serverip` varchar(21) NOT NULL default '0',
  `gamename` varchar(100) NOT NULL default '0',
  `gid` tinyint(3) unsigned NOT NULL default '0',
  `gametime` float NOT NULL default '0',
  `mutators` longtext NOT NULL,
  `insta` tinyint(1) NOT NULL default '0',
  `tournament` varchar(5) NOT NULL default '',
  `teamgame` varchar(5) NOT NULL default '',
  `mapname` varchar(100) NOT NULL default '',
  `mapfile` varchar(100) NOT NULL default '',
  `serverinfo` mediumtext NOT NULL,
  `gameinfo` mediumtext NOT NULL,
  `firstblood` int(10) unsigned NOT NULL default '0',
  `frags` mediumint(5) NOT NULL default '0',
  `deaths` mediumint(5) NOT NULL default '0',
  `kills` mediumint(5) NOT NULL default '0',
  `suicides` mediumint(5) NOT NULL default '0',
  `teamkills` mediumint(5) NOT NULL default '0',
  `assaultid` varchar(10) NOT NULL default '',
  `ass_att` tinyint(1) NOT NULL default '0',
  `ass_win` tinyint(4) NOT NULL default '0',
  `t0` tinyint(1) NOT NULL default '0',
  `t1` tinyint(1) NOT NULL default '0',
  `t2` tinyint(1) NOT NULL default '0',
  `t3` tinyint(1) NOT NULL default '0',
  `t0score` mediumint(5) NOT NULL default '0',
  `t1score` mediumint(5) NOT NULL default '0',
  `t2score` mediumint(5) NOT NULL default '0',
  `t3score` mediumint(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `serverip` (`serverip`)
) TYPE=MyISAM AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_pinfo`
-- 

CREATE TABLE `uts_pinfo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `country` char(2) NOT NULL default '',
  `banned` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`(22))
) TYPE=MyISAM AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_player`
-- 

CREATE TABLE `uts_player` (
  `id` mediumint(10) NOT NULL auto_increment,
  `matchid` mediumint(10) NOT NULL default '0',
  `insta` tinyint(1) NOT NULL default '0',
  `playerid` tinyint(3) NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `team` tinyint(2) NOT NULL default '0',
  `isabot` tinyint(1) NOT NULL default '0',
  `country` char(2) NOT NULL default '',
  `ip` int(10) unsigned NOT NULL default '0',
  `gid` tinyint(3) unsigned NOT NULL default '0',
  `gametime` float NOT NULL default '0',
  `gamescore` smallint(5) unsigned NOT NULL default '0',
  `lowping` smallint(5) unsigned NOT NULL default '0',
  `highping` smallint(5) unsigned NOT NULL default '0',
  `avgping` smallint(5) unsigned NOT NULL default '0',
  `frags` smallint(5) unsigned NOT NULL default '0',
  `deaths` smallint(5) unsigned NOT NULL default '0',
  `kills` smallint(5) unsigned NOT NULL default '0',
  `suicides` smallint(5) unsigned NOT NULL default '0',
  `teamkills` smallint(5) unsigned NOT NULL default '0',
  `eff` float NOT NULL default '0',
  `accuracy` float NOT NULL default '0',
  `ttl` float NOT NULL default '0',
  `flag_taken` smallint(5) unsigned NOT NULL default '0',
  `flag_dropped` smallint(5) unsigned NOT NULL default '0',
  `flag_return` smallint(5) unsigned NOT NULL default '0',
  `flag_capture` tinyint(3) unsigned NOT NULL default '0',
  `flag_cover` smallint(5) unsigned NOT NULL default '0',
  `flag_seal` smallint(5) unsigned NOT NULL default '0',
  `flag_assist` smallint(5) unsigned NOT NULL default '0',
  `flag_kill` mediumint(5) unsigned NOT NULL default '0',
  `flag_pickedup` smallint(5) unsigned NOT NULL default '0',
  `dom_cp` smallint(5) unsigned NOT NULL default '0',
  `ass_obj` smallint(5) unsigned NOT NULL default '0',
  `spree_double` smallint(5) unsigned NOT NULL default '0',
  `spree_triple` smallint(5) unsigned NOT NULL default '0',
  `spree_multi` smallint(5) unsigned NOT NULL default '0',
  `spree_mega` tinyint(3) unsigned NOT NULL default '0',
  `spree_ultra` tinyint(3) unsigned NOT NULL default '0',
  `spree_monster` tinyint(3) unsigned NOT NULL default '0',
  `spree_kill` smallint(5) unsigned NOT NULL default '0',
  `spree_rampage` smallint(5) unsigned NOT NULL default '0',
  `spree_dom` tinyint(3) unsigned NOT NULL default '0',
  `spree_uns` tinyint(3) unsigned NOT NULL default '0',
  `spree_god` smallint(5) unsigned NOT NULL default '0',
  `pu_pads` tinyint(3) unsigned NOT NULL default '0',
  `pu_armour` tinyint(3) unsigned NOT NULL default '0',
  `pu_keg` tinyint(3) unsigned NOT NULL default '0',
  `pu_invis` tinyint(3) unsigned NOT NULL default '0',
  `pu_belt` tinyint(3) unsigned NOT NULL default '0',
  `pu_amp` tinyint(3) unsigned NOT NULL default '0',
  `rank` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `matchid` (`matchid`,`team`),
  KEY `pid` (`pid`),
  KEY `gid` (`gid`)
) TYPE=MyISAM AUTO_INCREMENT=615 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_rank`
-- 

CREATE TABLE `uts_rank` (
  `id` mediumint(10) NOT NULL auto_increment,
  `time` float unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `gid` tinyint(3) unsigned NOT NULL default '0',
  `rank` float NOT NULL default '0',
  `prevrank` float NOT NULL default '0',
  `matches` mediumint(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`pid`,`gid`),
  KEY `rank` (`rank`),
  KEY `gamename` (`gid`,`rank`)
) TYPE=MyISAM AUTO_INCREMENT=173 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_weapons`
-- 

CREATE TABLE `uts_weapons` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `image` varchar(50) NOT NULL default '',
  `sequence` tinyint(3) unsigned NOT NULL default '200',
  `hide` enum('N','Y') NOT NULL default 'N',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`(20))
) TYPE=MyISAM AUTO_INCREMENT=20 ;

-- 
-- Dumping data for table `uts_weapons`
-- 

INSERT INTO `uts_weapons` VALUES (1, 'Translocator', 'trans.jpg', 1, 'N');
INSERT INTO `uts_weapons` VALUES (2, 'Impact Hammer', 'impact.jpg', 2, 'N');
INSERT INTO `uts_weapons` VALUES (3, 'Enforcer', 'enforcer.jpg', 3, 'N');
INSERT INTO `uts_weapons` VALUES (4, 'Double Enforcer', 'enforcer2.jpg', 4, 'N');
INSERT INTO `uts_weapons` VALUES (5, 'GES Bio Rifle', 'bio.jpg', 5, 'N');
INSERT INTO `uts_weapons` VALUES (6, 'Ripper', 'ripper.jpg', 6, 'N');
INSERT INTO `uts_weapons` VALUES (7, 'Shock Rifle', 'shock.jpg', 7, 'N');
INSERT INTO `uts_weapons` VALUES (8, 'Enhanced Shock Rifle', 'ishock.jpg', 8, 'N');
INSERT INTO `uts_weapons` VALUES (9, 'Pulse Gun', 'pulse.jpg', 9, 'N');
INSERT INTO `uts_weapons` VALUES (10, 'Minigun', 'minigun.jpg', 10, 'N');
INSERT INTO `uts_weapons` VALUES (11, 'Flak Cannon', 'flak.jpg', 11, 'N');
INSERT INTO `uts_weapons` VALUES (12, 'Rocket Launcher', 'rockets.jpg', 12, 'N');
INSERT INTO `uts_weapons` VALUES (13, 'Sniper Rifle', 'sniper.jpg', 13, 'N');
INSERT INTO `uts_weapons` VALUES (14, 'Redeemer', 'deemer.jpg', 14, 'N');
INSERT INTO `uts_weapons` VALUES (15, 'None', 'blank.jpg', 15, 'N');
INSERT INTO `uts_weapons` VALUES (16, 'Chainsaw', 'chainsaw.jpg', 16, 'N');

-- --------------------------------------------------------

-- 
-- Table structure for table `uts_weaponstats`
-- 

CREATE TABLE `uts_weaponstats` (
  `matchid` mediumint(8) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `weapon` tinyint(3) unsigned NOT NULL default '0',
  `kills` mediumint(8) unsigned NOT NULL default '0',
  `shots` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `damage` int(10) unsigned NOT NULL default '0',
  `acc` float unsigned NOT NULL default '0',
  KEY `full` (`matchid`,`pid`)
) TYPE=MyISAM;