CREATE TABLE IF NOT EXISTS `#__vmvendor_plans` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`jusergroups` TEXT NOT NULL ,
`commission_pct` TINYINT(3)  NOT NULL ,
`autopublish` BOOLEAN NOT NULL ,
`max_products` INT(11)  NOT NULL ,
`max_img` TINYINT(2)  NOT NULL ,
`max_files` TINYINT(2)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
INSERT INTO #__vmvendor_plans ( `jusergroups` , `commission_pct`,`autopublish`,`max_products`,`max_img`,`max_files`)
VALUES ('2', '20', '1','','5','5');


CREATE TABLE IF NOT EXISTS `#__vmvendor_vendorratings` (
  `vendor_rating_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `percent` int(3) NOT NULL DEFAULT '0',
  `voter_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendor_rating_id`),
  UNIQUE KEY (`vendor_rating_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__vmvendor_vendoraddress` (
  `vendor_address_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `address` char(64) NOT NULL DEFAULT '',
  `zip` char(32) NOT NULL DEFAULT '',
  `city` char(64) NOT NULL DEFAULT '',
  `virtuemart_state_id` smallint(1) NOT NULL DEFAULT '0',
  `virtuemart_country_id` smallint(1) NOT NULL DEFAULT '0',

  PRIMARY KEY (`vendor_address_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;




CREATE TABLE IF NOT EXISTS `#__vmvendor_userpoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `points` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__vmvendor_userpoints_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `points` float(10,2) NOT NULL DEFAULT '0',
  `insert_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expire_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `keyreference` varchar(255) NOT NULL DEFAULT '',
  `datareference` text NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  INDEX (userid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__vmvendor_paypal_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `vendorid` int(11) NOT NULL DEFAULT '0',
  `paypal_email` varchar(100) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#__vmvendor_profilevisits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_userid` int(11) unsigned NOT NULL DEFAULT '0',
  `visitor_userid` int(11) unsigned NOT NULL DEFAULT '0',
  `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;