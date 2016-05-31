<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
?>


1. Copyright and disclaimer
---------------------------

This application is released under GNU/GPL License.
All Rights Reserved
Copyright (c) 2008-2015 - Bernard Gilly
ALPHAUSERPOINTS IS DISTRIBUTED "AS IS". NO WARRANTY OF ANY KIND IS EXPRESSED OR IMPLIED. YOU USE IT AT YOUR OWN RISK.
THE AUTHOR WILL NOT BE LIABLE FOR ANY DAMAGES, INCLUDING BUT NOT LIMITED TO DATA LOSS, LOSS OF PROFITS OR ANY OTHER KIND OF LOSS WHILE USING OR MISUSING THIS SCRIPT.


2. Changelog
------------

This is a non-exhaustive (but still near complete) changelog for
AlphaUserPoints, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.


3. Legend
---------

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note


--------------------  2.0.4 stable [17 december 2015]  ---------------------
# fixed avatar issue
+ added back previous user group if the points are below threshold specified in rule change level
# fixed loading Bootstrap script if not loaded by default template
# fixed error sql duplicate fields on update version 
# fixed "return avatarPath" var declaration in helper.php
+ added new module mod_alphauserpoints_points_from_start
^ changed : now, all activities are not deleted of table details, just enabled or disabled after deleting in backend or purged after limit date.
# fixed module Online Users
# fixed image on send html notification 

--------------------  2.0.3 stable [08 september 2015]  ---------------------
- removed button for simple xml install in rules manager
# fixed invite points
# fixed new fields in tables on update by server system
^ Updated documentation developer

--------------------  2.0.2 stable [05 august 2015]  ---------------------
# fixed coupon code generator
# fixed save user function
+ added new option on each rule to display or not this activity on frontend activities listing
# fixed rule coupon code point (set default to only once per user)
+ added installation 8 default modules directly on installation or upgrade component


--------------------  2.0.1 stable [02 july 2015]  ---------------------
# fixed RSS activities
# fixed gid error with Uddeim integration enabled
# fixed limit max option for winner
# fixed send email winner notification
# fixed Recalculate function if not allow negative account


--------------------  2.0.0 stable [12 february 2015]  ---------------------
^ Responsive frontend
- removed internal CSS class for AlphaUserPoints
- Deprecated simple install plugin. Now, only auto-detect method. 
- Deprecated Plaxo service and OpenInviter for import addresses book (projects stopped). Not replaced for the moment.
* security fix
# fixed sending invite for guest
# fixed body message email for notification rule with HTML format
+ added new tag for notification email {Datareference} to show the reason (e.g. Rule custom points)
# fixed hide rules parameter for frontend menu "Rules list"
- removed format date with "/" separator. Not accepted by calendar script for birthday in user profile


--------------------  1.9.13 stable [11 december 2014]  ---------------------
+ added email in export user registration raffle
# fixed rules list (frontend)
+ added features for Click on Menu(s) rule


--------------------  1.9.12 stable [17 november 2014]  ---------------------
# fixed simple rule/plugin installation core file
# fixed send email on raffle


--------------------  1.9.11 stable [24 october 2014]  ---------------------
# fixed simple rule/plugin installation by xml file
# fixed new rule/plugin installation core file
^ changed expire date rule function
# fixed invite view


--------------------  1.9.10 stable [25 september 2014]  ---------------------
# fixed model raffle.php administrator
# fixed import rules from JomSocial


--------------------  1.9.9 stable [15 september 2014]  ---------------------
+ added additional registration for raffle to the referrer user (draw system) on invitation when invited register on this raffle too
# fixed possibility to register to a raffle after expired date
+ added JomSocial notification instead e-mail system of AlphaUserPoints in configuration
+ added duplicate Bonus Points rule
+ added link up rules with bonus points rule and its duplicates
# fixed check rank/medal after attribs custom points on unique user in backend
# fixed save email notification rule with HTML format
# fixed save template invite with HTML format
# fixed router.php
# added e-mail notification on rank or medal


--------------------  1.9.8 stable [12 june 2014]  ---------------------
+ added import Joomla user profile fields if already exists on synchronize first time and new users
# fixed country on profile view

--------------------  1.9.7 stable [19 may 2014]  ---------------------
# fixed date on update rank or medal
# fixed discover new rules by auto-detect in plugins and modules directories

