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
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/imagesloaded.pkgd.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.isotope.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/isotope.init.js');

class JBusinessDirectoryViewCatalog extends JViewLegacy
{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function display($tpl = null)
	{
		
		$state = $this->get('State');
		$this->params = $state->get("parameters.menu");
		
		$categoryId= JRequest::getVar('categoryId');
		$this->letter = JRequest::getVar('letter');
		
		$this->companies = $this->get('CompaniesByLetter');
	
		$this->letters = $this->get('UsedLetter');
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$pagination = $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
}
?>
