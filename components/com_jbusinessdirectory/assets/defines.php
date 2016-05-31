<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

if( !defined( 'COMPANY_DESCRIPTIION_MAX_LENGHT') )
	define( 'COMPANY_DESCRIPTIION_MAX_LENGHT',1200);
if( !defined( 'CATEGORY_DESCRIPTIION_MAX_LENGHT') )
	define( 'CATEGORY_DESCRIPTIION_MAX_LENGHT',500);
if( !defined( 'COMPANY_SHORT_DESCRIPTIION_MAX_LENGHT') )
	define( 'COMPANY_SHORT_DESCRIPTIION_MAX_LENGHT',250);
if( !defined( 'OFFER_DESCRIPTIION_MAX_LENGHT') )
	define( 'OFFER_DESCRIPTIION_MAX_LENGHT',1200);
if( !defined( 'COMPANY_SLOGAN_MAX_LENGHT') )
	define( 'COMPANY_SLOGAN_MAX_LENGHT',250);
if( !defined( 'EVENT_DESCRIPTION_MAX_LENGHT') )
	define( 'EVENT_DESCRIPTION_MAX_LENGHT',1200);

if( !defined( 'COMPANY_PICTURES_PATH') )
	define( 'COMPANY_PICTURES_PATH','/companies/');
if( !defined( 'OFFER_PICTURES_PATH') )
	define( 'OFFER_PICTURES_PATH','/offers/');
if( !defined( 'CATEGORY_PICTURES_PATH') )
	define( 'CATEGORY_PICTURES_PATH','/categories/');
if( !defined( 'BANNER_PICTURES_PATH') )
	define( 'BANNER_PICTURES_PATH','/upload/images/banners/');
if( !defined( 'EVENT_PICTURES_PATH') )
	define( 'EVENT_PICTURES_PATH','/events/');
if( !defined( 'COUNTRIES_PICTURES_PATH') )
	define( 'COUNTRIES_PICTURES_PATH','/countries/');

if( !defined( 'PICTURES_PATH') )
	define( 'PICTURES_PATH', 'media/com_jbusinessdirectory/pictures');
if( !defined( 'ATTACHMENT_PATH') )
	define( 'ATTACHMENT_PATH', 'media/com_jbusinessdirectory/attachments');

if( !defined( 'BUSINESS_ATTACHMENTS_PATH') )
	define( 'BUSINESS_ATTACHMENTS_PATH','/companies/');
if( !defined( 'OFFER_ATTACHMENTS_PATH') )
	define( 'OFFER_ATTACHMENTS_PATH','/offers/');
if( !defined( 'EVENT_ATTACHMENTS_PATH') )
	define( 'EVENT_ATTACHMENTS_PATH','/events/');

//types for translation
if( !defined( 'BUSSINESS_ATTACHMENTS'))
	define('BUSSINESS_ATTACHMENTS',1);
if( !defined( 'OFFER_ATTACHMENTS'))
	define('OFFER_ATTACHMENTS',2);
if( !defined( 'EVENTS_ATTACHMENTS'))
	define('EVENTS_ATTACHMENTS',3);

if( !defined( 'MAX_COMPANY_PICTURE_WIDTH') )
	define( 'MAX_COMPANY_PICTURE_WIDTH', 1000);
if( !defined( 'MAX_COMPANY_PICTURE_HEIGHT') )
	define( 'MAX_COMPANY_PICTURE_HEIGHT', 800);

if( !defined( 'MAX_LOGO_WIDTH') )
	define( 'MAX_LOGO_WIDTH', 600);
if( !defined( 'MAX_LOGO_HEIGHT') )
	define( 'MAX_LOGO_HEIGHT', 600);

if( !defined( 'MAX_OFFER_PICTURE_WIDTH') )
	define( 'MAX_OFFER_PICTURE_WIDTH', 600);
if( !defined( 'MAX_OFFER_PICTURE_HEIGHT') )
	define( 'MAX_OFFER_PICTURE_HEIGHT', 600);

if( !defined( 'PICTURE_TYPE_COMPANY') )
	define( 'PICTURE_TYPE_COMPANY', 'picture_type_company');
if( !defined( 'PICTURE_TYPE_OFFER') )
	define( 'PICTURE_TYPE_OFFER', 'picture_type_offer');
if( !defined( 'PICTURE_TYPE_LOGO') )
	define( 'PICTURE_TYPE_LOGO', 'picture_type_logo');
