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


JHTML::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/categories.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/gallery.css');

JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.opacityrollover.js');
JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.galleriffic.js');

class JBusinessDirectoryViewOffer extends JViewLegacy
{

	function __construct()
	{
		parent::__construct();
	}
	
	function display($tpl = null)
	{
		$offerId= JRequest::getVar('offerId');
		$this->assignRef('offerId', $offerId);
		
		$offers = $this->get('Offer');
		$this->assignRef('offer', $offers);
		
		$this->appSettings =  JBusinessUtil::getInstance()->getApplicationSettings();
		
		
		parent::display($tpl);
	}
	
	
}
?>
