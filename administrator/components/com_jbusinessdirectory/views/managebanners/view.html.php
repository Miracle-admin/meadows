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



class JBusinessDirectoryViewManageBanners extends JViewLegacy
{

	function display($tpl = null)
	{
		//$canDo = JBusinessDirectoryHelper::getActions();
		//$user  = JFactory::getUser();
		
		if(JRequest::getString( 'task') !='edit'	&& 	JRequest::getString( 'task') !='add' ){
			JToolBarHelper::title(   'J-BusinessDirectory : '.JText::_('LNG_MANAGE_BANNERS'), 'generic.png' );
			JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::divider();
			JToolBarHelper::deleteList(JText::_('LNG_ARE_YOU_SURE_YOU_WANT_TO_DELETE'), 'Delete',  JText::_("LNG_DELETE"), 'Delete button', false, false );
			JToolBarHelper::divider();
			JToolBarHelper::custom( 'back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
				
			$this->state		= $this->get('State');
			$this->statuses		= $this->get('Statuses');
			$this->states		= $this->get('States');
			$this->bannerTypes	= $this->get('BannerTypes');
			
			$items		= $this->get('Datas');
			$this->assignRef('items', $items);
			
			$pagination = $this->get('Pagination');
			$this->assignRef('pagination', $pagination);

		}
		else
		{
			$item = $this->get('Data');
			$this->assignRef('item', $item);

			JToolBarHelper::title(   'J-BusinessDirectory : '.( JText::_("LNG_ADD_NEW") ).' '.JText::_('LNG_BANNERS'), 'generic.png' );
			JRequest::setVar( 'hidemainmenu', 1 );
			JToolBarHelper::save(); 
			JToolBarHelper::cancel();
		}

		parent::display($tpl);
	}
	
	function displayBannerTypes($banners, $selectedBannerId){
		ob_start();
		?>
			
			<select id="banners" name="type" class="banner-types-select">
				<option value="">Select Banner Type</option>
				<?php
				foreach( $banners as $banner)
				{
					$selected = false;
					if($banner->id == $selectedBannerId){
						$selected =true;
					}
					?>
					<option <?php echo $selected? 'selected="selected"' : ''?> 	value='<?php echo $banner->id?>'><?php echo $banner->name ?></option>
					<?php
					}
					?>
			</select>
			<?php 
			$buff = ob_get_contents();
			ob_end_clean();
			return $buff;
		}
}

