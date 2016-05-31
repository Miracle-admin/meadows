<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewManageCategories extends JViewLegacy
{

	function display($tpl = null)
	{
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();
		
		$layout = JRequest::getVar("layout");
		if(isset($layout)){
			$tpl = $layout;
		}
		
		if(JRequest::getString( 'task') !='edit'){
			JBusinessDirectoryHelper::addSubmenu('categories');
			JToolBarHelper::title(   'J-BusinessDirectory : '.JText::_('LNG_MANAGE_CATEGORIES'), 'generic.png' );
			
			if (($canDo->get('core.edit')))
			{
				JToolBarHelper::editList("managecategories.edit");
			}
			
			if ($canDo->get('core.admin'))
			{
				JToolbarHelper::preferences('com_jbusinessdirectory');
			}
			JToolbarHelper::divider();
			JToolBarHelper::custom('managecategories.importFromCsv', 'upload', 'publish', JText::_('LNG_IMPORT_CSV'), false, false );
			JToolBarHelper::custom('managecategories.showExportCsv', 'download', 'stats.png', JText::_('LNG_EXPORT_CSV'), false, false );
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'managecategories.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );

			$this->items		= $this->get('Datas');
		}
		else
		{
			$this->item = $this->get('Datas');
			JToolBarHelper::title(   'J-BusinessDirectory : '.( JText::_("LNG_ADD_NEW") ).' '.JText::_('LNG_CATEGORIES'), 'generic.png' );
			JRequest::setVar( 'hidemainmenu', 1 );
			JToolbarHelper::cancel('managecategories.cancel', 'JTOOLBAR_CLOSE');
		}

		parent::display($tpl);
	}

	
	function displayAllCategories($categories, $level){
		echo "<div class='category-level$level'>";
		foreach ($categories as $cat){
			echo isset($cat[0]->name)?$cat[0]->name:""; 
			if(isset($cat["subCategories"])) {
				 $this->displayAllCategories($cat["subCategories"], $level+1);
			}
		}
		echo "</div>";
		return;
	}
	
	function displayCategories($categories){
		ob_start();
		if(count($categories)){
			foreach ($categories as $cat){
				if(isset($cat[0]->name)){?>
					<div class='category-box' id='category<?php echo $cat[0]->id?>'
						 onclick ="displaySubcategories(<?php echo $cat[0]->id?>,<?php echo $cat["level"]?>,4)"> 
						
						<div class="category-options">
							<a href="javascript:" onclick="editCategory(<?php echo $cat[0]->id?>);stopClickPropagation(event)"> <?php echo JText::_("LNG_EDIT") ?> </a>
							<a href="javascript:" onclick="deleteCategory(<?php echo $cat[0]->id?>);stopClickPropagation(event)"><?php echo JText::_("LNG_DELETE") ?></a>
							<?php if(intval($cat["level"]) < 4){ ?>
							<a href="javascript:" onclick="addNewCategory(<?php echo $cat[0]->id?>);stopClickPropagation(event)"><?php echo JText::_("LNG_ADD_NEW") ?></a>
							<?php } ?>
							
							<img id="image-<?php echo $cat[0]->id ?>" height="15px"
								src ="<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($cat[0]->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick="changeCategoryState(<?php echo $cat[0]->id?>)"
							/>
						</div>
						<?php echo$cat[0]->name ?> (<?php echo $cat[0]->id?>)
					</div>
					
					<?php  
				}
			}
		}
		$buff = ob_get_contents();
		ob_end_clean();
		return $buff;
	}
}

