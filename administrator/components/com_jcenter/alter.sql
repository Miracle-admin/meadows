ALTER TABLE `#__filters_node`  ADD `requiresvalue` TINYINT UNSIGNED  DEFAULT '1' NOT NULL ;
ALTER TABLE `#__dataset_tables`  ADD `engine` TINYINT UNSIGNED  DEFAULT '0' NOT NULL ;
ALTER TABLE `#__eguillage_node`  ADD `pnamekey` VARCHAR  ( 100 )  NOT NULL ;
ALTER TABLE `#__layout_node`  ADD `pnamekey` VARCHAR ( 100 )  NOT NULL ;
ALTER TABLE `#__theme_node`  ADD `framework` TINYINT UNSIGNED  DEFAULT '0' NOT NULL ;