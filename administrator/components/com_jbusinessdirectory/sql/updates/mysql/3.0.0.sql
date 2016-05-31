CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_payments` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `processor_type` varchar(100) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date` date NOT NULL,
  `transaction_id` varchar(80) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `currency` char(5) NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `response_code` varchar(45) DEFAULT NULL,
  `message` blob,
  `payment_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `NewIndex` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2810 DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_invoices` RENAME TO  `#__jbusinessdirectory_orders` ;

ALTER TABLE `#__jbusinessdirectory_payment_processors` DROP COLUMN `password` , DROP COLUMN `username` , ADD COLUMN `timeout` INT(7) NULL  AFTER `mode` , ADD COLUMN `ordering` TINYINT NULL  AFTER `status` , ADD COLUMN `displayfront` TINYINT(1) NOT NULL DEFAULT 1  AFTER `ordering` ;

--
INSERT INTO `#__jbusinessdirectory_payment_processors` (`id`, `name`, `type`, `mode`, `timeout`, `status`, `ordering`, `displayfront`) VALUES
(2, 'Bank Transfer', 'wiretransfer', 'live', 0, 1, 2, 1),
(3, 'Cash', 'cash', 'live', 0, 1, 3, 0),
(4, 'Buckaroo', 'buckaroo', 'test', 60, 1, NULL, 1),
(6, 'Cardsave', 'cardsave', 'test', 15, 1, NULL, 1);

INSERT INTO `#__jbusinessdirectory_payment_processor_fields` (`id`, `column_name`, `column_value`, `processor_id`) VALUES
(88, 'bank_name', 'Bank Name', 2),
(86, 'bank_city', 'City', 2),
(87, 'bank_address', 'Address', 2),
(85, 'bank_country', 'Counntry', 2),
(84, 'swift_code', 'SW1321', 2),
(83, 'iban', 'BR213 123 123 123', 2),
(82, 'bank_account_number', '123123123123 ', 2),
(81, 'bank_holder_name', 'Account holder name', 2),
(89, 'secretKey', '5729E78B00CA4BA3994A480B169FB288', 4),
(90, 'merchantId', 'Z7bmGUrAps', 4),
(100, 'merchantId', '7714586', 6),
(98, 'preSharedKey', 'kZfhKfAsT+9st5/qVSnnYqG6M9Y+EYzHK4mwVNQmUuxs=', 6),
(99, 'password', '1M75C4R8', 6);


ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `currency` VARCHAR(4) NULL  AFTER `type` ;

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_default_attributes` (

  `id` INT NOT NULL AUTO_INCREMENT ,

  `name` VARCHAR(55) NULL ,

  `config` TINYINT(1) NULL ,

  PRIMARY KEY (`id`) );

INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES
(2, 'comercial_name', 3),
(3, 'tax_code', 3),
(4, 'registration_code', 2),
(5, 'website', 2),
(6, 'company_type', 1),
(7, 'slogan', 2),
(8, 'description', 1),
(9, 'keywords', 2),
(10, 'category', 1),
(11, 'logo', 1),
(12, 'street_number', 1),
(13, 'address', 1),
(14, 'city', 1),
(15, 'region', 1),
(16, 'country', 1),
(17, 'postal_code', 1),
(18, 'map', 1),
(20, 'phone', 1),
(21, 'mobile_phone', 2),
(22, 'fax', 2),
(23, 'email', 1),
(24, 'pictures', 2),
(25, 'video', 2),
(26, 'social_networks', 2);

ALTER TABLE `#__jbusinessdirectory_attributes` ADD `show_on_search` TINYINT( 1 ) NOT NULL AFTER `show_in_front`;

--
-- Table structure for table `#__jbusinessdirectory_company_events`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(245) DEFAULT NULL,
  `description` text,
  `location` varchar(145) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL,
  `view_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_pictures`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_pictures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventId` int(10) NOT NULL DEFAULT '0',
  `picture_info` blob NOT NULL,
  `picture_path` blob NOT NULL,
  `picture_enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jbusinessdirectory_company_event_types`
--

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `#__jbusinessdirectory_company_offers` CHANGE `aproved` `approved` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `#__jbusinessdirectory_companies` CHANGE `aproved` `approved` TINYINT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `show_pending_approval` TINYINT(1) NOT NULL DEFAULT 0  AFTER `nr_images_slide` , ADD COLUMN `allow_multiple_companies` TINYINT(1) NOT NULL DEFAULT 1  AFTER `show_pending_approval` , ADD COLUMN `meta_description` TEXT NULL  AFTER `allow_multiple_companies` , ADD COLUMN `meta_keywords` TEXT NULL  AFTER `meta_description` , ADD COLUMN `meta_description_facebook` TEXT NULL  AFTER `meta_keywords` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_events` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_offers` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_search_filter_events` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_search_filter_offers` ;


