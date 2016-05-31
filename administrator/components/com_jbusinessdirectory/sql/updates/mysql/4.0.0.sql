ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `direct_processing` TINYINT(1) NULL  AFTER `show_cat_description` ;
INSERT INTO `#__jbusinessdirectory_emails` (`email_subject`, `email_name`, `email_type`, `email_content`, `is_default`) VALUES
('Quote Request', 'Request Quote', 'Request Quote Email', 0x3c703e4e616d653a5b66697273745f6e616d655d205b6c6173745f6e616d655d3c6272202f3e452d6d61696c3a205b636f6e746163745f656d61696c5d3c6272202f3e43617465676f72793a205b63617465676f72795d3c6272202f3e3c6272202f3e5b636f6e746163745f656d61696c5f636f6e74656e745d3c2f703e, 0);

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `publish_only_city` TINYINT(1) NULL DEFAULT 0 ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `max_video` TINYINT(2) NULL DEFAULT 10  , ADD COLUMN `max_pictures` TINYINT(2) NULL DEFAULT 15 ;

ALTER TABLE `#__jbusinessdirectory_packages` CHANGE COLUMN `name` `name` VARCHAR(145) NULL DEFAULT NULL  ;

ALTER TABLE `#__jbusinessdirectory_companies` 

 ADD INDEX `idx_keywords` (`keywords` ASC) 

, ADD INDEX `idx_description` (`description`(100) ASC) 

, ADD INDEX `idx_city` (`city` ASC) 

, ADD INDEX `idx_county` (`county` ASC) 

, ADD INDEX `idx_maincat` (`mainSubcategory` ASC) 

, ADD INDEX `idx_zipcode` (`latitude` ASC, `longitude` ASC) 

, ADD INDEX `idx_phone` (`phone` ASC) ;


ALTER TABLE `#__jbusinessdirectory_company_category` 

ADD INDEX `idx_category` (`companyId` ASC, `categoryId` ASC) ;

ALTER TABLE `#__jbusinessdirectory_categories` 

ADD INDEX `idx_state` (`state` ASC) ;

ALTER TABLE `#__jbusinessdirectory_company_activity_city` 

ADD INDEX `idx_company` (`company_id` ASC) 

, ADD INDEX `idx_city` (`city_id` ASC) ;

ALTER TABLE `#__jbusinessdirectory_orders` 

ADD INDEX `idx_company` (`company_id` ASC) 

, ADD INDEX `idx_package` (`package_id` ASC) 

, ADD INDEX `idx_date` (`start_date` ASC) 

, ADD INDEX `idx_order` (`order_id` ASC) ;

ALTER TABLE `#__jbusinessdirectory_company_ratings` ADD INDEX `idx_company` (`companyId` ASC) ;

ALTER TABLE `#__jbusinessdirectory_company_offer_pictures` ADD INDEX `idx_offer` (`offerId` ASC) ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `show_secondary_locations` TINYINT(1) NULL DEFAULT 0 , ADD COLUMN `search_view_mode` TINYINT(1) NULL DEFAULT 0  AFTER `show_secondary_locations` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `alias` VARCHAR(100) NOT NULL DEFAULT '' AFTER `name` ;

ALTER TABLE `#__jbusinessdirectory_categories` ADD COLUMN `alias` VARCHAR(100) NOT NULL DEFAULT '' AFTER `name` , CHANGE COLUMN `name` `name` VARCHAR(100) NOT NULL  ;

ALTER TABLE `#__jbusinessdirectory_company_events` ADD COLUMN `alias` VARCHAR(100) NOT NULL DEFAULT '' AFTER `name` ;

ALTER TABLE `#__jbusinessdirectory_company_offers` ADD COLUMN `alias` VARCHAR(100) NOT NULL DEFAULT '';

ALTER TABLE `#__jbusinessdirectory_company_offers` ADD COLUMN  `address` varchar(45) DEFAULT NULL,  ADD COLUMN  `city` varchar(45) DEFAULT NULL,  ADD COLUMN `short_description` varchar(255) DEFAULT NULL,
  ADD COLUMN `county` varchar(45) DEFAULT NULL, ADD COLUMN `publish_start_date` DATE NULL , ADD COLUMN `publish_end_date` DATE NULL  AFTER `publish_start_date` , ADD COLUMN `view_type` TINYINT(2) NOT NULL DEFAULT 1  AFTER `publish_end_date` ;

CREATE TABLE `#__jbusinessdirectory_company_offer_category` (
  `offerId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`offerId`,`categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__jbusinessdirectory_company_offers` ADD COLUMN `url` VARCHAR(145) NULL  AFTER `view_type` , ADD COLUMN `article_id` INT NULL  AFTER `url` ;

ALTER TABLE `#__jbusinessdirectory_company_offers` ADD COLUMN `latitude` VARCHAR(45) , ADD COLUMN `longitude` VARCHAR(45) ;

ALTER TABLE `#__jbusinessdirectory_companies` CHANGE COLUMN `short_description` `short_description` VARCHAR(255) NULL DEFAULT NULL  ;

ALTER TABLE `#__jbusinessdirectory_companies` CHANGE COLUMN `slogan` `slogan` VARCHAR(255) NULL DEFAULT NULL  ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `address_format` TINYINT(1) NOT NULL DEFAULT 0  AFTER `search_view_mode` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `featured` TINYINT(1) NOT NULL DEFAULT 0  AFTER `publish_only_city` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `offer_search_results_grid_view` TINYINT(1) NULL DEFAULT 0  AFTER `address_format` ;

UPDATE `#__jbusinessdirectory_companies` set alias =  LOWER(REPLACE (name, ' ', '-'));
UPDATE `#__jbusinessdirectory_company_offers` set alias =  LOWER(REPLACE (subject, ' ', '-'));
UPDATE `#__jbusinessdirectory_company_events` set alias =  LOWER(REPLACE (name, ' ', '-'));
UPDATE `#__jbusinessdirectory_categories` set alias =  LOWER(REPLACE (name, ' ', '-'));

ALTER TABLE `#__jbusinessdirectory_packages` ADD COLUMN `time_unit` VARCHAR(10) NOT NULL DEFAULT 'D'  AFTER `ordering` , ADD COLUMN `time_amount` MEDIUMINT NOT NULL DEFAULT 1  AFTER `time_unit` ;


