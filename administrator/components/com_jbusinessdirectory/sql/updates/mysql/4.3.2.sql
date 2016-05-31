ALTER TABLE `#__jbusinessdirectory_company_locations` 
ADD COLUMN `phone` VARCHAR(45) NULL COMMENT '' AFTER `longitude`;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `facebook` VARCHAR(45) NULL COMMENT '',
ADD COLUMN `twitter` VARCHAR(45) NULL COMMENT '' AFTER `facebook`,
ADD COLUMN `googlep` VARCHAR(45) NULL COMMENT '' AFTER `twitter`,
ADD COLUMN `linkedin` VARCHAR(45) NULL COMMENT '' AFTER `googlep`,
ADD COLUMN `youtube` VARCHAR(45) NULL COMMENT '' AFTER `linkedin`;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `logo` VARCHAR(145) NULL COMMENT '';

ALTER TABLE `#__jbusinessdirectory_company_offers` 
CHANGE COLUMN `featured` `featured` TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE `#__jbusinessdirectory_company_events` 
CHANGE COLUMN `featured` `featured` TINYINT(1) NOT NULL DEFAULT '0';