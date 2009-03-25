-- --------------------------------------------------------

ALTER TABLE `uts_player` 
    CHANGE COLUMN `team` `team` tinyint(2) unsigned NOT NULL default '0',
    CHANGE COLUMN `gamescore` `gamescore` smallint(5) NOT NULL default '0',
    CHANGE COLUMN `frags` `frags` smallint(5)  NOT NULL default '0';  

-- --------------------------------------------------------

ALTER TABLE `uts_match` 
    CHANGE COLUMN `id`  `id` mediumint(10) unsigned NOT NULL auto_increment;
   
-- --------------------------------------------------------
 
ALTER TABLE `uts_player`
    CHANGE COLUMN `id`  `id` mediumint(10) unsigned NOT NULL auto_increment,
    CHANGE COLUMN `matchid` `matchid` mediumint(10) unsigned NOT NULL default '0',
    CHANGE COLUMN `playerid` `playerid` tinyint(3) unsigned NOT NULL default '0';
    
-- --------------------------------------------------------

ALTER TABLE `uts_rank`
    CHANGE COLUMN `id`  `id` mediumint(10) unsigned NOT NULL auto_increment;  