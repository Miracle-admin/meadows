ALTER TABLE `#__jbusinessdirectory_applicationsettings` ADD COLUMN `enable_seo` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_offers` , ADD COLUMN `enable_search_filter` TINYINT(1) NOT NULL DEFAULT 1  AFTER `enable_seo` ;

