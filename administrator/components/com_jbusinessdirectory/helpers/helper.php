<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * BusinessDirectory component helper.
 *
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 */
class JBusinessDirectoryHelper
{
	/**
	 * Defines the valid request variables for the reverse lookup.
	 */
	protected static $_filter = array('option', 'view', 'layout');

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string	The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_SETTINGS'),
			'index.php?option=com_jbusinessdirectory&view=applicationsettings',
			$vName == 'applicationsettings' 
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_CATEGORIES'),
				'index.php?option=com_jbusinessdirectory&view=categories',
				$vName == 'categories'
		);
				
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANIES'),
				'index.php?option=com_jbusinessdirectory&view=companies',
				$vName == 'companies'
		);
			
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_ATTRIBUTES'),
				'index.php?option=com_jbusinessdirectory&view=attributes',
				$vName == 'attributes'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COMPANY_TYPES'),
				'index.php?option=com_jbusinessdirectory&view=companytypes',
				$vName == 'companytypes'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_OFFERS'),
				'index.php?option=com_jbusinessdirectory&view=offers',
				$vName == 'offers'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EVENTS'),
				'index.php?option=com_jbusinessdirectory&view=events',
				$vName == 'events'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EVENT_TYPES'),
				'index.php?option=com_jbusinessdirectory&view=eventtypes',
				$vName == 'eventtypes'
		);
	
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_PACKAGES'),
				'index.php?option=com_jbusinessdirectory&view=packages',
				$vName == 'packages'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_DISCOUNTS'),
				'index.php?option=com_jbusinessdirectory&view=discounts',
				$vName == 'discounts'
		);
				
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_ORDERS'),
				'index.php?option=com_jbusinessdirectory&view=orders',
				$vName == 'orders'
		);

		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_PAYMENT_PROCESSORS'),
				'index.php?option=com_jbusinessdirectory&view=paymentprocessors',
				$vName == 'paymentprocessors'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_COUNTRIES'),
				'index.php?option=com_jbusinessdirectory&view=countries',
				$vName == 'countries'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_RATING'),
			'index.php?option=com_jbusinessdirectory&view=ratings',
			$vName == 'ratings'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW'),
			'index.php?option=com_jbusinessdirectory&view=reviews',
			$vName == 'reviews'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_CRITERIAS'),
				'index.php?option=com_jbusinessdirectory&view=reviewcriterias',
				$vName == 'reviewcriterias'
		);

		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_RESPONSE'),
				'index.php?option=com_jbusinessdirectory&view=reviewresponses',
				$vName == 'reviewresponses'
		);

		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REVIEW_ABUSE'),
				'index.php?option=com_jbusinessdirectory&view=reviewabuses',
				$vName == 'reviewabuses'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_REPORTS'),
				'index.php?option=com_jbusinessdirectory&view=reports',
				$vName == 'reports'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_EMAILS_TEMPLATES'),
			'index.php?option=com_jbusinessdirectory&view=emailtemplates',
			$vName == 'emailtemplates'
		);
		
		JSubMenuHelper::addEntry(
				JText::_('COM_JBUSINESS_DIRECTORY_SUBMENU_UPDATE'),
				'index.php?option=com_jbusinessdirectory&view=updates',
				$vName == 'updates'
		);
		
	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 * @param   integer  The article ID.
	 *
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions()
	{
		// Reverted a change for version 2.5.6
		$user	= JFactory::getUser();
		$result	= new JObject;
	
		$assetName = 'com_jbusinessdirectory';
	
		$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit',  'core.edit.state', 'core.delete',
				'directory.access.directory.management','directory.access.listings','directory.access.categories','directory.access.events','directory.access.offers',
				'directory.access.payment.config','directory.access.packages','directory.access.countries','directory.access.reviews','directory.access.emails','directory.access.reports',
				'directory.access.cities','directory.access.controlpanel','directory.access.bookmarks','directory.access.discounts'
		);
	
		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}
	
		return $result;
	}

	public static function getPackageFeatures(){
		$feaures = array(
						FEATURED_COMPANIES=>JText::_("LNG_FEATURED_COMPANY"),
						HTML_DESCRIPTION=>JText::_("LNG_HTML_DESCRIPTION"),
						SHOW_COMPANY_LOGO=>JText::_("LNG_SHOW_COMPANY_LOGO"),
						WEBSITE_ADDRESS=>JText::_("LNG_WEBSITE_ADDRESS"),
						IMAGE_UPLOAD=>JText::_("LNG_IMAGE_UPLOAD"),
						VIDEOS=>JText::_("LNG_VIDEOS"),
						GOOGLE_MAP=>JText::_("LNG_GOOGLE_MAP"),
						CONTACT_FORM=>JText::_("LNG_CONTACT_FORM"),
						COMPANY_OFFERS=>JText::_("LNG_COMPANY_OFFERS"),
						COMPANY_EVENTS=>JText::_("LNG_COMPANY_EVENTS"),
						SOCIAL_NETWORKS=>JText::_("LNG_SOCIAL_NETWORK"),
						PHONE=>JText::_("LNG_PHONE"),
						ATTACHMENTS=>JText::_("LNG_ATTACHMENTS"));
		return $feaures;
	}
	
	public static function getCompanyParams(){
		
		$params = array("id"=>"LNG_ID",
				"name"=>"LNG_NAME",
				"comercialName"=>"LNG_COMPANY_COMERCIAL_NAME",
				"short_description"=>"LNG_SHORT_DESCRIPTION",
				"description"=>"LNG_DESCRIPTION",
				"slogan"=>"LNG_COMPANY_SLOGAN",
				"street_number"=>"LNG_STREET_NUMBER",
				"address"=>"LNG_ADDRESS",
				"city"=>"LNG_CITY",
				"county"=>"LNG_COUNTY",
				"countryName"=>"LNG_COUNTRY",
				"website"=>"LNG_WEBSITE",
				"keywords"=>"LNG_KEYWORDS",
				"registrationCode"=>"LNG_REGISTRATION_CODE",
				"phone"=>"LNG_PHONE",
				"email"=>"LNG_EMAIL",
				"fax"=>"LNG_FAX",
				"type"=>"LNG_COMPANY_TYPE",
				"creationDate"=>"LNG_CREATED",
				"latitude"=>"LNG_LATITUDE",
				"longitude"=>"LNG_LONGITUDE",
				"activity_radius"=>"LNG_ACTIVITY_RADIUS",
				"userName"=>"LNG_USER",
				"averageRating"=>"LNG_RATING",
				"taxCode"=>"LNG_TAX_CODE",
				"package"=>"LNG_PACKAGE",
				"facebook"=>"LNG_FACEBOOK",
				"twitter"=>"LNG_TWITTER",
				"googlep"=>"LNG_GOOGLE_PLUS",
				"postalCode"=>"LNG_POSTAL_CODE",
				"mobile"=>"LNG_MOBILE",
				"viewCount"=>"LNG_VIEW_NUMBER",
				"contactCount"=>"LNG_CONTACT_NUMBER",
				"websiteCount"=>"LNG_WEBSITE_CLICKS",
				"contact_name"=>"LNG_CONTACT_NAME",
				"contact_email"=>"LNG_CONTACT_EMAIL",
				"contact_phone"=>"LNG_CONTACT_PHONE",
				"contact_fax"=>"LNG_CONTACT_FAX"
				);
		return $params;
	}
	
	public static function getPackageCustomFeatures(){
		$db = JFactory::getDbo();
		$query = "select * from #__jbusinessdirectory_attributes";
		$db->setQuery($query);
		$feaures = $db->loadObjectList();
		return $feaures;
	}
	
	public static function getPackageOptions($price=0){
		
		$db = JFactory::getDbo();
		$query = "select * from #__jbusinessdirectory_packages p where p.status =1 and price>=$price order by ordering asc";
		$db->setQuery($query);
		$packages = $db->loadObjectList();
		$result=array();
		foreach($packages as $package){
			$result[$package->id]=$package->name;
		}
		return $result;
	}
	
	public static function getCompanyStates(){
		
	}
	
	public static function getCompanyStatus(){
		
	}
	
	public static function getOrderStates(){
			$states = array();
			$state = new stdClass();
			$state->value = 0;
			$state->text = JTEXT::_("LNG_NOT_PAID");
			$states[] = $state;
			$state = new stdClass();
			$state->value = 1;
			$state->text = JTEXT::_("LNG_PAID");
			$states[] = $state;
			
			return $states;
	}
	
	public static function getStatuses(){
		$states = array();
		$state = new stdClass();
		$state->value = 0;
		$state->text = JTEXT::_("LNG_INACTIVE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 1;
		$state->text = JTEXT::_("LNG_ACTIVE");
		$states[] = $state;
	
		return $states;
	}
	
	public static function getAttributeConfiguration(){
		$states = array();
		$state = new stdClass();
		$state->value = ATTRIBUTE_MANDATORY;
		$state->text = JTEXT::_("LNG_MANDATORY");
		$states[] = $state;
		$state = new stdClass();
		$state->value = ATTRIBUTE_OPTIONAL;
		$state->text = JTEXT::_("LNG_OPTIONAL");
		$states[] = $state;
		$state = new stdClass();
		$state->value = ATTRIBUTE_NOT_SHOW;
		$state->text = JTEXT::_("LNG_NOT_SHOW");
		$states[] = $state;
	
		return $states;
	}
	
	public static function getModes(){
		$modes = array();
		$state = new stdClass();
		$state->value = "test";
		$state->text = JTEXT::_("LNG_TEST");
		$modes[] = $state;
		$state = new stdClass();
		$state->value = "live";
		$state->text = JTEXT::_("LNG_LIVE");
		$modes[] = $state;
		
		return $modes;
	}
}


