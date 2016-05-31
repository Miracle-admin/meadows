CREATE TABLE IF NOT EXISTS `#__mailer_node` (
  `mailerid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `modifiedby` int(10) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `sendername` varchar(100) NOT NULL,
  `senderemail` varchar(100) NOT NULL,
  `replyname` varchar(100) NOT NULL,
  `replyemail` varchar(100) NOT NULL,
  `bouncebackemail` varchar(100) NOT NULL,
  `embedimages` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `multiplepart` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `encodingformat` varchar(50) NOT NULL,
  `charset` varchar(100) NOT NULL,
  `wordwrapping` int(10) unsigned NOT NULL DEFAULT '0',
  `addnames` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `premium` tinyint(4) NOT NULL DEFAULT '0',
  `designation` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '9',
  `hostname` varchar(254) NOT NULL,
  PRIMARY KEY (`mailerid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailer_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__scheduler_node` (
  `schid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nextdate` int(10) unsigned NOT NULL DEFAULT '0',
  `frequency` int(10) unsigned NOT NULL DEFAULT '0',
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `cron` varchar(50) NOT NULL,
  `ptype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(50) NOT NULL,
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `report` text NOT NULL,
  `maxtime` int(10) unsigned NOT NULL DEFAULT '5',
  `maxprocess` smallint(5) unsigned NOT NULL DEFAULT '1',
  `viewname` varchar(50) NOT NULL,
  PRIMARY KEY (`schid`),
  UNIQUE KEY `UK_namekey_jos_scheduler_node` (`namekey`),
  KEY `IX_scheduler_node_wid` (`wid`),
  KEY `IX_scheduler_node_publish_priority_nextdate` (`publish`,`nextdate`,`priority`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__scheduler_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_node` (
  `mgid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wid` int(10) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(100) NOT NULL,
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '101',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `senddate` int(10) unsigned NOT NULL DEFAULT '0',
  `html` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tags` text NOT NULL,
  `archive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '10',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `publishstart` int(10) unsigned NOT NULL DEFAULT '0',
  `publishend` int(10) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `sms` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `template` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `format` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notify` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`mgid`),
  UNIQUE KEY `UK_mailing_node_namekey` (`namekey`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__joobi_languages` (
  `lgid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(10)  NOT NULL,
  `name` varchar(100)  NOT NULL,
  `main` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `real` varchar(100)  NOT NULL,
  `premium` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rtl` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `availsite` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `availadmin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `localeconv` text  NOT NULL,
  `locale` varchar(255)  NOT NULL,
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `automatic` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lgid`),
  UNIQUE KEY `UK_languages_code` (`code`),
  KEY `IX_languages_main_publish_availadmin_availsite` (`main`,`publish`,`availadmin`,`availsite`)
) ENGINE=MyISAM    /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__joobi_languages` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__action_node` (
  `actid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(255) NOT NULL,
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `folder` varchar(30) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `filter` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `before` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`actid`),
  UNIQUE KEY `UK_action_node_namekey` (`namekey`),
  KEY `IX_action_node_wid_publish` (`wid`,`publish`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__action_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dropset_node` (
  `namekey` varchar(50) NOT NULL,
  `did` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `map` varchar(20) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `outype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ref_sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `first_value` int(10) unsigned NOT NULL DEFAULT '0',
  `first_all` tinyint(4) NOT NULL DEFAULT '0',
  `lib_ext` tinyint(4) NOT NULL DEFAULT '0',
  `external` varchar(60) NOT NULL,
  `first_caption` varchar(50) NOT NULL,
  `core` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `mon` tinyint(4) NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `reload` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `parent` varchar(50) NOT NULL,
  `isparent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `allowothers` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `colorstyle` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  UNIQUE KEY `UK_dropset_node_namekey` (`namekey`),
  KEY `IX_dropset_node_wid_publish_level_rolid` (`publish`,`wid`,`level`,`rolid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dropset_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__widgets_type` (
  `wgtypeid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(100) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`wgtypeid`),
  UNIQUE KEY `UK_widgets_type_namekey` (`namekey`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__widgets_type` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dropset_values` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `value` varchar(100) NOT NULL,
  `valuetxt` varchar(50) NOT NULL,
  `premium` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(150) NOT NULL DEFAULT '',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `color` varchar(20) NOT NULL,
  `parent` varchar(100) NOT NULL,
  `inputbox` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vid`),
  UNIQUE KEY `UK_dropset_values_did_value` (`did`,`value`),
  UNIQUE KEY `UK_dropset_values_namekey` (`namekey`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dropset_values` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_node` (
  `yid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(100) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `subtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `wid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `frontend` tinyint(4) NOT NULL DEFAULT '0',
  `menu` tinyint(4) NOT NULL DEFAULT '1',
  `wizard` tinyint(4) NOT NULL DEFAULT '0',
  `form` tinyint(4) NOT NULL DEFAULT '1',
  `dropdown` tinyint(4) NOT NULL DEFAULT '0',
  `pagination` tinyint(4) NOT NULL DEFAULT '0',
  `filters` tinyint(4) NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `core` tinyint(4) NOT NULL DEFAULT '1',
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `icon` varchar(50) NOT NULL,
  `tmid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `fields` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent` varchar(100) NOT NULL,
  `reload` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `widgets` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `faicon` varchar(50) NOT NULL,
  `useredit` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`yid`),
  UNIQUE KEY `UK_layout_node_namekey` (`namekey`),
  KEY `IX_layout_node_wid_type_publish_level_rolid` (`wid`,`publish`,`type`,`level`,`rolid`),
  KEY `IX_layout_node_sid` (`sid`)
) ENGINE=InnoDB   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_mlinks` (
  `mid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `yid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `icon` varchar(50) NOT NULL,
  `action` varchar(150) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '99',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '1',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `namekey` varchar(100) NOT NULL,
  `ref_wid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `faicon` varchar(50) NOT NULL,
  `color` varchar(15) NOT NULL,
  `xsvisible` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xshidden` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `devicevisible` varchar(100) NOT NULL,
  `devicehidden` varchar(100) NOT NULL,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `UK_layout_menus_namekey` (`namekey`),
  KEY `layout_mlinks_index_yid_publish_level_rolid_ordering` (`yid`,`level`,`publish`,`ordering`,`rolid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_mlinks` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__translation_en` (
  `text` text   NOT NULL,
  `auto` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `imac` varchar(255) NOT NULL,
  `nbchars` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`imac`),
  KEY `ix_translation_en_nbchars` (`nbchars`),
  FULLTEXT KEY `FTXT_translation_en_text` (`text`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__dropset_valuestrans` (
  `vid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `lgid` tinyint(4) unsigned NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dropset_valuestrans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dropset_trans` (
  `did` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lgid` tinyint(3) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`,`lgid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dropset_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__eguillage_roles` (
  `ctrid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `override` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ctrid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__eguillage_roles` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_trans` (
  `yid` mediumint(8) unsigned NOT NULL,
  `lgid` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `wname` varchar(255) NOT NULL,
  `wdescription` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`yid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__joobi_preferences_users` (
  `namekey` varchar(50) NOT NULL,
  `wid` smallint(5) unsigned NOT NULL,
  `text` text NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`namekey`,`wid`,`uid`)
) ENGINE=InnoDB  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__joobi_preferences_users` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dataset_columns` (
  `dbcid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dbtid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `pkey` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checkval` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attributes` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mandatory` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `default` varchar(50) NOT NULL,
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `extra` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `size` decimal(8,0) NOT NULL DEFAULT '0',
  `export` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `namekey` varchar(50) NOT NULL,
  `core` tinyint(4) NOT NULL DEFAULT '1',
  `columntype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `indexed` tinyint(4) NOT NULL DEFAULT '0',
  `noaudit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`dbcid`),
  UNIQUE KEY `UK_dataset_columns` (`namekey`),
  UNIQUE KEY `UK_dataset_columns_dbtid` (`name`,`dbtid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dataset_columns` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__model_trans` (
  `sid` smallint(5) unsigned NOT NULL,
  `lgid` tinyint(4) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__model_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_multiforms` (
  `fid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `yid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `map` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '99',
  `initial` varchar(255) NOT NULL,
  `readonly` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '1',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `area` varchar(20) NOT NULL,
  `ref_yid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `frame` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `ref_wid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `namekey` varchar(100) NOT NULL,
  `fdid` int(10) unsigned NOT NULL DEFAULT '0',
  `parentdft` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checktype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xsvisible` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xshidden` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `devicevisible` varchar(100) NOT NULL,
  `devicehidden` varchar(100) NOT NULL,
  PRIMARY KEY (`fid`),
  UNIQUE KEY `UK_layout_forms_namekey` (`namekey`),
  KEY `IX_layout_multiform_yid_publish_level_rolid_ordering` (`yid`,`publish`,`level`,`rolid`,`ordering`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_multiforms` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dataset_constraints` (
  `ctid` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `dbtid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(255) NOT NULL,
  PRIMARY KEY (`ctid`),
  UNIQUE KEY `UK_dataset_constraints_namekey` (`namekey`),
  KEY `IX_dataset_constraints_dbtid_type` (`dbtid`,`type`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dataset_constraints` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_mlinkstrans` (
  `mid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lgid` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  PRIMARY KEY (`mid`,`lgid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_mlinkstrans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__model_node` (
  `sid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `dbtid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `path` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `extended` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `checkval` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL,
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '0',
  `prefix` varchar(50) NOT NULL,
  `suffix` varchar(50) NOT NULL,
  `extstid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fields` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checkout` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `trash` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `audit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parentsid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `totalcustom` smallint(5) unsigned NOT NULL DEFAULT '0',
  `reload` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `faicon` varchar(50) NOT NULL,
  `pnamekey` varchar(50) NOT NULL,
  PRIMARY KEY (`sid`),
  UNIQUE KEY `UK_model_node_namekey` (`namekey`),
  KEY `IX_model_node_prefix_level` (`prefix`,`level`),
  KEY `IX_model_node_dbtic` (`dbtid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__model_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dataset_constraintsitems` (
  `ctid` mediumint(5) unsigned NOT NULL,
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '5',
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `dbcid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`dbcid`,`ctid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dataset_constraintsitems` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__eguillage_action` (
  `ctr_action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ctrid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `actid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `action` varchar(100) NOT NULL,
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `namekey` varchar(50) NOT NULL,
  PRIMARY KEY (`ctr_action_id`),
  KEY `IX_eguillage_action_ctrid_publish` (`ctrid`,`publish`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__eguillage_action` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__widgets_trans` (
  `widgetid` int(10) unsigned NOT NULL,
  `lgid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned DEFAULT '2',
  PRIMARY KEY (`widgetid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__widgets_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__joobi_preferences` (
  `namekey` varchar(50) NOT NULL,
  `wid` smallint(5) unsigned NOT NULL,
  `text` text,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `premium` text,
  PRIMARY KEY (`wid`,`namekey`)
) ENGINE=InnoDB  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__joobi_preferences` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__translation_reference` (
  `wid` mediumint(8) unsigned NOT NULL,
  `load` tinyint(4) NOT NULL DEFAULT '0',
  `imac` varchar(255) NOT NULL,
  PRIMARY KEY (`wid`,`imac`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__translation_reference` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dataset_tables` (
  `dbtid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dbid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `namekey` varchar(100) NOT NULL,
  `size` decimal(14,0) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `rows` int(10) unsigned NOT NULL DEFAULT '0',
  `rows_average_length` decimal(8,0) unsigned NOT NULL DEFAULT '0',
  `data_length` decimal(14,0) unsigned NOT NULL DEFAULT '0',
  `prefix` varchar(255) NOT NULL,
  `version` varchar(50) NOT NULL,
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pkey` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `suffix` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `domain` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `export` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `exportdelete` tinyint(4) NOT NULL DEFAULT '0',
  `noaudit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`dbtid`),
  UNIQUE KEY `UK_dataset_tables_namekey` (`namekey`),
  UNIQUE KEY `UK_dbid_name` (`dbid`,`name`),
  KEY `IX_dataset_tables_rolid` (`rolid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dataset_tables` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__filters_node` (
  `flid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `bktbefore` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `map` varchar(20) NOT NULL,
  `condopr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bktafter` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `logicopr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `refmap` varchar(30) NOT NULL,
  `ref_sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `typea` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `typeb` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(50) NOT NULL,
  `name` varchar(40) NOT NULL,
  `yid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`flid`),
  UNIQUE KEY `UK_filters_node_namekey` (`namekey`),
  KEY `IX_filters_node_yid` (`yid`),
  KEY `IX_filters_node_sid` (`sid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__filters_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__eguillage_trans` (
  `ctrid` int(10) unsigned NOT NULL,
  `lgid` tinyint(4) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ctrid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__eguillage_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__widgets_node` (
  `widgetid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `modifiedby` int(10) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `framework_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `framework_id` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `wgtypeid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`widgetid`),
  UNIQUE KEY `UK_widgets_node_namekey` (`namekey`),
  KEY `IX_widgets_node_framework_type_publish_core` (`framework_type`,`publish`,`core`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__widgets_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_dropset` (
  `did` mediumint(8) unsigned NOT NULL,
  `yid` mediumint(8) unsigned NOT NULL,
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`,`yid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_dropset` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__dataset_foreign` (
  `fkid` smallint(5) NOT NULL AUTO_INCREMENT,
  `dbtid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ref_dbtid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ondelete` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `feid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ref_feid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `namekey` varchar(100) NOT NULL,
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `map` varchar(50) NOT NULL,
  `map2` varchar(50) NOT NULL,
  `onupdate` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '99',
  PRIMARY KEY (`fkid`),
  UNIQUE KEY `UK_dataset_foreign_feid_dbtid_red_dbtid` (`feid`,`dbtid`,`ref_dbtid`),
  UNIQUE KEY `UK_dataset_foreign_namekey` (`namekey`),
  KEY `IX_dataset_foreign_publish_ref_feid` (`ref_feid`,`publish`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__dataset_foreign` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_listingstrans` (
  `lid` mediumint(8) unsigned NOT NULL,
  `lgid` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_listingstrans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_multiformstrans` (
  `fid` mediumint(8) unsigned NOT NULL,
  `lgid` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_multiformstrans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__eguillage_node` (
  `ctrid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(100) NOT NULL,
  `app` varchar(100) NOT NULL,
  `task` varchar(100) NOT NULL,
  `premium` tinyint(4) NOT NULL DEFAULT '0',
  `admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `yid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `path` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '1',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `trigger` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `visible` tinyint(3) unsigned NOT NULL DEFAULT '9',
  `checkowner` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reload` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `checkadminowner` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ctrid`),
  UNIQUE KEY `UK_controller_app_task_admin` (`app`,`task`,`admin`),
  UNIQUE KEY `UK_controller_namekey` (`namekey`),
  KEY `IX_controller_publish_premium_level` (`publish`,`level`,`premium`),
  KEY `IX_controller_node_wid` (`wid`),
  KEY `IX_controller_node_yid` (`yid`)
) ENGINE=InnoDB   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__eguillage_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__sesion_node` (
  `sessid` varchar(250) NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` double unsigned NOT NULL DEFAULT '0',
  `data` longtext,
  `framework` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sessid`(64)),
  KEY `IX_sesion_node_uid` (`uid`),
  KEY `IX_sesion_node_modified` (`modified`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__sesion_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__layout_listings` (
  `lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `yid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `map` varchar(30) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL,
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '99',
  `hidden` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '1',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `search` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `advsearch` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `advordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL,
  `params` text NOT NULL,
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ref_wid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `namekey` varchar(100) NOT NULL,
  `fdid` int(10) unsigned NOT NULL DEFAULT '0',
  `parentdft` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xsvisible` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xshidden` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `devicevisible` varchar(100) NOT NULL,
  `devicehidden` varchar(100) NOT NULL,
  PRIMARY KEY (`lid`),
  UNIQUE KEY `UK_layout_listings_namekey` (`namekey`),
  KEY `IX_layout_listing_yid_publish_level_rolid_hidden_ordering` (`yid`,`level`,`publish`,`rolid`,`hidden`,`ordering`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__layout_listings` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__joobi_files` (
  `filid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(254) NOT NULL,
  `path` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `md5` varchar(40) NOT NULL DEFAULT '',
  `secure` tinyint(4) NOT NULL DEFAULT '0',
  `thumbnail` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `width` smallint(6) NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `twidth` smallint(5) unsigned NOT NULL DEFAULT '0',
  `theight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mime` varchar(40) NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `modifiedby` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `alias` varchar(255) NOT NULL,
  `vendid` int(10) unsigned NOT NULL DEFAULT '1',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `storage` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `folder` varchar(254) NOT NULL,
  PRIMARY KEY (`filid`),
  UNIQUE KEY `UK_joobi_files_name_type_path` (`path`(70),`type`(5),`name`(250))
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__joobi_files` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__engine_languages` (
  `englgid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `engid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `lgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ref_lgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`englgid`),
  UNIQUE KEY `UK_translation_engine_lgid_ref_lgid` (`engid`,`lgid`,`ref_lgid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__engine_languages` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__translation_populate` (
  `dbcid` int(10) unsigned NOT NULL,
  `eid` int(10) unsigned NOT NULL,
  `imac` varchar(50) NOT NULL,
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`dbcid`,`eid`),
  KEY `IK_translation_populate_wid` (`wid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__translation_populate` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_languages` (
  `wid` mediumint(8) unsigned NOT NULL,
  `lgid` tinyint(3) unsigned NOT NULL,
  `available` tinyint(4) NOT NULL DEFAULT '0',
  `translation` tinyint(4) NOT NULL DEFAULT '1',
  `completed` double(5,2) unsigned NOT NULL DEFAULT '0.00',
  `automatic` double(5,2) unsigned NOT NULL DEFAULT '0.00',
  `totalimac` int(10) unsigned NOT NULL DEFAULT '0',
  `manual` double(5,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`wid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_languages` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_dependency` (
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ref_wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`wid`,`ref_wid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_dependency` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_translation` (
  `wid` int(10) unsigned NOT NULL,
  `lgid` int(10) unsigned NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`wid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_translation` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_level` (
  `lwid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `namekey` varchar(50) NOT NULL,
  PRIMARY KEY (`lwid`),
  UNIQUE KEY `UK_extension_level_wid_level` (`wid`,`level`),
  UNIQUE KEY `NamekeyExtensionLevel` (`namekey`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_level` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_version` (
  `vsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wid` int(10) unsigned NOT NULL DEFAULT '0',
  `version` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `beta` smallint(5) unsigned NOT NULL DEFAULT '0',
  `filid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `encoding` varchar(100) NOT NULL,
  `changelog` text NOT NULL,
  `final` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sha1` varchar(100) NOT NULL,
  `code` char(32) NOT NULL,
  `marketing` varchar(255) NOT NULL,
  PRIMARY KEY (`vsid`),
  UNIQUE KEY `UK_extension_version_wid_version` (`wid`,`version`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_version` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_leveltrans` (
  `lwid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lgid` tinyint(3) unsigned NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lwid`,`lgid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_leveltrans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_trans` (
  `lgid` tinyint(3) unsigned NOT NULL,
  `wid` mediumint(8) unsigned NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lgid`,`wid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_node` (
  `wid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(100) NOT NULL,
  `folder` varchar(40) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` smallint(5) unsigned NOT NULL DEFAULT '1',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `destination` varchar(255) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `trans` tinyint(4) NOT NULL DEFAULT '0',
  `certify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `version` int(10) unsigned NOT NULL DEFAULT '0',
  `lversion` int(10) unsigned NOT NULL DEFAULT '0',
  `pref` tinyint(4) NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `install` text NOT NULL,
  `ordering` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `reload` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `showconfig` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `framework` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`wid`),
  UNIQUE KEY `UK_extension_node_namekey` (`namekey`),
  KEY `IX_extension_node_publish` (`publish`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_info` (
  `wid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(100) NOT NULL,
  `documentation` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `flash` varchar(255) NOT NULL,
  `support` varchar(255) NOT NULL,
  `forum` varchar(255) NOT NULL,
  `homeurl` varchar(200) NOT NULL,
  `feedback` varchar(255) NOT NULL,
  `userversion` varchar(100) NOT NULL,
  `userlversion` varchar(100) NOT NULL,
  `beta` smallint(5) NOT NULL DEFAULT '0',
  `keyword` varchar(200) NOT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_info` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__extension_userinfos` (
  `wid` int(10) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ltype` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `license` varchar(255) NOT NULL,
  `token` varchar(254) NOT NULL,
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  `maintenance` int(10) unsigned NOT NULL DEFAULT '0',
  `flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `subtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`wid`,`level`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__extension_userinfos` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__role_node` (
  `rolid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lft` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rgt` smallint(5) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `joomla` smallint(5) unsigned NOT NULL DEFAULT '0',
  `j16` int(10) unsigned NOT NULL DEFAULT '1',
  `namekey` varchar(50) NOT NULL DEFAULT '',
  `color` varchar(20) NOT NULL DEFAULT 'black',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `depth` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rolid`),
  UNIQUE KEY `UK_role_node_namekey` (`namekey`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__role_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__role_trans` (
  `rolid` smallint(5) unsigned NOT NULL,
  `lgid` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rolid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__role_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__role_members` (
  `rolid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`rolid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__role_members` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__theme_node` (
  `tmid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `namekey` varchar(255) NOT NULL,
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `premium` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(3) NOT NULL DEFAULT '0',
  `wid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL,
  `filid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `availability` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `folder` varchar(100) NOT NULL,
  `params` text NOT NULL,
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '999',
  PRIMARY KEY (`tmid`),
  UNIQUE KEY `UK_theme_node_namekey` (`namekey`),
  KEY `IK_theme_node_type_publish_core_premium` (`type`,`publish`,`core`,`premium`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__theme_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__theme_trans` (
  `tmid` smallint(6) NOT NULL,
  `lgid` tinyint(4) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tmid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__theme_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__members_lang` (
  `uid` int(10) unsigned NOT NULL,
  `lgid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `ordering` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__members_lang` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__members_node` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL,
  `username` varchar(40) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `blocked` tinyint(3) NOT NULL DEFAULT '0',
  `activation` varchar(100) NOT NULL,
  `timezone` smallint(6) NOT NULL DEFAULT '999',
  `confirmed` tinyint(3) NOT NULL DEFAULT '0',
  `registerdate` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `lgid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `html` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `registered` tinyint(4) NOT NULL DEFAULT '1',
  `unsub` tinyint(4) NOT NULL DEFAULT '0',
  `login` int(10) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `visibility` tinyint(3) unsigned NOT NULL DEFAULT '231',
  `curid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ip` double unsigned NOT NULL DEFAULT '0',
  `ctyid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(20) NOT NULL,
  `filid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `UK_members_node_email` (`email`),
  UNIQUE KEY `UK_members_node_username` (`username`),
  KEY `IX_members_node_name` (`name`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__members_node` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_queue` (
  `qid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mgid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `senddate` int(10) unsigned NOT NULL DEFAULT '0',
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '100',
  `attempt` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cmpgnid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `actid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `lsid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`qid`),
  KEY `IX_mailing_queue_publish_priority_senddate` (`publish`,`priority`,`senddate`),
  KEY `IX_mailing_queue_uid_cmpgnid_lsid` (`uid`,`cmpgnid`,`lsid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_queue` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_trans` (
  `mgid` int(10) unsigned NOT NULL,
  `lgid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `ctext` longtext NOT NULL,
  `chtml` longtext NOT NULL,
  `smail` char(100) NOT NULL,
  `sname` char(50) NOT NULL,
  `rmail` varchar(100) NOT NULL,
  `rname` varchar(100) NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `bouncemail` varchar(100) NOT NULL,
  `authorid` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `intro` text NOT NULL,
  `smsmessage` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  PRIMARY KEY (`mgid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_trans` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_statistics_user` (
  `mgid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `htmlsent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `textsent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `failed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bounced` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `smssent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hitlinks` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `readdate` int(10) unsigned NOT NULL DEFAULT '0',
  `mailerid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mgid`,`uid`,`created`),
  KEY `IX_mailing_statistics_user_created` (`created`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_statistics_user` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_statistics` (
  `mgid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  `failed` int(10) unsigned NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `htmlsent` int(10) unsigned NOT NULL DEFAULT '0',
  `textsent` int(10) unsigned NOT NULL DEFAULT '0',
  `htmlread` int(10) unsigned NOT NULL DEFAULT '0',
  `textread` int(10) unsigned NOT NULL DEFAULT '0',
  `hitlinks` int(10) unsigned NOT NULL DEFAULT '0',
  `bounces` int(10) unsigned NOT NULL DEFAULT '0',
  `smssent` int(10) unsigned NOT NULL DEFAULT '0',
  `read` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mgid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_statistics` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_type` (
  `mgtypeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `namekey` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `designation` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rolid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `core` tinyint(4) NOT NULL DEFAULT '0',
  `publish` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`mgtypeid`)
) ENGINE=MyISAM   /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_type` ENGINE = INNODB;
CREATE TABLE IF NOT EXISTS `#__mailing_type_trans` (
  `mgtypeid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `auto` tinyint(4) NOT NULL DEFAULT '1',
  `fromlgid` tinyint(3) unsigned DEFAULT '2',
  `lgid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`mgtypeid`,`lgid`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

ALTER TABLE `#__mailing_type_trans` ENGINE = INNODB;