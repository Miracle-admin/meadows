<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'managecategories.php');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.selectlist.js');
JHTML::_('script', 'https://maps.google.com/maps/api/js?sensor=true&libraries=places');

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/joomlatabs.css');
JBusinessUtil::includeValidation();


require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewManageCompanyOffer extends JViewLegacy
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display($tpl = null)
	{
		$this->companies = $this->get('UserCompanies');
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		$this->states = JBusinessDirectoryHelper::getStatuses();
		
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(OFFER_DESCRIPTION_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		//check if user has access to offer
		$user = JFactory::getUser();
		$found = false;
		foreach($this->companies as $company){
			if($company->userId == $user->id && $this->item->companyId == $company->id){
				
				$found = true;
			}
		}
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->categoryOptions = JBusinessUtil::getCategoriesOptions(true);

		//redirect if the user has no access and the event is not new
		if(!$found &&  $this->item->id !=0){
			$msg = JText::_("LNG_ACCESS_RESTRICTED");
			$app =JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffers', $msg));
		}
		
		parent::display($tpl);
	}
	
	function displayCategoriesOptions($categories, $level, $selectedCategories){
		$delimiter = "";
		for($i=1;$i<$level;$i++){
			$delimiter.="-";
		}
	
		foreach ($categories as $cat){
	
			$selected = false;
			foreach($selectedCategories as $sc){
				if($cat[0]->id == $sc->categoryId)
					$selected= true;
			}
	
			echo isset($cat[0]->name)? "<option value='".$cat[0]->id."'". ($selected?"selected":"").">".$delimiter." ".$cat[0]->name."</option>":"";
			if(isset($cat["subCategories"])) {
				$this->displayCategoriesOptions($cat["subCategories"], $level+1,$selectedCategories);
			}
		}
	
		return;
	}
}
?>
