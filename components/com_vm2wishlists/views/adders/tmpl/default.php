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
$juri = JURI::Base();
$doc 						= JFactory::getDocument();
$user 						= JFactory::getUser();
$app = JFactory::getApplication();
$doc->addStylesheet($juri.'components/com_vm2wishlists/assets/css/fontello.css');
$doc->addStylesheet($juri.'components/com_vm2wishlists/assets/css/adders.css');
$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
$profileman 		= $cparams->get('profileman', '0');
$naming 		= $cparams->get('naming', 'username');
echo '<h3><i class="vm2w-icon-users"></i> '.JText::_('COM_VM2WISHLISTS_THEYADDEDIT').'</h3>';
echo '<table class="table table-hover table-condensed table-striped" >';

$script = "function redirectTo(profile_url){
				window.parent.location.href=profile_url;
				window.parent.SqueezeBox.close();
        	}";
			$doc->addScriptDeclaration($script);
			
			
foreach($this->users as $adder)
{
	echo '<tr>';
	$thumb = '';
	$avatar_class='';
	$name = $adder->$naming;
	//avatars
	if($profileman!='0')
	{
		echo '<td width="50">';
		if($profileman=='es')
		{ // avatars
			require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php' ;
			$thumb = Foundry::user( $adder->userid )->getAvatar('medium');
			$name = Foundry::user( $adder->userid )->getName();
			$avatar_class = 'img img-circle';
			$profile_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$adder->userid.':'.$adder->username, false);
		}
		elseif($profileman=='js')
		{
			require_once JPATH_BASE.'/components/com_community/libraries/core.php';	
			$config				= CFactory::getConfig();
			$juri_storage		= $juri;
			if($config->get('storages3bucket')!='' &&   $config->get('storages3bucket_url')!=''  && $config->get('user_avatar_storage')=='s3' )
			{
				$juri_storage	= '//'.str_replace( '<bucket>', $config->get('storages3bucket') , $config->get('storages3bucket_url') ).'/';
			}
			if(!$adder->thumb  )
				$thumb = $juri.'components/com_community/assets/user-Male-thumb.png';
			else
			{
				$thumb = $juri_storage.$adder->thumb;
			}
			$avatar_class = 'img img-circle';
			$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$adder->userid, false);
		}
		elseif($profileman=='cb')
		{
			if($adder->avatarapproved && $adder->thumb)
				$thumb = $juri.'images/comprofiler/'.$adder->thumb;
			elseif($adder->thumb)
				$thumb = $juri.'components/com_comprofiler/plugin/templates/default/images/avatar/tnpending_n.png';
			else
				$thumb = $juri.'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';
			$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$adder->userid.'&Itemid=268', false);
		}
		echo '<div class="vm2w-adders-avatar">';
		
		if($profileman!='0')
		{
			echo '<a href="'.$profile_url.'" ';
			if($profileman=='es')
				echo ' data-user-id="'.$adder->userid.'" data-popbox="module://easysocial/profile/popbox" ';
			echo '>';
		}
		echo '<img class="'.$avatar_class.'" src="'.$thumb.'" width="32" />';
		
		if($profileman!='0')
			echo '</a>';
		echo '</div></td>';
		
	}
	echo '<td class="vm2w-adders-naming">';
	if($profileman!='0')
		{
			echo '<a  onclick="javascript:redirectTo(\''.$profile_url.'\');"  href="'.$profile_url.'" ';
			if($profileman=='es')
				echo ' data-user-id="'.$adder->userid.'" data-popbox="module://easysocial/profile/popbox" ';
			echo '>';
		}
	echo ucwords($name);
	if($profileman!='0')
			echo '</a>';
	echo '<br /><div class="vm2w-adders-dateadded"><i class="vm2w-icon-clock"></i> '.$adder->date_added.'</div>';
	echo '</td>
	</tr>';
}
echo '<table>';
echo '<div style="clear:both;"></div>';
echo '<div class="pagination center" >';
echo '<div>'.$this->pagination->getResultsCounter().'</div>';
echo '<div>'.$this->pagination->getPagesLinks().'</div>';
echo '<div>'.$this->pagination->getPagesCounter().'</div>';
echo '</div>';
?>
