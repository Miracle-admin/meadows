ALTER TABLE #__alpha_userpoints_levelrank ADD `notification` tinyint(1) NOT NULL DEFAULT '0', ADD `emailsubject` varchar(255) NOT NULL DEFAULT '', ADD `emailbody` text NOT NULL DEFAULT '', ADD `emailformat` tinyint(1) NOT NULL DEFAULT '0', ADD `bcc2admin` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE #__alpha_userpoints_raffle_inscriptions ADD `ticket` varchar(30) NOT NULL DEFAULT '', ADD `referredraw` int(11) NOT NULL DEFAULT '0', ADD `inscription` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE #__alpha_userpoints_rules ADD `chain` tinyint(1) NOT NULL DEFAULT '0', ADD `linkup` int(11) NOT NULL DEFAULT '0';

UPDATE #__alpha_userpoints_rules SET `duplicate`='1', `blockcopy`='0', `chain`='1' WHERE `plugin_function`='sysplgaup_bonuspoints';
