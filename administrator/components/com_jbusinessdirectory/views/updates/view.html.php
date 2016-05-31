<?php
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JBusinessDirectoryViewUpdates extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->appSettings = JBusinessUtil::getApplicationSettings();
		$this->items = $this->get('Items');
		$this->currentVersion = $this->get('CurrentVersion');
		$this->state = $this->get('State');
		$this->expirationDate= $this->get('ExpirationDate');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$language = JFactory::getLanguage();
		$language_tag 	= $language->getTag();
		
		$language->load(
				'com_installer' ,
				dirname(JPATH_ADMINISTRATOR.DS.'language') ,
				$language_tag,
				true
		);
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar(){
		JToolBarHelper::title('J-BusinessDirectory '.JText::_('LNG_UPDATES',true), 'generic.png');
		JToolbarHelper::custom('updates.saveOrder', 'save', 'save', 'LNG_SAVE_ORDER', true, false);
		JToolbarHelper::custom('updates.update', 'upload', 'upload', 'COM_INSTALLER_TOOLBAR_UPDATE', true, false);
		JToolbarHelper::custom('updates.find', 'refresh', 'refresh', 'COM_INSTALLER_TOOLBAR_FIND_UPDATES', false, false);
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'updates.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#updates' );
	
	}
}