--------------------  1.9.6 stable [27 march 2014]  ---------------------
# fixed error in plugin function (example)
+ added auto-detect rules button for third components, plugins and modules in control panel

--------------------  1.9.5 stable [10 march 2014]  ---------------------
+ added ACL support to extension (permissions in options configuration)
# fixed send notification on change level rule
# Fixed cancel function on view user detail activity

--------------------  1.9.4 stable [14 february 2014]  ---------------------
# fixed bug on duplicate function nicetime() called in backend -> renamed in nicetimeAdm()
# fixed approve action in details activity of a specific user
# fixed fatal error on reset points in backend
- removed Plaxo feature for invite

--------------------  1.9.3 stable [09 december 2013]  ---------------------
# fixed bug on week and format date in BuildKeyReference function in helper.php
# fixed checkReference function in helper.php after change on BuildKeyReference
+ added button "save" and "save & close" on edit rule in backend
+ added button "save" and "save & close" on edit coupon code in backend
+ added button "save" and "save & close" on edit raffle in backend
+ added button "save" and "save & close" on edit user in backend
+ added button "save" and "save & close" on edit template invite in backend
# fixed bug on newpoints() function
# fixed bug in config.xml related format to display points
+ added 2 format of points (config)
- removed internal checking system version (using already system updater of Joomla)
# fixed save user function in backend

--------------------  1.9.2 stable [16 october 2013]  ---------------------
# fixed helper.php

--------------------  1.9.1 stable [15 october 2013]  ---------------------
! Full compatibilty with Joomla 3.2.x
- removed Jsite class deprecated
- remove JRequest class deprecated
# fixed sort activities view (backend)
# fixed sort users (backend)
# fixed all redirect()
# fixed download activity in profile user
# fixed router.php

--------------------  1.9.0 stable [27 september 2013]  ---------------------
# fixed simplexml if already in use
# fixed get AUP avatar function
# fixed static functions in API
# fixed function checkRuleEnabled()
# fixed sort activities view (backend)
# fixed sort users (backend)

--------------------  1.9.0 beta [17 july 2013]  ---------------------
^ rewriting for full compatibility with Joomla 3.0
# fixed Kunena 2 avatar
- removed phpThumb library
- removed Mootools Crop Avatar library

--------------------  1.8.3 stable [19 march 2013]  ---------------------
# fixed Title Profile page browser (formatted points)
# fixed link to register in template invite
# fixed clean cache on enabled/disabled rules
# fixed problem with referrer var in session and cookies
# fixed decimal on raffle subscription
* security fix on download activity
^ updated Italian language frontend and backend


--------------------  1.8.2 stable [17 january 2013]  ---------------------
# fixed notification and send email on not auto-approved points (frontend)
# fixed invite with other registration system
# fixed recaptcha option on invite
^ change redirection page after send email on invite
# fixed warning division by zero on profile page
+ added editor for HTML email notification for each rule
+ added templates system for invite by email (new button on Control Panel of AUP)
+ added new toolbar button in Users view to global reset all points for selected user(s) and its referrees, referral user, medals and ranks.
+ added link to reset number of profile views in edit specific user view
+ added link option to reset Referrer User in edit specific user view
+ added link option to reset Referrals in edit specific user view
# fixed make a raffle
# fixed new rank API
+ added format number 'Round Down' to output
+ added Polish language frontend and backend
+ added Hungarian language frontend and backend


--------------------  1.8.1 stable [30 october 2012]  ----------------------
+ detect if Curl is enabled
+ show formatted points in backend
# fixed UTF-8 format file for fr-FR.com_alphauserpoints.sys.ini
^ new Quick Icon on Joomla Control Panel (replace old module)

--------------------  1.8.0 stable [16 october 2012]  ---------------------
# fixed check all (checkbox) on pending approval (control panel)
# fixed tick image in coupon code view
+ added QRCode for coupon code
+ added QRCode for invite link in user profile (new param show/hide in menu)
+ added categories for coupon code
# fixed coupon code generator with Internet Explorer
+ added categories for levels - ranks - medals
+ added categories for raffle
+ added 'Once per week per user' possibility for attrib/remove points in rule
+ added 'Once per month per user' possibility for attrib/remove points in rule
+ added 'Once per year per user' possibility for attrib/remove points in rule
+ added format number for output points (new param in general config)
+ added format number function for output points in API
# fixed global var $base in synch.php
# fixed var $error in synch.php
# fixed global var $base in recalculate.php
# fixed var $error in recalculate.php
^ changed code to integration JomWall avatar
# fixed email (HTML) notification
+ added new sql update Joomla system
# fixed german language file frontend
# fixed username tag in email notification
+ added new event triggered by AlphaUserPoints and used by other extensions: onSendNotificationAlphaUserPoints()
# fixed params in invite.php
# fixed SQL script install

