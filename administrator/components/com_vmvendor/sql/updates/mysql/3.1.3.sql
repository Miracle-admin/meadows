CREATE TABLE IF NOT EXISTS `#__vmvendor_paypal_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `vendorid` int(11) NOT NULL DEFAULT '0',
  `paypal_email` varchar(100) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;