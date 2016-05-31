ALTER TABLE `#__jbusinessdirectory_company_events` ADD COLUMN `short_description` VARCHAR(245) NULL , ADD COLUMN `address` VARCHAR(50) NULL  , ADD COLUMN `city` VARCHAR(45) NULL  , ADD COLUMN `county` VARCHAR(45) NULL , ADD COLUMN `latitude` VARCHAR(45) NULL  , ADD COLUMN `longitude` VARCHAR(45) NULL  , ADD COLUMN `featured` VARCHAR(45) NULL , ADD COLUMN `created` TIMESTAMP NULL  AFTER `featured` , ADD COLUMN `price` DECIMAL(10,2) NULL  AFTER `created` , CHANGE COLUMN `view_count` `view_count` INT(11) NOT NULL  , CHANGE COLUMN `approved` `approved` TINYINT(1) NOT NULL  ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `order_search_listings` VARCHAR(45) NULL  , ADD COLUMN `order_search_offers` VARCHAR(45) NULL , ADD COLUMN `order_search_events` VARCHAR(45) NULL ;

ALTER TABLE `#__jbusinessdirectory_company_events` ADD COLUMN `recurring_id` INT NULL ;
update `#__jbusinessdirectory_company_events` set address = location;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `events_search_view` TINYINT(1) NULL DEFAULT 2 ;

ALTER TABLE `#__jbusinessdirectory_countries` ADD COLUMN `logo` VARCHAR(100) NULL  AFTER `country_currency_short` , ADD COLUMN `description` VARCHAR(245) NULL  AFTER `logo` ;

ALTER TABLE `#__jbusinessdirectory_countries` CHANGE COLUMN `country_id` `id` INT(10) NOT NULL ;

UPDATE `#__jbusinessdirectory_company_review_responses` set `state` =1;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_google_map_clustering` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_bookmarks` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `max_attachments` TINYINT(4) NOT NULL DEFAULT '10';
ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `max_categories` TINYINT(4) NOT NULL DEFAULT '10';

ALTER TABLE `#__jbusinessdirectory_packages` ADD COLUMN `max_attachments` tinyint(4) NOT NULL DEFAULT '5';
ALTER TABLE `#__jbusinessdirectory_packages` ADD COLUMN `max_categories` tinyint(4) NOT NULL DEFAULT '10';

ALTER TABLE `#__jbusinessdirectory_company_pictures` 
CHANGE COLUMN `picture_info` `picture_info` VARCHAR(255) NOT NULL  ,
CHANGE COLUMN `picture_path` `picture_path` VARCHAR(255) NOT NULL  ;

ALTER TABLE `#__jbusinessdirectory_company_offer_pictures` 
CHANGE COLUMN `picture_info` `picture_info` VARCHAR(255) NOT NULL  ,
CHANGE COLUMN `picture_path` `picture_path` VARCHAR(255) NOT NULL  ;

ALTER TABLE `#__jbusinessdirectory_company_event_pictures` 
CHANGE COLUMN `picture_info` `picture_info` VARCHAR(255) NOT NULL  ,
CHANGE COLUMN `picture_path` `picture_path` VARCHAR(255) NOT NULL  ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `time_format` VARCHAR(45) NOT NULL DEFAULT 'H:i:s' ;

ALTER TABLE `#__jbusinessdirectory_company_events` 
CHANGE COLUMN `featured` `featured` TINYINT(1) NULL DEFAULT 0 COMMENT '';

ALTER TABLE `#__jbusinessdirectory_language_translations` 
ADD COLUMN `name` VARCHAR(75) NULL COMMENT '';

ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `review_score` float DEFAULT NULL  ;
ALTER TABLE `#__jbusinessdirectory_companies` ADD COLUMN `skype` VARCHAR(100) NULL  ;

ALTER TABLE `#__jbusinessdirectory_applicationsettings` 
ADD COLUMN `front_end_acl` TINYINT(1) NULL DEFAULT 0;

