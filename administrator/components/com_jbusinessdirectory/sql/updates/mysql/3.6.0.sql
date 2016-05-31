CREATE TABLE `#__jbusinessdirectory_company_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `street_number` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `county` varchar(60) DEFAULT NULL,
  `postalCode` varchar(45) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;


ALTER TABLE `#__jbusinessdirectory_categories` ADD COLUMN `markerLocation` VARCHAR(250) NULL  AFTER `imageLocation` ;

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `modified` DATETIME NULL AFTER `creationDate` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `terms_conditions` BLOB NULL  AFTER `claim_business` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `vat` TINYINT NULL DEFAULT 0  AFTER `terms_conditions` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `expiration_day_notice` TINYINT(2) NULL  AFTER `vat` ;

ALTER TABLE `#__jbusinessdirectory_orders` ADD COLUMN `expiration_email_date` DATETIME NULL  AFTER `currency` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `show_cat_description` TINYINT(1) NULL DEFAULT 1  ;




