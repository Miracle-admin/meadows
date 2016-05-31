CREATE TABLE IF NOT EXISTS `#__vm2wishlists_lists` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`list_name` VARCHAR(180)  NOT NULL ,
`list_description` TEXT NOT NULL ,
`icon_class` VARCHAR(180)  NOT NULL ,
`privacy` INT(11)  NOT NULL ,
`forbidcatids` VARCHAR(180)  NOT NULL ,
`onlycatids` VARCHAR(180)  NOT NULL ,
`adders` TINYINT(1)  NOT NULL ,
`amz_link` TINYINT(1)  NOT NULL ,
`amz_base`  VARCHAR(4)  NOT NULL ,
`amz_prefix`  VARCHAR(180)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__vm2wishlists_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(11) NOT NULL,
  `listid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
)  DEFAULT CHARSET=utf8  ;

