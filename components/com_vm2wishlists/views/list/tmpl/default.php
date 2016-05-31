<?php
/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// no direct access
defined('_JEXEC') or die;
$juri 	= JURI::Base();
$doc 	= JFactory::getDocument();
$lang 	= JFactory::getLanguage();
$jsconstant = 'var VM2W_AJAX_URL = \'' . $juri . '\';';
$doc->addScriptDeclaration($jsconstant);
$user 	= JFactory::getUser();
$doc->addStylesheet($juri.'components/com_vm2wishlists/assets/css/masonry.css');
$doc->addStylesheet($juri.'components/com_vm2wishlists/assets/css/fontello.css');
$doc->addStylesheet($juri.'components/com_vm2wishlists/assets/css/list.css');
if (!class_exists( 'VmConfig' ))
	require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
VmConfig::loadConfig();

$showCustoms = VmConfig::get('show_pcustoms',1);

if(!class_exists('shopFunctionsF') && !VmConfig::get('use_as_catalog', 0) )
{
	require JPATH_BASE.'/components/com_virtuemart/helpers/shopfunctionsf.php';
	vmJsApi::jQuery();
	vmJsApi::jSite();
	vmJsApi::jPrice();
	vmJsApi::cssSite();
	echo vmJsApi::writeJS();


}
require_once JPATH_BASE.'/administrator/components/com_virtuemart/models/product.php';
$productModel = VmModel::getModel('product');	

$app = JFactory::getApplication();
$Itemid = $app->input->getInt('Itemid');
$userid = $app->input->getInt('userid');
if(!$userid)
	$userid = $user->id;

$cparams 			= JComponentHelper::getParams('com_vm2wishlists');
$naming 			= $cparams->get('naming', 'username');
$profileman			= $cparams->get('profileman', '0');
$profile_itemid		= $cparams->get('profile_itemid');
$facebookshare		= $cparams->get('facebookshare',1);
$fb_appid			= $cparams->get('fb_appid',''	);
$twitter			= $cparams->get('twitter',1);

$show_price			= $cparams->get('show_price',1);

$use_cookies 		= $cparams->get('use_cookies',1);
$cookie_expires		= $cparams->get('cookie_expires','365');

$maxrating 			= VmConfig::get('vm_maximum_rating_scale', 5);

$list_data			= $this->list_data;
$listid				= $list_data->id;
$list_name			= JText::_($list_data->list_name);
$list_description	= JText::_($list_data->list_description);
$icon_class			= $list_data->icon_class;
$list_privacy		= $list_data->privacy;
$amz_link 			= $list_data->amz_link;
$amz_base 			= $list_data->amz_base;
$amz_prefix			= $list_data->amz_prefix;
$user_naming 		= ucfirst( $list_data->$naming );
$pagetitle 			= $doc->getTitle();

$doc->setTitle( $pagetitle . ' '.JText::_('COM_VM2WISHLISTS_BY').' ' . ucfirst($user_naming) .' - '.$app->getCfg( 'sitename' ));

if($profileman=='cb')
{
	$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$userid.'&Itemid=');
	$user_naming = '<a href="'.$profile_url.'" >'.$user_naming.'</a>';
}
elseif($profileman=='js'){
	$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$userid.'&Itemid=');
	$user_naming = '<a href="'.$profile_url.'" >'.$user_naming.'</a>';
}
elseif($profileman=='es'){
	$profile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$userid.'&Itemid=');
	$user_naming = '<a href="'.$profile_url.'" data-user-id="'.$userid.'" data-popbox="module://easysocial/profile/popbox">'.$user_naming.'</a>';
}

echo '<h1><i class="'.$icon_class.'"></i> '.$list_name.'</h1>';
if(!$userid && !$use_cookies)
{
	$app->enqueueMessage(JText::_('COM_VM2WISHLISTS_MUSTBELOGGEDIN') );
	return false;
}
elseif(!$userid  && $use_cookies)
{
	$app->enqueueMessage(JText::_('COM_VM2WISHLISTS_COOKIELIST_WARNING') , 'warning' );
}
echo '<div id="vm2w_listdata">';

echo '<div id="vm2w_desc"><i class="vm2w-icon-info"></i> '.$list_description.'</div>';
if($user->id == $userid)
{
	echo  '<div class="vm2w_privacy">';
	if($list_privacy=='0')
		echo '<i class="vm2w-icon-lock-open"></i> ';
	else
		echo '<i class="vm2w-icon-lock"></i> ';
	echo JText::_('COM_VM2WISHLISTS_PRIVACY_'.$list_privacy);
	echo '</div>';
}
if($userid=='0')
{
	echo  '<div class="vm2w_privacy">';
	echo '<i class="vm2w-icon-laptop"></i> ';
	echo JText::_('COM_VM2WISHLISTS_COOKIELIST');
	echo '</div>';
}
if($userid)
	echo '<div id="vm2w_selectby"><i class="vm2w-icon-user"></i> '. JText::_('COM_VM2WISHLISTS_BY').': <strong>'.$user_naming.'</strong></div>';
