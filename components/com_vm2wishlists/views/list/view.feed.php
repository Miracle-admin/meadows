<?php
/*
 * @component VM2tags
 * @copyright Copyright (C) 2008-2012 Adrien Roussel
 * @license : GNU/GPL
 * @Website : http://www.nordmograph.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
/**
 * HTML View class for the HelloWorld Component
 */
 jimport( 'joomla.html.pagination' );
 
class Vm2wishlistsViewlist extends JViewLegacy
{
    
    
    
    public function display($tpl = null)
    {
        $app	= JFactory::getApplication();
        $doc    = JFactory::getDocument();
		$db     = JFactory::getDBO();

        if (!class_exists( 'VmConfig' ))
            require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
        VmConfig::loadConfig();
         $vm_itemid = Vm2wishlistsModellist::getVMItemid($item->virtuemart_category_id);
		
		$this->list_data			= $this->get('listdata' );
		$privacy = $this->list_data->privacy;
		if($privacy!='0')
		{
			return false;	
		}
		
		$cparams 				= JComponentHelper::getParams('com_vm2wishlists');
		$naming 				= $cparams->get('naming', 'username');
		$user_naming 			= ucfirst( $this->list_data->$naming );

		$doc->setTitle( JText::_($this->list_data->list_name) .' '.JText::_('COM_VM2WISHLISTS_BY').' '.$user_naming );
		$doc->setDescription( JText::_($this->list_data->list_description));
		$doc->setLink( JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$this->list_data->id.'&userid='.$app->input->getInt('userid').'&Itemid='.$vm_itemid));
		
        
        $this->productsarray        = $this->get('products');
        // Display the view
        $this->products = $this->productsarray[0];
        $this->total    = $this->productsarray[1];
        $this->limit    = $this->productsarray[2];
        $this->limitstart   = $this->productsarray[3];
        
        $this->price_format         = $this->get('priceformat');
        
        
        //$doc->link = JRoute::_(TagsHelperRoute::getTagRoute($app->input->getInt('id')));
        $app->input->set('limit', $app->get('feed_limit'));
        
        $siteEmail        = $app->get('mailfrom');
        $fromName         = $app->get('fromname');
        $feedEmail        = $app->get('feed_email', 'author');
        $doc->editor = $fromName;
        if ($feedEmail != "none")
        {
            $doc->editorEmail = $siteEmail;
        }   
     //   $cparams        = JComponentHelper::getParams('com_vm2tags');
  
        // Get some data from the model
        $items    = $this->products;
        foreach ($items as $item)
        {
 
            // Strip HTML from feed item title
            $title = $this->escape($item->name);
            $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');
            
            $product_url = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$item->virtuemart_product_id.'&virtuemart_category_id='.$item->virtuemart_category_id.'&Itemid='.$vm_itemid;
            $feeditem_img = $item->file_url_thumb;
                
                
                if ( $feeditem_img =='' && $item->file_url!='')
                {
                    $product_thumb = str_replace('virtuemart/product/','virtuemart/product/resized/',$item->file_url);
            
                    $thum_side_width            =   VmConfig::get( 'img_width' );
                    $thum_side_height           =   VmConfig::get( 'img_height' );
                    $extension_pos = strrpos($product_thumb, "."); // find position of the last dot, so where the extension starts
                    $feeditem_img = substr($product_thumb, 0, $extension_pos) . '_'.$thum_side_width.'x'.$thum_side_height . substr($product_thumb, $extension_pos);
                }
				
				if ( $feeditem_img =='')
					$feeditem_img = 'components/com_virtuemart/assets/images/vmgeneral/'.VmConfig::get( 'no_image_set' );
                
                
            // URL link to tagged item
            // Change to new routing once it is merged
            $link = JRoute::_($product_url);
            // Strip HTML from feed item description text
            $description = $item->core_body;
            $author      = $item->core_created_by_alias ? $item->core_created_by_alias : $item->author;
            $date        = ($item->displayDate ? date('r', strtotime($item->displayDate)) : '');
            // Load individual item creator class
            $feeditem              = new JFeedItem;
            $feeditem->title       = $title;
            $feeditem->link        = $link;
            $feeditem->description = '<img src="'.$feeditem_img.'" /> '.$item->product_s_desc;
            $feeditem->date        = $date;
            $feeditem->category    = $item->category_name;
            $feeditem->author      = '
            ';
            if ($feedEmail == 'site')
            {
                $item->authorEmail = $siteEmail;
            }
            elseif ($feedEmail === 'author')
            {
                $item->authorEmail = $item->author_email;
            }
            // Loads item info into RSS array
            $doc->addItem($feeditem);
        }
    }

    function get_string_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }
}