--------------------  1.7.4 stable [12 Jul 2012]  ---------------------
^ update help file 
# fixed Undefined variable: row in view rules default.php
^ Modification function getCurrentTotalPoints() in helper.php (API)
# fixed detection rules for Kunena 2.0
+ added JomWALL avatar integration
+ added JomWALL profile link integration

--------------------  1.7.3 stable [13 may 2012]  ---------------------
# fixed english language related to referrer and referrals
# fixed auto-detect if migration with Jupdate
# fixed includes DEFINES in recalculate.php
# fixed authorized levels for rules

--------------------  1.7.2 stable [07 march 2012]  ---------------------
+ added update server
+ added rule ID in rule manager list and form
+ added getNextUserRank() function in API
# fixed includes DEFINES in synch.php file
# fixed double quote in backend language
# fixed blank registration link on invite email if guest
+ added possibility activate reCaptcha only for guest user on invite

--------------------  1.7.1 stable [19 january 2012]  ---------------------
# fixed syntax error, unexpected T_IF on model raffle in backend
# fixed toolbar button 'Options'
# fixed division by zero on profile view
# fixed start pane on profile view
+ added new icon in toolbar to showing Rules

--------------------  1.7.0 stable [24 november 2011]  ---------------------
+ added : category field in rule installer 
+ added : display message field in rule installer 
+ added : email notification field in rule installer 
+ added : possibility to upload a standard plugin for Joomla with the rules installer
+ added : Joomla Plugins can be used to handle events triggered by AlphaUserPoints and used by other extensions :
	Available events :
	- onBeforeInsertUserActivityAlphaUserPoints
	- onUpdateAlphaUserPoints
	- onChangeLevelAlphaUserPoints
	- onUnlockMedalAlphaUserPoints
	- onGetNewRankAlphaUserPoints
    - onResetGeneralPointsAlphaUserPoints
    - onBeforeDeleteUserActivityAlphaUserPoints
    - onAfterDeleteUserActivityAlphaUserPoints
    - onBeforeDeleteAllUserActivitiesAlphaUserPoints
    - onAfterDeleteAllUserActivitiesAlphaUserPoints
    - onBeforeMakeRaffleAlphaUserPoints
    - onAfterMakeRaffleAlphaUserPoints
+ added : plugin files for example Joomla plugins
+ added : 2 positions module on the Control Panel (backend)
+ added : 1 position module on the Profile Tabs (frontend profile view)
# fixed exclude user(s) on make Raffle
# fixed checkMaxDailyPoints call in helper.php
# fixed invite form to add Itemid for best SEF compatibility
# fixed pagination medals and detail medals
# fixed avatar from JomSocial
+ added : new function in API -> getListActivity()

--------------------  1.6.4 stable [05 october 2011]  ---------------------
# fixed author ID and article ID (Undefined property: stdClass::$created_by) in plugins content
# fixed button for Plaxo on invite layout
^ update phpThumb library 1.7.11
^ update reCaptcha library 1.11
+ added possibility to show/hide "Referral User Name" column on menu "users list"
+ added rules installer
- removed Joomunity Profile option
# fixed admin menu icon and label on installation
^ help files updated

--------------------  1.6.3 stable [17 july 2011]  ---------------------
# fixed error language backend
# fixed spacer in invite menu params (frontend)
# fixed ALERTNOTAUTH language var (frontend)
# fixed helper.php (function $row->store())

--------------------  1.6.2 stable [14 july 2011]  ---------------------
# fixed language files and sorted by asc
# fixed rank
# fixed API for build key reference (on whenever method to assign points)
# fixed rule reader to author

--------------------  1.6.1 stable [22 april 2011]  ---------------------
# fixed error on edit plugins and module in backend
+ added ExtendedReg system registration on invite
# fixed German language Admin file
# fixed Russian language files
# fixed content plugin raffle to show button and informations
# fixed error with UddeIM integration in profile