echo '</div>';

if($list_privacy=='0' && $userid>0)
{
	$uri 	 = JFactory::getURI();
	$href = $uri->getScheme().'://'.$uri->getHost()
	.JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$userid.'&Itemid='.$Itemid , true);
	$encodedurl = urlencode($href);
	echo '<div id="vm2w_share" ><i class="vm2w-icon-share"></i> '. JText::_('COM_VM2WISHLISTS_SHARE').'<br />';
	echo  '<div class="btn-group" >';
	JHTML::_('behavior.modal');	
	
	
	if($user->id>0)
	{		
		echo '<a class="btn btn-default btn-small modal hasTooltip" target="_blank" title="'.JText::_('COM_VM2WISHLISTS_EMAILTHISLIST').'" 
		href="'.JRoute::_('index.php?option=com_vm2wishlists&view=recommend&listid='.$listid.'&userid='.$userid.'&tmpl=component&Itemid='.$Itemid).'" 
		 rel="{handler: \'iframe\', size: {x: 600, y: 550}}">
		<i class="vm2w-icon-mail" ></i></a> ';
		
	}
	else
		echo '<a class="btn btn-default btn-small vm2w-icon-mail hasTooltip"  title="'.JText::_('COM_VM2WISHLISTS_LOGINTOMAIL').'" disabled ><i ></i></a>';
	
	
	if($fb_appid)
	{
			echo '<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/'.str_replace('-','_',$lang->getTag() ).'/all.js#xfbml=1&appId='.$fb_appid.'";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, \'script\', \'facebook-jssdk\'));</script>
			<a class="btn btn-default btn-small fb-send hasTooltip" data-href="'.$href.'" 
			title="Facebook Messenger" disable></a>';	
	}
		
	if($facebookshare)
	{
		echo '<a target="_blank" href="http://www.facebook.com/sharer.php?s=100&p[title]='.$list_name.'&p[summary]='.$list_description.'&p[url]='.$encodedurl.'" 
		title="Facebook" class="hasTooltip btn btn-default btn-small" >
		<i class="vm2w-icon-facebook"></i></a> ';	
	}	
		
		
	if($twitter)
	{
		echo '<a target="_blank" href="https://twitter.com/intent/tweet?text=' .$list_name. ': '.$list_description.'&url='.$encodedurl.'" 
			title="Twitter" class="btn btn-default btn-small hasTooltip" >
			<i class="vm2w-icon-twitter"></i></a> ';
	}
		

		$rss_url = JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$userid.'&format=feed');
	echo '<a href="'.$rss_url.'" target="_blank" title="RSS" class="btn btn-default btn-small hasTooltip" >
	<i class="vm2w-icon-rss" ></i></a> ';
	echo '<br />';
	
	echo '</div>';
	echo '</div>';
}
echo '<div class="clear"></div>';

