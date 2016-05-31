<?php

/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Vm2wishlistsViewList extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $products;
	protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {

        $app = JFactory::getApplication();
		$userid = $app->input->get('userid');
        $user = JFactory::getUser();
		//$doc = JFactory::getDocument();
		//$doc->addScriptDeclaration($jsconstant);

		$ismylist = 0;
		if(!$userid OR $userid == $user->id)
			$ismylist = 1;
		

        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_vm2wishlists');
		
		$this->productsarray		= $this->get('Products');
		// Display the view
		$this->products	= $this->productsarray[0];
		$this->total	= $this->productsarray[1];
 		$this->limit		= $this->productsarray[2];
		$this->limitstart	= $this->productsarray[3];
		
		$this->list_data			= $this->get('listdata' );
		$this->owner_data			= $this->get('ownerdata' );
		$this->price_format			= $this->get('priceformat');
		$privacy = $this->list_data->privacy;
		
		
		if($privacy == '20' && !$ismylist )
		{ // Members only
			if($user->id ==0)
			{
				$app->enqueueMessage( JText::_('COM_VM2WISHLISTS_MUSTBELOGGEDIN') , 'warning' );
				return false;
			}
		}
		elseif($privacy == '30' )
		{ // friends and owner only
			$this->friends = $this->get('friends');
			if ( ( in_array($user->id  ,$this->friends ) OR $ismylist)  && $user->id >0 )
				$privacy_ok = 1;
			else
			{
				$app->enqueueMessage( JText::_('COM_VM2WISHLISTS_MUSTBEFRIEND') , 'warning' );
				return false;
			}
		}
		elseif($privacy == '40')
		{ //  owner only
			if( !$ismylist)
			{
				$app->enqueueMessage( JText::_('COM_VM2WISHLISTS_LISTISPRIVATE') , 'warning' );
				return false;
			}
		}


		
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef('pagination', $pagination );

        if (!empty($this->item)) {
            
        }


        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        

        if ($this->_layout == 'edit') {

            $authorised = $user->authorise('core.create', 'com_vm2wishlists');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_VM2WISHLISTS_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}
