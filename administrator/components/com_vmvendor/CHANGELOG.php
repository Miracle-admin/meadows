<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>


1. Copyright and disclaimer
---------------------------
This application is released under GNU/GPL License.
All Rights Reserved
Copyright (c) 2010-2015 - Adrien ROUSSEL
VMVENDOR IS DISTRIBUTED "AS IS". NO WARRANTY OF ANY KIND IS EXPRESSED OR IMPLIED. YOU USE IT AT YOUR OWN RISK.
THE AUTHOR WILL NOT BE LIABLE FOR ANY DAMAGES, INCLUDING BUT NOT LIMITED TO DATA LOSS, LOSS OF PROFITS OR ANY OTHER KIND OF LOSS WHILE USING OR MISUSING THIS SCRIPT.


2. Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
VMVendor, including beta and release candidate versions.
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


------------------------ 3.3.1 [ April 30th 2015] ---------------------------
+ Who VIsited My Vendor Profile page
+ New option on Vendor profile categories filter.
# Support for Jomsocial version 4 PMS popup windows

------------------------ 3.1 [ November 15th 2014] ---------------------------
+ Standard Shipment management in Vendor Dashboard
# bug fixed when restricting component use to EasySocial profiletypes

//////////////// No new features on 1.3 serie on Joomla2.5


------------------------- 1.3.11 [ February 18th 2014 ]
# fixed category addition form javascript validation
^ maincontroller split in specific controllers
+ Support for EasySocial profile links from Vendor profile


---------------------- 1.3.9 [ January 15th 2014 ]
+ Resample MP3 feature


---------------------- 1.3.8 [ November 29th 2013 ]
+ #vmvendor_vendorratings table added
+ Ajax Vendor rating on vendor profile, only members can rate, vendor's customers only or not. Number of stars, half star support.


---------------------- 1.3.7 [ November 7th 2013 ]
+ Manufacturer select field option in product addition and edition forms
+ option to add admininstration email as BCC when emailing customer from dashboard
+ maxtime option to limit istraxx_download plugin to x number of days after first download



---------------------- 1.3.6 [ November 6th 2013 ]
# question tovendor fixed
# email to customer fixed

---------------------- 1.3.3 [ November 4th 2013 ]
+ option added to disable worldmap in Vendor Dashboard
+ getting rid of mootools tabs replacing by jQuery UI tabs
# Fixed category addition on IE & FF
^ Points2vendor hack replaced by a plugin
^ Bootstrap modal class replaced by Joomla modal for Question to Vendor on Product Details page

 

---------------------- 1.3 [ September 30th 2013 ]
^ Views redisigned with Twitter's Bootsrap framework : http://getbootstrap.com/
^ Product details view settings for Content - Vendor link  plugin now moved from component settings to plugin's setting.
^ Too large images are now added but resized to the max allowed size.
# Masonry now fixed on CB user profile plugin
# Various minor bug fixes

---------------------- 1.2.9  [ August 2nd 2013 ]
+ Added product media ordering for better compatibility with latest VM2tags


---------------------- 1.2.8  [ August 2nd 2013 ]
# Fixed currency_id var not set. Now set with store's main currency


---------------------- 1.2.7  [ june 16th 2013 ]
# Modification code in AUP API for Points atribution's link to product made front end, not backend anymore
^ Activity stram support for Jomsocial 2.8



---------------------- 1.2.6  [ april 9th 2013 ]
+ Module positions added to vendor dashboard: vmv-dashboard-top , vmv-dashboard-tab , vmv-dashboard-bot
+ masonry.js feature added to CB and Jomsocial profile plugins
# Fixed DB insertion of dropdown fields on product edition
# Fixed issue when editing vendor profile in different languages when muliling mode is enabled in VMVendor
# Fixed product showing multiple times in vendor profiles (vmvendor, JS, CB) when added via BE to multiple categories
* Security fix


