ALTER TABLE `#__vm2wishlists_lists` DROP `auprule_prefix`;
ALTER TABLE `#__vm2wishlists_lists` CHANGE `list_description` `list_description` TEXT NOT NULL;
ALTER TABLE `#__vm2wishlists_lists` CHANGE `privacy` `privacy` INT(11) NOT NULL;
ALTER TABLE `#__vm2wishlists_lists` ADD `asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `#__vm2wishlists_lists` ADD `icon_class` VARCHAR(180) NOT NULL AFTER `list_description`;
ALTER TABLE `#__vm2wishlists_lists` ADD `forbidcatids` VARCHAR(180) NOT NULL AFTER `privacy`, 
	ADD `onlycatids` VARCHAR(180) NOT NULL AFTER `forbidcatids`, 
	ADD `adders` TINYINT(1) NOT NULL AFTER `onlycatids`, 
	ADD `amz_link` TINYINT(1) NOT NULL AFTER `adders`, 
	ADD `amz_base` VARCHAR(4) NOT NULL AFTER `amz_link`, 
	ADD `amz_prefix` VARCHAR(180) NOT NULL AFTER `amz_base`;
ALTER TABLE `#__vm2wishlists_items` CHANGE `list_id` `listid` INT(11) NOT NULL;