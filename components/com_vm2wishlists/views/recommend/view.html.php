<?php
/*
 * @component Vm2wishlists
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
class Vm2wishlistsViewRecommend extends JViewLegacy
{
    protected $form;
    // Overwriting JViewLegacy display method
    function display($tpl = null) 
    {
        $app            = JFactory::getApplication();
        $user           = JFactory::getUser();
        if(!$user->id)
        {
            $app->enqueueMessage(JText::_('COM_VM2WISHLISTS_RECOMMEND_DISCONNECTED') );
            return false;
        }
        jimport( 'joomla.form.form' );
        JHtml::_('behavior.keepalive'); 
        JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
        $model  = $this->getModel('recommend', 'Vm2wishlistsModel');
        $this->form = $this->get('Form');   
        $this->list_data            = $this->get('listdata' );
        // Display the view
        parent::display($tpl);
    }
}