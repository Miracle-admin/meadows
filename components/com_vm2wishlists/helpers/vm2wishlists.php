<?php

/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die;
class Vm2wishlistsFrontendHelper {
	static function getFavs($listid,$virtuemart_product_id)
	{
		$db = JFactory::getDBO();
		$q = "SELECT count(*) FROM `#__vm2wishlists_items` vl  
		WHERE listid='".$listid."' AND virtuemart_product_id='".$virtuemart_product_id."'  ";
		$db->setQuery( $q );
		$favs = $db->loadResult();
		return $favs;
	}
	static function getListData($listid)
	{
		$db = JFactory::getDBO();
		$q = "SELECT list_name, list_description, icon_class , privacy FROM `#__vm2wishlists_lists`  
		WHERE id='".$listid."' ";
		$db->setQuery( $q );
		$list_data = $db->loadObject();
		return $list_data;
	} 
	
	static function getListPrivacy($listid)
	{
		$db = JFactory::getDBO();
		$q = "SELECT privacy FROM `#__vm2wishlists_lists`  
		WHERE id='".$listid."' ";
		$db->setQuery( $q );
		$privacy = $db->loadResult();
		return JText::_( $privacy );
	} 
	static function processActivitystream($listid , $virtuemart_product_id )
	{
		$user = JFactory::getUser();
		$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
		$profileman 	= $cparams->get('profileman','es');
		
		if($profileman=='es')
		{
			require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php' ;
			$stream     = Foundry::stream();
			$template   = $stream->getTemplate();
			$actorType = 'user'; //string
			$template->setActor(  $user->id ,  $actorType );
			
			
			// @required
			// Set the context type of this stream item.
			$contextId = $virtuemart_product_id;
			$template->setContext(  $contextId ,  'product' );
			
			
			// @required
			// Set the verb / action for this stream item.
			// Example verbs: add, edit , create , update , delete.
			$verb = 'add';
			$template->setVerb(  $verb );

			$type = 'full'; // or mini
			$template->setType(  $type );
			
			
			// @optional
			// If you do not plan to create an application to manipulate the stream, you may specify the title here.
			// Example: "Jennifer created a new textbook."
			$title = " Added to list"; //string
			$template->setTitle(  $title );
			
			
			// @optional
			// If you do not plan to create an application to manipulate the stream, you may specify the contents of the stream here.
			// Example: "Some descriptions of the book."
			$content=' test';
			$template->setContent(  $content );
			
			
			// @optional
			// You may also specify that stream items should be available site wide to everyone like a broadcast message.
			// Example: "Hello everyone, this is a custom message."
			// $template->setSideWide( boolean $state );
			
			
			// @optional
			// You may also bind a location to the stream item if you utilize the location library.
			// $template->setLocation( SocialTableLocation $location );
			
			
			// @optional
			// You may also tag other users in the stream by supplying the user id's.
			// Example: array( 62,63,64);
			$template->setWith( 997 );
			
		
				
		}
		elseif($profileman=='js')
		{
			
		}
	} 
}