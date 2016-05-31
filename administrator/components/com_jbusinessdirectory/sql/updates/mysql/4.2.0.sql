
CREATE TABLE `#__jbusinessdirectory_discounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `value` float(6,2) NOT NULL,
  `percent` tinyint(1) NOT NULL DEFAULT '0',
  `price_type` tinyint(1) NOT NULL DEFAULT '1',
  `package_ids` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `uses_per_coupon` int(11) DEFAULT NULL,
  `coupon_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `discount_id` INT NULL ;
ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `discount_amount` DECIMAL(6,2) DEFAULT 0 ;
ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `discount_code` varchar(50) DEFAULT NULL ;
ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `initial_amount` DECIMAL(8,2) NULL  AFTER `package_id` ;
ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `vat_amount` DECIMAL(6,2) NULL;

INSERT INTO `#__jbusinessdirectory_attribute_types` (`id`, `code`, `name`) VALUES (5, 'header', 'Header');

ALTER TABLE `#__jbusinessdirectory_categories` ADD COLUMN `lft` INT NOT NULL DEFAULT 0 , ADD COLUMN `rgt` INT NOT NULL DEFAULT 0  AFTER `lft` , ADD COLUMN `level` INT UNSIGNED NOT NULL DEFAULT 0  AFTER `rgt` , CHANGE COLUMN `state` `published` TINYINT(4) NOT NULL ;

ALTER TABLE `#__jbusinessdirectory_categories` CHANGE COLUMN `description` `description` VARCHAR(550) NULL DEFAULT NULL  ;

ALTER TABLE `#__jbusinessdirectory_categories` CHANGE COLUMN `parentId` `parent_id` INT(11) NOT NULL DEFAULT '0'  ;

ALTER TABLE `#__jbusinessdirectory_categories` ADD COLUMN `path` VARCHAR(255) NULL  ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_multilingual` TINYINT(1) NOT NULL DEFAULT 0 ;


CREATE TABLE `#__jbusinessdirectory_language_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `language_tag` varchar(10) NOT NULL,
  `content_short` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `idx_object` (`object_id`),
  KEY `ids_langauge` (`language_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `#__jbusinessdirectory_company_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `path` varchar(155) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_object` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `offers_view_mode` TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_geolocation` TINYINT(1) NOT NULL DEFAULT 0  AFTER `offers_view_mode` ;

INSERT INTO `#__jbusinessdirectory_attribute_types` (`id`, `code`, `name`) VALUES (6, 'textarea', 'Textarea');


CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(77) DEFAULT NULL,
  `ordering` tinyint(4) DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_company_reviews_user_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `score` decimal(2,1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `business_hours` VARCHAR(145) NULL  AFTER `featured` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `add_url_id` TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE `#__jbusinessdirectory_packages` ADD COLUMN `max_pictures` TINYINT NOT NULL DEFAULT 15 , ADD COLUMN `max_videos` TINYINT NOT NULL DEFAULT 5  ;


ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `currency_display` TINYINT(1) NOT NULL DEFAULT 1  AFTER `add_url_id` , ADD COLUMN `amount_separator` TINYINT(1) NOT NULL DEFAULT 1  AFTER `currency_display` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `currency_location` TINYINT(1) NULL DEFAULT 1  AFTER `amount_separator` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `currency_symbol` VARCHAR(45) NULL  AFTER `currency_location` ;

INSERT INTO `#__jbusinessdirectory_date_formats` (`id`, `name`, `dateFormat`, `calendarFormat`, `defaultDateValue`) VALUES (3, 'm/d/y', 'm/d/Y', '%m/%d/%Y', '00-00-0000');

CREATE TABLE IF NOT EXISTS `#__jbusinessdirectory_billing_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `company_name` varchar(55) DEFAULT NULL,
  `address` varchar(55) DEFAULT NULL,
  `postal_code` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `#__jbusinessdirectory_company_offers` ADD COLUMN `featured` TINYINT(1) NULL;
ALTER TABLE `#__jbusinessdirectory_company_offers` ADD COLUMN `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP  AFTER `featured` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD INDEX `idx_alis` (`alias` ASC) ;

ALTER TABLE `#__jbusinessdirectory_company_offers` 

ADD INDEX `idx_company` (`companyId` ASC) , ADD INDEX `idx_search` (`state` ASC, `endDate` ASC, `startDate` ASC, `publish_end_date` ASC, `publish_start_date` ASC) 

, ADD INDEX `idx_alias` (`alias` ASC) ;

ALTER TABLE `#__jbusinessdirectory_categories` ADD INDEX `idx_alias` (`alias` ASC) ;
ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `show_email` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_attachments` TINYINT(1) NULL DEFAULT 1;