--------------------  1.6.0 stable [19 april 2011]  ---------------------
# fixed german language
# fixed language admin menus 
^ integration new raffle button plugin (created by Mike Gusev)
+ added new menu to list the rules (created by Mike Gusev)
^ help page in backend updated
+ added integration K2 avatar
# fixed Undefined variable: excludeusers in models/latestactivity.php

--------------------  1.6.0 RC [17 february 2011]  ---------------------
+ added export coupon code
# fixed gravatar implementation
# fixed negative account referrees users
^ change install file for plugins system, content, and admin module (new manifest.xml)
+ added period (instead fixed date) on expire date rule
+ added migration script for members profiles and activities (on install AlphaUserpoints component after use JUpgrade to migrate a website)

--------------------  1.6.0 beta 2 [11 january 2011]  ---------------------
+ added editor plugin button to insert raffle ID
+ added install new rules
# fixed prevent install from older version
# fixed date in combined activities
# fixed decimal format in statistics profile
# fixed RSS feed
+ added script migration datas from 1.5.13

--------------------  1.6.0 beta [13 November 2010]  ---------------------
! Full compliance Joomla 1.6
! Not compatible with AlphaUserPoints 1.5.x (ACL)
^ Some pre-installed rules removed to limit the loading of plugins unnecessarily if not used (each webmaster can install independently the rules that will actually need)
- removed params Show message on frontend in general configuration
+ added and improved specific user message for each rule
- removed rule for send e-mail notification
+ added and improved specific e-mail notification for each rule
+ added method to attribs points per user and per day for each rule
^ change type field in database for the column points (int(11) to float(10,2))
+ added possibility to allows negative account balance
+ added new fields in rules table
- removed system to upload plugin replaced by new button add in rules manager
^ change system to exclude member for rules, now enabled/disabled in the users manager in backend of the component
+ added show/hide Edit profile tab for frontend profile
^ change system to attrib new level automatically (remove system to send request to admin, then admin approve change level)
- removed profile tab for request for change level
+ added crop avatar feature in user profile 
+ added reporting system in backend
+ added possibility to copy rule (duplicate) if not a rule system

--------------------  1.5.13 stable [11 january 2011]  ---------------------
+ added function to insert raffle in editor for plg_editors-xtd_raffle 1.0
# fixed loading language for com_media
# fixed problem with Uddeim API
# fixed medals system on recalculation
+ added Greek language for frontend

--------------------  1.5.12 stable [10 November 2010]  ---------------------
+ added view for all activities in backend
# fixed recalculate and attribs medal on certain rules in backend
- removed adding unique index on userid during installation/upgrade
+ added new pre installed rule "Thank You" for Kunena forum 1.6.1
+ added new pre installed rule "Delete post" for Kunena forum 1.6.1
^ Migration old names of plugins/rules for Kunena -> Create Topic and Reply Topic
# fixed possibility to download full activity by a guest user from module
+ added russian help in backend
+ added new table #__alpha_userpoints_version (for future upgrade 1.5 to 1.6)

--------------------  1.5.11 stable [09 September 2010]  ---------------------
# fixed router.php
# fixed Uddeim integration
# fixed medals

--------------------  1.5.10 stable [28 August 2010]  ---------------------
# fixed link to profil in account view
# fixed import Jplugin in API
# fixed error on return value of new by reference is deprecated in controller backend line 464
# fixed Donate Points fields dissappear in IE
# fixed birthday on edit/save when format xx/xx/xxxx
# fixed show avatar from AlphaUserPoints even if set to none
# fixed problem in AlphaUserPointsHelper::getAupAvatar() function if called from backend
+ added OSE Membership Control system registration on invite
+ added arabic language for backend component

--------------------  1.5.9 stable [01 July 2010]  ---------------------
# fixed OpenInviter with SEF enabled
# fixed router.php

--------------------  1.5.8 stable [29 June 2010]  ---------------------
# fixed invite with success and IP in keyreference
# fixed Undefined index: gender on save profile
# fixed combine activities
# fixed rule invite to read an article with SEF enabled
# fixed url of article in rule Reader to Author
+ added option to hide referral user column in frontend users list
* security fix Local File Inclusion Vulnerability http://www.secunia.com/advisories/39250/
+ added Arabic language (ar-DZ) for frontend

