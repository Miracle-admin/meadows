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
jimport('joomla.application.component.controller');
class Vm2wishlistsController extends JControllerLegacy {
    /**
     * Method to display a view.
     *
     * @param   boolean         $cachable   If true, the view output will be cached
     * @param   array           $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController     This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/vm2wishlists.php';
        $view = JFactory::getApplication()->input->getCmd('view', 'lists');
        JFactory::getApplication()->input->set('view', $view);
        parent::display($cachable, $urlparams);
        return $this;
    }
    
    
    public function savefave()
    {
        $app = JFactory::getApplication();
        $listid                 = $app->input->getInt('listid');
        $virtuemart_product_id  = $app->input->getInt('product_id');
        $user = JFactory::getUser();
        $cparams        = JComponentHelper::getParams('com_vm2wishlists');
        $use_cookies    = $cparams->get('use_cookies',1);
        $cookie_expires = $cparams->get('cookie_expires','365');
        $activitystream = $cparams->get('activitystream',1);
		$email_onlaunch = $cparams->get('email_onlaunch',1);
        $just_added = 0;
        if ($user->id)
        {
            $db = JFactory::getDBO();
            $q = "INSERT INTO #__vm2wishlists_items 
            (virtuemart_product_id , listid , userid , date_added)
            VALUES
            ('".$virtuemart_product_id."','".$listid."','".$user->id."','".date('Y-m-d H:i:s')."')";
            $db->setQuery( $q );
            if (!$db->query()) die($db->stderr(true));  
            $just_added = $listid;
			
			if($email_onlaunch)
			{
				$q = "SELECT count(*) FROM #__vm2wishlists_items WHERE listid='".$listid."' AND userid='".$user->id."' ";
				$db->setQuery($q);
				$started = $db->loadResult();
				if($started=='1')
				{
				// if no starter cookie, we email owner with list summary.
					if(!isset($_COOKIE['vm2wishlists-starter-'.$listid]) )
					{ //we email and set cookie
						// cookie
						setcookie("vm2wishlists-starter-".$listid , '1' , time() + 86400 * $cookie_expires , "/");
						// email
						$mailer 	= JFactory::getMailer();
						$q = "SELECT list_name, list_description , icon_class , privacy 
						FROM  #__vm2wishlists_lists WHERE id = '".$listid."' ";
						$db->setQuery($q);
						$list = $db->loadObject();
						$lang = JFactory::getLanguage();
						$q = "SELECT `id` FROM `#__menu` 
						WHERE `link` = 'index.php?option=com_vm2wishlists&view=list' 
						AND `params` LIKE '{\"item_id\":\"".$listid."\"%' 
						AND `type`='component'  
						AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  AND client_id='0' ";
						$db->setQuery($q);
						$Itemid = $db->loadResult();
						$uri 	 = JFactory::getURI();
						$list_url = $uri->getScheme().'://'.$uri->getHost()
						.JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$user->id.'&Itemid='.$Itemid );
						$subject = JText::_('COM_VM2WISHLISTS_NEWLISTSTARTED_SUBJECT').': '.JText::_($list->list_name);
						$body = '<link rel="stylesheet" href="'.$uri->getScheme().'://'.$uri->getHost().'/components/com_vm2wishlists/assets/css/fontello.css" type="text/css" />';
						$body .= sprintf(JText::_('COM_VM2WISHLISTS_NEWLISTSTARTED_HELLO') , ucwords($user->name)) ;
						$body .= "<br /><br /> ".JText::_('COM_VM2WISHLISTS_NEWLISTSTARTED_INTRO' );
						$body .= '<h1><i class="'.$list->icon_class.'"></i> '. JText::_( $list->list_name).'</h1>';
						$body .= "<b>".JText::_( 'COM_VM2WISHLISTS_LISTDESC').'</b>: '. JText::_( $list->list_description);
						$body .= "<br /><b>".JText::_( 'COM_VM2WISHLISTS_LISTS_PRIVACY').'</b>: '.JText::_('COM_VM2WISHLISTS_PRIVACY_'.$list->privacy);
						$body .= "<br /><b>".JText::_( 'COM_VM2WISHLISTS_LISTURL').'</b>: <a href="'.$list_url.'">'.$list_url.'</a>';
						if($list->privacy =='0')
						{
							$list_rss = $uri->getScheme().'://'.$uri->getHost()
							.JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$user->id.'&format=feed&Itemid='.$Itemid );
							$body .= "<br /><b>".JText::_( 'COM_VM2WISHLISTS_LISTRSS').'</b>: <a href="'.$list_rss.'">'.$list_rss.'</a>';
							$body .= "<br />".JText::_( 'COM_VM2WISHLISTS_SAFE2FORWARD_EVERYBODY');
						}
						$body .= "<br /><br />".JText::_( 'COM_VM2WISHLISTS_MAILOUTRO');
						$mailer->addRecipient( $user->email );
						$mailer->setSubject( $subject );
						$mailer->setBody($body);
						$config = JFactory::getConfig();
						$sender = array( 
							$config->get( 'config.mailfrom' ),
							$config->get( 'config.fromname' )
						);
						$mailer->isHTML(true);
						$mailer->Encoding = 'base64';
						$mailer->setSender( $sender );
						$mailer->addRecipient( $config->get( 'config.mailfrom' ) );
						$sent = $mailer->send();
						
					}
				}
			}
        }
        elseif($use_cookies)
        {
            if( isset($_COOKIE['vm2wishlists-'.$listid] ) )
            { // we open the cookie add the new product and close the cookie                        
                $cookie_content = $_COOKIE['vm2wishlists-'.$listid].','.$virtuemart_product_id;                 
                setcookie ("vm2wishlists-".$listid , $cookie_content , time() + 86400 * $cookie_expires , "/"); //86400 is 24hours * 365 =  15768000 for 1 year     
                $message = JText::_( 'COM_VM2WISHLISTS_ADDED_TOCOOKIE' );
            }
            else
            { //we create the cookie and add the product
                $cookie_content = $virtuemart_product_id;
                setcookie ("vm2wishlists-".$listid , $cookie_content , time() + 86400 * $cookie_expires , "/"); //86400 is 24hours * 365 =  15768000 for 1 year
                $message = sprintf( JText::_( 'COM_VM2WISHLISTS_ADDED_TOCOOKIE_1STTIME' ) , $cookie_expires  );
            }
            $app->enqueueMessage( $message );
            $just_added = $listid;
        }
        else
        {
            $app-enqueueMessage( JText::_( 'COM_VM2WISHLISTS_YOUNEEDTOLOGIN' ) , 'warning');
        }
        
        if ($just_added)
        {
            require_once JPATH_BASE.'/components/com_vm2wishlists/helpers/vm2wishlists.php';
            
            $counter = Vm2wishlistsFrontendHelper::getFavs($listid , $virtuemart_product_id);
            if($counter>0)
                $favs = '<a class="btn btn-default btn-small " disabled>'.$counter.'</a>';
            else
                $favs = '';
            $list_data = Vm2wishlistsFrontendHelper::getListData($listid );

            echo '<a class="btn btn-default btn-small " href="#" onclick="jQuery.vm2w.delfave('.$listid.',' . $virtuemart_product_id . '); return false;" > 
                    <i class="vm2w-icon-remove" ></i> ' . JText::_('COM_VM2WISHLISTS_ADDED_TO') .' '.JText::_($list_data->list_name).
                    '</a>'.$favs;

        } else {
            echo 'Error Saving Favorite';
        }
    }
    
    public function delfave()
    {
        $app = JFactory::getApplication();
        $listid = (int)$app->input->getInt('listid');
        $virtuemart_product_id = (int)$app->input->getInt('product_id');
        $user = JFactory::getUser();
        $cparams        = JComponentHelper::getParams('com_vm2wishlists');
        $use_cookies    = $cparams->get('use_cookies',1);
        $cookie_expires = $cparams->get('cookie_expires','365');
        $just_removed = 0;
        if ($user->id)
        {
            $db = JFactory::getDBO();
            $q = "DELETE FROM #__vm2wishlists_items 
            WHERE virtuemart_product_id='".$virtuemart_product_id."' 
            AND listid='".$listid."' 
            AND userid='".$user->id."' ";
            $db->setQuery( $q );
            if (!$db->query()) die($db->stderr(true));  
            $just_removed = $listid;
        }
        elseif($use_cookies)
        {
            if( $_COOKIE['vm2wishlists-'.$listid] == $virtuemart_product_id )
            { // we remove the cookie                       
                        $cookie_content = '';                   
                                   
            }
            if( strpos( $_COOKIE['vm2wishlists-'.$listid] , ','.$virtuemart_product_id.',' ) ){ 
                        $cookie_content = str_replace(  ','.$virtuemart_product_id.',' , ',' , $_COOKIE['vm2wishlists-'.$listid] );
                        
            }
            elseif( strpos( $_COOKIE['vm2wishlists-'.$listid] , ','.$virtuemart_product_id ) ){ 
                        $cookie_content = str_replace(  ','.$virtuemart_product_id , '' , $_COOKIE['vm2wishlists-'.$listid] );
                        
            }
            elseif( strpos( $_COOKIE['vm2wishlists-'.$listid] , $virtuemart_product_id.',' ) )
            { 
                        $cookie_content = str_replace(  ','.$virtuemart_product_id , '' , $_COOKIE['vm2wishlists-'.$listid] );
                        
            }
                    
            setcookie ("vm2wishlists-".$listid , $cookie_content , time() + 86400 * $cookie_expires , "/"); 
            //86400 is 24hours * 365 =  15768000 for 1 year
            $message = JText::_( 'COM_VM2WISHLISTS_REMOVED_FROMCOOKIE' );
            $app->enqueueMessage( $message );
            $just_removed = $listid;
        }
        else
        {
            $message = JText::_( 'COM_VM2WISHLISTS_YOUNEEDTOLOGIN' );
            $app->enqueueMessage( $message );
        }

        if ($just_removed)
        {
            require_once JPATH_BASE.'/components/com_vm2wishlists/helpers/vm2wishlists.php';
            $counter = Vm2wishlistsFrontendHelper::getFavs($listid , $virtuemart_product_id);
            if($counter>0)
                $favs = '<a class="btn btn-default btn-small " disabled>'.$counter.'</a>';
            else
                $favs = '';
            $list_data = Vm2wishlistsFrontendHelper::getListData($listid );
            echo '<a class="btn btn-primary btn-small " href="#" onclick="jQuery.vm2w.savefave(' . $listid . ','.$virtuemart_product_id.'); return false;">
                        <i class="'.$list_data->icon_class.'"></i> ' . JText::_('COM_VM2WISHLISTS_ADD_TO') .' '.JText::_($list_data->list_name).
                    '</a>'.$favs;
                
        } else {
            echo 'Error Removing Favorite';
        }
        exit;
    }
}