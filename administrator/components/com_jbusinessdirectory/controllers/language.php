<?php
/**
* @copyright    Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

class JBusinessDirectoryControllerLanguage extends JControllerLegacy {
    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent::__construct();
    }

    function editLanguage() {
        JRequest::setVar('layout', 'language');
        return parent::display();
    }

    function apply() {
        $code = JRequest::getString('code');
        $model = $this->getModel('language');
        $msg = $model->saveLanguage();
        $link = 'index.php?option=com_jbusinessdirectory&tmpl=component&controller=language&view=language&task=language.editLanguage&code='.$code;
        $this->setRedirect($link, $msg);
    }

    function save() {
        $model = $this->getModel('language');
        $msg = $model->saveLanguage();
        $link = 'index.php?option=com_jbusinessdirectory&view=applicationsettings';
        $this->setRedirect($link, $msg);
    }

    function create() {
        $link = 'index.php?option=com_jbusinessdirectory&view=language&layout=create';
        $this->setRedirect($link);
    }

    function store() {
        $code = JRequest::getString('code');
        $content = JRequest::getString('content');
        $model = $this->getModel('language');

        if(empty($code) || empty($content)) {
            if (empty($content)) { $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_CONTENT_CANNOT_BE_BLANK'), 'error'); }
            if (empty($code)) { $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_CODE_NOT_SPECIFIED'), 'error'); }
            $this->setRedirect('index.php?option=com_jbusinessdirectory&view=language&layout=create', $msg);
        } else {
            $msg = $model->createLanguage();
            $link = 'index.php?option=com_jbusinessdirectory&view=applicationsettings';
            $this->setRedirect($link, $msg);
        }
    }

    function send_email() {
        $code = JRequest::getString('code');
        $model = $this->getModel('language');
        $msg = $model->send_email($code);
        $link = 'index.php?option=com_jbusinessdirectory&tmpl=component&controller=language&view=language&view=language&task=language.editLanguage&code='.$code;
        $this->setRedirect($link, $msg);
    }

    function remove() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('language');
        $app =JFactory::getApplication();
        
        // Get items to remove from the request.
        $codes = JRequest::getVar('cid', array(), '', 'array');
        
        if (!is_array($codes) || count($codes) < 1) {
            $msg = $app->enqueueMessage(JText::_('LNG_NO_LANGUAGES_SELECTED'), 'error');
            $link = 'index.php?option=com_jbusinessdirectory&view=applicationsettings';
            $this->setRedirect($link, $msg);
        }
        else{
            foreach($codes as $code){
                $msg = $model->deleteFolder($code);
            }
            $link = 'index.php?option=com_jbusinessdirectory&view=applicationsettings';
            $this->setRedirect($link, $msg);
        }
    }

    function cancel() {
        $msg = JText::_('LNG_OPERATION_CANCELLED',true);
        $this->setRedirect( 'index.php?option=com_jbusinessdirectory&view=applicationsettings', $msg );
    }
}