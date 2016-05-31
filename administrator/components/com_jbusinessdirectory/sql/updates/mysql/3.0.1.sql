ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `short_description` TEXT NULL  AFTER `comercialName` ;

INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES (27, 'short_description', 1);
INSERT INTO `#__jbusinessdirectory_default_attributes` (`id`, `name`, `config`) VALUES (28, 'contact_person', 1);

ALTER TABLE `#__jbusinessdirectory_company_contact` CHANGE COLUMN `telefon` `phone` VARCHAR(20) NULL DEFAULT NULL  ;

ALTER TABLE `#__jbusinessdirectory_company_contact` DROP COLUMN `typeId` , CHANGE COLUMN `contact_person_name` `contact_name` VARCHAR(50) NULL DEFAULT NULL  , CHANGE COLUMN `contact_pers_function` `contact_function` VARCHAR(50) NULL DEFAULT NULL  , CHANGE COLUMN `department` `contact_department` VARCHAR(100) NULL DEFAULT NULL  , CHANGE COLUMN `email` `contact_email` VARCHAR(60) NULL DEFAULT NULL  , CHANGE COLUMN `phone` `contact_phone` VARCHAR(20) NULL DEFAULT NULL  , CHANGE COLUMN `fax` `contact_fax` VARCHAR(20) NULL DEFAULT NULL  

, DROP PRIMARY KEY 

, ADD PRIMARY KEY (`id`, `companyId`) 

, DROP INDEX `R_13` 

, ADD INDEX `R_13` (`companyId` ASC) ;

ALTER TABLE `#__jbusinessdirectory_company_contact` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `category_view` TINYINT(1) NOT NULL DEFAULT 1  AFTER `company_view` ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `search_result_view` TINYINT(1) NOT NULL DEFAULT 1  AFTER `category_view` ;



