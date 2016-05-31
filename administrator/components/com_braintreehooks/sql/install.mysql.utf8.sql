CREATE TABLE IF NOT EXISTS `#__braintreehooks` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`webhook_name` VARCHAR(500) NOT NULL,
	`last_triggered` DATETIME NOT NULL,
	`active` TINYINT(1) NOT NULL,
	`trigger_count` INT(11) NOT NULL,
	`ordering` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;