if( !defined( 'PICTURE_TYPE_EVENT') )
	define( 'PICTURE_TYPE_EVENT', 'picture_type_event');

if( !defined( 'ICON_SIZE') )
	define( 'ICON_SIZE', 300);	 

if( !defined( 'EMAIL_FIRST_NAME') )
	define( 'EMAIL_FIRST_NAME','[first_name]');
if( !defined( 'EMAIL_CATEGORY') )
	define( 'EMAIL_CATEGORY','[category]');
if( !defined( 'EMAIL_LAST_NAME') )
	define( 'EMAIL_LAST_NAME','[last_name]');
if( !defined( 'EMAIL_REVIEW_LINK') )
	define( 'EMAIL_REVIEW_LINK','[review_link]');
if( !defined( 'EMAIL_COMPANY_NAME') )
	define( 'EMAIL_COMPANY_NAME','[company_name]');
if( !defined( 'EMAIL_BUSINESS_NAME') )
	define( 'EMAIL_BUSINESS_NAME','[business_name]');
if( !defined( 'EMAIL_BUSINESS_ADDRESS') )
	define( 'EMAIL_BUSINESS_ADDRESS','[business_address]');
if( !defined( 'EMAIL_BUSINESS_WEBSITE') )
	define( 'EMAIL_BUSINESS_WEBSITE','[business_website]');
if( !defined( 'EMAIL_BUSINESS_LOGO') )
	define( 'EMAIL_BUSINESS_LOGO','[business_logo]');
if( !defined( 'EMAIL_BUSINESS_CATEGORY') )
	define( 'EMAIL_BUSINESS_CATEGORY','[business_category]');
if( !defined( 'EMAIL_BUSINESS_CONTACT_PERSON') )
	define( 'EMAIL_BUSINESS_CONTACT_PERSON','[business_contact_person]');
if( !defined( 'EMAIL_BUSINESS_REFFERED_BY') )
	define( 'EMAIL_BUSINESS_REFFERED_BY','[business_referred_by]');
if( !defined( 'EMAIL_OFFER_NAME') )
	define( 'EMAIL_OFFER_NAME','[offer_name]');
if( !defined( 'EMAIL_EVENT_NAME') )
	define( 'EMAIL_EVENT_NAME','[event_name]');
if( !defined( 'EMAIL_EVENT_LINK') )
	define( 'EMAIL_EVENT_LINK','[event_link]');

if( !defined( 'EMAIL_CLAIMED_COMPANY_NAME') )
	define( 'EMAIL_CLAIMED_COMPANY_NAME','[claimed_company_name]'); 
if( !defined( 'EMAIL_CONTACT_CONTENT') )
	define( 'EMAIL_CONTACT_CONTENT','[contact_email_content]');
if( !defined( 'EMAIL_CONTACT_EMAIL') )
	define( 'EMAIL_CONTACT_EMAIL','[contact_email]');
if( !defined( 'EMAIL_ABUSE_DESCRIPTION') )
	define( 'EMAIL_ABUSE_DESCRIPTION','[abuse_description]');
if( !defined( 'EMAIL_REVIEW_NAME') )
	define( 'EMAIL_REVIEW_NAME','[review_name]');


if( !defined( 'EMAIL_CUSTOMER_NAME') )
	define( 'EMAIL_CUSTOMER_NAME','[customer_name]');
if( !defined( 'EMAIL_SITE_ADDRESS') )
	define( 'EMAIL_SITE_ADDRESS','[site_address]');
if( !defined( 'EMAIL_ORDER_ID') )
	define( 'EMAIL_ORDER_ID','[order_id]');
if( !defined( 'EMAIL_PAYMENT_METHOD') )
	define( 'EMAIL_PAYMENT_METHOD','[payment_method]');
if( !defined( 'EMAIL_ORDER_DATE') )
	define( 'EMAIL_ORDER_DATE','[order_date]');
if( !defined( 'EMAIL_TOTAL_PRICE') )
	define( 'EMAIL_TOTAL_PRICE','[total_price]');
if( !defined( 'EMAIL_SUBTOTAL_PRICE') )
	define( 'EMAIL_SUBTOTAL_PRICE','[subtotal_price]');
if( !defined( 'EMAIL_SERVICE_NAME') )
	define( 'EMAIL_SERVICE_NAME','[service_name]');
