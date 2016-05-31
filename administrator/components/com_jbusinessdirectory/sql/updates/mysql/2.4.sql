ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_reviews_users` TINYINT(1) NULL DEFAULT 0 AFTER `enable_search_filter` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_numbering` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_reviews_users`;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_search_filter_offers` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_numbering` ;

ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `start_date` DATE NULL  AFTER `description` ;
ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `type` TINYINT NOT NULL DEFAULT 0  AFTER `start_date` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `slogan` TEXT NULL  AFTER `mobile` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `show_search_map` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_search_filter_offers` , ADD COLUMN `show_search_description` TINYINT(1) NOT NULL DEFAULT 1  AFTER `show_search_map` , ADD COLUMN `show_details_user` TINYINT(1) NOT NULL DEFAULT 0  AFTER `show_search_description` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `company_view` TINYINT(1) NOT NULL DEFAULT 1  AFTER `show_details_user` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `captcha` TINYINT(1) NOT NULL DEFAULT 0  AFTER `company_view` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `street_number` VARCHAR(20) NULL  AFTER `description` ;


INSERT INTO `#__jbusinessdirectory_emails` (`email_id`, `email_subject`, `email_name`, `email_type`, `email_content`, `is_default`) VALUES
(6, 'Report abuse', 'Report Abuse', 'Report Abuse Email', 0x3c703e48692c3c6272202f3e3c6272202f3e3c2f703e0d0a3c703e596f7527726520726563656976696e67207468697320652d6d61696c206265636175736520616e20616275736520776173207265706f7274656420666f722074686520726576696577c2a03c7374726f6e673e5b7265766965775f6e616d655d3c2f7374726f6e673e2c20666f7220746865205b627573696e6573735f6e616d655d2e3c2f703e0d0a3c703e596f752063616e207669657720746865207265766965772061743a3c6272202f3e5b7265766965775f6c696e6b5d3c2f703e0d0a3c703e452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e4162757365206465736372697074696f6e3a3c6272202f3e5b61627573655f6465736372697074696f6e5d3c2f703e, 0),
(7, 'Your business listing is about to expire', 'Expiration Notification', 'Expiration Notification Email', 0x3c703e48692c3c2f703e0d0a3c703e3c6272202f3e596f757220627573696e657373206c697374696e672077697468206e616d65205b627573696e6573735f6e616d655d2069732061626f757420746f2065787069726520696e205b6578705f646179735d20646179732e3c6272202f3e596f752063616e20657874656e642074686520627573696e657373206c697374696e6720627920636c69636b696e672022457874656e6420706572696f642220696e20636f6d70616e792065646974206d6f64652e3c2f703e0d0a3c703ec2a03c2f703e0d0a3c703ec2a04265737420726567617264732c3c6272202f3e5b636f6d70616e795f6e616d655d3c2f703e, 0);

--
-- Table structure for table `#__jbusinessdirectory_attributes`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `is_mandatory` int(1) NOT NULL DEFAULT '0',
  `show_in_filter` int(1) NOT NULL DEFAULT '1',
  `show_in_front` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `#__jbusinessdirectory_attributes`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attribute_options`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attribute_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_attribute_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_attribute_types` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `#__jbusinessdirectory_attribute_types`
--

INSERT INTO `#__jbusinessdirectory_attribute_types` (`id`, `code`, `name`) VALUES
(1, 'input', 'Input'),
(2, 'select_box', 'Select Box'),
(3, 'checkbox', 'Checkbox(Multiple Select)'),
(4, 'radio', 'Radio(Single Select)');


CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_UNIQUE` (`company_id`,`attribute_id`,`value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;