echo '<hr class="vm2w-separator" />';
echo '<div id="masonry_container">';
foreach($this->products as $product)
{
	$vm_itemid = Vm2wishlistsModellist::getVMItemid($product->virtuemart_category_id);
	$vmproducts = $productModel->getProducts ( array($product->virtuemart_product_id) );
	if($showCustoms)
		shopFunctionsF::sortLoadProductCustomsStockInd($vmproducts,$productModel);

	$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id.'&Itemid='.$vm_itemid);
	
	echo '<div class="vm2w_product brick" id="brick'.$product->virtuemart_product_id.'" >';
	if($user->id == $userid)
	{
		echo '<a class="vm2w-rmv-btn hasTooltip" title="'.JText::_('COM_VM2WISHLISTS_REMOVE').'" id="rmv'.$product->virtuemart_product_id.'"  
		onclick="jQuery.vm2w.delfromlist('.$listid.','.$product->virtuemart_product_id.');return false;" 
		 ><i class="vm2w-icon-cancel"></i></a>';
	}
	 
	echo '<div class="vm2w_product_name" >
	<strong><a href="'.$product_url.'" >'.ucfirst($product->name).'</a></strong>
	</div>';
	echo '<div class="vm2w_product_thumb" >';
		$product_thumb =$product->file_url_thumb;
		if ($product_thumb =='')
		{
			$product_thumb = str_replace('virtuemart/product/','virtuemart/product/resized/',$product->file_url);
			$thum_side_width			=	VmConfig::get( 'img_width' );
			$thum_side_height			=	VmConfig::get( 'img_height' );
			$extension_pos = strrpos($product_thumb, "."); // find position of the last dot, so where the extension starts
			$product_thumb = substr($product_thumb, 0, $extension_pos) . '_'.$thum_side_width.'x'.$thum_side_height . substr($product_thumb, $extension_pos);
		}
		if ($product->file_url =='')
			$product_thumb =  'components/com_virtuemart/assets/images/vmgeneral/'.VmConfig::get('no_image_set');
			
		echo '<a href="'.$product_url.'" ><img src="'.$juri.$product_thumb.'" height="90" alt="img" /></a>';
		
		echo '<div class="vm2w_product_cat" >
		<a href="'.JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$product->virtuemart_category_id).'">
		<i class="vm2w-icon-folder" title="'.JText::_('COM_VM2TAGS_CATEGORY').'"  class="hasTooltip" ></i> ';
		echo  JText::_($product->category_name).'</a>
		</div>';

		if($show_price > 0 )
		{
			echo '<div class="vm2w_product_price" >';
			$price_format					= $this->price_format;
			$symbol 						= $price_format[7];
			$currency_positive_style 		= $price_format[11];
			$currency_id					= $price_format[0];
			$currency 						= $price_format[4];
			$currency_decimal_place 		= $price_format[8];
			if($show_price=='1')  // without tax
				$list_item__product_price = $product->product_price;
			if($show_price=='2')	// with tax(es)
			{
				//$list_item__product_price = Vm2wishlistsModellist::applytaxes( $product->product_price   , $product->virtuemart_category_id ,  $product->virtuemart_vendor_id)  ;
				$list_item__product_price = $vmproducts[0]->prices['salesPrice'];
			}
				
			
				
			$price_val =  number_format($list_item__product_price , $currency_decimal_place , '.' , ' ' );
			$print_price = str_replace('{number}' ,$price_val ,$currency_positive_style);
			$print_price = str_replace('{symbol}' ,$symbol ,$print_price);
			echo '<a href="'.$product_url.'" >'. $print_price .'</a>';
			echo '</div>';
		}
		echo '</div>';

		if($amz_link)
		{
			$amazon_id			= $cparams->get('amazon_id','nordmograph-21');
			$amz_url = 'http://www.amazon.com/s/ref=nb_sb_noss_2?url=search-alias%3Daps&field-keywords=';
			if($amz_prefix)
				$amz_url .= $amz_prefix.'+'; 
			$amz_url .= $product->$amz_base.'&tag='.$amazon_id;
			echo '<div class="vm2w_amz" >';
			if($userid == $user->id)
			{
				echo '<a target="_blank" href="'.$amz_url.'" class="btn btn-default btn-small btn-mini">';
				echo '<i class="vm2w-icon-amazon" title="'.JText::_('COM_VM2TAGS_CATEGORY').'"  class="hasTooltip" ></i> ';
				echo  '</a>';
			}
			else
			{
				echo '<a target="_blank" href="'.$amz_url.'" class="btn btn-primary ">';
				echo '<i class="vm2w-icon-cart" title="'.JText::_('COM_VM2TAGS_CATEGORY').'"  class="hasTooltip" ></i> ';
				echo '<i class="vm2w-icon-amazon" title="'.JText::_('COM_VM2TAGS_CATEGORY').'"  class="hasTooltip" ></i> ';
				echo  '</a>';
			}
			echo '</div>';
		}
	
		if(!VmConfig::get('use_as_catalog', 0))
		{
			echo '<div class="add2cartcontainer">';
			$array['product'] = $vmproducts[0];
			$vmproducts[0]->position = 'addtocart';
			echo @shopFunctionsF::renderVmSubLayout('addtocart', $array ,'');
			echo '</div>';
		}
	echo '</div>';

}
$doc->addScript($juri.'components/com_vm2wishlists/assets/js/jquery.vm2w.js');
echo '</div>';

echo '<script type="text/javascript" src="'.$juri.'components/com_vm2wishlists/assets/js/jquery.masonry.min.js"></script>';
echo "<script type=\"text/javascript\">
				jQuery(function(){
		jQuery('#masonry_container').imagesLoaded( function(){
		 jQuery('#masonry_container').masonry({
			itemSelector : '.brick'
		  });
		});  
	  });
	</script>";

echo '<div style="clear:both;"></div>';
echo '<div class="pagination center" >';
echo '<div>'.$this->pagination->getResultsCounter().'</div>';
echo '<div>'.$this->pagination->getPagesLinks().'</div>';
echo '<div>'.$this->pagination->getPagesCounter().'</div>';
echo '</div>';
?>