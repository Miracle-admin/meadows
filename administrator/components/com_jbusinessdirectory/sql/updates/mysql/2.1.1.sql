ALTER TABLE `#__jbusinessdirectory_companies` 

ADD INDEX `idx_name` (`name` ASC) 

, ADD INDEX `idx_type` (`typeId` ASC) 

, ADD INDEX `idx_user` (`userId` ASC) 

, ADD INDEX `idx_state` (`state` ASC) 

, ADD INDEX `idx_approved` (`approved` ASC) ;

ALTER TABLE `#__jbusinessdirectory_categories` 

ADD INDEX `idx_parent` (`parentId` ASC) ;

ALTER TABLE `#__jbusinessdirectory_companies` 

ADD INDEX `idx_country` (`countryId` ASC) ;

ALTER TABLE `#__jbusinessdirectory_categories` 

ADD INDEX `idx_name` (`name` ASC) ;

