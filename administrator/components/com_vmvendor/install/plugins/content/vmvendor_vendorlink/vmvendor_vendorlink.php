<?php
/**
 * @version 2.5.0
 * @package VMVendor Vendor Link
 * @author   Nordmograph
 * @link http://www.nordmograph.com
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright Copyright (C) 2012 nordmograph.com. All rights reserved.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class plgContentVmvendor_Vendorlink extends JPlugin {
	public function __construct(& $subject, $config)	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	 public function getVendorRating($vendor_user_id) 
	{
		$db = JFactory::getDBO();
		$vendor_rating = array();
		$q = "SELECT percent FROM #__vmvendor_vendorratings WHERE vendor_user_id = '".$vendor_user_id."' AND percent >0 ";
		$db->setQuery($q);
		$votes = $db->loadObjectList();
		$votes_count = count($votes);
		$total_pct = 0;
		if(count($votes))
		{
			foreach($votes as $vote)
			{
				$total_pct = $total_pct + $vote->percent;
			}
			if($votes_count)
				$average_percent = $total_pct / $votes_count;
			$vendor_rating['count'] = $votes_count;
			$vendor_rating['percent'] = $average_percent;
		}
		else
		{
			$vendor_rating['count'] = 0;
			$vendor_rating['percent'] = 0;
		}
		return $vendor_rating;
	}
	
	function getAddproductItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=addproduct' AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND `published`='1' ";
		$db->setQuery($q);
		return $addproduct_itemid = $db->loadResult();
	}
	
	public function onContentPrepare($context, $row, $params, $page = 0)
	{	
		if($context == 'com_virtuemart.productdetails')	
		{	
			$user 				= JFactory::getUser();
			$juri 				= JURI::base();
			$app 				= JFactory::getApplication();
			$db 				= JFactory::getDBO();
			$doc				= JFactory::getDocument();
			$doc->addStyleSheet($juri.'plugins/content/vmvendor_vendorlink/css/style.css');	
			echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
	
					
			$cparams 					= JComponentHelper::getParams('com_vmvendor');
			$profileman 				= $cparams->get('profileman', '0');
			$profileitemid 				= $cparams->get('profileitemid');
			$naming		 				= $cparams->get('naming', 'username');	
			$rating_stars				= $cparams->get('rating_stars', '5');
			$forbidcatids				= $cparams->get('forbidcatids');
			$onlycatids					= $cparams->get('onlycatids');
			
			$banned_cats = explode(',',$forbidcatids);
			$prefered_cats = explode(',',$onlycatids);
			
			
			$virtuemart_category_id =  $row->virtuemart_category_id;
			
			if($onlycatids!='' && ( !in_array($virtuemart_category_id, $prefered_cats) &&  $virtuemart_category_id!= $onlycatids))
				return false;
				
			if($forbidcatids!='' && ( in_array($virtuemart_category_id, $banned_cats)  OR  $virtuemart_category_id ==  $forbidcatids  ) )
				return false;

			$linkto		 				= $this->params->get('linkto',1);
			$questionform		 		= $this->params->get('questionform',1);
			$show_deletebutton			= $this->params->get('show_deletebutton',1);
			$show_rating				= $this->params->get('show_rating',1);
		

			$virtuemart_product_id 		= $app->input->get('virtuemart_product_id');
						
			$html ='';
			$q = "SELECT u.`username`, u.`name`, u.`id` 
				FROM `#__users` u 
					LEFT JOIN `#__virtuemart_vmusers` vv ON vv.`virtuemart_user_id` = u.`id` 
					LEFT JOIN `#__virtuemart_products` vp ON vp.`virtuemart_vendor_id` = vv.`virtuemart_vendor_id` 
					 WHERE vv.`user_is_vendor`='1'  
					AND vp.`virtuemart_product_id` = '".$virtuemart_product_id."'";
			$db->setQuery($q);
			$resultrow = $db->loadRow();
			$vendor_username = $resultrow[0];
			$vendor_name= $resultrow[1];
			$vendor_userid = $resultrow[2];
			
			
			
			if($profileman=='js')
			{
				require_once JPATH_BASE .  '/components/com_community/libraries/core.php';
				//require_once JPATH_ROOT .  '/components/com_community/libraries/window.php' ;
				CWindow::load();
				
				/*$config		= CFactory::getConfig();
				$js	= '/assets/script-1.2';
				$js	.= ( $config->get('usepackedjavascript') == 1 ) ? '.pack.js' : '.js';
				CAssets::attach($js, 'js');*/
			}
			if($profileman=='es')
			{
				$doc->addStyleSheet( $juri.'components/com_easysocial/themes/wireframe/styles/more.min.css' );	// popwindows
				$file 	= JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
				jimport( 'joomla.filesystem.file' );
				if( !JFile::exists( $file ) )
					return;
				require_once $file ;
				Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
				$modules 	= Foundry::modules( 'mod_easysocial_toolbar' );
				$modules->loadComponentScripts();
				$config 	= Foundry::config();
				$es_followers = $config->get( 'followers.enabled' );
				$es_user = Foundry::user($vendor_userid);
			}
					
					$lang = JFactory::getLanguage();
			$q = "SELECT `id` FROM `#__menu` WHERE `link` ='index.php?option=com_vmvendor&view=vendorprofile' AND ( language ='".$lang->getTag()."' OR language='*') AND published='1' AND access='1' ";
			$db->setQuery($q);
			$vmvendoritemid = $db->loadResult();
				
			if($linkto==1)
			{	
				$profile_url = 	'index.php?option=com_vmvendor&view=vendorprofile&userid='.$vendor_userid.'&Itemid='.$vmvendoritemid;
				$profile_url = JRoute::_($profile_url);
			}
			elseif($linkto==2){
				switch($profileman){
					case '0':
						$profile_url = '';
					break;
					case 'cb':
						$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$vendor_userid.'&Itemid='.$profileitemid);
					break;
					case 'js':
						$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$vendor_userid.'&Itemid='.$profileitemid);
					break;
					case 'es':
						$profile_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$vendor_userid.':'.JFilterOutput::stringURLSafe($vendor_username).'&Itemid='.$profileitemid);
					break;
				}
			}
		

			if($vendor_userid && $questionform)
				$html .= '<div id="vmvendor_link" class="well well-sm">';
				
				if	($vendor_userid == $user->id)
				{
					$html .='<div>';
					if($show_deletebutton)
					{
						$html .='<div class="btn-group">';
						$html .='<form id="delete_product" action="'.JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&Itemid='.$vmvendoritemid).'" method="POST" onsubmit="return confirm(\''.JText::_('PLG_VMVENDOR_VENDORLINK_ARE YOUSURE').'\');">';
						
					}
					
					$html .= '<a href="'.JRoute::_('index.php?option=com_vmvendor&view=editproduct&productid='.$virtuemart_product_id.'&Itemid='.$this->getAddproductItemid() ).'" class="btn btn-small btn-mini btn-default hasTooltip" title="'.JText::_('PLG_VMVENDOR_VENDORLINK_EDITYOURPRODUCT').'"><span class="icon-edit"></span></a> ';
					
					if($show_deletebutton)
					{
						$q ="SELECT product_price FROM #__virtuemart_product_prices WHERE virtuemart_product_id='".$virtuemart_product_id."' " ;
						$db->setQuery($q);
						$virtuemart_product_price = $db->loadResult();
						$html .= '<input type="hidden" name="controller" value="vendorprofile">
						<input type="hidden" name="task" value="deleteproduct">
						<input type="hidden" name="price" value="'.$virtuemart_product_price.'">
						<input type="hidden" name="delete_productid" value="'.$virtuemart_product_id.'">
						<input type="hidden" name="userid" value="'.$user->id.'">
					<button class="btn btn-small btn-mini btn-default hasTooltip" title="'.JText::_('PLG_VMVENDOR_VENDORLINK_DELETEYOURPRODUCT').'"><span class="icon-trash"></span></button></form></div>';

					}
					$html .='</div>';
				}
			
			if($vendor_userid)
			{
				$html .= '<div id="addedby" >';
				$html .= '<div id="addedby_text" >';
				
				
				$html .= '<div>'.JText::_('PLG_VMVENDOR_VENDORLINK_ADDEDBY').' ';				
				if($linkto && $profile_url!='' )
				{
					$html .= ' <a href="'.$profile_url.'" ';
						if($profileman=='es')
						{
							$html .=' data-user-id="'.$vendor_userid.'"  data-popbox="module://easysocial/profile/popbox" ';
						}
					
					$html .= '> ';
				}
				
				if($naming=='username')
					$title = $vendor_username;
				else
					$title = $vendor_name;
				
				
				$html .= ucfirst($title);
				if($linkto && $profile_url!='' )
					$html .= ' <span class="icon-user"></span></a>';
				$html .= '</div>';
				
				
				
				
				
				$html .= '</div>';
				
				
				
				
				if($show_rating)
				{
					$vendor_rating = plgContentVmvendor_Vendorlink::getVendorRating($vendor_userid);
					$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/jquery.rating.css');
					$votes_count = $vendor_rating['count']  ;
					 $average_percent = $vendor_rating['percent']  ;
					$stars = ($average_percent * $rating_stars)/100 ;
					$html .= '<div id="vendor_rating" class="hasTooltip" title="'.JText::_('PLG_VMVENDOR_VENDORLINK_VENDORRATING').' '.$stars.'/'.$rating_stars.'" >';
					for ($i=1;$i<= $stars;$i++){
							$html .= '<i class="icon-star"></i>';	
					}
					if(!is_int($stars)){
							$html .= '<i class="icon-star-2"></i>';
					}
					for ($j=1;$j<= $rating_stars - $stars ; $j++){
							$html .= '<i class="icon-star-empty"></i>';	
					}
					$html .= '</div>';	
					
				}
				
			
				$html .= '</div>';
			}
			
			if($profileman=='es' && $es_followers)
				{
					$html .= '<div class="clear"></div>
					<div id="follow_vendor">';
					$isfollowed = $es_user->isFollowed($user->id);
					if(!$isfollowed && $user->id != $vendor_userid)
					{
						$html .= '<a href="javascript:void(0);"  class="btn btn-success btn-small btn-mini" data-es-followers-follow data-es-followers-id="'.$vendor_userid.'">
						<i class="vmv-icon-follow" ></i> '. JText::_( 'PLG_VMVENDOR_VENDORLINK_FOLLOW' ).'
						</a>';
					}
					else
					{
						$html .= '<a class="author-friend btn btn-default btn-small btn-mini" disabled>
						<i class="vmv-icon-follow" ></i> '. JText::_( 'PLG_VMVENDOR_VENDORLINK_FOLLOWING' ).'
						</a>';	
						
					}
					$html .= '</div>';	
				}
			
			if($questionform && $vendor_userid>0)
			{
				$uri 	 = JFactory::getURI();
				$href	 = urlencode(htmlentities($uri->toString() ));
				$html .= '<div id="questionto">';
				if($questionform==1)
				{ 

					 $name = "askvendor";
					$html .= '<a  class="btn btn-default btn-small btn-mini" ';
					if($vendor_userid == $user->id)
						$html .= ' disabled ';
					else
						$html .= 'href="#modal-' . $name.'"  data-toggle="modal" ';
					$html .= '><i class="vmv-icon-question"></i>  '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK'). '</a>';
					$params = array();
					$params['title']  = JText::_('PLG_VMVENDOR_VENDORLINK_ASK');
					$params['url']    = 'index.php?option=com_vmvendor&view=askvendor&productid='.$virtuemart_product_id.'&vendoruserid='.$vendor_userid.'&tmpl=component&href='.$href;
					$params['height'] = "600";
					$params['width']  = "100%";
					$footer='';
				 //JHtml::_('bootstrap.modal', 'collapseModal');
					$html .= JHtml::_('bootstrap.renderModal', 'modal-' . $name, $params, $footer);

				}
				elseif($questionform==2 )
				{ 
					$html .= '<a ';
					if($vendor_userid == $user->id)
						$html .= ' disabled ';
					elseif( $profileman=='js')
					{
						//$html .= ' href="javascript:void(0)" onclick="javascript: joms.messaging.loadComposeWindow('.$vendor_userid.');" ';
						$html .= ' href="javascript:" onclick="joms.api.pmSend('.$vendor_userid.');" ';
					}
					elseif( $profileman=='es')
					{
						$html .= ' href="javascript:void(0)"  data-es-conversations-compose data-es-conversations-id="'.$vendor_userid.'"  ';
						
					}
					$html .= ' class="btn btn-mini btn-default">';
					$html .= '<i class="icon-question-sign"></i> '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK');
					$html .= '</a>';
					
				}
				$html .= '</div>';
			}
			$html .= '<div style="clear:both"></div>';
			if($vendor_userid && $questionform)
				$html .= '</div>';
			$row->text = $html . $row->text;
		}
	}
}