if( !defined( 'EMAIL_UNIT_PRICE') )
	define( 'EMAIL_UNIT_PRICE','[unit_price]');
if( !defined( 'EMAIL_TAX_AMOUNT') )
	define( 'EMAIL_TAX_AMOUNT','[tax_amount]');
if( !defined( 'EMAIL_BILLING_INFORMATION') )
	define( 'EMAIL_BILLING_INFORMATION','[billing_information]');
if( !defined( 'EMAIL_EXPIRATION_DAYS') )
	define( 'EMAIL_EXPIRATION_DAYS','[exp_days]');
if( !defined( 'EMAIL_PAYMENT_DETAILS') )
	define( 'EMAIL_PAYMENT_DETAILS','[payment_details]');
if( !defined( 'EMAIL_DIRECTORY_WEBSITE') )
	define( 'EMAIL_DIRECTORY_WEBSITE','[directory_website]');
if( !defined( 'EMAIL_COMPANY_SOCIAL_NETWORKS') )
	define( 'EMAIL_COMPANY_SOCIAL_NETWORKS','[company_social_networks]');
if( !defined( 'EMAIL_COMPANY_LOGO') )
	define( 'EMAIL_COMPANY_LOGO','[company_logo]');

if( !defined( 'COMPANY_STATUS_CLAIMED') )
define( 'COMPANY_STATUS_CLAIMED',-1);

if( !defined( 'COMPANY_STATUS_CREATED') )
define( 'COMPANY_STATUS_CREATED',0);

if( !defined( 'COMPANY_STATUS_DISAPPROVED') )
define( 'COMPANY_STATUS_DISAPPROVED',1);

if( !defined( 'COMPANY_STATUS_APPROVED') )
define( 'COMPANY_STATUS_APPROVED',2);

if( !defined( 'COMPANY_STATUS_CLAIMED_APPROVED') )
	define( 'COMPANY_STATUS_CLAIMED_APPROVED',3);

if( !defined( 'EVENT_CREATED') )
	define( 'EVENT_CREATED',0);
if( !defined( 'EVENT_APPROVED') )
	define( 'EVENT_APPROVED',1);

if( !defined( 'OFFER_CREATED') )
	define( 'OFFER_CREATED',0);
if( !defined( 'OFFER_APPROVED') )
	define( 'OFFER_APPROVED',1);

if( !defined( 'HTML_DESCRIPTION') )
	define( 'HTML_DESCRIPTION',"html_description");
if( !defined( 'FEATURED_COMPANIES') )
	define( 'FEATURED_COMPANIES',"featured_companies");
if( !defined( 'SHOW_COMPANY_LOGO') )
	define( 'SHOW_COMPANY_LOGO',"company_logo");
if( !defined( 'WEBSITE_ADDRESS') )
	define( 'WEBSITE_ADDRESS',"website_address");

if( !defined( 'MULTIPLE_CATEGORIES') )
	define( 'MULTIPLE_CATEGORIES',"multiple_categories");
if( !defined( 'IMAGE_UPLOAD') )
	define( 'IMAGE_UPLOAD',"image_upload");
if( !defined( 'VIDEOS') )
	define( 'VIDEOS',"videos");
if( !defined( 'GOOGLE_MAP') )
	define( 'GOOGLE_MAP',"google_map");
if( !defined( 'CONTACT_FORM') )
	define( 'CONTACT_FORM',"contact_form");
if( !defined( 'COMPANY_OFFERS') )
	define( 'COMPANY_OFFERS',"company_offers");
if( !defined( 'SOCIAL_NETWORKS') )
	define( 'SOCIAL_NETWORKS',"social_networks");
if( !defined( 'COMPANY_EVENTS') )
	define( 'COMPANY_EVENTS',"company_events");
if( !defined( 'PHONE') )
	define( 'PHONE',"phone");
if( !defined( 'ATTACHMENTS') )
	define( 'ATTACHMENTS',"attachments");



if(!defined( 'PAYMENT_REDIRECT'))
	define( 'PAYMENT_REDIRECT',1);
if(!defined( 'PAYMENT_SUCCESS'))
	define( 'PAYMENT_SUCCESS',2);
if(!defined( 'PAYMENT_WAITING'))
	define( 'PAYMENT_WAITING',3);
if(!defined( 'PAYMENT_ERROR'))
	define( 'PAYMENT_ERROR',4);
