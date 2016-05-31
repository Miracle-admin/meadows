<?php
/**
 * @package    JHotelReservation
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport( 'joomla.application.component.view' );
/**
 * The HTML  View.
 */
class JBusinessDirectoryViewLanguage extends JViewLegacy {
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$function = $this->getLayout();
		if(method_exists($this,$function)) $tpl = $this->$function();
		$this->setLayout('default');
		$this->setLayout('create');
		$this->addToolbar();
		parent::display($tpl);
	}
	
	function language() {
		$app =JFactory::getApplication();
		$code = JRequest::getString('code');
		if(empty($code)){
			$app->enqueueMessage(JFactory::getApplication()->enqueueMessage(JText::_('LNG_CODE_NOT_SPECIFIED'), 'error'));
			return;
		}
	
		$file = new stdClass();
		$file->name = $code;
		
		$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'.'.JBusinessUtil::getComponentName().'.ini';
		$customPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'-custom.'.JBusinessUtil::getComponentName().'.ini';
		$file->path = $path;
		$file->customPath = $customPath;

		jimport('joomla.filesystem.file');
		$showLatest = true;
		$loadLatest = false;

		if(JFile::exists($path)){
			$file->content = JFile::read($path);
			if(empty($file->content)){
				$app->enqueueMessage('File not found : '.$path);
			}

		} else{
			$loadLatest = true;
			$file->content = JFile::read(JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.'.JBusinessUtil::getComponentName().'.ini');
		}

		if(JFile::exists($customPath)) {
			$file->custom_content = JFile::read($customPath);
			if(empty($file->custom_content)) {
				$app->enqueueMessage('File not found : '.$customPath);
			}
		} else {
			$file->custom_content = " ";
		}
		$this->assignRef('file',$file);
		
		$tpl = "language";
		return $tpl;
	}

	protected function addToolbar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user  = JFactory::getUser();

		JToolbarHelper::title(JText::_('LNG_LANGUAGE' ,true), 'menu.png');

		$layout = JFactory::getApplication()->input->get('layout');

		if ($layout == 'create') {
			JToolbarHelper::save('language.store');
		} else {
			JToolbarHelper::apply('language.apply');
			JToolbarHelper::save('language.save');
		}

		JToolbarHelper::cancel('language.cancel', 'JTOOLBAR_CLOSE');
		
		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_JBUSINESSDIRECTORY_COMPANY_TYPE_EDIT');
	}
}