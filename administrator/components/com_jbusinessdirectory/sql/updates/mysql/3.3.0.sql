CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `limit_cities` VARCHAR(1) NOT NULL DEFAULT 0  AFTER `meta_description_facebook` ;