if(!defined( 'PAYMENT_CANCELED'))
	define( 'PAYMENT_CANCELED',5);

if( !defined( 'PAYMENT_STATUS_NOT_PAID') )
	define( 'PAYMENT_STATUS_NOT_PAID',"0");
if( !defined( 'PAYMENT_STATUS_PAID') )
	define( 'PAYMENT_STATUS_PAID',"1");
if( !defined( 'PAYMENT_STATUS_PENDING') )
	define( 'PAYMENT_STATUS_PENDING','2');
if( !defined( 'PAYMENT_STATUS_WAITING') )
	define( 'PAYMENT_STATUS_WAITING','3');
if( !defined( 'PAYMENT_STATUS_FAILURE') )
	define( 'PAYMENT_STATUS_FAILURE','4');
if( !defined( 'PAYMENT_STATUS_CANCELED') )
	define( 'PAYMENT_STATUS_CANCELED','5');


if( !defined( 'UPDATE_TYPE_NEW') )
	define( 'UPDATE_TYPE_NEW',"0");
if( !defined( 'UPDATE_TYPE_UPGRADE') )
	define( 'UPDATE_TYPE_UPGRADE',"1");
if( !defined( 'UPDATE_TYPE_EXTEND') )
	define( 'UPDATE_TYPE_EXTEND',"2");

if( !defined( 'LIST_VIEW') )
	define( 'LIST_VIEW',"list");
if( !defined( 'GRID_VIEW') )
	define( 'GRID_VIEW',"grid");


if( !defined( 'ATTRIBUTE_MANDATORY') )
	define( 'ATTRIBUTE_MANDATORY',1);
if( !defined( 'ATTRIBUTE_OPTIONAL') ) 
	define( 'ATTRIBUTE_OPTIONAL',2);
if( !defined( 'ATTRIBUTE_NOT_SHOW') )
	define( 'ATTRIBUTE_NOT_SHOW',3);

if( !defined( 'SEARCH_BY_DISTNACE') )
	define( 'SEARCH_BY_DISTNACE',0);

if( !defined( 'DS') )
	define( 'DS','/');

//types for translation
if( !defined( 'BUSSINESS_DESCRIPTION_TRANSLATION'))
	define('BUSSINESS_DESCRIPTION_TRANSLATION',1);

if( !defined( 'BUSSINESS_SLOGAN_TRANSLATION'))
	define('BUSSINESS_SLOGAN_TRANSLATION',2);

if( !defined( 'CATEGORY_TRANSLATION'))
	define('CATEGORY_TRANSLATION',3);

if( !defined( 'PACKAGE_TRANSLATION'))
	define('PACKAGE_TRANSLATION',4);

if( !defined( 'OFFER_DESCRIPTION_TRANSLATION'))
	define('OFFER_DESCRIPTION_TRANSLATION',5);

if( !defined( 'EVENT_DESCRIPTION_TRANSLATION'))
	define('EVENT_DESCRIPTION_TRANSLATION',6);

if( !defined( 'COUNTRY_DESCRIPTION_TRANSLATION'))
	define('COUNTRY_DESCRIPTION_TRANSLATION',7);

if( !defined( 'TYPE_TRANSLATION'))
	define('TYPE_TRANSLATION',8);

if( !defined( 'ATTRIBUTE_TRANSLATION'))
	define('ATTRIBUTE_TRANSLATION',9);

if( !defined( 'DOCUMENTATION_URL'))
	define('DOCUMENTATION_URL','http://cmsjunkie.com/docs/jbusinessdirectory/');

if( !defined( 'LANGUAGE_RECEIVING_EMAIL'))
	define('LANGUAGE_RECEIVING_EMAIL','support@cmsjunkie.com');

if( !defined( 'CATEGORY_URL_NAMING'))
	define('CATEGORY_URL_NAMING','category');

if( !defined( 'OFFER_CATEGORY_URL_NAMING'))
	define('OFFER_CATEGORY_URL_NAMING','offer-category');

if( !defined( 'EVENT_CATEGORY_URL_NAMING'))
	define('EVENT_CATEGORY_URL_NAMING','event-category');

if( !defined( 'OFFER_URL_NAMING'))
	define('OFFER_URL_NAMING','offer');

if( !defined( 'EVENT_URL_NAMING'))
	define('EVENT_URL_NAMING','event');


?>