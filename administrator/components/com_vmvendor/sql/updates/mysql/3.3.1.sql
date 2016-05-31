CREATE TABLE IF NOT EXISTS `#__vmvendor_profilevisits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_userid` int(11) unsigned NOT NULL DEFAULT '0',
  `visitor_userid` int(11) unsigned NOT NULL DEFAULT '0',
  `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;