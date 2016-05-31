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
 * The HTML  View.
 */

JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/manage.companies.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.upload.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/common.js');

JBusinessUtil::includeValidation();

JHTML::_('script', 'https://maps.googleapis.com/maps/api/js?sensor=true&libraries=places');
 
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/jquery.timepicker.css');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.timepicker.min.js');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'managecategories.php');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewCompany extends JViewLegacy
{
	protected $item;
	protected $state;
	protected $packages;
	protected $claimDetails;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
		$this->item	 = $this->get('Item');
		$this->state = $this->get('State');
		
		$this->translations = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_DESCRIPTION_TRANSLATION,$this->item->id);
		$this->translationsSlogan = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_SLOGAN_TRANSLATION,$this->item->id);
		$this->languages = JBusinessUtil::getLanguages();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->categoryOptions = JBusinessUtil::getCategoriesOptions(true);
		$this->claimDetails = $this->get('ClaimDetails');
		
		//get all upgrade packages - cannot downgrade
		$price = 0;
		if(!empty($this->item->lastActivePackage) && $this->item->lastActivePackage->expired == false){
			$price = $this->item->lastActivePackage->price;
		}
		$this->packageOptions = JBusinessDirectoryHelper::getPackageOptions($price);
	
		$this->location = $this->get('Location');
	
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->addToolbar($this->claimDetails);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar($claimDetails)
	{	
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		JToolbarHelper::title(JText::_($isNew ? 'COM_JBUSINESSDIRECTORY_NEW_COMPANY' : 'COM_JBUSINESSDIRECTORY_EDIT_COMPANY'), 'menu.png');

		if ($canDo->get('core.edit')){
			JToolbarHelper::apply('company.apply');	
			JToolbarHelper::save('company.save');
		}
		
		if($this->item->id > 0 && !(isset($claimDetails) && $claimDetails->status == 0)){
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'company.aprove', 'publish.png', 'publish.png', JText::_("LNG_APPROVE"), false, false );
			JToolBarHelper::custom( 'company.disaprove', 'unpublish.png', 'unpublish.png', JText::_("LNG_DISAPPROVE"), false, false );
		}
			
		if(isset($claimDetails) && $claimDetails->status == 0){
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'company.aproveClaim', 'publish.png', 'publish.png', JText::_("LNG_APPROVE_CLAIM"), false, false );
			JToolBarHelper::custom( 'company.disaproveClaim', 'unpublish.png', 'unpublish.png', JText::_("LNG_DISAPPROVE_CLAIM"), false, false );
			JToolBarHelper::divider();
		}
	
		JToolbarHelper::cancel('company.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#manage-companies');
	}
	
	function displayCompanyCategories($categories, $level){
		ob_start();
		?>
		
		<select class="category-box" id="category<?php echo $level ?>"
				onchange ="displaySubcategories('category<?php echo $level ?>',<?php echo $level ?>,4)">
			<option value=""></option>	
		<?php 
			foreach ($categories as $cat){
				if(isset($cat[0]->name) && isset($cat["subCategories"]) && count($cat["subCategories"])>0){?>
					<option value="<?php echo $cat[0]->id?>"><?php echo $cat[0]->name?></option>
						
					<?php  
					}
				}
			?>
			</select>
			<?php 
			$buff = ob_get_contents();
			ob_end_clean();
			return $buff;
	}
	
	function displayCompanyCategoriesOptions($categories){
		ob_start();
		foreach ($categories as $cat){
			if(isset($cat[0]->name)){?>
				<option value="<?php echo $cat[0]->id?>"><?php echo $cat[0]->name?></option>
				<?php  
				}
			}

		$buff = ob_get_contents();
		ob_end_clean();
		return $buff;
	}
}