---------------------- 1.2.4 [ march 21th 2013 ]
+ Added jQuery's Masonry.js for better products list rendering on Vendor profile.
+ Aded Multilanguage feature option to add current Language product into every languages set in VM config
# Undefined variable: userid in components\com_vmvendor\views\vendorprofile\tmpl\default.php on line 341

---------------------- 1.2.3 [ march 1st 2013 ]
# Fixed/removed order Item showing duplicated in dashboard if more than one product category for related product (if more categories are added via BE)
# Fixed/removed order Item showing duplicated in dashboard because of customer's billing address
# Fixed downloadable customfield not correctly assigned to product


---------------------- 1.1.18 [december 8th 2012 ]
# faulty space removed from category insertion query. Category addition now fixed
# On product submission a query to check if vendor ID has not been reset to 0 by VM when as a customer editing a shipping/billing address

---------------------- 1.1.17 [november 19th 2012 ]
# minus sign was missing in the AUP call for points deduction for orders being cancelled by vendor
+ Option to make 1st image field mandatory


---------------------- 1.1.15 [november 15th 2012]
+ formatted price in addition form (only for decimals)
# Vm2tags support now handles accentuated tags
# ACymailing subscribtion query fixed
+ tags now can be as short as 2chars long


----------------------1.1.11 stable [October 31st 2012]  ---------------------
+ Short description made optional
+ Order Item status update notification to customer


----------------------1.1.9 stable [October 9th 2012]  ---------------------
+ Virtuemart Core Custom fields integration for most usual custom fields such as : string, integer, bolean , textarea , editor ...
+ Support for Vendor dedicated categories: Shared categories have the shared attribute to 1 or 1 as vendor ID. Others will be Vendor specific.
# edittax and catsuggest views missing in router.php
# missing Itemid after editing a product
# edition not submitted when only one weight unit set






----------------------1.1.8 stable [October 1st 2012]  ---------------------
+ Since VM2.0.11 - Tax are now added too to products price on Vendor profile.
+ Shared tax use recommended
+ Support for Vendor managed VAT (beta)
+ Product Taxes not concerned by AUP for site commission on sales and fully rewarded to Vendor as points. 
+ Products reviews management in Vendor dashboard
+ Category addition/suggestion
+ Joomla VMVENDORS search plugin for Vendor profiles
+ French lang files for modules and plugins
# user instead of userid in url for product addition and edition email notification to admin
# Product Addition/Edition forms not submitted when only one weight unit set in admin.
# On some setups new products were not displayed in categories untill they were saved once in the backend.

----------------------1.1.7 stable [August29 2012]  ---------------------
+ Product sales announcements added to Vendor's Jomsocial activity stream.
+ Product weight now supported
+ Price optional, can now be disbled in VMVendor if using VM as a catlogue only.
# Edition from free products to commercial relocation of downloadable products from media folder to safe path foler
# Edition commercial to free products relocation of downloadable products from safe path folder to media folder.

----------------------1.1.6 stable [August 16th 2012]  ---------------------
# Price edition fixed

--------------------  1.1.5 stable [July 28th 2012]  ---------------------
# Facebook Like button stopped showing
+ VM2Geolocator custom plugin integration
+ jComments 3rd party component integration on Vendor Profile pages
# mod_vendorpoints2paypal form tag action attribute removed to avoid 3rd party sef url issues
^ Changed language text string on productedition notification to make it different from addition notification
# email notification on product addition fixed
^ email notifications on addtion and edition now as HTML

--------------------  1.1.2 stable [July 11st 2012]  ---------------------
+ language: french front end translation file added 

--------------------  1.1 stable [July 1st 2012]  ---------------------
+ Feature: Update order item status from 'confirmed' to 'shipped' or 'canceled' and notifications to admin  and/or customer(optional)
- plg_vmpayment_points2vendor plugin removed replaced by
+ points2vendor VM core file modification for best compatibility with every payment methods
# duplicate product slug mysql error when adding a product with a a name allready existing in the database.
# Vendor avatar thumb url not added to the Database at first upload.
+ CHANGELOG.php added

--------------------  1.0 initial release [June 21st 2012] ---------------------