--------------------  1.5.7 stable [15 June 2010]  ---------------------
* security fix Local File Inclusion Vulnerability http://www.secunia.com/advisories/39250/
# fixed getAupUsersURL() function in API
# fixed Undefined variable: percent in view Statistics
# fixed implementation with phpThumb and JomSocial

--------------------  1.5.6 stable [02 May 2010]  ---------------------
+ added Profile view members authorized for guest visitor (new parameter in backend configuration)
# fixed getAvatar function
+ added phpThumb integration for avatar and rank
# fixed url inserted in datareference (reader to author rule)
# fixed export CSV list members subscriptions to a raffle
+ added new raffle system: link for download as a prize
+ added multiple entries for a raffle (as a system tickets lottery)
+ added button to delete all activities in each user details
+ added export activities for each user (CSV format)
+ added export all activities for all users (CSV format)
+ added index on userid column in #__alpha_userpoints table
+ added show current points of the current user in article content (new content plugin and new content tag)
# fixed prevent subscription if raffle already made
# fixed insert date to new user registration rule
# fixed rank on upgrade or downgrade and recalculate auto on any changes in the rank manager
+ added combine activities: combine the set of all actions in one activity from a specified date (perform database)
* security fix Local File Inclusion Vulnerability http://www.secunia.com/advisories/39250/
+ added router.php for SEF on frontend
+ added show link if enabled to guest users on medals list
# fixed recount Num chars on field "About me" in the profile member if not empty
+ added possibility choose dates format for the birthdate (new param in the menu "Account"
+ added new rule "Donate to author", the reader gives points to the author to read his article with ability to customize the amount of points (it's a content plugin)
^ help files updated
^ change generic_gravatar_grey.gif to generic_gravatar_grey.png
+ added russian language backend

--------------------  1.5.5 stable [21 February 2010]  ---------------------
# fixed code to use class uddeim api
# fixed system cache to check update new version available
# fixed max daily points function
# fixed request to change user level
# fixed export email in backend (missing all emails with [at] instead @)
# fixed invite with success
# fixed insertion data reference on submit a weblink
+ added OpenInviter integration on invite layout
+ added possibility to delete a pending approval activity directly from control panel
+ added on send notification the reason of custom points rule
+ added new param in configuration to allows the integration of all activities, even if the activity is zero point
- removed global $mainframe

--------------------  1.5.4 stable [27 december 2009]  ---------------------
# fixed html code for class in latest activity (missing equal symbol)
# fixed timezone in Uddeim notification
# prevent error if Uddeim component is not installed
^ improve API function getAupAvatar() and add Itemid in url
+ added param class and other profile url in API function getAupAvatar()
+ added Romanian (ro-RO) language for frontend
^ upgrade language files spanish, brazilian and German

--------------------  1.5.3 stable [12 october 2009]  ---------------------
* security fix in ajax function to check username (donate points to other users)
# fixed save phone numbers on user profile
# fixed wrong time in latest activity
# fixed Avatar and profile automatically resets if some changes in backend
# fixed problem with limit daily points with some rules (e.g. coupons code)
# fixed error in plugin sysplgaup_newregistered line 161
# fixed recount referrees on delete user and delete the referral ID for other user
# fixed donate points only works once, now you can donate anymore
# fixed round year for determine age in profile
# fixed function make raffle with coupons code in backend
# fixed division by zero in profil (statistics)
# fixed rule Reader to Author with guest user
^ change process to install and publish plugins (system, users and content) on install
^ change process to uninstall plugins (system, users and content) on uninstall
^ change keyreference for the rule reader to author ( old key = id of article; new key = id of article + ":reader2author" + IP ). Now, IP of reader not show in activity
+ added allows html in description for custom rule in backend
+ added new tab in configuration component for integration third components (backend)
+ added integration Uddeim component
+ added choice to link to other profile provide by third components
+ added pre-installed rules for Kunena
+ added caching system to check current version
+ added Dutch (nl-NL) language in backend


--------------------  1.5.2 stable [03 august 2009]  ---------------------
^ format raffle date format (DATE_FORMAT_LC2) in raffle list (admin)
^ change coupon code point(admin): display list of user awarded per coupon
^ change lenght 'referreid' field in tables for better compliance with user field lenght of Joomla! (150 chars)
# fixed recalculate points function on user when remove his action(s)
# fixed display coupon code in activity
# fixed Notice: Undefined variable: referrerid in currentrequests.php on line 48
# fixed Notice: Use of undefined constant AUP_ACCEPT - assumed 'AUP_ACCEPT' in /views/cpanel/tmpl/default.php on line 397 (admin)
# fixed Notice: Use of undefined constant AUP_REJECT - assumed 'AUP_REJECT' in /views/cpanel/tmpl/default.php on line 398 (admin)
# fixed Notice: line 555 in helper.php on recalculate function
# fixed block negative points on user to user points rule
# fixed error on statistic TOP 10 in user profile tab
# fixed showing data reference for owner coupon
# fixed operation aborted with reCaptcha and IE7
+ added tab to display coupons code points used in user profil
+ added categories for rules
+ added new profile fields
+ added progress profile complete
+ added use avatar from CBE
+ added new rule for upload avatar/photo
+ added new rule for profile complete
+ added new rule for inactive users
+ added new statistics in backend 
+ added Czech language (frontend)


--------------------  1.5.1 stable [07 july 2009]  ---------------------
^ changed notification message : add rule name for action
# fixed division by zero on statistics user fontend
# fixed error in SQL syntax on AlphaUserPointsHelper::checkRankMedal()
# fixed time offset in raffle proceed
# fixed display only one message of congratulation on sending multiple invites with total points earned
# fixed {AUP::CONTENT=XX} on article showing by guest user
# fixed uninstall plugins and admin module before upgrade
# fixed language for upload image in level/rank (backend)
# fixed Medals List layout menuitem type show Users List layout menuitem type in Medals List top
+ added show/hide referrer ID column on users list (frontend)
+ added label 'Rank' column on users list (frontend)
+ added missing var 'AUP_RAFFLE' in frontend languages

--------------------  1.5.0 stable [22 june 2009]  ---------------------
Backend :
---------
# fixed Notice: Undefined offset: 0 on user details if no activity
# improved users synchronization  (support huge site)
# improved users recalculation points (support huge site)
+ added icon on administrator backend's Control Panel page. The icon displays a warning site if there are pending approval points
+ hide automatically the Current requests for change level on control panel of AUP if rules change level disabled
+ added possibility to approve all (or selected) pending approval points directly from control panel of AUP
+ added upload image directly in ranks/medals manager
+ added coupon generator in coupon codes manager (toolbar)
+ added 10 Latest activities on control panel
+ added new rule for specific content based on "onPrepareContent" of content plugin of Joomla
+ added avatar from AlphaUserPoints

Frontend :
----------
^ distinct controllers
# fixed rule birthday
# fixed Notice: Undefined index: referrerid in /plugins/user/sysplgaup_newregistered.php on line 41
# fixed Notice: Undefined variable: changelevel1 in /components/com_alphauserpoints/controller.php on line 176
# fixed bad statistics on frontend for other users
# fixed Exclude Users on statistics
# fixed display RSS activity
# fixed rank/medals
# fixed date format on frontend
# fixed ordering medals on menu medals
# fixed problem script with reCaptcha and IE
# prevent loading reCaptcha libray if already loaded
+ added show/hide tab statistics in profil/account
+ added show/hide total community points
+ added show/hide percentage community points
+ added show/hide top 10 in statistics profil
+ added show/hide links to other profil users
+ added (show/hide) link to profil on frontend users list
+ added (show/hide) avatar on frontend users list
+ added send email to admin on each request to change user level
+ added possibility to set a number item for latest activities in profil or display full activity with pagination
+ added Jomsocial into system registration of invite menu
+ added RSS activity menu on frontend
+ added (show/hide) link to profil on medal users listing
+ added (show/hide) avatar on medal users listing
+ added new menu for showing latest activity
+ added JQuery to checking username validation on form to donate points to another user
+ added upload avatar in user profil/account (avatar from AlphaUserPoints)

--------------------  1.4.0 stable [04 june 2009]  ---------------------
# fixed var language for AUP_CUSTOM in frontend
# fixed return in helper.php
+ added purge automatically old users not reliable to #__users table
+ added ordering on medals/ranks
+ added possibility to attachment at a rule for the medals/ranks
+ added check version update in backend (hide/show in general params)
+ added download link of full activity for current user (profile/account)
+ added show avatar from other profils components (account/profil)
+ added new tab in account/profil to swhowing referrees
+ added settings in profil user
+ added profil can view by other members
+ added new rule "birthday"
^ user profil/account modified
^ statistics user improved in profil/account user
+ added Brazilian language (frontend)

--------------------  1.3.2 stable [22 may 2009]  ---------------------
+ added custom points rules
^ possibility to limit the number of points donation to another user
^ rule donation to another user now is limited once per day
+ added new tab in account/profil to swhowing statistics
^ help file modified
# fixed feedback on API (return true or false)

--------------------  1.3.1 stable [27 april 2009]  -------------------
# fixed Undefined index on referreid
# fixed Undefined variable filterlevelrank (backend)
# fixed problem on Buy Points with Paypal and IE
+ added upload multiple rules in zip

--------------------  1.3.0 stable [04 april 2009]  -------------------
+ invitation compliance with Joomsuite User
+ new parameter to constrain access rule in API
+ rank and medals system for members (both can be combined)
^ user help file
# fixed reCaptcha security if blank

--------------------  1.2.0 stable [05 march 2009]  -------------------
+ added invitation compliance with Joomunity
+ added invitation compliance with VirtueMart
+ added tacking referrals with a cookie/session for new registered
# fixed error on check key reference if used rule not published
# fixed error with PHP4 and function JFactory::getDate()->toFormat (only available with PHP5)
# fixed error after search details user -> no result
# fixed error in backend after manual approve an action -> if Referral points rule enabled, not point assigned to referral user
^ improving the rule "Read article", now possibility to negative points (like paid to read)
^ improving the rules "Read article" and "Reader to author", now an author can't earn point on its own articles
+ added rss feed to global activity (module mod_alphauserpoints_rss_activity must be installed)
+ added Referral invitation link in the form to invite (invite/recommend menu)
+ added export list users registration to a raffle (csv format)
+ added German language
+ added Dutch language (frontend)
+ added Russian language (frontend)

--------------------  1.1.0 stable [24 december 2008]  -------------------
* Prevent multi registered with same ip (Ip tracking on invite with success)
+ added daily login rule
+ added invitation available with CBE (CBE must be modified)
+ added set max daily points (in backend configuration)
+ added Raffle system
^ upgrade version system

--------------------  1.0.0 stable [26 October 2008]  --------------------
# fixed function check rule in helper.php
# fixed navigation in details user (blank page)
+ added rules select list in statistics date to date
+ added new system rule "Read article"
+ added new system rule "Vote article"
+ added new system rule "Click banner"
+ added configuration in backend
+ added possibility public or private coupon code (Using with coupon module v.2)
+ added possibility use native Identifier AUP or use username (in configuration)
- removed _ALPHAUSERPOINTS_WARNING_CONGRATULATION in helper.php (move in configuration component)
- removed prefix identifier in settings plugin User - AlphaUserPoints (move in configuration component)

--------------------  1.0.0 RC 2 [13 October 2008]  --------------------
# fixed messages on e-mail notification
# fixed french language file with no BOM tag

--------------------  1.0.0 RC 1 [01 October 2008]  --------------------
# fixed error on var menuname in view/buypoints
# fixed var userid and title in controller for the task buy points
# fixed table for content title and description article in default view (buy points)
+ added message on frontend for negative points
+ added confirm before reset all points in backend
+ added coupon codes system for points
+ added statistics date to date

--------------------  1.0.0 Release Candidate [24 September 2008]  --------------------
# fixed approved points in backend
# fixed params tag in plugin for new registered
+ added system and new menu for buy points with Paypal (new rule added)
+ added Referral ID in account page (frontend)
^ Editing and adding in the help file

--------------------  0.9.14 beta release [20 September 2008]  --------------------
# fixed message on current user if point assign to other member
# fixed install rule by xml
+ added possibility to show/hide Name column on user list (frontend)

--------------------  0.9.13 beta release [16 September 2008]  --------------------
# fixed conflicts with QContacts component and Captcha image
+ added choice system registration Joomla Core/Community Builder on settings invite menu

--------------------  0.9.12 beta release [15 September 2008]  --------------------
# fixed navigation on rules page
# fixed navigation on users statistics page
# fixed navigation on details user
# fixed error on API newpoints() in help file (english only)
+ added sort on ID in Users Statistics

--------------------  0.9.11 beta release [12 September 2008]  --------------------
! -> First release of this new component
